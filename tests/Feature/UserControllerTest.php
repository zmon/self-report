<?php

namespace Tests\Feature;

use App\User;
use App\User;
use DB;
use Faker;
//use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use function MongoDB\BSON\toJSON;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Class UserControllerTest
 *
 * 1. Test that you must be logged in to access any of the controller functions.
 */
class UserControllerTest extends TestCase
{
    //use RefreshDatabase;
    //------------------------------------------------------------------------------
    // Test that you must be logged in to access any of the controller functions.
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_seeing_user_index()
    {
        $response = $this->get('/user');

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_creating_user()
    {
        $response = $this->get(route('user.create'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_storing_user()
    {
        $response = $this->get(route('user.store'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_showing_user()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('user.show', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_editing_user()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('user.edit', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_updateing_user()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->put(route('user.update', ['id' => 1]));
        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_destroying_user()
    {

        // Should check for permisson before checking to see if record exists
        $response = $this->delete(route('user.destroy', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    //------------------------------------------------------------------------------
    // Test that you must have access any of the controller functions.
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_seeing_user_index()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get('/user');

        // TODO: Check for message???

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_creating_user()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('user.create'));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_storing_user()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->post(route('user.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_showing_user()
    {
        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('user.show', ['id' => 1]));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_editing_user()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('user.edit', ['id' => 1]));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_updateing_user()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->put(route('user.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_destroying_user()
    {
        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('user.destroy', ['id' => 1]));

        $response->assertRedirect('home');
    }

    ////////////

    //------------------------------------------------------------------------------
    // Test that you must have access any of the controller functions
    //   user does have access to index
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_creating_user()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('user.create'));

        $response->assertRedirect('user');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_storing_user()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->post(route('user.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_showing_user()
    {
        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('user.show', ['id' => 1]));

        $response->assertRedirect('user');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_editing_user()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('user.edit', ['id' => 1]));

        $response->assertRedirect('user');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_updating_user()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->put(route('user.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_destroying_user()
    {
        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('user.destroy', ['id' => 1]));

        $response->assertRedirect('user');
    }

    /// ////////

    //------------------------------------------------------------------------------
    // Now lets test that we have the functionality to add, change, delete, and
    //   catch validation errors
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_showing_a_nonexistent_user()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('user.show', ['id' => 100]));

        $response->assertSessionHas('flash_error_message', 'Unable to find Users to display.');
    }

    /**
     * @test
     */
    public function prevent_editing_a_nonexistent_user()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('user.edit', ['id' => 100]));

        $response->assertSessionHas('flash_error_message', 'Unable to find Users to edit.');
    }

    /**
     * @test
     */
    public function it_allows_logged_in_users_to_create_new_user()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('user.create'));

        $response->assertStatus(200);
        $response->assertViewIs('user.create');
        $response->assertSee('user-form');
    }

    /**
     * @test
     */
    public function prevent_creating_a_blank_user()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => '',
            'name' => '',
            'email' => '',
            'active' => '',
        ];

        $totalNumberOfUsersBefore = User::count();

        $response = $this->actingAs($user)->post(route('user.store'), $data);

        $totalNumberOfUsersAfter = User::count();
        $this->assertEquals($totalNumberOfUsersAfter, $totalNumberOfUsersBefore, 'the number of total article is supposed to be the same ');

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0], 'The name field is required.');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_invalid_data_when_creating_a_user()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => '',
            'name' => 'a',
            'email' => 'a',
            'active' => 'a',
        ];

        $totalNumberOfUsersBefore = User::count();

        $response = $this->actingAs($user)->post(route('user.store'), $data);

        $totalNumberOfUsersAfter = User::count();
        $this->assertEquals($totalNumberOfUsersAfter, $totalNumberOfUsersBefore, 'the number of total article is supposed to be the same ');

        $errors = session('errors');

        $this->assertEquals($errors->get('name')[0], 'The name must be at least 3 characters.');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function create_a_user()
    {
        $faker = Faker\Factory::create();
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
          'name' => $faker->name,
          'email' => '',
          'active' => '',
        ];

        info('--  User  --');
        info(print_r($data, true));
        info('----');

        $totalNumberOfUsersBefore = User::count();

        $response = $this->actingAs($user)->post(route('user.store'), $data);

        $totalNumberOfUsersAfter = User::count();

        $errors = session('errors');

        info(print_r($errors, true));

        $this->assertEquals($totalNumberOfUsersAfter, $totalNumberOfUsersBefore + 1, 'the number of total user is supposed to be one more ');

        $lastInsertedInTheDB = User::orderBy('id', 'desc')->first();

        $this->assertEquals($lastInsertedInTheDB->name, $data['name'], 'the name of the saved user is different from the input data');

        $this->assertEquals($lastInsertedInTheDB->email, $data['email'], 'the email of the saved user is different from the input data');

        $this->assertEquals($lastInsertedInTheDB->active, $data['active'], 'the active of the saved user is different from the input data');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_creating_a_duplicate_user()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $totalNumberOfUsersBefore = User::count();

        $user = User::get()->random();
        $data = [
            'id' => '',
            'name' => $user->name,
            'email' => '',
            'active' => '',
        ];

        $response = $this->actingAs($user)->post(route('user.store'), $data);
        $response->assertStatus(302);

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0], 'The name has already been taken.');

        $totalNumberOfUsersAfter = User::count();
        $this->assertEquals($totalNumberOfUsersAfter, $totalNumberOfUsersBefore, 'the number of total user should be the same ');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_changing_user()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = User::get()->random()->toArray();

        $data['name'] = $data['name'].'1';

        $totalNumberOfUsersBefore = User::count();

        $response = $this->actingAs($user)->json('PATCH', 'user/'.$data['id'], $data);

        $response->assertStatus(200);

        $totalNumberOfUsersAfter = User::count();
        $this->assertEquals($totalNumberOfUsersAfter, $totalNumberOfUsersBefore, 'the number of total user should be the same ');
    }

    /**
     * @test
     *
     * Check validation works on change for catching dups
     */
    public function prevent_creating_a_duplicate_by_changing_user()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = User::get()->random()->toArray();

        // Create one that we can duplicate the name for, at this point we only have one user record
        $user_dup = [

            'name' => $faker->name,
            'email' => '',
            'active' => '',
        ];

        $response = $this->actingAs($user)->post(route('user.store'), $user_dup);

        $data['name'] = $user_dup['name'];

        $totalNumberOfUsersBefore = User::count();

        $response = $this->actingAs($user)->json('PATCH', 'user/'.$data['id'], $data);
        $response->assertStatus(422);  // From web page we get a 422

        $errors = session('errors');

        info(print_r($errors, true));

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
            ]);

        $response->assertJsonValidationErrors(['name']);

        $totalNumberOfUsersAfter = User::count();
        $this->assertEquals($totalNumberOfUsersAfter, $totalNumberOfUsersBefore, 'the number of total user should be the same ');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_deleting_user()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = User::get()->random()->toArray();

        $totalNumberOfUsersBefore = User::count();

        $response = $this->actingAs($user)->json('DELETE', 'user/'.$data['id'], $data);

        $totalNumberOfUsersAfter = User::count();
        $this->assertEquals($totalNumberOfUsersAfter, $totalNumberOfUsersBefore - 1, 'the number of total user should be the same ');
    }

    /**
     * Get a random user with optional role and guard
     *
     * @param null $role
     * @param string $guard
     * @return mixed
     */
    public function getRandomUser($role = null, $guard = 'web')
    {
        if ($role) {

            // This should work but throws a 'Spatie\Permission\Exceptions\RoleDoesNotExist: There is no role named `super-admin`.
            $role_id = Role::findByName($role, 'web')->id;

            $sql = "SELECT model_id FROM model_has_roles WHERE model_type = 'App\\\User' AND role_id = $role_id ORDER BY RAND() LIMIT 1";
            $ret = DB::select($sql);
            $user_id = $ret[0]->model_id;

            $this->user = User::find($user_id);
        } else {
            $this->user = User::get()->random();
        }

        return $this->user;
    }
}
