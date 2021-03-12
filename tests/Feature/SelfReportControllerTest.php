<?php

namespace Tests\Feature;

use App\SelfReport;
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
 * Class SelfReportControllerTest
 *
 * 1. Test that you must be logged in to access any of the controller functions.
 */
class SelfReportControllerTest extends TestCase
{
    //use RefreshDatabase;
    //------------------------------------------------------------------------------
    // Test that you must be logged in to access any of the controller functions.
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_seeing_self_report_index()
    {
        $response = $this->get('/self-report');

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_creating_self_report()
    {
        $response = $this->get(route('self-report.create'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_storing_self_report()
    {
        $response = $this->get(route('self-report.store'));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_showing_self_report()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('self-report.show', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_editing_self_report()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->get(route('self-report.edit', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_updateing_self_report()
    {
        // Should check for permisson before checking to see if record exists
        $response = $this->put(route('self-report.update', ['id' => 1]));
        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    /**
     * @test
     */
    public function prevent_non_logged_in_users_from_destroying_self_report()
    {

        // Should check for permisson before checking to see if record exists
        $response = $this->delete(route('self-report.destroy', ['id' => 1]));

        $this->withoutMiddleware();
        $response->assertRedirect('login');
    }

    //------------------------------------------------------------------------------
    // Test that you must have access any of the controller functions.
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_seeing_self_report_index()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get('/self-report');

        // TODO: Check for message???

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_creating_self_report()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('self-report.create'));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_storing_self_report()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->post(route('self-report.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_showing_self_report()
    {
        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('self-report.show', ['id' => 1]));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_editing_self_report()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->get(route('self-report.edit', ['id' => 1]));

        $response->assertRedirect('home');
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_updateing_self_report()
    {
        $user = $this->getRandomUser('cant');

        $response = $this->actingAs($user)->put(route('self-report.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_without_permissions_from_destroying_self_report()
    {
        $user = $this->getRandomUser('cant');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('self-report.destroy', ['id' => 1]));

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
    public function prevent_users_withonly_index_permissions_from_creating_self_report()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('self-report.create'));

        $response->assertRedirect('self-report');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_storing_self_report()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->post(route('self-report.store'));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_showing_self_report()
    {
        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->get(route('self-report.show', ['id' => 1]));

        $response->assertRedirect('self-report');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_editing_self_report()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->get(route('self-report.edit', ['id' => 1]));

        $response->assertRedirect('self-report');
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_updating_self_report()
    {
        $user = $this->getRandomUser('only index');

        $response = $this->actingAs($user)->put(route('self-report.update', ['id' => 1]));

        $response->assertStatus(403);  // Form Request::authorized() returns 403 when user is not authorized
    }

    /**
     * @test
     */
    public function prevent_users_withonly_index_permissions_from_destroying_self_report()
    {
        $user = $this->getRandomUser('only index');

        // Should check for permisson before checking to see if record exists
        $response = $this->actingAs($user)->delete(route('self-report.destroy', ['id' => 1]));

        $response->assertRedirect('self-report');
    }

    /// ////////

    //------------------------------------------------------------------------------
    // Now lets test that we have the functionality to add, change, delete, and
    //   catch validation errors
    //------------------------------------------------------------------------------

    /**
     * @test
     */
    public function prevent_showing_a_nonexistent_self_report()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('self-report.show', ['id' => 100]));

        $response->assertSessionHas('flash_error_message', 'Unable to find SelfReports to display.');
    }

    /**
     * @test
     */
    public function prevent_editing_a_nonexistent_self_report()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('self-report.edit', ['id' => 100]));

        $response->assertSessionHas('flash_error_message', 'Unable to find SelfReports to edit.');
    }

    /**
     * @test
     */
    public function it_allows_logged_in_users_to_create_new_self_report()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        // act as the user we got and request the create_new_article route
        $response = $this->actingAs($user)->get(route('self-report.create'));

        $response->assertStatus(200);
        $response->assertViewIs('self-report.create');
        $response->assertSee('self-report-form');
    }

    /**
     * @test
     */
    public function prevent_creating_a_blank_self_report()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => '',
            'organization_id' => '',
            'name' => '',
            'state' => '',
            'zipcode' => '',
            'symptom_start_date' => '',
            'county_calc' => '',
            'form_received_at' => '',
        ];

        $totalNumberOfSelfReportsBefore = SelfReport::count();

        $response = $this->actingAs($user)->post(route('self-report.store'), $data);

        $totalNumberOfSelfReportsAfter = SelfReport::count();
        $this->assertEquals($totalNumberOfSelfReportsAfter, $totalNumberOfSelfReportsBefore, 'the number of total article is supposed to be the same ');

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0], 'The name field is required.');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_invalid_data_when_creating_a_self_report()
    {
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
            'id' => '',
            'organization_id' => 'a',
            'name' => 'a',
            'state' => 'a',
            'zipcode' => 'a',
            'symptom_start_date' => 'a',
            'county_calc' => 'a',
            'form_received_at' => 'a',
        ];

        $totalNumberOfSelfReportsBefore = SelfReport::count();

        $response = $this->actingAs($user)->post(route('self-report.store'), $data);

        $totalNumberOfSelfReportsAfter = SelfReport::count();
        $this->assertEquals($totalNumberOfSelfReportsAfter, $totalNumberOfSelfReportsBefore, 'the number of total article is supposed to be the same ');

        $errors = session('errors');

        $this->assertEquals($errors->get('name')[0], 'The name must be at least 3 characters.');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function create_a_self_report()
    {
        $faker = Faker\Factory::create();
        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = [
          'organization_id' => '',
          'name' => $faker->name,
          'state' => '',
          'zipcode' => '',
          'symptom_start_date' => '',
          'county_calc' => '',
          'form_received_at' => '',
        ];

        info('--  SelfReport  --');
        info(print_r($data, true));
        info('----');

        $totalNumberOfSelfReportsBefore = SelfReport::count();

        $response = $this->actingAs($user)->post(route('self-report.store'), $data);

        $totalNumberOfSelfReportsAfter = SelfReport::count();

        $errors = session('errors');

        info(print_r($errors, true));

        $this->assertEquals($totalNumberOfSelfReportsAfter, $totalNumberOfSelfReportsBefore + 1, 'the number of total self_report is supposed to be one more ');

        $lastInsertedInTheDB = SelfReport::orderBy('id', 'desc')->first();

        $this->assertEquals($lastInsertedInTheDB->organization_id, $data['organization_id'], 'the organization_id of the saved self_report is different from the input data');

        $this->assertEquals($lastInsertedInTheDB->name, $data['name'], 'the name of the saved self_report is different from the input data');

        $this->assertEquals($lastInsertedInTheDB->state, $data['state'], 'the state of the saved self_report is different from the input data');

        $this->assertEquals($lastInsertedInTheDB->zipcode, $data['zipcode'], 'the zipcode of the saved self_report is different from the input data');

        $this->assertEquals($lastInsertedInTheDB->symptom_start_date, $data['symptom_start_date'], 'the symptom_start_date of the saved self_report is different from the input data');

        $this->assertEquals($lastInsertedInTheDB->county_calc, $data['county_calc'], 'the county_calc of the saved self_report is different from the input data');

        $this->assertEquals($lastInsertedInTheDB->form_received_at, $data['form_received_at'], 'the form_received_at of the saved self_report is different from the input data');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function prevent_creating_a_duplicate_self_report()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $totalNumberOfSelfReportsBefore = SelfReport::count();

        $self_report = SelfReport::get()->random();
        $data = [
            'id' => '',
            'organization_id' => '',
            'name' => $self_report->name,
            'state' => '',
            'zipcode' => '',
            'symptom_start_date' => '',
            'county_calc' => '',
            'form_received_at' => '',
        ];

        $response = $this->actingAs($user)->post(route('self-report.store'), $data);
        $response->assertStatus(302);

        $errors = session('errors');
        $this->assertEquals($errors->get('name')[0], 'The name has already been taken.');

        $totalNumberOfSelfReportsAfter = SelfReport::count();
        $this->assertEquals($totalNumberOfSelfReportsAfter, $totalNumberOfSelfReportsBefore, 'the number of total self_report should be the same ');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_changing_self_report()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = SelfReport::get()->random()->toArray();

        $data['name'] = $data['name'].'1';

        $totalNumberOfSelfReportsBefore = SelfReport::count();

        $response = $this->actingAs($user)->json('PATCH', 'self-report/'.$data['id'], $data);

        $response->assertStatus(200);

        $totalNumberOfSelfReportsAfter = SelfReport::count();
        $this->assertEquals($totalNumberOfSelfReportsAfter, $totalNumberOfSelfReportsBefore, 'the number of total self_report should be the same ');
    }

    /**
     * @test
     *
     * Check validation works on change for catching dups
     */
    public function prevent_creating_a_duplicate_by_changing_self_report()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = SelfReport::get()->random()->toArray();

        // Create one that we can duplicate the name for, at this point we only have one self_report record
        $self_report_dup = [

            'organization_id' => '',
            'name' => $faker->name,
            'state' => '',
            'zipcode' => '',
            'symptom_start_date' => '',
            'county_calc' => '',
            'form_received_at' => '',
        ];

        $response = $this->actingAs($user)->post(route('self-report.store'), $self_report_dup);

        $data['name'] = $self_report_dup['name'];

        $totalNumberOfSelfReportsBefore = SelfReport::count();

        $response = $this->actingAs($user)->json('PATCH', 'self-report/'.$data['id'], $data);
        $response->assertStatus(422);  // From web page we get a 422

        $errors = session('errors');

        info(print_r($errors, true));

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
            ]);

        $response->assertJsonValidationErrors(['name']);

        $totalNumberOfSelfReportsAfter = SelfReport::count();
        $this->assertEquals($totalNumberOfSelfReportsAfter, $totalNumberOfSelfReportsBefore, 'the number of total self_report should be the same ');
    }

    /**
     * @test
     *
     * Check validation works
     */
    public function allow_deleting_self_report()
    {
        $faker = Faker\Factory::create();

        // get a random user
        $user = $this->getRandomUser('super-admin');

        $data = SelfReport::get()->random()->toArray();

        $totalNumberOfSelfReportsBefore = SelfReport::count();

        $response = $this->actingAs($user)->json('DELETE', 'self-report/'.$data['id'], $data);

        $totalNumberOfSelfReportsAfter = SelfReport::count();
        $this->assertEquals($totalNumberOfSelfReportsAfter, $totalNumberOfSelfReportsBefore - 1, 'the number of total self_report should be the same ');
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
