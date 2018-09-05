<?php

namespace Tests\Feature;

use App\Client;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserClientApiTest extends TestCase
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
    public function a_user_can_get_clients_that_paginate_by_10()
    {
        $response = $this->json('get', '/api/clients');

        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_get_his_own_clients_only()
    {
        $users = factory(User::class, 3)->create()->each(function ($user) {
            factory(Client::class, 15)->create()->each(function ($client) use ($user) {
                $user->addClient($client);
            });
        });

        $this->actingAs($users[1]);
        $response = $this->json('get', '/api/clients');

        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'clients.data');
    }

    /** @test */
    public function a_user_can_add_client()
    {
        $response = $this->json('POST', '/api/clients', [
            'name' => 'Client One'
        ]);

        $response->assertOk();
    }

    /** @test */
    public function a_user_can_get_single_client_by_id()
    {
        $client = factory(Client::class)->create();
        $this->current_user->addClient($client);

        $response = $this->json('GET', '/api/clients/' . $client->id);

        $response->assertOk()
            ->assertJsonFragment([
                'client' => $client->toArray()
            ]);
    }

    /** @test */
    public function a_user_cannot_get_others_client()
    {
        $users = factory(User::class, 2)->create()->each(function ($user) {
            factory(Client::class, 3)->create()->each(function ($client) use ($user) {
                $user->addClient($client);
            });
        });

        $this->actingAs($users[0]);
        $response = $this->json('GET', '/api/clients/' . $users[1]->clients()->first()->id);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }

    /** @test */
    public function a_user_can_update_client()
    {
        $client = factory(Client::class)->create();
        $this->current_user->addClient($client);

        $response = $this->json('PUT', '/api/clients/' . $client->id, [
            'name' => 'Updated Client'
        ]);

        $response->assertOk()
            ->assertJsonFragment([
                'message' => "Client has been updated!"
            ]);
    }

    /** @test */
    public function a_user_cannot_update_others_client()
    {
        $users = factory(User::class, 2)->create()->each(function ($user) {
            factory(Client::class, 3)->create()->each(function ($client) use ($user) {
                $user->addClient($client);
            });
        });

        $this->actingAs($users[0]);
        $response = $this->json('PUT', '/api/clients/' . $users[1]->clients()->first()->id, [
            'name' => 'Updated Client'
        ]);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }

    /** @test */
    public function a_user_can_delete_client()
    {
        $client = factory(Client::class)->create();
        $this->current_user->addClient($client);

        $response = $this->json("DELETE", '/api/clients/' . $client->id);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => "Client deleted!"
            ]);
    }

    /** @test */
    public function a_user_cannot_delete_others_client()
    {
        $users = factory(User::class, 2)->create()->each(function ($user) {
            factory(Client::class, 3)->create()->each(function ($client) use ($user) {
                $user->addClient($client);
            });
        });

        $this->actingAs($users[0]);

        $response = $this->json("DELETE", '/api/clients/' . $users[1]->clients()->first()->id);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }
}
