<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Http\Requests\UserIndexRequest;
use App\UserRole;
use Exception;;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\User;
use App\Organization;
use Illuminate\Support\Facades\Session;

use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
//use PDF; // TCPDF, not currently in use

class UserController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserIndexRequest $request)
    {

        if (!Auth::user()->can('user index')) {
            \Session::flash('flash_error_message', 'You do not have access to Users.');
            return Redirect::route('home');
        }

        // Remember the search parameters, we saved them in the Query
        $page = session('user_page', '');
        $search = session('user_keyword', '');
        $column = session('user_column', 'Name');
        $direction = session('user_direction', '-1');

        $can_add = Auth::user()->can('user add');
        $can_show = Auth::user()->can('user view');
        $can_edit = Auth::user()->can('user edit');
        $can_delete = Auth::user()->can('user delete');
        $can_excel = Auth::user()->can('user excel');
        $can_pdf = Auth::user()->can('user pdf');

        return view('user.index', compact('page', 'column', 'direction', 'search', 'can_add', 'can_edit', 'can_delete', 'can_show', 'can_excel', 'can_pdf'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function create()
	{

        if (!Auth::user()->can('user add')) {  // TODO: add -> create
            \Session::flash('flash_error_message', 'You do not have access to add a Users.');
            if (Auth::user()->can('user index')) {
                return Redirect::route('user.index');
            } else {
                return Redirect::route('home');
            }
        }

	    return view('user.create');
	}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {

        $user = new \App\User;

        try {
            $user->add($request->validated());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to process request'
            ], 400);
        }

        \Session::flash('flash_success_message', 'Users ' . $user->name . ' was added.');

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

        if (!Auth::user()->can('user view')) {
            \Session::flash('flash_error_message', 'You do not have access to view a Users.');
            if (Auth::user()->can('user index')) {
                return Redirect::route('user.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($user = $this->sanitizeAndFind($id)) {
            $can_edit = Auth::user()->can('user edit');
            $can_delete = (Auth::user()->can('user delete') && $user->canDelete());
            return view('user.show', compact('user','can_edit', 'can_delete'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Users to display.');
            return Redirect::route('user.index');
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
        if (!Auth::user()->can('user edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a Users.');
            if (Auth::user()->can('user index')) {
                return Redirect::route('user.index');
            } else {
                return Redirect::route('home');
            }
        }

        if ($user = $this->sanitizeAndFind($id)) {
            $role_name = $user->getRoleNames();
            return view('user.edit', compact('user', 'role_name'));
        } else {
            \Session::flash('flash_error_message', 'Unable to find Users to edit.');
            return Redirect::route('user.index');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, $id)
    {

//        if (!Auth::user()->can('user update')) {
//            \Session::flash('flash_error_message', 'You do not have access to update a Users.');
//            if (!Auth::user()->can('user index')) {
//                return Redirect::route('user.index');
//            } else {
//                return Redirect::route('home');
//            }
//        }

        if (!$user = $this->sanitizeAndFind($id)) {
       //     \Session::flash('flash_error_message', 'Unable to find Users to edit.');
            return response()->json([
                'message' => 'Not Found'
            ], 404);
        }

        $current_hashed_password = $user->password;

        $user->fill($request->all());

        // Hash the pw (but only if a new one has been set)
        if (!empty($request->password)) {
            // Reset
            $user->password = bcrypt($request->password);
        } else {
            // Preserve current value
            $user->password = $current_hashed_password;
        }



        if ($user->isDirty() || $user->areRolesDirty($user->roles, $request->selected_roles)) {
            try {
                $user->save();
                $user->syncRoles($request->selected_roles);
                info('roles');
            } catch (Exception $e) {
                info($e->getMessage());
                return response()->json([
                    'message' => 'Unable to process request'
                ], 400);
            }

            // Process Role

            $current_role_name = $user->getRoleNames();

            if ($current_role_name != $user->role_name) {
                info('new role = ' . $user->role_name);
            }

            \Session::flash('flash_success_message', 'User ' . $user->name . ' was changed.');
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
     * @param  \App\User $user     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!Auth::user()->can('user delete')) {
            \Session::flash('flash_error_message', 'You do not have access to remove a Users.');
            if (Auth::user()->can('user index')) {
                 return Redirect::route('user.index');
            } else {
                return Redirect::route('home');
            }
        }

        $user = $this->sanitizeAndFind($id);

        if ( $user  && $user->canDelete()) {

            try {
                $user->delete();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Unable to process request.'
                ], 400);
            }

            \Session::flash('flash_success_message', 'Users ' . $user->name . ' was removed.');
        } else {
            \Session::flash('flash_error_message', 'Unable to find Users to delete.');

        }

        if (Auth::user()->can('user index')) {
             return Redirect::route('user.index');
        } else {
            return Redirect::route('home');
        }


    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return User or null
     */
    private function sanitizeAndFind($id)
    {
        return \App\User::with('organization')->find(intval($id));
    }


    public function download()
    {

        if (!Auth::user()->can('user excel')) {
            \Session::flash('flash_error_message', 'You do not have access to download Users.');
            if (Auth::user()->can('user index')) {
                return Redirect::route('user.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('user_keyword', '');
        $column = session('user_column', 'name');
        $direction = session('user_direction', '-1');

        $column = $column ? $column : 'name';

        // #TODO wrap in a try/catch and display english message on failuer.

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $search");

        $dataQuery = User::exportDataQuery($column, $direction, $search);
        //dump($data->toArray());
        //if ($data->count() > 0) {

        // TODO: is it possible to do 0 check before query executes somehow? i think the query would have to be executed twice, once for count, once for excel library
        return Excel::download(
            new UserExport($dataQuery),
            'user.xlsx');

    }


        public function print()
{
        if (!Auth::user()->can('user export-pdf')) { // TODO: i think these permissions may need to be updated to match initial permissions?
            \Session::flash('flash_error_message', 'You do not have access to print Users.');
            if (Auth::user()->can('user index')) {
                return Redirect::route('user.index');
            } else {
                return Redirect::route('home');
            }
        }

        // Remember the search parameters, we saved them in the Query
        $search = session('user_keyword', '');
        $column = session('user_column', 'name');
        $direction = session('user_direction', '-1');
        $column = $column ? $column : 'name';

        info(__METHOD__ . ' line: ' . __LINE__ . " $column, $direction, $search");

        // Get query data
        $columns = [
            'name',
            'email',
            'active',
        ];
        $dataQuery = User::pdfDataQuery($column, $direction, $search, $columns);
        $data = $dataQuery->get();

        // Pass it to the view for html formatting:
        $printHtml = view('user.print', compact( 'data' ) );

        // Begin DOMPDF/laravel-dompdf
        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['isPhpEnabled' => TRUE]);
        $pdf->loadHTML($printHtml);
        $currentDate = new \DateTime(null, new \DateTimeZone('America/Chicago'));
        return $pdf->stream('user-' . $currentDate->format('Ymd_Hi') . '.pdf');

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
