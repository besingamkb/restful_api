<?php

namespace Tests\Feature;

use App\Client;
use App\Phonenumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminApiTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware;

    protected $current_user;
    public function setUp()
    {
        parent::setUp();
        $this->current_user = factory(User::class)->create([
            'role' => 'admin'
        ]);
        $this->actingAs($this->current_user);
    }

    /**
     *
     * ******************
     * Admin Users Section
     * ******************
     *
     */
    /** @test */
    public function admin_can_get_users_that_paginate_by_10()
    {
        factory(User::class, 50)->create(['role' => 'user']);
        $response = $this->json('GET', '/api/users');
        $response
            ->assertOk()
            ->assertJsonCount(10, 'users.data');
    }

    /** @test */
    public function admin_can_create_new_user()
    {
        $response = $this->json('POST', '/api/users', [
            'name' => 'new user',
            'email' => 'new sample email',
            'password' => 'password!',
            'role' => 'user'
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => 'A new user has been created.'
            ]);
    }

    /** @test */
    public function admin_can_get_any_users()
    {
        $users = factory(User::class, 15)->create([
            'role' => 'user'
        ]);

        $response = $this->json('GET', '/api/users/' . $users[rand(3, 10)]->id);
        $response
            ->assertOk()
            ->assertJsonCount(1, '');
    }

    /** @test */
    public function admin_can_update_any_users()
    {
        $users = factory(User::class, 25)->create([
            'role' => 'user'
        ]);

        $response = $this->json('PUT', '/api/users/' . $users[rand(3, 15)]->id, [
            'name' => 'new updated name',
            'email' => 'new_123@updated.com',
            'role' => 'user'
        ]);
        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => "User has been updated."
            ]);
    }

    /** @test */
    public function admin_can_delete_any_users()
    {
        $users = factory(User::class, 10)->create([
            'role' => 'user'
        ]);

        $response = $this->json('DELETE', '/api/users/' . $users[rand(3, 5)]->id);
        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => "User has been deleted."
            ]);
    }

    /**
     *
     * ******************
     * Admin Phonenumbers Section
     * ******************
     *
     */
    /** @test */
    public function admin_can_get_phonenumbers_that_paginate_by_10()
    {
        factory(User::class, 5)->create(['role' => 'user'])->each(function ($user) {
            factory(Phonenumber::class, 10)->create()->each(function ($phonenumber) use ($user) {
                $this->current_user->addPhoneNumber($phonenumber);
            });
        });

        $response = $this->json('GET', '/api/phonenumbers');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'phonenumbers.data');
    }

    /** @test */
    public function admin_can_get_his_own_phonenumbers_only()
    {
        factory(Phonenumber::class, 10)->create()->each(function ($phonenumber) {
            $this->current_user->addPhoneNumber($phonenumber);
        });
        $response = $this->json('GET', '/api/phonenumbers');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'phonenumbers.data');
    }

    /** @test */
    public function admin_can_add_phonenumber()
    {
        $response = $this->json('POST', '/api/phonenumbers', [
            'phonenumber' => '+639211234567'
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => "New phonenumber successfully added!"
            ]);
    }

    /** @test */
    public function admin_can_get_any_phonenumber_by_id()
    {
        $users = factory(User::class, 10)->create(['role' => 'user'])->each(function ($user) {
            factory(Phonenumber::class, 5)->create()->each(function ($phonenumber) use ($user) {
                $user->addPhoneNumber($phonenumber);
            });
        });

        $response = $this->json('GET', '/api/phonenumbers/' . $users[1]->id);
        $response
            ->assertOk();
    }

    /** @test */
    public function admin_can_update_any_phonenumber()
    {
        $phonenumbers = null;
        $users = factory(User::class, 2)->create(['role' => 'user'])->each(function($user) use ($phonenumbers) {
            $phonenumbers = factory(Phonenumber::class, 5)->create()->each(function ($phonenumber) use ($user) {
                $user->addPhoneNumber($phonenumber);
            });
        });

        $response = $this->json('PUT', '/api/phonenumbers/' . $users[0]->phonenumbers()->first()->id, [
            'phonenumber' => "+639211234567"
        ]);

        $response
            ->assertOk();
    }

    public function admin_can_delete_any_phonenumber()
    {
        $users = factory(User::class, 2)->create(['role' => 'user'])->each(function($user) {
            factory(Phonenumber::class, 5)->create()->each(function ($phonenumber) use ($user) {
                $user->addPhoneNumber($phonenumber);
            });
        });

        $response = $this->json('DELETE', '/api/phonenumbers/' . $users[1]->id);
        $response
            ->assertOk();
    }

    /**
     *
     * ******************
     * Admin Phonenumbers Section
     * ******************
     *
     */
    /** @test */
    public function admin_can_get_clients_that_paginate_by_10()
    {
        factory(User::class, 5)->create(['role' => 'user'])->each(function ($user) {
            factory(Client::class, 10)->create()->each(function ($client) use ($user) {
                $this->current_user->addClient($client);
            });
        });

        $response = $this->json('GET', '/api/clients');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'clients.data');
    }

    /** @test */
    public function admin_can_get_his_own_clients_only()
    {
        factory(Client::class, 10)->create()->each(function ($client) {
            $this->current_user->addClient($client);
        });
        $response = $this->json('GET', '/api/clients');

        $response
            ->assertOk()
            ->assertJsonCount(10, 'clients.data');
    }

    /** @test */
    public function admin_can_add_client()
    {
        $response = $this->json('POST', '/api/clients', [
            'name' => 'new client'
        ]);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => "New client successfully added!"
            ]);
    }

    /** @test */
    public function admin_can_get_any_client_by_id()
    {
        $users = factory(User::class, 10)->create(['role' => 'user'])->each(function ($user) {
            factory(Client::class, 5)->create()->each(function ($client) use ($user) {
                $user->addClient($client);
            });
        });

        $response = $this->json('GET', '/api/clients/' . $users[1]->id);
        $response
            ->assertOk();
    }

    /** @test */
    public function admin_can_update_any_client()
    {
        $clients = null;
        $users = factory(User::class, 2)->create(['role' => 'user'])->each(function($user) use ($clients) {
            $clients = factory(Client::class, 5)->create()->each(function ($client) use ($user) {
                $user->addClient($client);
            });
        });

        $response = $this->json('PUT', '/api/clients/' . $users[0]->clients()->first()->id, [
            'name' => 'Updated Clientname'
        ]);

        $response
            ->assertOk();
    }

    public function admin_can_delete_any_client()
    {
        $users = factory(User::class, 2)->create(['role' => 'user'])->each(function($user) {
            factory(Client::class, 5)->create()->each(function ($client) use ($user) {
                $user->addClient($client);
            });
        });

        $response = $this->json('DELETE', '/api/clients/' . $users[1]->id);
        $response
            ->assertOk();
    }
}
