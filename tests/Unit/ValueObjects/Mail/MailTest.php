<?php

namespace Tests\Unit\ValueObjects\Mail;

use Tests\TestCase;
use App\Entities\Contact;
use App\ValueObjects\Mail\Mail;
use App\ValueObjects\Mail\MailContent;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class MailTest
 * @package Tests\Unit\ValueObjects\Mail
 * @coversDefaultClass \App\ValueObjects\Mail\Mail
 */
class MailTest extends TestCase
{
    use WithFaker;

    /*** @var string */
    private $subject;
    /*** @var Contact */
    private $to;
    /*** @var Contact */
    private $from;
    /*** @var Contact */
    private $replyTo;
    /*** @var MailContentTest */
    private $content;
    /*** @var Mail */
    private $mail;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->faker->text(20);
        $this->from = new Contact($this->faker->name, $this->faker->email);
        $this->to = [new Contact($this->faker->name, $this->faker->email)];
        $this->replyTo = new Contact($this->faker->name, $this->faker->email);
        $this->content = new MailContent($this->faker->randomElement(['text/html', 'text/plain']), $this->faker->text);
        $this->mail = new Mail($this->subject, $this->from, $this->to, $this->replyTo, $this->content);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getSubject
     */
    function it_should_return_mail_subject()
    {
        $this->assertEquals($this->subject, $this->mail->getSubject());
    }

    /**
     * @test
     * @covers ::getFrom
     */
    function it_should_return_mail_from()
    {
        $this->assertEquals($this->from, $this->mail->getFrom());
    }

    /**
     * @test
     * @covers ::getTo
     */
    function it_should_return_mail_to()
    {
        $this->assertEquals($this->to, $this->mail->getTo());
    }

    /**
     * @test
     * @covers ::getReplyTo
     */
    function it_should_return_mail_reply_to()
    {
        $this->assertEquals($this->replyTo, $this->mail->getReplyTo());
    }

    /**
     * @test
     * @covers ::getContent
     */
    function it_should_return_mail_content()
    {
        $this->assertEquals($this->content, $this->mail->getContent());
    }
}
