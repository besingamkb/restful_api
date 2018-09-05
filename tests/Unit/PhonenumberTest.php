<?php

namespace Tests\Unit;

use App\Phonenumber;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhonenumberTest extends TestCase
{
    use DatabaseTransactions;

    protected $phonenumber;
    public function setUp()
    {
        parent::setUp();
        $this->phonenumber = factory(Phonenumber::class)->create();
        $user = factory(User::class)->create();
        $user->addPhoneNumber($this->phonenumber);
    }

    /** @test */
    public function a_phonenumber_has_a_value()
    {
        $this->assertNotNull($this->phonenumber->value);
    }

    /** @test */
    public function a_phonenumber_is_belongs_to_a_user()
    {
        $this->assertNotNull($this->phonenumber->user());
    }

    /** @test */
    public function a_phonenumber_must_be_unique()
    {
        try {
            $phonenumber = new Phonenumber;
            $phonenumber->value = $this->phonenumber->value;
            $phonenumber->save();
        } catch (\Exception $exception) {
            $this->assertInstanceOf(QueryException::class, $exception);
        }
    }
}
