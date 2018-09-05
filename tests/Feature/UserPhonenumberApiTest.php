<?php

namespace Tests\Feature;

use App\Phonenumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPhonenumberApiTest extends TestCase
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
    public function a_user_can_get_phonenumbers_that_paginate_by_10()
    {
        $response = $this->json('get', '/api/phonenumbers');

        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_get_his_own_phonenumbers_only()
    {
        $users = factory(User::class, 3)->create()->each(function ($user) {
            factory(Phonenumber::class, 15)->create()->each(function ($phonenumber) use ($user) {
                $user->addPhoneNumber($phonenumber);
            });
        });

        $this->actingAs($users[1]);
        $response = $this->json('get', '/api/phonenumbers');

        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'phonenumbers.data');
    }

    /** @test */
    public function a_user_can_add_phonenumber()
    {
        $response = $this->json('POST', '/api/phonenumbers', [
            'phonenumber' => '+639211234567'
        ]);

        $response->assertOk();
    }

    /** @test */
    public function a_user_can_get_single_phonenumber_by_id()
    {
        $phonenumber = factory(Phonenumber::class)->create();
        $this->current_user->addPhoneNumber($phonenumber);

        $response = $this->json('GET', '/api/phonenumbers/' . $phonenumber->id);

        $response->assertOk()
            ->assertJsonFragment([
                'phonenumber' => $phonenumber->toArray()
            ]);
    }

    /** @test */
    public function a_user_cannot_get_others_phonenumber()
    {
        $users = factory(User::class, 2)->create()->each(function ($user) {
            factory(Phonenumber::class, 3)->create()->each(function ($phonenumber) use ($user) {
                $user->addPhoneNumber($phonenumber);
            });
        });

        $this->actingAs($users[0]);
        $response = $this->json('GET', '/api/phonenumbers/' . $users[1]->phonenumbers()->first()->id);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }

    /** @test */
    public function a_user_can_update_phonenumber()
    {
        $phonenumber = factory(Phonenumber::class)->create();
        $this->current_user->addPhoneNumber($phonenumber);

        $response = $this->json('PUT', '/api/phonenumbers/' . $phonenumber->id, [
            'phonenumber' => '+639211234567'
        ]);

        $response->assertOk()
            ->assertJsonFragment([
                'message' => "Phonenumber has been updated!"
            ]);
    }

    /** @test */
    public function a_user_cannot_update_others_phonenumber()
    {
        $users = factory(User::class, 2)->create()->each(function ($user) {
            factory(Phonenumber::class, 3)->create()->each(function ($phonenumber) use ($user) {
                $user->addPhoneNumber($phonenumber);
            });
        });

        $this->actingAs($users[0]);
        $response = $this->json('PUT', '/api/phonenumbers/' . $users[1]->phonenumbers()->first()->id, [
            'phonenumber' => "+639211234567"
        ]);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }

    /** @test */
    public function a_user_can_delete_phonenumber()
    {
        $phonenumber = factory(Phonenumber::class)->create();
        $this->current_user->addPhoneNumber($phonenumber);

        $response = $this->json("DELETE", '/api/phonenumbers/' . $phonenumber->id);

        $response
            ->assertOk()
            ->assertJsonFragment([
                'message' => "Phone number deleted!"
            ]);
    }

    /** @test */
    public function a_user_cannot_delete_others_phonenumber()
    {
        $users = factory(User::class, 2)->create()->each(function ($user) {
            factory(Phonenumber::class, 3)->create()->each(function ($phonenumber) use ($user) {
                $user->addPhoneNumber($phonenumber);
            });
        });

        $this->actingAs($users[0]);

        $response = $this->json("DELETE", '/api/phonenumbers/' . $users[1]->phonenumbers()->first()->id);

        $response
            ->assertStatus(403)
            ->assertJsonFragment([
                'message' => "This action is unauthorized."
            ]);
    }
}
