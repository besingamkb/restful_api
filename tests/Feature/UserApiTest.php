<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $current_user;
    public function setUp()
    {
        parent::setUp();
        $this->current_user = factory(User::class)->create([
            'role' => 'user'
        ]);
        $this->actingAs($this->current_user);
    }

    /** @test */
    public function a_user_can_get_users_that_paginate_by_10()
    {
        factory(User::class, 50)->create(['role' => 'user']);
        $response = $this->json('GET', '/api/users');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'users.data');
    }

    /** @test */
    public function a_user_cannot_create_new_user()
    {
        $response = $this->json('POST', '/api/users', [
            'name' => "Test User New",
            'email' => "test@newuser.com",
            'password' => "password",
            'role' => 'user'
        ]);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }

    /** @test */
    public function a_user_can_get_his_own_data()
    {
        $response = $this->json('get', '/api/users/' . $this->current_user->id);

        $response
            ->assertOk();
    }

    /** @test */
    public function a_user_cannot_get_others_data()
    {
        $other_user = factory(User::class)->create(['role' => "user"]);
        $response = $this->json('GET', '/api/users/' . $other_user->id);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }

    /** @test */
    public function a_user_can_update_his_own_data()
    {
        $response = $this->json('PUT', '/api/users/' . $this->current_user->id, [
            'name' => "My New User Name",
            'email' => "new@email.com",
            'role' => "user"
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => "User has been updated."
            ]);
    }

    /** @test */
    public function a_user_cannot_update_others_data()
    {
        $other_user = factory(User::class)->create(['role' => "user"]);
        $response = $this->json('PUT', '/api/users/' . $other_user->id, [
            'name' => "Updating Other Users",
            'email' => "new@email.com",
            'role' => "user"
        ]);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }

    /** @test */
    public function a_user_cannot_delete_a_user()
    {
        $other_user = factory(User::class)->create(['role' => 'user']);
        $response = $this->json('DELETE', '/api/users/' . $other_user->id);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }
}
