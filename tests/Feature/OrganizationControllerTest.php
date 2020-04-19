<?php

namespace Tests\Feature;

use function MongoDB\BSON\toJSON;
use Tests\TestCase;

use App\Organization;
use Faker;

//use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\DatabaseTransactions;


use DB;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

/**
 * Class OrganizationControllerTest
 *
 * 1. Test that you must be logged in to access any of the controller functions.
 *
 * @package Tests\Feature
 */
class OrganizationControllerTest extends TestCase
{

    //use RefreshDatabase;
    //------------------------------------------------------------------------------
    // Test that you must be logged in to access any of the controller functions.
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_seeing_organization_index()
    {
        $response = $this->get('/organization');

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_creating_organization()
    {
        $response = $this->get(route('organization.create'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_storing_organization()
    {
        $response = $this->get(route('organization.store'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_showing_organization()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('organization.show', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_editing_organization()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('organization.edit', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }


    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_updateing_organization()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->put(route('organization.update', ['id' => 1]));
        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }


    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_destroying_organization()
    {

        // Should check for permisson before checking to see if record exists
        $response = $this->delete(route('organization.destroy', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    //------------------------------------------------------------------------------
    // Test that you must have access any of the controller functions.
    //------------------------------------------------------------------------------


    /**
     * @test
     */
    public function prevent_users_without_permissions_from_seeing_organization_index()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get('/organization');

        // TODO: Check for message???

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_creating_organization()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('organization.create'));

        $response->assertRedirect('home');
    }


    /**
     * @test
     */
    public function prevent_users_without_permissions_from_storing_organization()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->post(route('organization.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized

    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_showing_organization()
    {

        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('organization.show', ['id' => 1]));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_editing_organization()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('organization.edit', ['id' => 1]));

        $response->assertRedirect('home');
    }


    /**
     * @test
     */
    public function prevent_users_without_permissions_from_updateing_organization()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->put(route('organization.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized

    }


    /**
     * @test
     */
    public function prevent_users_without_permissions_from_destroying_organization()
    {

        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('organization.destroy', ['id' => 1]));

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
    public function prevent_users_withonly_index_permissions_from_creating_organization()
    {

        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('organization.create'));

        $response->assertRedirect('organization');
    }


    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_storing_organization()
    {

        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->post(route('organization.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized

    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_showing_organization()
    {

        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('organization.show', ['id' => 1]));

        $response->assertRedirect('organization');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_editing_organization()
    {

        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('organization.edit', ['id' => 1]));

        $response->assertRedirect('organization');
    }


    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_updating_organization()
    {

        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->put(route('organization.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized

    }


    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_destroying_organization()
    {

        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('organization.destroy', ['id' => 1]));

        $response->assertRedirect('organization');
    }

    /// ////////

    //------------------------------------------------------------------------------
    // Now lets test that we have the functionality to add, change, delete, and
    //   catch validation errors
    //------------------------------------------------------------------------------
    /**
     * @test
     */
    public function prevent_showing_a_nonexistent_organization()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('organization.show',['id' => 100]));

        $response->assertSessionHas('flash_error_message','Unable to find Organizations to display.');

    }

    /**
     * @test
     */
    public function prevent_editing_a_nonexistent_organization()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('organization.edit',['id' => 100]));

        $response->assertSessionHas('flash_error_message','Unable to find Organizations to edit.');

    }




    /**
     * @test
     */
    public function it_allows_logged_in_users_to_create_new_organization()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('organization.create'));

        $response->assertStatus(200);
        $response->assertViewIs('organization.create');
        $response->assertSee('organization-form');

    }

    /**
     * @test
     */
    public function prevent_creating_a_blank_organization()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => "",
            'name' => "",
            'alias' => "",
        ];

        $totalNumberOfOrganizationsBefore = Organization::count();

        $response = $this->actingAs($user)->post(route('organization.store'), $data);

        $totalNumberOfOrganizationsAfter = Organization::count();
        $this->assertEquals($totalNumberOfOrganizationsAfter, $totalNumberOfOrganizationsBefore, "the number of total article is supposed to be the same ");

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0],"The name field is required.");

    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_invalid_data_when_creating_a_organization()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => "",
            'name' => "a",
            'alias' => "a",
        ];

        $totalNumberOfOrganizationsBefore = Organization::count();

        $response = $this->actingAs($user)->post(route('organization.store'), $data);

        $totalNumberOfOrganizationsAfter = Organization::count();
        $this->assertEquals($totalNumberOfOrganizationsAfter, $totalNumberOfOrganizationsBefore, "the number of total article is supposed to be the same ");

        $errors = session('errors');

        $this->assertEquals($errors->get('name')[0],"The name must be at least 3 characters.");

    }

