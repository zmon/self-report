<?php

namespace App\Http\Controllers;



use App\Http\Middleware\TrimStrings;
use App\Organization;
use Illuminate\Http\Request;

use App\Http\Requests\OrganizationFormRequest;
use App\Http\Requests\OrganizationIndexRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Exports\OrganizationExport;
use Maatwebsite\Excel\Facades\Excel;
//use PDF; // TCPDF, not currently in use

class OrganizationController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrganizationIndexRequest $request)
    {

        if (!Auth::user()->can('organization index')) {
            \Session::flash('flash_error_message', 'You do not have access to Organizations.');
            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('organization_page', '');
        $search = session('organization_keyword', '');
        $column = session('organization_column', 'Name');
        $direction = session('organization_direction', '-1');

        $can_add = Auth::user()->can('organization add');
        $can_show = Auth::user()->can('organization view');
        $can_edit = Auth::user()->can('organization edit');
        $can_delete = Auth::user()->can('organization delete');
        $can_excel = Auth::user()->can('organization excel');
        $can_pdf = Auth::user()->can('organization pdf');

        return view('organization.index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
	{

        if (!Auth::user()->can('organization add')) {  // TODO: add -> create
            \Session::flash('flash_error_message', 'You do not have access to add a Organizations.');
            if (Auth::user()->can('organization index')) {
                return Redirect::route('organization.index');
            } else {
                return Redirect::route('home');
            }
        }

	    return view('organization.create');
	}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationFormRequest $request)
    {

        $organization = new \App\Organization;

        try {
            $organization->add($request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to process request'
            ], 400);
        }

        \Session::flash('flash_success_message', 'Organizations ' . $organization->name . ' was added.');

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

        if (!Auth::user()->can('organization view')) {
            \Session::flash('flash_error_message', 'You do not have access to view a Organizations.');
            if (Auth::user()->can('organization index')) {
                return Redirect::route('organization.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($organization = $this->sanitizeAndFind($id)) {
            $can_edit = Auth::user()->can('organization edit');
            $can_delete = (Auth::user()->can('organization delete') && $organization->canDelete());
            return view('organization.show', compact('organization','can_edit', 'can_delete'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Organizations to display.');
            return Redirect::route('organization.index');
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
        if (!Auth::user()->can('organization edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a Organizations.');
            if (Auth::user()->can('organization index')) {
                return Redirect::route('organization.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($organization = $this->sanitizeAndFind($id)) {

            info(print_r($organization->toArray(),true));
            return view('organization.edit', compact('organization'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Organizations to edit.');
            return Redirect::route('organization.index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Organization $organization     * @return \Illuminate\Http\Response
     */
    public function update(OrganizationFormRequest $request, $id)
    {

//        if (!Auth::user()->can('organization update')) {
//            \Session::flash('flash_error_message', 'You do not have access to update a Organizations.');
//            if (!Auth::user()->can('organization index')) {
//                return Redirect::route('organization.index');
//            } else {
//                return Redirect::route('home');
//            }
//        }

        if (!$organization = $this->sanitizeAndFind($id)) {
       //     \Session::flash('flash_error_message', 'Unable to find Organizations to edit.');
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }

        $organization->fill($request->all());

        if ($organization->isDirty()) {

            try {
                $organization->save();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request'
                ], 400);
            }

            \Session::flash('flash_success_message', 'Organizations ' . $organization->name . ' was changed.');
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
     * @param  \App\Organization $organization     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!Auth::user()->can('organization delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a Organizations.');
            if (Auth::user()->can('organization index')) {
                 return Redirect::route('organization.index');
            } else {
                return Redirect::route('home');
            }
        }

        $organization = $this->sanitizeAndFind($id);

        if ( $organization  && $organization->canDelete()) {

            try {
                $organization->delete();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request.'
                ], 400);
            }

            \Session::flash('flash_success_message', 'Organizations ' . $organization->name . ' was removed.');
        } else {
            \Session::flash('flash_error_message', 'Unable to find Organizations to delete.');

        }

        if (Auth::user()->can('organization index')) {
             return Redirect::route('organization.index');
        } else {
            return Redirect::route('home');
        }


    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return Organization or null
     */
    private function sanitizeAndFind($id)
    {
        return \App\Organization::find(intval($id));
    }


    public function download()
    {

        if (!Auth::user()->can('organization excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download Organizations.');
            if (Auth::user()->can('organization index')) {
                return Redirect::route('organization.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('organization_keyword', '');
        $column = session('organization_column', 'name');
        $direction = session('organization_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $search");

        $dataQuery = Organization::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new OrganizationExport($dataQuery),
            'organization.xlsx');

    }


        public function print()
{
        if (!Auth::user()->can('organization export-pdf')) { // TODO: i think these permissions may need to be updated to match initial permissions?
            \Session::flash('flash_error_message', 'You do not have access to print Organizations.');
            if (Auth::user()->can('organization index')) {
                return Redirect::route('organization.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('organization_keyword', '');
        $column = session('organization_column', 'name');
        $direction = session('organization_direction', '-1');
        $column = $column ? $column : 'name';

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $search");

        // Get query data
        $columns = [
            'name',
            'contact_name',
            'email',
            'active',
        ];
        $dataQuery = Organization::pdfDataQuery($column, $direction, $search, $columns);
        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('organization.print', compact( 'data' ) );

        // Begin DOMPDF/laravel-dompdf
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => TRUE]);
        $pdf->loadHTML($printHtml);
        $currentDate = new \DateTime(null, new \DateTimeZone('America/Chicago'));
        return $pdf->stream('organization-' . $currentDate->format('Ymd_Hi') . '.pdf');

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
