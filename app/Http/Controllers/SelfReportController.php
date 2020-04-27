<?php

namespace App\Http\Controllers;



use App\Http\Middleware\TrimStrings;
use App\SelfReport;
use Illuminate\Http\Request;

use App\Http\Requests\SelfReportFormRequest;
use App\Http\Requests\SelfReportIndexRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Exports\SelfReportExport;
use Maatwebsite\Excel\Facades\Excel;
//use PDF; // TCPDF, not currently in use

class SelfReportController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SelfReportIndexRequest $request)
    {

        if (!Auth::user()->can('self_report index')) {
            \Session::flash('flash_error_message', 'You do not have access to SelfReports.');
            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('self_report_page', '');
        $search = session('self_report_keyword', '');
        $column = session('self_report_column', 'Name');
        $direction = session('self_report_direction', '-1');

        $can_add = Auth::user()->can('self_report add');
        $can_show = Auth::user()->can('self_report view');
        $can_edit = Auth::user()->can('self_report edit');
        $can_delete = Auth::user()->can('self_report delete');
        $can_excel = Auth::user()->can('self_report excel');
        $can_pdf = Auth::user()->can('self_report pdf');

        $access = \Auth::user()->organization_id;

        return view('self-report.index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf', 'access'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
	{

        if (!Auth::user()->can('self_report add')) {  // TODO: add -> create
            \Session::flash('flash_error_message', 'You do not have access to add a SelfReports.');
            if (Auth::user()->can('self_report index')) {
                return Redirect::route('self-report.index');
            } else {
                return Redirect::route('home');
            }
        }

	    return view('self-report.create');
	}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SelfReportFormRequest $request)
    {

        $self_report = new \App\SelfReport;

        try {
            $self_report->add($request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to process request'
            ], 400);
        }

        \Session::flash('flash_success_message', 'SelfReports ' . $self_report->name . ' was added.');

        return response()->json([
            'message' => 'Added record'
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (!Auth::user()->can('self_report view')) {
            \Session::flash('flash_error_message', 'You do not have access to view a SelfReports.');
            if (Auth::user()->can('self_report index')) {
                return Redirect::route('self-report.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($self_report = $this->sanitizeAndFind($id)) {
            $can_edit = Auth::user()->can('self_report edit');
            $can_delete = (Auth::user()->can('self_report delete') && $self_report->canDelete());
            return view('self-report.show', compact('self_report','can_edit', 'can_delete'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find SelfReports to display.');
            return Redirect::route('self-report.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('self_report edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a SelfReports.');
            if (Auth::user()->can('self_report index')) {
                return Redirect::route('self-report.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($self_report = $this->sanitizeAndFind($id)) {
            return view('self-report.edit', compact('self_report'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find SelfReports to edit.');
            return Redirect::route('self-report.index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\SelfReport $self_report     * @return \Illuminate\Http\Response
     */
    public function update(SelfReportFormRequest $request, $id)
    {

//        if (!Auth::user()->can('self_report update')) {
//            \Session::flash('flash_error_message', 'You do not have access to update a SelfReports.');
//            if (!Auth::user()->can('self_report index')) {
//                return Redirect::route('self-report.index');
//            } else {
//                return Redirect::route('home');
//            }
//        }

        if (!$self_report = $this->sanitizeAndFind($id)) {
       //     \Session::flash('flash_error_message', 'Unable to find SelfReports to edit.');
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }

        $self_report->fill($request->all());

        if ($self_report->isDirty()) {

            try {
                $self_report->save();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request'
                ], 400);
            }

            \Session::flash('flash_success_message', 'SelfReports ' . $self_report->name . ' was changed.');
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
     * @param  \App\SelfReport $self_report     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!Auth::user()->can('self_report delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a SelfReports.');
            if (Auth::user()->can('self_report index')) {
                 return Redirect::route('self-report.index');
            } else {
                return Redirect::route('home');
            }
        }

        $self_report = $this->sanitizeAndFind($id);

        if ( $self_report  && $self_report->canDelete()) {

            try {
                $self_report->delete();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request.'
                ], 400);
            }

            \Session::flash('flash_success_message', 'SelfReports ' . $self_report->name . ' was removed.');
        } else {
            \Session::flash('flash_error_message', 'Unable to find SelfReports to delete.');

        }

        if (Auth::user()->can('self_report index')) {
             return Redirect::route('self-report.index');
        } else {
            return Redirect::route('home');
        }


    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return SelfReport or null
     */
    private function sanitizeAndFind($id)
    {
        return SelfReport::with('organizations',
            'preexisting_conditions',
            'race_ethnicities',
            'symptoms')->find(intval($id));
    }


    public function download()
    {

        if (!Auth::user()->can('self_report excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download SelfReports.');
            if (Auth::user()->can('self_report index')) {
                return Redirect::route('self-report.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('self_report_keyword', '');
        $column = session('self_report_column', 'name');
        $direction = session('self_report_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $search");

        $dataQuery = SelfReport::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new SelfReportExport($dataQuery),
            'self-report.xlsx');

    }


        public function print()
{
        if (!Auth::user()->can('self_report export-pdf')) { // TODO: i think these permissions may need to be updated to match initial permissions?
            \Session::flash('flash_error_message', 'You do not have access to print SelfReports.');
            if (Auth::user()->can('self_report index')) {
                return Redirect::route('self-report.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('self_report_keyword', '');
        $column = session('self_report_column', 'name');
        $direction = session('self_report_direction', '-1');
        $column = $column ? $column : 'name';

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $search");

        // Get query data
        $columns = [
            'organization_id',
            'name',
            'state',
            'zipcode',
            'symptom_start_date',
            'county_calc',
            'form_received_at',
        ];
        $dataQuery = SelfReport::pdfDataQuery($column, $direction, $search, $columns);
        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('self-report.print', compact( 'data' ) );

        // Begin DOMPDF/laravel-dompdf
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => TRUE]);
        $pdf->loadHTML($printHtml);
        $currentDate = new \DateTime(null, new \DateTimeZone('America/Chicago'));
        return $pdf->stream('self-report-' . $currentDate->format('Ymd_Hi') . '.pdf');

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
