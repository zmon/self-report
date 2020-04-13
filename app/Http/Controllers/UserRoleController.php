<?php

namespace App\Http\Controllers;


use App;
use App\Http\Middleware\TrimStrings;
use App\UserRole;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests\UserRoleFormRequest;
use App\Http\Requests\UserRoleIndexRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Exports\UserRoleExport;
use Maatwebsite\Excel\Facades\Excel;

//use PDF; // TCPDF, not currently in use

class UserRoleController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(UserRoleIndexRequest $request)
    {

        if (!Auth::user()->can('user_role index')) {
            \Session::flash('flash_error_message', 'You do not have access to User Roless.');
            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('user_role_page', '');
        $search = session('user_role_keyword', '');
        $column = session('user_role_column', 'name');
        $direction = session('user_role_direction', '-1');

        $can_add = Auth::user()->can('user_role add');
        $can_show = Auth::user()->can('user_role view');
        $can_edit = Auth::user()->can('user_role edit');
        $can_delete = Auth::user()->can('user_role delete');
        $can_excel = Auth::user()->can('user_role export-excel');
        $can_pdf = Auth::user()->can('user_role pdf');

        return view('user-role.index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        if (!Auth::user()->can('user_role add')) {  // TODO: add -> create
            \Session::flash('flash_error_message', 'You do not have access to add a User Roles.');
            if (Auth::user()->can('user_role index')) {
                return Redirect::route('user-role.index');
            } else {
                return Redirect::route('home');
            }
        }

        $cancel_url = Redirect::back()->getTargetUrl();
        return view('user-role.create', compact('cancel_url'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(UserRoleFormRequest $request)
    {

        $user_role = new UserRole;

        try {
            $attributes = $request->validated();
            $attributes['organization_id'] = session('organization_id', 0);
            unset($attributes['id']);
            $user_role->add($attributes);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unable to process request'
            ], 400);
        }

        \Session::flash('flash_success_message', 'User Roles ' . $user_role->name . ' was added.');

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

        if (!Auth::user()->can('user_role view')) {
            \Session::flash('flash_error_message', 'You do not have access to view a User Roles.');
            if (Auth::user()->can('user_role index')) {
                return Redirect::route('user-role.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($user_role = $this->sanitizeAndFind($id)) {
            $can_edit = Auth::user()->can('user_role edit');
            $can_delete = (Auth::user()->can('user_role delete') && $user_role->canDelete());
            return view('user-role.show', compact('user_role', 'can_edit', 'can_delete'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find User Roles to display.');
            return Redirect::route('user-role.index');
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
        if (!Auth::user()->can('user_role edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a User Roles.');
            if (Auth::user()->can('user_role index')) {
                return Redirect::route('user-role.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($user_role = $this->sanitizeAndFind($id)) {
            $cancel_url = Redirect::back()->getTargetUrl();
            return view('user-role.edit', compact('user_role', 'cancel_url'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find User Roles to edit.');
            return Redirect::route('user-role.index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param UserRole $user_role * @return \Illuminate\Http\Response
     */
    public function update(UserRoleFormRequest $request, $id)
    {

//        if (!Auth::user()->can('user_role update')) {
//            \Session::flash('flash_error_message', 'You do not have access to update a User Roles.');
//            if (!Auth::user()->can('user_role index')) {
//                return Redirect::route('user-role.index');
//            } else {
//                return Redirect::route('home');
//            }
//        }

        if (!$user_role = $this->sanitizeAndFind($id)) {
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }

        $attributes = $request->all();
        $attributes['organization_id'] = session('organization_id', 0);
        $user_role->fill($attributes);

        if ($user_role->isDirty()) {

            try {
                $user_role->save();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request'
                ], 400);
            }

            \Session::flash('flash_success_message', 'User Roles ' . $user_role->name . ' was changed.');
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
     * @param UserRole $user_role * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!Auth::user()->can('user_role delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a User Roles.');
            if (Auth::user()->can('user_role index')) {
                return Redirect::route('user-role.index');
            } else {
                return Redirect::route('home');
            }
        }

        if (!$user_role = $this->sanitizeAndFind($id)) {
            \Session::flash('flash_error_message', 'Unable to find User Roles to delete.');
        } else {

            if ($user_role && $user_role->canDelete()) {

                try {
                    $user_role->delete();
                } catch (Exception $e) {
                    return response()->json([
                        'message' => 'Unable to process request.'
                    ], 400);
                }
                \Session::flash('flash_success_message', 'User Roles ' . $user_role->name . ' was removed.');
            } else {
                \Session::flash('flash_error_message', 'Unable to find User Roles to delete.');
            }
        }

        if (Auth::user()->can('user_role index')) {
            return Redirect::route('user-role.index');
        } else {
            return Redirect::route('home');
        }


    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return UserRole or null
     */
    private function sanitizeAndFind($id)
    {
        return UserRole::where('organization_id', session('organization_id', 0))
            ->where('id', intval($id))
            ->first();
    }


    public function download()
    {

        if (!Auth::user()->can('user_role export-excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download User Roles.');
            if (Auth::user()->can('user_role index')) {
                return Redirect::route('user-role.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('user_role_keyword', '');
        $column = session('user_role_column', 'name');
        $direction = session('user_role_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        $dataQuery = UserRole::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new UserRoleExport($dataQuery),
            'user-role.xlsx');
    }


    public function print()
    {
        if (!Auth::user()->can('user_role export-pdf')) { // TODO: i think these permissions may need to be updated to match initial permissions?
            \Session::flash('flash_error_message', 'You do not have access to print User Roles.');
            if (Auth::user()->can('user_role index')) {
                return Redirect::route('user-role.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('user_role_keyword', '');
        $column = session('user_role_column', 'name');
        $direction = session('user_role_direction', '-1');
        $column = $column ? $column : 'name';


        // Get query data
        $columns = [
            'name',
            'type',
            'alias',
            'sequence',
            'menu_id',
        ];
        $dataQuery = UserRole::pdfDataQuery($column, $direction, $search, $columns);
        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('user-role.print', compact('data'));

        // Begin DOMPDF/laravel-dompdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => TRUE]);
        $pdf->loadHTML($printHtml);
        $currentDate = new DateTime(null, new DateTimeZone('America/Chicago'));
        return $pdf->stream('user-role-' . $currentDate->format('Ymd_Hi') . '.pdf');

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
