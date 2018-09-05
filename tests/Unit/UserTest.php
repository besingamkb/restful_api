<?php

namespace Tests\Unit;

use App\Client;
use App\Phonenumber;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $phonenumber = factory(Phonenumber::class)->create();
        $client = factory(Client::class)->create();

        $this->user->addPhoneNumber($phonenumber);
        $this->user->addClient($client);
    }

    /** @test */
    public function a_user_has_name()
    {
        $this->assertArrayHasKey('name', $this->user);
    }

    /** @test */
    public function a_user_has_email()
    {
        $this->assertArrayHasKey('email', $this->user);
    }

    /** @test */
    public function a_user_can_add_phonenumbers()
    {
        $this->assertEquals(1, $this->user->phonenumbers()->count());
    }

    /** @test */
    public function a_user_can_add_multiple_phonenumbers()
    {
        $phonenumber = factory(Phonenumber::class)->create();

        $this->user->addPhoneNumber($phonenumber);
        $this->assertEquals(2, $this->user->phonenumbers()->count());
    }

    /** @test */
    public function a_user_can_update_phonenumbers()
    {
        $updatedPhone = $this->user->phonenumbers()->first();
        $updatedPhone->value = "+639219876543";
        $updatedPhone->save();
        $this->assertEquals("+639219876543", $this->user->phonenumbers()->first()->value);
    }

    /** @test */
    public function a_user_can_delete_phonenumbers()
    {
        $this->user->phonenumbers()->where('value', "+639213456789")->delete();
        $this->assertNull($this->user->phonenumbers()->where('value', "+639213456789")->first());
    }

    /** @test */
    public function a_user_can_add_client()
    {
        $this->assertEquals(1, $this->user->clients()->count());
    }

    /** @test */
    public function a_user_can_add_multiple_clients()
    {
        factory(Client::class, 3)->create()
            ->each(function ($client) {
                $this->user->addClient($client);
            });

        $this->assertEquals(4, $this->user->clients()->count());
    }

    /** @test */
    public function a_user_can_update_a_client()
    {
        $client = $this->user->clients()->first();
        $client->name = "Updated Client Name";
        $client->save();

        $this->assertEquals("Updated Client Name", $this->user->clients()->first()->name);
    }

    /** @test */
    public function a_user_can_delete_a_client()
    {
        $client = $this->user->clients()->first();
        $client_id = $client->id;
        $client->delete();
        $this->assertNull(Client::find($client_id));
    }
}
