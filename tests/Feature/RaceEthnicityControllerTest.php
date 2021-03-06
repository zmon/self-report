<?php

namespace Tests\Feature;

use function MongoDB\BSON\toJSON;
use Tests\TestCase;

use App\RaceEthnicity;
use Faker;

//use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\DatabaseTransactions;


use DB;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

/**
 * Class RaceEthnicityControllerTest
 *
 * 1. Test that you must be logged in to access any of the controller functions.
 *
 * @package Tests\Feature
 */
class RaceEthnicityControllerTest extends TestCase
{

    //use RefreshDatabase;
    //------------------------------------------------------------------------------
    // Test that you must be logged in to access any of the controller functions.
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_seeing_race_ethnicity_index()
    {
        $response = $this->get('/race-ethnicity');

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_creating_race_ethnicity()
    {
        $response = $this->get(route('race-ethnicity.create'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_storing_race_ethnicity()
    {
        $response = $this->get(route('race-ethnicity.store'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_showing_race_ethnicity()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('race-ethnicity.show', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_editing_race_ethnicity()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('race-ethnicity.edit', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }


    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_updateing_race_ethnicity()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->put(route('race-ethnicity.update', ['id' => 1]));
        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }


    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_destroying_race_ethnicity()
    {

        // Should check for permisson before checking to see if record exists
        $response = $this->delete(route('race-ethnicity.destroy', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    //------------------------------------------------------------------------------
    // Test that you must have access any of the controller functions.
    //------------------------------------------------------------------------------


    /**
     * @test
     */
    public function prevent_users_without_permissions_from_seeing_race_ethnicity_index()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get('/race-ethnicity');

        // TODO: Check for message???

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_creating_race_ethnicity()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('race-ethnicity.create'));

        $response->assertRedirect('home');
    }


    /**
     * @test
     */
    public function prevent_users_without_permissions_from_storing_race_ethnicity()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->post(route('race-ethnicity.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized

    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_showing_race_ethnicity()
    {

        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('race-ethnicity.show', ['id' => 1]));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_editing_race_ethnicity()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('race-ethnicity.edit', ['id' => 1]));

        $response->assertRedirect('home');
    }


    /**
     * @test
     */
    public function prevent_users_without_permissions_from_updateing_race_ethnicity()
    {

        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->put(route('race-ethnicity.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized

    }


    /**
     * @test
     */
    public function prevent_users_without_permissions_from_destroying_race_ethnicity()
    {

        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('race-ethnicity.destroy', ['id' => 1]));

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
    public function prevent_users_withonly_index_permissions_from_creating_race_ethnicity()
    {

        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('race-ethnicity.create'));

        $response->assertRedirect('race-ethnicity');
    }


    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_storing_race_ethnicity()
    {

        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->post(route('race-ethnicity.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized

    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_showing_race_ethnicity()
    {

        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('race-ethnicity.show', ['id' => 1]));

        $response->assertRedirect('race-ethnicity');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_editing_race_ethnicity()
    {

        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('race-ethnicity.edit', ['id' => 1]));

        $response->assertRedirect('race-ethnicity');
    }


    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_updating_race_ethnicity()
    {

        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->put(route('race-ethnicity.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized

    }


    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_destroying_race_ethnicity()
    {

        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('race-ethnicity.destroy', ['id' => 1]));

        $response->assertRedirect('race-ethnicity');
    }

    /// ////////

    //------------------------------------------------------------------------------
    // Now lets test that we have the functionality to add, change, delete, and
    //   catch validation errors
    //------------------------------------------------------------------------------
    /**
     * @test
     */
    public function prevent_showing_a_nonexistent_race_ethnicity()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('race-ethnicity.show',['id' => 100]));

        $response->assertSessionHas('flash_error_message','Unable to find Rax Ethnicity to display.');

    }

    /**
     * @test
     */
    public function prevent_editing_a_nonexistent_race_ethnicity()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('race-ethnicity.edit',['id' => 100]));

        $response->assertSessionHas('flash_error_message','Unable to find Rax Ethnicity to edit.');

    }




    /**
     * @test
     */
    public function it_allows_logged_in_users_to_create_new_race_ethnicity()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('race-ethnicity.create'));

        $response->assertStatus(200);
        $response->assertViewIs('race-ethnicity.create');
        $response->assertSee('race-ethnicity-form');

    }

    /**
     * @test
     */
    public function prevent_creating_a_blank_race_ethnicity()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => "",
            'name' => "",
        ];

        $totalNumberOfRaceEthnicitiesBefore = RaceEthnicity::count();

        $response = $this->actingAs($user)->post(route('race-ethnicity.store'), $data);

        $totalNumberOfRaceEthnicitiesAfter = RaceEthnicity::count();
        $this->assertEquals($totalNumberOfRaceEthnicitiesAfter, $totalNumberOfRaceEthnicitiesBefore, "the number of total article is supposed to be the same ");

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0],"The name field is required.");

    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_invalid_data_when_creating_a_race_ethnicity()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => "",
            'name' => "a",
        ];

        $totalNumberOfRaceEthnicitiesBefore = RaceEthnicity::count();

        $response = $this->actingAs($user)->post(route('race-ethnicity.store'), $data);

        $totalNumberOfRaceEthnicitiesAfter = RaceEthnicity::count();
        $this->assertEquals($totalNumberOfRaceEthnicitiesAfter, $totalNumberOfRaceEthnicitiesBefore, "the number of total article is supposed to be the same ");

        $errors = session('errors');

        $this->assertEquals($errors->get('name')[0],"The name must be at least 3 characters.");

    }

    /**
     * @test
     *
     * Check validation works
     */
    public function create_a_race_ethnicity()
    {

        $faker = Faker\Factory::create();
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
          'name' => $faker->name,
        ];

        info('--  RaceEthnicity  --');
         info(print_r($data,true));
          info('----');

        $totalNumberOfRaceEthnicitiesBefore = RaceEthnicity::count();

        $response = $this->actingAs($user)->post(route('race-ethnicity.store'), $data);

        $totalNumberOfRaceEthnicitiesAfter = RaceEthnicity::count();


        $errors = session('errors');

        info(print_r($errors,true));

        $this->assertEquals($totalNumberOfRaceEthnicitiesAfter, $totalNumberOfRaceEthnicitiesBefore + 1, "the number of total race_ethnicity is supposed to be one more ");

        $lastInsertedInTheDB = RaceEthnicity::orderBy('id', 'desc')->first();


        $this->assertEquals($lastInsertedInTheDB->name, $data['name'], "the name of the saved race_ethnicity is different from the input data");


    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_creating_a_duplicate_race_ethnicity()
    {

        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');


        $totalNumberOfRaceEthnicitiesBefore = RaceEthnicity::count();

        $race_ethnicity = RaceEthnicity::get()->random();
        $data = [
            'id' => "",
            'name' => $race_ethnicity->name,
        ];

        $response = $this->actingAs($user)->post(route('race-ethnicity.store'), $data);
        $response->assertStatus(302);

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0],"The name has already been taken.");

