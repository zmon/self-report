<?php

namespace App\Http\Controllers;


use App;
use App\Http\Middleware\TrimStrings;
use App\Symptom;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests\SymptomFormRequest;
use App\Http\Requests\SymptomIndexRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Exports\SymptomExport;
use Maatwebsite\Excel\Facades\Excel;

//use PDF; // TCPDF, not currently in use

class SymptomController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(SymptomIndexRequest $request)
    {

        if (!Auth::user()->can('symptom index')) {
            \Session::flash('flash_error_message', 'You do not have access to Symptoms.');
            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('symptom_page', '');
        $search = session('symptom_keyword', '');
        $column = session('symptom_column', 'Name');
        $direction = session('symptom_direction', '-1');

        $can_add = Auth::user()->can('symptom add');
        $can_show = Auth::user()->can('symptom view');
        $can_edit = Auth::user()->can('symptom edit');
        $can_delete = Auth::user()->can('symptom delete');
        $can_excel = Auth::user()->can('symptom excel');
        $can_pdf = Auth::user()->can('symptom pdf');

        return view('symptom.index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        if (!Auth::user()->can('symptom add')) {  // TODO: add -> create
            \Session::flash('flash_error_message', 'You do not have access to add a Symptoms.');
            if (Auth::user()->can('symptom index')) {
                return Redirect::route('symptom.index');
            } else {
                return Redirect::route('home');
            }
        }

        return view('symptom.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(SymptomFormRequest $request)
    {

        $symptom = new Symptom;

        try {
            $symptom->add($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unable to process request'
            ], 400);
        }

        \Session::flash('flash_success_message', 'Symptoms ' . $symptom->name . ' was added.');

        return response()->json([
            'message' => 'Added record'
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param integer $id
     * @return Response
     */
    public function show($id)
    {

        if (!Auth::user()->can('symptom view')) {
            \Session::flash('flash_error_message', 'You do not have access to view a Symptoms.');
            if (Auth::user()->can('symptom index')) {
                return Redirect::route('symptom.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($symptom = $this->sanitizeAndFind($id)) {
            $can_edit = Auth::user()->can('symptom edit');
            $can_delete = (Auth::user()->can('symptom delete') && $symptom->canDelete());
            return view('symptom.show', compact('symptom', 'can_edit', 'can_delete'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Symptoms to display.');
            return Redirect::route('symptom.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param integer $id
     * @return Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('symptom edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a Symptoms.');
            if (Auth::user()->can('symptom index')) {
                return Redirect::route('symptom.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($symptom = $this->sanitizeAndFind($id)) {
            return view('symptom.edit', compact('symptom'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Symptoms to edit.');
            return Redirect::route('symptom.index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Symptom $symptom * @return \Illuminate\Http\Response
     */
    public function update(SymptomFormRequest $request, $id)
    {

//        if (!Auth::user()->can('symptom update')) {
//            \Session::flash('flash_error_message', 'You do not have access to update a Symptoms.');
//            if (!Auth::user()->can('symptom index')) {
//                return Redirect::route('symptom.index');
//            } else {
//                return Redirect::route('home');
//            }
//        }

        if (!$symptom = $this->sanitizeAndFind($id)) {
            //     \Session::flash('flash_error_message', 'Unable to find Symptoms to edit.');
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }

        $symptom->fill($request->all());

        if ($symptom->isDirty()) {

            try {
                $symptom->save();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request'
                ], 400);
            }

            \Session::flash('flash_success_message', 'Symptoms ' . $symptom->name . ' was changed.');
        } else {
            \Session::flash('flash_info_message', 'No changes were made.');
        }

        return response()->json([
            'message' => 'Changed record'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Symptom $symptom * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!Auth::user()->can('symptom delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a Symptoms.');
            if (Auth::user()->can('symptom index')) {
                return Redirect::route('symptom.index');
            } else {
                return Redirect::route('home');
            }
        }

        $symptom = $this->sanitizeAndFind($id);

        if ($symptom && $symptom->canDelete()) {

            try {
                $symptom->delete();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request.'
                ], 400);
            }

            \Session::flash('flash_success_message', 'Symptoms ' . $symptom->name . ' was removed.');
        } else {
            \Session::flash('flash_error_message', 'Unable to find Symptoms to delete.');

        }

        if (Auth::user()->can('symptom index')) {
            return Redirect::route('symptom.index');
        } else {
            return Redirect::route('home');
        }


    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return Symptom or null
     */
    private function sanitizeAndFind($id)
    {
        return Symptom::find(intval($id));
    }


    public function download()
    {

        if (!Auth::user()->can('symptom excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download Symptoms.');
            if (Auth::user()->can('symptom index')) {
                return Redirect::route('symptom.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('symptom_keyword', '');
        $column = session('symptom_column', 'name');
        $direction = session('symptom_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $search");

        $dataQuery = Symptom::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new SymptomExport($dataQuery),
            'symptom.xlsx');

    }


    public function print()
    {
        if (!Auth::user()->can('symptom export-pdf')) { // TODO: i think these permissions may need to be updated to match initial permissions?
            \Session::flash('flash_error_message', 'You do not have access to print Symptoms.');
            if (Auth::user()->can('symptom index')) {
                return Redirect::route('symptom.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('symptom_keyword', '');
        $column = session('symptom_column', 'name');
        $direction = session('symptom_direction', '-1');
        $column = $column ? $column : 'name';

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $search");

        // Get query data
        $columns = [
            'name',
        ];
        $dataQuery = Symptom::pdfDataQuery($column, $direction, $search, $columns);
        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('symptom.print', compact('data'));

        // Begin DOMPDF/laravel-dompdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => TRUE]);
        $pdf->loadHTML($printHtml);
        $currentDate = new DateTime(null, new DateTimeZone('America/Chicago'));
        return $pdf->stream('symptom-' . $currentDate->format('Ymd_Hi') . '.pdf');

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
