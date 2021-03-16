<?php

namespace App\Http\Controllers;

use App;
use App\Exports\PreexistingConditionExport;
use App\Http\Middleware\TrimStrings;
use App\Http\Requests\PreexistingConditionFormRequest;
use App\Http\Requests\PreexistingConditionIndexRequest;
use App\PreexistingCondition;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

//use PDF; // TCPDF, not currently in use

class PreexistingConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PreexistingConditionIndexRequest $request)
    {
        if (! Auth::user()->can('preexisting_condition index')) {
            \Session::flash('flash_error_message', 'You do not have access to Preexisting Conditions.');

            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('preexisting_condition_page', '');
        $search = session('preexisting_condition_keyword', '');
        $column = session('preexisting_condition_column', 'Name');
        $direction = session('preexisting_condition_direction', '-1');

        $can_add = Auth::user()->can('preexisting_condition add');
        $can_show = Auth::user()->can('preexisting_condition view');
        $can_edit = Auth::user()->can('preexisting_condition edit');
        $can_delete = Auth::user()->can('preexisting_condition delete');
        $can_excel = Auth::user()->can('preexisting_condition excel');
        $can_pdf = Auth::user()->can('preexisting_condition pdf');

        return view('preexisting-condition.index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (! Auth::user()->can('preexisting_condition add')) {  // TODO: add -> create
            \Session::flash('flash_error_message', 'You do not have access to add a Preexisting Conditions.');
            if (Auth::user()->can('preexisting_condition index')) {
                return Redirect::route('preexisting-condition.index');
            } else {
                return Redirect::route('home');
            }
        }

        return view('preexisting-condition.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(PreexistingConditionFormRequest $request)
    {
        $preexisting_condition = new PreexistingCondition;

        try {
            $preexisting_condition->add($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unable to process request',
            ], 400);
        }

        \Session::flash('flash_success_message', 'Preexisting Conditions '.$preexisting_condition->name.' was added.');

        return response()->json([
            'message' => 'Added record',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        if (! Auth::user()->can('preexisting_condition view')) {
            \Session::flash('flash_error_message', 'You do not have access to view a Preexisting Conditions.');
            if (Auth::user()->can('preexisting_condition index')) {
                return Redirect::route('preexisting-condition.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($preexisting_condition = $this->sanitizeAndFind($id)) {
            $can_edit = Auth::user()->can('preexisting_condition edit');
            $can_delete = (Auth::user()->can('preexisting_condition delete') && $preexisting_condition->canDelete());

            return view('preexisting-condition.show', compact('preexisting_condition', 'can_edit', 'can_delete'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Preexisting Conditions to display.');

            return Redirect::route('preexisting-condition.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        if (! Auth::user()->can('preexisting_condition edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a Preexisting Conditions.');
            if (Auth::user()->can('preexisting_condition index')) {
                return Redirect::route('preexisting-condition.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($preexisting_condition = $this->sanitizeAndFind($id)) {
            return view('preexisting-condition.edit', compact('preexisting_condition'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Preexisting Conditions to edit.');

            return Redirect::route('preexisting-condition.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PreexistingCondition $preexisting_condition * @return \Illuminate\Http\Response
     */
    public function update(PreexistingConditionFormRequest $request, $id)
    {

//        if (!Auth::user()->can('preexisting_condition update')) {
//            \Session::flash('flash_error_message', 'You do not have access to update a Preexisting Conditions.');
//            if (!Auth::user()->can('preexisting_condition index')) {
//                return Redirect::route('preexisting-condition.index');
//            } else {
//                return Redirect::route('home');
//            }
//        }

        if (! $preexisting_condition = $this->sanitizeAndFind($id)) {
            //     \Session::flash('flash_error_message', 'Unable to find Preexisting Conditions to edit.');
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        $preexisting_condition->fill($request->all());

        if ($preexisting_condition->isDirty()) {
            try {
                $preexisting_condition->save();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request',
                ], 400);
            }

            \Session::flash('flash_success_message', 'Preexisting Conditions '.$preexisting_condition->name.' was changed.');
        } else {
            \Session::flash('flash_info_message', 'No changes were made.');
        }

        return response()->json([
            'message' => 'Changed record',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PreexistingCondition $preexisting_condition * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Auth::user()->can('preexisting_condition delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a Preexisting Conditions.');
            if (Auth::user()->can('preexisting_condition index')) {
                return Redirect::route('preexisting-condition.index');
            } else {
                return Redirect::route('home');
            }
        }

        $preexisting_condition = $this->sanitizeAndFind($id);

        if ($preexisting_condition && $preexisting_condition->canDelete()) {
            try {
                $preexisting_condition->delete();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request.',
                ], 400);
            }

            \Session::flash('flash_success_message', 'Preexisting Conditions '.$preexisting_condition->name.' was removed.');
        } else {
            \Session::flash('flash_error_message', 'Unable to find Preexisting Conditions to delete.');
        }

        if (Auth::user()->can('preexisting_condition index')) {
            return Redirect::route('preexisting-condition.index');
        } else {
            return Redirect::route('home');
        }
    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return PreexistingCondition or null
     */
    private function sanitizeAndFind($id)
    {
        return PreexistingCondition::find(intval($id));
    }

    public function download()
    {
        if (! Auth::user()->can('preexisting_condition excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download Preexisting Conditions.');
            if (Auth::user()->can('preexisting_condition index')) {
                return Redirect::route('preexisting-condition.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('preexisting_condition_keyword', '');
        $column = session('preexisting_condition_column', 'name');
        $direction = session('preexisting_condition_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        info(__METHOD__.' line: '.__LINE__." $column, $direction, $search");

        $dataQuery = PreexistingCondition::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new PreexistingConditionExport($dataQuery),
            'preexisting-condition.xlsx');
    }

    public function print()
    {
        if (! Auth::user()->can('preexisting_condition export-pdf')) { // TODO: i think these permissions may need to be updated to match initial permissions?
            \Session::flash('flash_error_message', 'You do not have access to print Preexisting Conditions.');
            if (Auth::user()->can('preexisting_condition index')) {
                return Redirect::route('preexisting-condition.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('preexisting_condition_keyword', '');
        $column = session('preexisting_condition_column', 'name');
        $direction = session('preexisting_condition_direction', '-1');
        $column = $column ? $column : 'name';

        info(__METHOD__.' line: '.__LINE__." $column, $direction, $search");

        // Get query data
        $columns = [
            'name',
        ];
        $dataQuery = PreexistingCondition::pdfDataQuery($column, $direction, $search, $columns);
        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('preexisting-condition.print', compact('data'));

        // Begin DOMPDF/laravel-dompdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->loadHTML($printHtml);
        $currentDate = new DateTime(null, new DateTimeZone('America/Chicago'));

        return $pdf->stream('preexisting-condition-'.$currentDate->format('Ymd_Hi').'.pdf');

        /*
        ///////////////////////////////////////////////////////////////////////
        /// Begin TCPDF/tcpdf-laravel test - keeping for reference
        // NOTE: you'll need to uncomment the use at the top for "PDF"
        PDF::SetTitle('Vendors');
        PDF::SetAutoPageBreak(TRUE, 15);
        PDF::SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT, 15);
        PDF::SetFooterMargin(PDF_MARGIN_FOOTER);
        PDF::setHeaderCallback(function($pdf){
            $currentDate = new \DateTime();
            $currentDate->setTimezone(new \DateTimeZone('America/Chicago'));
            $pdf->Cell(0,10,'Date ' . $currentDate->format('Y-m-d g:ia'),0,false,'C',0,'',0,false,'T','M');
        });
        PDF::setFooterCallback(function($pdf){
            //$pdf->SetY(-15);
            //var_dump(get_class_methods('Elibyy\TCPDF\TCPDFHelper')); exit;
            $pdf->Cell(0,10,'Page ' . $pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(),0,false,'C',0,'',0,false,'T','M');
        });
        PDF::AddPage('L'); // Landscape
        //var_dump($dataQuery->get()); exit;
        //var_dump(get_class_methods('App\Exports\VcVendorExport')); exit; // query headings map download store queue toResponse
        PDF::writeHTML($html);
        PDF::Output('vc-vendor.pdf');
        /// End TCPDF/tcpdf-laravel test
        ///////////////////////////////////////////////////////////////////////
        */
    }
}
