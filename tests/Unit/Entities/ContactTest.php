<?php

namespace Tests\Unit\Entities;

use Tests\TestCase;
use App\Entities\Contact;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class ContactTest
 * @package Tests\Unit\Entities
 * @coversDefaultClass \App\Entities\Contact
 */
class ContactTest extends TestCase
{
    use WithFaker;

    /** @var string */
    private $name;
    /** @var string */
    private $email;
    /** @var Contact */
    private $contact;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->name = $this->faker->name;
        $this->email = $this->faker->email;
        $this->contact = new Contact($this->name, $this->email);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getName
     */
    function it_should_return_name()
    {
        $this->assertEquals($this->name, $this->contact->getName());
    }

    /**
     * @test
     * @covers ::getEmail
     */
    function it_should_return_email()
    {
        $this->assertEquals($this->email, $this->contact->getEmail());
    }

    /**
     * @test
     * @covers ::toArray
     */
    function it_should_return_contact_as_an_array()
    {
        $this->assertEquals(['name' => $this->name, 'email' => $this->email], $this->contact->toArray());
    }
}
