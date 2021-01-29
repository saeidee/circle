<?php

namespace Tests\Unit\Services\MailProviders;

use Tests\TestCase;
use App\Entities\Contact;
use App\ValueObjects\Mail\Mail;
use App\ValueObjects\Mail\MailContent;
use App\Services\MailProviders\MailJet;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class MailJetTest
 * @package Tests\Unit\Services\MailProviders
 * @coversDefaultClass \App\Services\MailProviders\MailJet
 */
class MailJetTest extends TestCase
{
    use WithFaker;

    const POST = 'post';
    const TEXT = 'text/plain';
    const HTML = 'text/html';
    const MAIL_JET = 'mailjet';

    /** @var Mail */
    private $mail;
    /** @var MailJet */
    private $mailJet;

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
        $content = new MailContent(self::HTML, $this->faker->text);
        $this->mail = new Mail($subject, $from, $to, $replyTo, $content);
        $this->mailJet = new MailJet($this->mail);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getUrl
     */
    function it_should_return_url()
    {
        $this->assertEquals(config('services.mailjet.endpoints.send_email'), $this->mailJet->getUrl());
    }

    /**
     * @test
     * @covers ::getParameters
     */
    function it_should_return_parameters_when_content_type_is_html()
    {
        $to = array_map(
            function (Contact $contact) {
                return ['Email' => $contact->getEmail(), 'Name' => $contact->getName()];
            },
            $this->mail->getTo()
        );

        $expected = collect([
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->mail->getFrom()->getEmail(),
                        'Name' => $this->mail->getFrom()->getName()
                    ],
                    'To' => $to,
                    'Subject' => $this->mail->getSubject(),
                    'TextPart' => '',
                    'HTMLPart' => $this->mail->getContent()->getValue(),
                    'CustomID' => 'AppGettingStartedTest'
                ],
            ],
        ]);

        $this->assertEquals($expected, $this->mailJet->getParameters());
    }

    /**
     * @test
     * @covers ::getParameters
     */
    function it_should_return_parameters_when_content_type_is_text()
    {
        $content = new MailContent(self::TEXT, $this->faker->text);
        $mail = new Mail(
            $this->mail->getSubject(),
            $this->mail->getFrom(),
            $this->mail->getTo(),
            $this->mail->getReplyTo(),
            $content
        );
        $mailJet = new MailJet($mail);

        $to = array_map(
            function (Contact $contact) {
                return ['Email' => $contact->getEmail(), 'Name' => $contact->getName()];
            },
            $this->mail->getTo()
        );

        $expected = collect([
            'Messages' => [
                [
                    'From' => [
                        'Email' => $mail->getFrom()->getEmail(),
                        'Name' => $mail->getFrom()->getName()
                    ],
                    'To' => $to,
                    'Subject' => $mail->getSubject(),
                    'TextPart' => $mail->getContent()->getValue(),
                    'HTMLPart' => '',
                    'CustomID' => 'AppGettingStartedTest'
                ],
            ],
        ]);

        $this->assertEquals($expected, $mailJet->getParameters());
    }

    /**
     * @test
     * @covers ::getHeaders
     */
    function it_should_return_headers()
    {
        $token = config('services.mailjet.secret');

        $this->assertEquals(
            ['Authorization' => "Basic {$token}", 'Content-Type' => 'application/json'],
            $this->mailJet->getHeaders()
        );
    }

    /**
     * @test
     * @covers ::getHttpMethod
     */
    function it_should_return_http_method()
    {
        $this->assertEquals(self::POST, $this->mailJet->getHttpMethod());
    }

    /**
     * @test
     * @covers ::getCircuitPrefix
     */
    function it_should_return_circuit_prefix()
    {
        $this->assertEquals(self::MAIL_JET, $this->mailJet->getCircuitPrefix());
    }
}
