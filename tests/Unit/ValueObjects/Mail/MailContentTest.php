<?php

namespace Tests\Unit\ValueObjects\Mail;

use Tests\TestCase;
use App\ValueObjects\Mail\MailContent;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class MailContentTest
 * @package Tests\Unit\ValueObjects\Mail
 * @coversDefaultClass \App\ValueObjects\Mail\MailContent
 */
class MailContentTest extends TestCase
{
    use WithFaker;

    const TEXT = 'text/plain';
    const HTML = 'text/html';

    /** @var string */
    private $type;
    /** @var string */
    private $value;
    /** @var MailContent */
    private $mailContent;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->value = $this->faker->text;
        $this->type = $this->faker->randomElement([self::TEXT, self::HTML]);
        $this->mailContent = new MailContent($this->type, $this->value);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getType
     */
    function it_should_return_mail_content_type()
    {
        $this->assertEquals($this->type, $this->mailContent->getType());
    }

    /**
     * @test
     * @covers ::getValue
     */
    function it_should_return_mail_content_value()
    {
        $this->assertEquals($this->value, $this->mailContent->getValue());
    }

    /**
     * @test
     * @covers ::isTextType
     */
    function it_should_return_true_when_mail_content_type_is_text()
    {
        $this->assertTrue((new MailContent(self::TEXT, $this->value))->isTextType());
    }

    /**
     * @test
     * @covers ::isTextType
     */
    function it_should_return_false_when_mail_content_type_is_not_text()
    {
        $this->assertFalse((new MailContent(self::HTML, $this->value))->isTextType());
    }

    /**
     * @test
     * @covers ::toArray
     */
    function it_should_return_mail_content_as_an_array()
    {
        $this->assertEquals(['type' => $this->type, 'value' => $this->value], $this->mailContent->toArray());
    }
}
