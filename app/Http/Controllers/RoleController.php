<?php

namespace App\Http\Controllers;

use App;
use App\Exports\RoleExport;
use App\Http\Middleware\TrimStrings;
use App\Http\Requests\RoleFormRequest;
use App\Http\Requests\RoleIndexRequest;
use App\Role;
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

class RoleController extends Controller
{
    /**
     * Examples
     *
     * Vue component example.
     *
     * <ui-select-pick-one
     * url="/api-role/options"
     * v-model="roleSelected"
     * :selected_id=roleSelected"
     * name="role">
     * </ui-select-pick-one>
     *
     *
     * Blade component example.
     *
     *   In Controler
     *
     * $role_options = \App\Role::getOptions();
     *
     *   In View
     *
     * @component('../components/select-pick-one', [
     * 'fld' => 'role_id',
     * 'selected_id' => $RECORD->role_id,
     * 'first_option' => 'Select a Roles',
     * 'options' => $role_options
     * ])
     * @endcomponent
     *
     * Permissions
     *
     *
     * Permission::findOrCreate('role index');
     * Permission::findOrCreate('role view');
     * Permission::findOrCreate('role export-pdf');
     * Permission::findOrCreate('role export-excel');
     * Permission::findOrCreate('role add');
     * Permission::findOrCreate('role edit');
     * Permission::findOrCreate('role delete');
     */

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(RoleIndexRequest $request)
    {
        if (! Auth::user()->can('role index')) {
            \Session::flash('flash_error_message', 'You do not have access to Roless.');

            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('role_page', '');
        $search = session('role_keyword', '');
        $column = session('role_column', 'name');
        $direction = session('role_direction', '-1');

        $can_add = Auth::user()->can('role add');
        $can_show = Auth::user()->can('role view');
        $can_edit = Auth::user()->can('role edit');
        $can_delete = Auth::user()->can('role delete');
        $can_excel = Auth::user()->can('role export-excel');
        $can_pdf = Auth::user()->can('role pdf');

        return view('role.index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (! Auth::user()->can('role add')) {  // TODO: add -> create
            \Session::flash('flash_error_message', 'You do not have access to add a Roles.');
            if (Auth::user()->can('role index')) {
                return Redirect::route('role.index');
            } else {
                return Redirect::route('home');
            }
        }

        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(RoleFormRequest $request)
    {
        $role = new Role;

        try {
            $role->add($request->validated());
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unable to process request',
            ], 400);
        }

        \Session::flash('flash_success_message', 'Roles '.$role->name.' was added.');

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
        if (! Auth::user()->can('role view')) {
            \Session::flash('flash_error_message', 'You do not have access to view a Roles.');
            if (Auth::user()->can('role index')) {
                return Redirect::route('role.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($role = $this->sanitizeAndFind($id)) {
            $can_edit = Auth::user()->can('role edit');
            $can_delete = (Auth::user()->can('role delete') && $role->canDelete());

            return view('role.show', compact('role', 'can_edit', 'can_delete'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Roles to display.');

            return Redirect::route('role.index');
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
        if (! Auth::user()->can('role edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a Roles.');
            if (Auth::user()->can('role index')) {
                return Redirect::route('role.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($role = $this->sanitizeAndFind($id)) {
            return view('role.edit', compact('role'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Roles to edit.');

            return Redirect::route('role.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role * @return \Illuminate\Http\Response
     */
    public function update(RoleFormRequest $request, $id)
    {

//        if (!Auth::user()->can('role update')) {
//            \Session::flash('flash_error_message', 'You do not have access to update a Roles.');
//            if (!Auth::user()->can('role index')) {
//                return Redirect::route('role.index');
//            } else {
//                return Redirect::route('home');
//            }
//        }

        if (! $role = $this->sanitizeAndFind($id)) {
            //     \Session::flash('flash_error_message', 'Unable to find Roles to edit.');
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        }

        $role->fill($request->all());

        if ($role->isDirty()) {
            try {
                $role->save();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request',
                ], 400);
            }

            \Session::flash('flash_success_message', 'Roles '.$role->name.' was changed.');
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
     * @param Role $role * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Auth::user()->can('role delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a Roles.');
            if (Auth::user()->can('role index')) {
                return Redirect::route('role.index');
            } else {
                return Redirect::route('home');
            }
        }

        $role = $this->sanitizeAndFind($id);

        if ($role && $role->canDelete()) {
            try {
                $role->delete();
            } catch (Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request.',
                ], 400);
            }

            \Session::flash('flash_success_message', 'Roles '.$role->name.' was removed.');
        } else {
            \Session::flash('flash_error_message', 'Unable to find Roles to delete.');
        }

        if (Auth::user()->can('role index')) {
            return Redirect::route('role.index');
        } else {
            return Redirect::route('home');
        }
    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return Role or null
     */
    private function sanitizeAndFind($id)
    {
        return Role::find(intval($id));
    }

    public function download()
    {
        if (! Auth::user()->can('role export-excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download Roles.');
            if (Auth::user()->can('role index')) {
                return Redirect::route('role.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('role_keyword', '');
        $column = session('role_column', 'name');
        $direction = session('role_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        info(__METHOD__.' line: '.__LINE__." $column, $direction, $search");

        $dataQuery = Role::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new RoleExport($dataQuery),
            'role.xlsx');
    }

    public function print()
    {
        if (! Auth::user()->can('role export-pdf')) { // TODO: i think these permissions may need to be updated to match initial permissions?
            \Session::flash('flash_error_message', 'You do not have access to print Roles.');
            if (Auth::user()->can('role index')) {
                return Redirect::route('role.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('role_keyword', '');
        $column = session('role_column', 'name');
        $direction = session('role_direction', '-1');
        $column = $column ? $column : 'name';

        info(__METHOD__.' line: '.__LINE__." $column, $direction, $search");

        // Get query data
        $columns = [
            'name',
            'can_assign',
        ];
        $dataQuery = Role::pdfDataQuery($column, $direction, $search, $columns);
        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('role.print', compact('data'));

        // Begin DOMPDF/laravel-dompdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => true]);
        $pdf->loadHTML($printHtml);
        $currentDate = new DateTime(null, new DateTimeZone('America/Chicago'));

        return $pdf->stream('role-'.$currentDate->format('Ymd_Hi').'.pdf');

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
