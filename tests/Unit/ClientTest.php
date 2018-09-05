<?php

namespace Tests\Unit;

use App\Client;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use DatabaseTransactions;

    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = factory(Client::class)->create();
        $user = factory(User::class)->create();
        $user->addClient($this->client);
    }

    /** @test */
    public function a_client_has_a_name()
    {
        $client = new Client;
        $client->name = "Client One";
        $this->assertEquals("Client One", $client->name);
    }

    /** @test */
    public function a_client_belongs_to_a_user()
    {
        $this->assertNotNull($this->client->user());
    }
}
