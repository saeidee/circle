<?php

namespace Tests\Unit\Factories;

use Tests\TestCase;
use App\ValueObjects\Mail\Mail;
use App\Factories\MailProviderFactory;
use App\Services\MailProviders\MailJet;
use App\Services\MailProviders\SendGrid;
use Illuminate\Foundation\Testing\WithFaker;
use App\ValueObjects\Payloads\CampaignPayload;

/**
 * Class MailProviderFactoryTest
 * @package Tests\Unit\Factories
 * @coversDefaultClass \App\Factories\MailProviderFactory
 */
class MailProviderFactoryTest extends TestCase
{
    use WithFaker;

    const MAIL_JET = 'mailjet';
    const SEND_GRID = 'sendgrid';

    /**
     * @test
     * @covers ::make
     */
    function it_should_return_the_send_grid_provider_when_provider_passed_as_send_grid()
    {
        $parameters = [
            'subject' => $this->faker->text(20),
            'from' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'to' => [['name' => $this->faker->name, 'email' => $this->faker->email]],
            'replyTo' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'content' => ['value' => $this->faker->text, 'type' => $this->faker->name],
        ];
        $campaignPayload = new CampaignPayload($parameters);
        $mail = new Mail(
            $campaignPayload->getSubject(),
            $campaignPayload->getFrom(),
            $campaignPayload->getTo(),
            $campaignPayload->getReplyTo(),
            $campaignPayload->getContent()
        );

        $this->assertEquals(new SendGrid($mail), (new MailProviderFactory())->make(self::SEND_GRID, $campaignPayload));
    }

    /**
     * @test
     * @covers ::make
     */
    function it_should_return_the_mail_jet_provider_when_provider_passed_as_mail_jet()
    {
        $parameters = [
            'subject' => $this->faker->text(20),
            'from' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'to' => [['name' => $this->faker->name, 'email' => $this->faker->email]],
            'replyTo' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'content' => ['value' => $this->faker->text, 'type' => $this->faker->name],
        ];
        $campaignPayload = new CampaignPayload($parameters);
        $mail = new Mail(
            $campaignPayload->getSubject(),
            $campaignPayload->getFrom(),
            $campaignPayload->getTo(),
            $campaignPayload->getReplyTo(),
            $campaignPayload->getContent()
        );

        $this->assertEquals(new MailJet($mail), (new MailProviderFactory())->make(self::MAIL_JET, $campaignPayload));
    }

    /**
     * @test
     * @covers ::make
     */
    function it_should_throw_exception_when_provider_is_not_configured()
    {
        $provider = $this->faker->name;
        $parameters = [
            'subject' => $this->faker->text(20),
            'from' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'to' => [['name' => $this->faker->name, 'email' => $this->faker->email]],
            'replyTo' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'content' => ['value' => $this->faker->text, 'type' => $this->faker->name],
        ];

        $this->expectExceptionMessage("Provider {$provider} is not configured");

        (new MailProviderFactory())->make($provider, new CampaignPayload($parameters));
    }
}