    /**
     * @test
     *
     * Check validation works
     */
    public function create_a_organization()
    {

        $faker = Faker\Factory::create();
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
          'name' => $faker->name,
          'alias' => "",
        ];

        info('--  Organization  --');
         info(print_r($data,true));
          info('----');

        $totalNumberOfOrganizationsBefore = Organization::count();

        $response = $this->actingAs($user)->post(route('organization.store'), $data);

        $totalNumberOfOrganizationsAfter = Organization::count();


        $errors = session('errors');

        info(print_r($errors,true));

        $this->assertEquals($totalNumberOfOrganizationsAfter, $totalNumberOfOrganizationsBefore + 1, "the number of total organization is supposed to be one more ");

        $lastInsertedInTheDB = Organization::orderBy('id', 'desc')->first();


        $this->assertEquals($lastInsertedInTheDB->name, $data['name'], "the name of the saved organization is different from the input data");


        $this->assertEquals($lastInsertedInTheDB->alias, $data['alias'], "the alias of the saved organization is different from the input data");


    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_creating_a_duplicate_organization()
    {

        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');


        $totalNumberOfOrganizationsBefore = Organization::count();

        $organization = Organization::get()->random();
        $data = [
            'id' => "",
            'name' => $organization->name,
            'alias' => "",
        ];

        $response = $this->actingAs($user)->post(route('organization.store'), $data);
        $response->assertStatus(302);

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0],"The name has already been taken.");

        $totalNumberOfOrganizationsAfter = Organization::count();
        $this->assertEquals($totalNumberOfOrganizationsAfter, $totalNumberOfOrganizationsBefore, "the number of total organization should be the same ");

    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_changing_organization()
    {

        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = Organization::get()->random()->toArray();

        $data['name'] = $data['name'] . '1';

        $totalNumberOfOrganizationsBefore = Organization::count();

        $response = $this->actingAs($user)->json('PATCH', 'organization/' . $data['id'], $data);

        $response->assertStatus(200);

        $totalNumberOfOrganizationsAfter = Organization::count();
        $this->assertEquals($totalNumberOfOrganizationsAfter, $totalNumberOfOrganizationsBefore, "the number of total organization should be the same ");

    }



    /**
     * @test
     *
     * Check validation works on change for catching dups
     */
    public function prevent_creating_a_duplicate_by_changing_organization()
    {

        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = Organization::get()->random()->toArray();



        // Create one that we can duplicate the name for, at this point we only have one organization record
        $organization_dup = [

            'name' => $faker->name,
            'alias' => "",
        ];

        $response = $this->actingAs($user)->post(route('organization.store'), $organization_dup);


        $data['name'] = $organization_dup['name'];

        $totalNumberOfOrganizationsBefore = Organization::count();

        $response = $this->actingAs($user)->json('PATCH', 'organization/' . $data['id'], $data);
        $response->assertStatus(422);  // From web page we get a 422

        $errors = session('errors');

        info(print_r($errors,true));

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.'
            ]);

        $response->assertJsonValidationErrors(['name']);

        $totalNumberOfOrganizationsAfter = Organization::count();
        $this->assertEquals($totalNumberOfOrganizationsAfter, $totalNumberOfOrganizationsBefore, "the number of total organization should be the same ");

    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_deleting_organization()
    {

        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = Organization::get()->random()->toArray();


        $totalNumberOfOrganizationsBefore = Organization::count();

        $response = $this->actingAs($user)->json('DELETE', 'organization/' . $data['id'], $data);

        $totalNumberOfOrganizationsAfter = Organization::count();
        $this->assertEquals($totalNumberOfOrganizationsAfter, $totalNumberOfOrganizationsBefore - 1, "the number of total organization should be the same ");

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
            $role_id = Role::findByName($role,'web')->id;

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
