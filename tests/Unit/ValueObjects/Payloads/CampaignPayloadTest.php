<?php

namespace Tests\Unit\ValueObjects\Payloads;

use Tests\TestCase;
use App\Entities\Contact;
use App\ValueObjects\Mail\MailContent;
use Illuminate\Foundation\Testing\WithFaker;
use App\ValueObjects\Payloads\CampaignPayload;

/**
 * Class CampaignPayloadTest
 * @package Tests\Unit\ValueObjects\Payloads
 * @coversDefaultClass \App\ValueObjects\Payloads\CampaignPayload
 */
class CampaignPayloadTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::getFrom
     */
    function it_should_return_from_recipient()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $parameters = ['from' => compact('name', 'email')];
        $campaignPayload = new CampaignPayload($parameters);

        $this->assertEquals(new Contact($name, $email), $campaignPayload->getFrom());
    }

    /**
     * @test
     * @covers ::getTo
     */
    function it_should_return_to_recipient()
    {
        $firstRecipient = ['name' => $this->faker->name, 'email' => $this->faker->email];
        $secondRecipient = ['name' => $this->faker->name, 'email' => $this->faker->email];
        $parameters = ['to' => [$firstRecipient, $secondRecipient]];
        $campaignPayload = new CampaignPayload($parameters);

        $this->assertEquals(
            [
                new Contact($firstRecipient['name'], $firstRecipient['email']),
                new Contact($secondRecipient['name'], $secondRecipient['email']),
            ],
            $campaignPayload->getTo()
        );
    }

    /**
     * @test
     * @covers ::getReplyTo
     */
    function it_should_return_reply_to_recipient()
    {
        $name = $this->faker->name;
        $email = $this->faker->email;
        $parameters = ['replyTo' => compact('name', 'email')];
        $campaignPayload = new CampaignPayload($parameters);

        $this->assertEquals(new Contact($name, $email), $campaignPayload->getReplyTo());
    }

    /**
     * @test
     * @covers ::getContent
     */
    function it_should_return_mail_content()
    {
        $value = $this->faker->text;
        $type = $this->faker->randomElement(['text/html', 'text/plain']);
        $parameters = ['content' => compact('value', 'type')];
        $campaignPayload = new CampaignPayload($parameters);

        $this->assertEquals(new MailContent($type, $value), $campaignPayload->getContent());
    }

    /**
     * @test
     * @covers ::getCampaign
     */
    function it_should_return_campaign_name()
    {
        $campaign = $this->faker->name;
        $parameters = compact('campaign');
        $campaignPayload = new CampaignPayload($parameters);

        $this->assertEquals($campaign, $campaignPayload->getCampaign());
    }
}
