<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {

//        $user = factory(\App\User::class)->create();
//        $this->actingAs($user);
        $response = $this->get('http://cms.apskc.ldev/login');

        $response->assertStatus(200);
    }
}
