<?php

namespace Tests\Unit\Services\MailProviders;

use Tests\TestCase;
use App\Entities\Contact;
use App\ValueObjects\Mail\Mail;
use App\ValueObjects\Mail\MailContent;
use App\Services\MailProviders\SendGrid;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class SendGridTest
 * @package Tests\Unit\Services\MailProviders
 * @coversDefaultClass \App\Services\MailProviders\SendGrid
 */
class SendGridTest extends TestCase
{
    use WithFaker;

    const POST = 'post';
    const TEXT = 'text/plain';
    const HTML = 'text/html';
    const SEND_GRID = 'sendgrid';

    /** @var Mail */
    private $mail;
    /** @var SendGrid */
    private $sendGrid;

    /**
     * @retur void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $subject = $this->faker->text(20);
        $from = new Contact($this->faker->name, $this->faker->email);
        $to = [new Contact($this->faker->name, $this->faker->email)];
        $replyTo = new Contact($this->faker->name, $this->faker->email);
        $content = new MailContent($this->faker->randomElement([self::HTML, self::TEXT]), $this->faker->text);
        $this->mail = new Mail($subject, $from, $to, $replyTo, $content);
        $this->sendGrid = new SendGrid($this->mail);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getUrl
     */
    function it_should_return_url()
    {
        $this->assertEquals(config('services.sendgrid.endpoints.send_email'), $this->sendGrid->getUrl());
    }

    /**
     * @test
     * @covers ::getParameters
     */
    function it_should_return_parameters()
    {
        $to = array_map(
            function (Contact $contact) {
                return $contact->toArray();
            },
            $this->mail->getTo()
        );

        $expected = collect([
            'personalizations' => [
                ['to' => $to, 'subject' => $this->mail->getSubject()],
            ],
            'from' => $this->mail->getFrom()->toArray(),
            'reply_to' => $this->mail->getReplyTo()->toArray(),
            'content' => [$this->mail->getContent()->toArray()],
            'categories' => ['123'],
        ]);

        $this->assertEquals($expected, $this->sendGrid->getParameters());
    }

    /**
     * @test
     * @covers ::getHeaders
     */
    function it_should_return_headers()
    {
        $token = config('services.sendgrid.secret');

        $this->assertEquals(
            ['Authorization' => "Bearer {$token}", 'Content-Type' => 'application/json'],
            $this->sendGrid->getHeaders()
        );
    }

    /**
     * @test
     * @covers ::getHttpMethod
     */
    function it_should_return_http_method()
    {
        $this->assertEquals(self::POST, $this->sendGrid->getHttpMethod());
    }

    /**
     * @test
     * @covers ::getCircuitPrefix
     */
    function it_should_return_circuit_prefix()
    {
        $this->assertEquals(self::SEND_GRID, $this->sendGrid->getCircuitPrefix());
    }
}
