<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Helper;
use Faker\Factory as Faker;

class UserTest extends TestCase
{
    use RefreshDatabase, Helper;

    protected function setUp(): void
    {
       parent::setUp();
       $this->createPersonalClient();
    }
    
    /** @test */
    public function it_can_create_a_user()
    {
        
        $faker = Faker::create();

        $userData = [
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => $faker->password(minLength: 8),
        ];;

        $response = $this->postJson('/api/register', $userData, [
            'Content-Type' => 'application/json'
        ]);

        // dd($userData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'name' => $userData["name"],
            'email' => $userData["email"]
        ]);

    }
}