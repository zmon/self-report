<?php

namespace App\Http\Controllers;

use App;
use App\Exports\PasswordResetExport;
use App\Http\Middleware\TrimStrings;
use App\Http\Requests\PasswordResetFormRequest;
use App\Http\Requests\PasswordResetIndexRequest;
use App\PasswordReset;
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

class PasswordResetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PasswordResetIndexRequest $request)
    {
        if (! Auth::user()->can('password_reset index')) {
            \Session::flash('flash_error_message', 'You do not have access to password_resetss.');

            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('password_reset_page', '');
        $search = session('password_reset_keyword', '');
        $column = session('password_reset_column', 'name');
        $direction = session('password_reset_direction', '-1');

        $can_add = Auth::user()->can('password_reset add');
        $can_show = Auth::user()->can('password_reset view');
        $can_edit = Auth::user()->can('password_reset edit');
        $can_delete = Auth::user()->can('password_reset delete');
        $can_excel = Auth::user()->can('password_reset export-excel');
        $can_pdf = Auth::user()->can('password_reset pdf');

        return view('password-reset.index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (! Auth::user()->can('password_reset add')) {  // TODO: add -> create
            \Session::flash('flash_error_message', 'You do not have access to add a password_resets.');
            if (Auth::user()->can('password_reset index')) {
                return Redirect::route('password-reset.index');
            } else {
                return Redirect::route('home');
            }
        }

        $cancel_url = Redirect::back()->getTargetUrl();

        return view('password-reset.create', compact('cancel_url'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(PasswordResetFormRequest $request)
    {
        $password_reset = new PasswordReset;

        try {
            $attributes = $request->validated();
            $attributes['organization_id'] = session('organization_id', 0);
            unset($attributes['id']);
            $password_reset->add($attributes);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unable to process request',
            ], 400);
        }

        \Session::flash('flash_success_message', 'password_resets '.$password_reset->name.' was added.');

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
        if (! Auth::user()->can('password_reset view')) {
            \Session::flash('flash_error_message', 'You do not have access to view a password_resets.');
            if (Auth::user()->can('password_reset index')) {
                return Redirect::route('password-reset.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($password_reset = $this->sanitizeAndFind($id)) {
            $can_edit = Auth::user()->can('password_reset edit');
            $can_delete = (Auth::user()->can('password_reset delete') && $password_reset->canDelete());

            return view('password-reset.show', compact('password_reset', 'can_edit', 'can_delete'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find password_resets to display.');

            return Redirect::route('password-reset.index');
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
        if (! Auth::user()->can('password_reset edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a password_resets.');
            if (Auth::user()->can('password_reset index')) {
                return Redirect::route('password-reset.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($password_reset = $this->sanitizeAndFind($id)) {
            $cancel_url = Redirect::back()->getTargetUrl();

            return view('password-reset.edit', compact('password_reset', 'cancel_url'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find password_resets to edit.');

            return Redirect::route('password-reset.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PasswordReset $password_reset * @return \Illuminate\Http\Response
     */
    public function update(PasswordResetFormRequest $request, $id)
    {

//        if (!Auth::user()->can('password_reset update')) {
//            \Session::flash('flash_error_message', 'You do not have access to update a password_resets.');
//            if (!Auth::user()->can('password_reset index')) {
//                return Redirect::route('password-reset.index');
//            } else {
//                return Redirect::route('home');
//            }
//        }

        if (! $password_reset = $this->sanitizeAndFind($id)) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        $attributes = $request->all();
        $attributes['organization_id'] = session('organization_id', 0);
        $password_reset->fill($attributes);

        if ($password_reset->isDirty()) {
            try {
                $password_reset->save();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request',
                ], 400);
            }

            \Session::flash('flash_success_message', 'password_resets '.$password_reset->name.' was changed.');
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
     * @param PasswordReset $password_reset * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Auth::user()->can('password_reset delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a password_resets.');
            if (Auth::user()->can('password_reset index')) {
                return Redirect::route('password-reset.index');
            } else {
                return Redirect::route('home');
            }
        }

        $password_reset = $this->sanitizeAndFind($id);

        if ($password_reset && $password_reset->canDelete()) {
            try {
                $password_reset->delete();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request.',
                ], 400);
            }

            \Session::flash('flash_success_message', 'password_resets '.$password_reset->name.' was removed.');
        } else {
            \Session::flash('flash_error_message', 'Unable to find password_resets to delete.');
        }

        if (Auth::user()->can('password_reset index')) {
            return Redirect::route('password-reset.index');
        } else {
            return Redirect::route('home');
        }
    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return PasswordReset or null
     */
    private function sanitizeAndFind($id)
    {
        return PasswordReset::find(intval($id));
    }

    public function download()
    {
        if (! Auth::user()->can('password_reset export-excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download password_resets.');
            if (Auth::user()->can('password_reset index')) {
                return Redirect::route('password-reset.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('password_reset_keyword', '');
        $column = session('password_reset_column', 'name');
        $direction = session('password_reset_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        info(__METHOD__.' line: '.__LINE__." $column, $direction, $search");

        $dataQuery = PasswordReset::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new PasswordResetExport($dataQuery),
            'password-reset.xlsx');
    }

    public function print()
    {
        if (! Auth::user()->can('password_reset export-pdf')) { // TODO: i think these permissions may need to be updated to match initial permissions?
            \Session::flash('flash_error_message', 'You do not have access to print password_resets.');
            if (Auth::user()->can('password_reset index')) {
                return Redirect::route('password-reset.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('password_reset_keyword', '');
        $column = session('password_reset_column', 'name');
        $direction = session('password_reset_direction', '-1');
        $column = $column ? $column : 'name';

        info(__METHOD__.' line: '.__LINE__." $column, $direction, $search");

        // Get query data
        $columns = [
        ];
        $dataQuery = PasswordReset::pdfDataQuery($column, $direction, $search, $columns);
        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('password-reset.print', compact('data'));

        // Begin DOMPDF/laravel-dompdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->loadHTML($printHtml);
        $currentDate = new DateTime(null, new DateTimeZone('America/Chicago'));

        return $pdf->stream('password-reset-'.$currentDate->format('Ymd_Hi').'.pdf');

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
