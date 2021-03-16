<?php

namespace Tests\Feature;

use App\PreexistingCondition;
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
 * Class PreexistingConditionControllerTest
 *
 * 1. Test that you must be logged in to access any of the controller functions.
 */
class PreexistingConditionControllerTest extends TestCase
{
    //use RefreshDatabase;
    //------------------------------------------------------------------------------
    // Test that you must be logged in to access any of the controller functions.
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_seeing_preexisting_condition_index()
    {
        $response = $this->get('/preexisting-condition');

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_creating_preexisting_condition()
    {
        $response = $this->get(route('preexisting-condition.create'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_storing_preexisting_condition()
    {
        $response = $this->get(route('preexisting-condition.store'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_showing_preexisting_condition()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('preexisting-condition.show', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_editing_preexisting_condition()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('preexisting-condition.edit', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_updateing_preexisting_condition()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->put(route('preexisting-condition.update', ['id' => 1]));
        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_destroying_preexisting_condition()
    {

        // Should check for permisson before checking to see if record exists
        $response = $this->delete(route('preexisting-condition.destroy', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    //------------------------------------------------------------------------------
    // Test that you must have access any of the controller functions.
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_seeing_preexisting_condition_index()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get('/preexisting-condition');

        // TODO: Check for message???

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_creating_preexisting_condition()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('preexisting-condition.create'));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_storing_preexisting_condition()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->post(route('preexisting-condition.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_showing_preexisting_condition()
    {
        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('preexisting-condition.show', ['id' => 1]));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_editing_preexisting_condition()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('preexisting-condition.edit', ['id' => 1]));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_updateing_preexisting_condition()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->put(route('preexisting-condition.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_destroying_preexisting_condition()
    {
        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('preexisting-condition.destroy', ['id' => 1]));

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
    public function prevent_users_withonly_index_permissions_from_creating_preexisting_condition()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('preexisting-condition.create'));

        $response->assertRedirect('preexisting-condition');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_storing_preexisting_condition()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->post(route('preexisting-condition.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_showing_preexisting_condition()
    {
        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('preexisting-condition.show', ['id' => 1]));

        $response->assertRedirect('preexisting-condition');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_editing_preexisting_condition()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('preexisting-condition.edit', ['id' => 1]));

        $response->assertRedirect('preexisting-condition');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_updating_preexisting_condition()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->put(route('preexisting-condition.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_destroying_preexisting_condition()
    {
        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('preexisting-condition.destroy', ['id' => 1]));

        $response->assertRedirect('preexisting-condition');
    }

    /// ////////

    //------------------------------------------------------------------------------
    // Now lets test that we have the functionality to add, change, delete, and
    //   catch validation errors
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_showing_a_nonexistent_preexisting_condition()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('preexisting-condition.show', ['id' => 100]));

        $response->assertSessionHas('flash_error_message', 'Unable to find Preexisting Conditions to display.');
    }

    /**
     * @test
     */
    public function prevent_editing_a_nonexistent_preexisting_condition()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('preexisting-condition.edit', ['id' => 100]));

        $response->assertSessionHas('flash_error_message', 'Unable to find Preexisting Conditions to edit.');
    }

    /**
     * @test
     */
    public function it_allows_logged_in_users_to_create_new_preexisting_condition()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('preexisting-condition.create'));

        $response->assertStatus(200);
        $response->assertViewIs('preexisting-condition.create');
        $response->assertSee('preexisting-condition-form');
    }

    /**
     * @test
     */
    public function prevent_creating_a_blank_preexisting_condition()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => '',
            'name' => '',
        ];

        $totalNumberOfPreexistingConditionsBefore = PreexistingCondition::count();

        $response = $this->actingAs($user)->post(route('preexisting-condition.store'), $data);

        $totalNumberOfPreexistingConditionsAfter = PreexistingCondition::count();
        $this->assertEquals($totalNumberOfPreexistingConditionsAfter, $totalNumberOfPreexistingConditionsBefore, 'the number of total article is supposed to be the same ');

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0], 'The name field is required.');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_invalid_data_when_creating_a_preexisting_condition()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => '',
            'name' => 'a',
        ];

        $totalNumberOfPreexistingConditionsBefore = PreexistingCondition::count();

        $response = $this->actingAs($user)->post(route('preexisting-condition.store'), $data);

        $totalNumberOfPreexistingConditionsAfter = PreexistingCondition::count();
        $this->assertEquals($totalNumberOfPreexistingConditionsAfter, $totalNumberOfPreexistingConditionsBefore, 'the number of total article is supposed to be the same ');

        $errors = session('errors');

        $this->assertEquals($errors->get('name')[0], 'The name must be at least 3 characters.');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function create_a_preexisting_condition()
    {
        $faker = Faker\Factory::create();
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
          'name' => $faker->name,
        ];

        info('--  PreexistingCondition  --');
        info(print_r($data, true));
        info('----');

        $totalNumberOfPreexistingConditionsBefore = PreexistingCondition::count();

        $response = $this->actingAs($user)->post(route('preexisting-condition.store'), $data);

        $totalNumberOfPreexistingConditionsAfter = PreexistingCondition::count();

        $errors = session('errors');

        info(print_r($errors, true));

        $this->assertEquals($totalNumberOfPreexistingConditionsAfter, $totalNumberOfPreexistingConditionsBefore + 1, 'the number of total preexisting_condition is supposed to be one more ');

        $lastInsertedInTheDB = PreexistingCondition::orderBy('id', 'desc')->first();

        $this->assertEquals($lastInsertedInTheDB->name, $data['name'], 'the name of the saved preexisting_condition is different from the input data');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_creating_a_duplicate_preexisting_condition()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $totalNumberOfPreexistingConditionsBefore = PreexistingCondition::count();

        $preexisting_condition = PreexistingCondition::get()->random();
        $data = [
            'id' => '',
            'name' => $preexisting_condition->name,
        ];

        $response = $this->actingAs($user)->post(route('preexisting-condition.store'), $data);
        $response->assertStatus(302);

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0], 'The name has already been taken.');

        $totalNumberOfPreexistingConditionsAfter = PreexistingCondition::count();
        $this->assertEquals($totalNumberOfPreexistingConditionsAfter, $totalNumberOfPreexistingConditionsBefore, 'the number of total preexisting_condition should be the same ');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_changing_preexisting_condition()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = PreexistingCondition::get()->random()->toArray();

        $data['name'] = $data['name'].'1';

        $totalNumberOfPreexistingConditionsBefore = PreexistingCondition::count();

        $response = $this->actingAs($user)->json('PATCH', 'preexisting-condition/'.$data['id'], $data);

        $response->assertStatus(200);

        $totalNumberOfPreexistingConditionsAfter = PreexistingCondition::count();
        $this->assertEquals($totalNumberOfPreexistingConditionsAfter, $totalNumberOfPreexistingConditionsBefore, 'the number of total preexisting_condition should be the same ');
    }

    /**
     * @test
     *
     * Check validation works on change for catching dups
     */
    public function prevent_creating_a_duplicate_by_changing_preexisting_condition()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = PreexistingCondition::get()->random()->toArray();

        // Create one that we can duplicate the name for, at this point we only have one preexisting_condition record
        $preexisting_condition_dup = [

            'name' => $faker->name,
        ];

        $response = $this->actingAs($user)->post(route('preexisting-condition.store'), $preexisting_condition_dup);

        $data['name'] = $preexisting_condition_dup['name'];

        $totalNumberOfPreexistingConditionsBefore = PreexistingCondition::count();

        $response = $this->actingAs($user)->json('PATCH', 'preexisting-condition/'.$data['id'], $data);
        $response->assertStatus(422);  // From web page we get a 422

        $errors = session('errors');

        info(print_r($errors, true));

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
            ]);

        $response->assertJsonValidationErrors(['name']);

        $totalNumberOfPreexistingConditionsAfter = PreexistingCondition::count();
        $this->assertEquals($totalNumberOfPreexistingConditionsAfter, $totalNumberOfPreexistingConditionsBefore, 'the number of total preexisting_condition should be the same ');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_deleting_preexisting_condition()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = PreexistingCondition::get()->random()->toArray();

        $totalNumberOfPreexistingConditionsBefore = PreexistingCondition::count();

        $response = $this->actingAs($user)->json('DELETE', 'preexisting-condition/'.$data['id'], $data);

        $totalNumberOfPreexistingConditionsAfter = PreexistingCondition::count();
        $this->assertEquals($totalNumberOfPreexistingConditionsAfter, $totalNumberOfPreexistingConditionsBefore - 1, 'the number of total preexisting_condition should be the same ');
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
