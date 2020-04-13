<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\UserRole;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\User;
use App\Organization;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    public function __construct()
    {

        //       $this->middleware(['auth', 'admin']);


    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index()
    {

        if (!Auth::user()->isAllowed('user')) {
            Session::flash('flash_error_message', 'You do not have access.');
            return Redirect::route('home.r');
        }
        $users = User::paginate(10);

        return view('user.index', compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function create()
    {

        if (!Auth::user()->isAllowed('user')) {
            Session::flash('flash_error_message', 'You do not have access.');
            return Redirect::route('home.r');
        }

        return view('user.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */

    public function store(Request $request)
    {

        if (!Auth::user()->isAllowed('user')) {
            Session::flash('flash_error_message', 'You do not have access.');
            return Redirect::route('home.r');
        }

        $this->validate($request, [

            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',

        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('xxxxssssswwwww333'),
        ]);


        $org = Organization::find(session('organization_id', 0));

        $org->users()->attach([$user->id => ['user_role_id' => UserRole::getIdFromMenuId($organization_id, 20)]]);

        return Redirect::route('client.index');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */

    public function show($id)
    {

        if (!Auth::user()->isAllowed('user')) {
            Session::flash('flash_error_message', 'You do not have access.');
            return Redirect::route('home.r');
        }

        $user = User::find($id);

        $profile = $user->profile;

        return view('user.show', compact('user', 'profile'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */

    public function edit($id)
    {

        if (!Auth::user()->can('user edit')) {
            \Session::flash('flash_error_message', 'You do not have access to edit a User.');
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
            \Session::flash('flash_error_message', 'Unable to find User to edit.');
            return Redirect::route('user.index');
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, $id)
    {


        if (!$user = $this->sanitizeAndFind($id)) {
            //     \Session::flash('flash_error_message', 'Unable to find User to edit.');
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
     * @param int $id
     * @return Response
     */

    public function destroy($id)
    {

        if (!Auth::user()->isAllowed('user')) {
            Session::flash('flash_error_message', 'You do not have access.');
            return Redirect::route('home.r');
        }

        User::destroy($id);

        alert()->overlay('Attention!', 'You deleted a user', 'error');

        return Redirect::route('user.index');
    }

    /**
     * Find by ID, sanitize the ID first
     *
     * @param $id
     * @return User or null
     */
    private function sanitizeAndFind($id)
    {
        return User::find(intval($id));
    }
}
