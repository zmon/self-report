<?php

namespace App\Http\Controllers;

use App;
use App\Exports\RoleHasPermissionExport;
use App\Http\Middleware\TrimStrings;
use App\Http\Requests\RoleHasPermissionFormRequest;
use App\Http\Requests\RoleHasPermissionIndexRequest;
use App\RoleHasPermission;
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

class RoleHasPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(RoleHasPermissionIndexRequest $request)
    {
        if (! Auth::user()->can('role_has_permission index')) {
            \Session::flash('flash_error_message', 'You do not have access to role_has_permissionss.');

            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('role_has_permission_page', '');
        $search = session('role_has_permission_keyword', '');
        $column = session('role_has_permission_column', 'name');
        $direction = session('role_has_permission_direction', '-1');

        $can_add = Auth::user()->can('role_has_permission add');
        $can_show = Auth::user()->can('role_has_permission view');
        $can_edit = Auth::user()->can('role_has_permission edit');
        $can_delete = Auth::user()->can('role_has_permission delete');
        $can_excel = Auth::user()->can('role_has_permission export-excel');
        $can_pdf = Auth::user()->can('role_has_permission pdf');

        return view('role-has-permission.index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (! Auth::user()->can('role_has_permission add')) {  // TODO: add -> create
            \Session::flash('flash_error_message', 'You do not have access to add a role_has_permissions.');
            if (Auth::user()->can('role_has_permission index')) {
                return Redirect::route('role-has-permission.index');
            } else {
                return Redirect::route('home');
            }
        }

        $cancel_url = Redirect::back()->getTargetUrl();

        return view('role-has-permission.create', compact('cancel_url'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(RoleHasPermissionFormRequest $request)
    {
        $role_has_permission = new RoleHasPermission;

        try {
            $attributes = $request->validated();
            $attributes['organization_id'] = session('organization_id', 0);
            unset($attributes['id']);
            $role_has_permission->add($attributes);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unable to process request',
            ], 400);
        }

        \Session::flash('flash_success_message', 'role_has_permissions '.$role_has_permission->name.' was added.');

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
        if (! Auth::user()->can('role_has_permission view')) {
            \Session::flash('flash_error_message', 'You do not have access to view a role_has_permissions.');
            if (Auth::user()->can('role_has_permission index')) {
                return Redirect::route('role-has-permission.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($role_has_permission = $this->sanitizeAndFind($id)) {
            $can_edit = Auth::user()->can('role_has_permission edit');
            $can_delete = (Auth::user()->can('role_has_permission delete') && $role_has_permission->canDelete());

            return view('role-has-permission.show', compact('role_has_permission', 'can_edit', 'can_delete'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find role_has_permissions to display.');

            return Redirect::route('role-has-permission.index');
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
        if (! Auth::user()->can('role_has_permission edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a role_has_permissions.');
            if (Auth::user()->can('role_has_permission index')) {
                return Redirect::route('role-has-permission.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($role_has_permission = $this->sanitizeAndFind($id)) {
            $cancel_url = Redirect::back()->getTargetUrl();

            return view('role-has-permission.edit', compact('role_has_permission', 'cancel_url'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find role_has_permissions to edit.');

            return Redirect::route('role-has-permission.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param RoleHasPermission $role_has_permission * @return \Illuminate\Http\Response
     */
    public function update(RoleHasPermissionFormRequest $request, $id)
    {

//        if (!Auth::user()->can('role_has_permission update')) {
//            \Session::flash('flash_error_message', 'You do not have access to update a role_has_permissions.');
//            if (!Auth::user()->can('role_has_permission index')) {
//                return Redirect::route('role-has-permission.index');
//            } else {
//                return Redirect::route('home');
//            }
//        }

        if (! $role_has_permission = $this->sanitizeAndFind($id)) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        $attributes = $request->all();
        $attributes['organization_id'] = session('organization_id', 0);
        $role_has_permission->fill($attributes);

        if ($role_has_permission->isDirty()) {
            try {
                $role_has_permission->save();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request',
                ], 400);
            }

            \Session::flash('flash_success_message', 'role_has_permissions '.$role_has_permission->name.' was changed.');
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
     * @param RoleHasPermission $role_has_permission * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Auth::user()->can('role_has_permission delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a role_has_permissions.');
            if (Auth::user()->can('role_has_permission index')) {
                return Redirect::route('role-has-permission.index');
            } else {
                return Redirect::route('home');
            }
        }

        $role_has_permission = $this->sanitizeAndFind($id);

        if ($role_has_permission && $role_has_permission->canDelete()) {
            try {
                $role_has_permission->delete();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request.',
                ], 400);
            }

            \Session::flash('flash_success_message', 'role_has_permissions '.$role_has_permission->name.' was removed.');
        } else {
            \Session::flash('flash_error_message', 'Unable to find role_has_permissions to delete.');
        }

        if (Auth::user()->can('role_has_permission index')) {
            return Redirect::route('role-has-permission.index');
        } else {
            return Redirect::route('home');
        }
    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return RoleHasPermission or null
     */
    private function sanitizeAndFind($id)
    {
        return RoleHasPermission::find(intval($id));
    }

    public function download()
    {
        if (! Auth::user()->can('role_has_permission export-excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download role_has_permissions.');
            if (Auth::user()->can('role_has_permission index')) {
                return Redirect::route('role-has-permission.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('role_has_permission_keyword', '');
        $column = session('role_has_permission_column', 'name');
        $direction = session('role_has_permission_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        info(__METHOD__.' line: '.__LINE__." $column, $direction, $search");

        $dataQuery = RoleHasPermission::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new RoleHasPermissionExport($dataQuery),
            'role-has-permission.xlsx');
    }

    public function print()
    {
        if (! Auth::user()->can('role_has_permission export-pdf')) { // TODO: i think these permissions may need to be updated to match initial permissions?
            \Session::flash('flash_error_message', 'You do not have access to print role_has_permissions.');
            if (Auth::user()->can('role_has_permission index')) {
                return Redirect::route('role-has-permission.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('role_has_permission_keyword', '');
        $column = session('role_has_permission_column', 'name');
        $direction = session('role_has_permission_direction', '-1');
        $column = $column ? $column : 'name';

        info(__METHOD__.' line: '.__LINE__." $column, $direction, $search");

        // Get query data
        $columns = [
        ];
        $dataQuery = RoleHasPermission::pdfDataQuery($column, $direction, $search, $columns);
        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('role-has-permission.print', compact('data'));

        // Begin DOMPDF/laravel-dompdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->loadHTML($printHtml);
        $currentDate = new DateTime(null, new DateTimeZone('America/Chicago'));

        return $pdf->stream('role-has-permission-'.$currentDate->format('Ymd_Hi').'.pdf');

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