        $totalNumberOfRaceEthnicitiesAfter = RaceEthnicity::count();
        $this->assertEquals($totalNumberOfRaceEthnicitiesAfter, $totalNumberOfRaceEthnicitiesBefore, "the number of total race_ethnicity should be the same ");

    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_changing_race_ethnicity()
    {

        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = RaceEthnicity::get()->random()->toArray();

        $data['name'] = $data['name'] . '1';

        $totalNumberOfRaceEthnicitiesBefore = RaceEthnicity::count();

        $response = $this->actingAs($user)->json('PATCH', 'race-ethnicity/' . $data['id'], $data);

        $response->assertStatus(200);

        $totalNumberOfRaceEthnicitiesAfter = RaceEthnicity::count();
        $this->assertEquals($totalNumberOfRaceEthnicitiesAfter, $totalNumberOfRaceEthnicitiesBefore, "the number of total race_ethnicity should be the same ");

    }



    /**
     * @test
     *
     * Check validation works on change for catching dups
     */
    public function prevent_creating_a_duplicate_by_changing_race_ethnicity()
    {

        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = RaceEthnicity::get()->random()->toArray();



        // Create one that we can duplicate the name for, at this point we only have one race_ethnicity record
        $race_ethnicity_dup = [

            'name' => $faker->name,
        ];

        $response = $this->actingAs($user)->post(route('race-ethnicity.store'), $race_ethnicity_dup);


        $data['name'] = $race_ethnicity_dup['name'];

        $totalNumberOfRaceEthnicitiesBefore = RaceEthnicity::count();

        $response = $this->actingAs($user)->json('PATCH', 'race-ethnicity/' . $data['id'], $data);
        $response->assertStatus(422);  // From web page we get a 422

        $errors = session('errors');

        info(print_r($errors,true));

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.'
            ]);

        $response->assertJsonValidationErrors(['name']);

        $totalNumberOfRaceEthnicitiesAfter = RaceEthnicity::count();
        $this->assertEquals($totalNumberOfRaceEthnicitiesAfter, $totalNumberOfRaceEthnicitiesBefore, "the number of total race_ethnicity should be the same ");

    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_deleting_race_ethnicity()
    {

        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = RaceEthnicity::get()->random()->toArray();


        $totalNumberOfRaceEthnicitiesBefore = RaceEthnicity::count();

        $response = $this->actingAs($user)->json('DELETE', 'race-ethnicity/' . $data['id'], $data);

        $totalNumberOfRaceEthnicitiesAfter = RaceEthnicity::count();
        $this->assertEquals($totalNumberOfRaceEthnicitiesAfter, $totalNumberOfRaceEthnicitiesBefore - 1, "the number of total race_ethnicity should be the same ");

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
