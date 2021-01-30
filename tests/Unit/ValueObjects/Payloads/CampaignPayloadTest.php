<?php

namespace Tests\Unit\ValueObjects\Payloads;

use Tests\TestCase;
use App\Entities\Contact;
use Illuminate\Support\Str;
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

    /**
     * @test
     * @covers ::getSubject
     */
    function it_should_return_mail_subject()
    {
        $subject = $this->faker->text(10);
        $parameters = compact('subject');
        $campaignPayload = new CampaignPayload($parameters);

        $this->assertEquals($subject, $campaignPayload->getSubject());
    }

    /**
     * @test
     * @covers ::getType
     */
    function it_should_return_content_type()
    {
        $type = $this->faker->randomElement(['text/plain', 'text/html']);
        $parameters = ['content' => compact('type')];
        $campaignPayload = new CampaignPayload($parameters);

        $this->assertEquals($type, $campaignPayload->getType());
    }

    /**
     * @test
     * @covers ::getId
     * @covers ::generateId
     */
    function it_should_return_campaign_id()
    {
        $uuidLengthCount = 36;
        $campaignPayload = new CampaignPayload([]);

        $this->assertEquals($uuidLengthCount, Str::length($campaignPayload->getId()));
    }

    /**
     * @test
     * @covers ::isValid
     * @dataProvider validationDataProvider
     * @param bool $expected
     * @param array $parameters
     */
    function it_should_validate_the_campaign_payload(bool $expected, array $parameters)
    {
        $this->assertEquals($expected, (new CampaignPayload($parameters))->isValid());
    }

    /**
     * @return array
     */
    public function validationDataProvider(): array
    {
        return [
            [false, ['to' => []]],
            [false, ['to' => ['name' => 'example', 'email' => 'x@example.com']]],
            [false, ['to' => [['name' => 'example', 'email' => 'x@example.com']]]],
            [false, ['campaign' => 'name', 'to' => [['name' => 'example', 'email' => 'x@example.com']]]],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                ],
            ],
            [
                false,
                [
                    'campaign' => '',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => '',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'example',
                    'subject' => '',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => '', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => ''],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => '', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => ''],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => '', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => '', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => ''],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => '', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                false,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => '']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
            [
                true,
                [
                    'campaign' => 'name',
                    'subject' => 'Urgent',
                    'from' => ['name' => 'example', 'email' => 'x@example.com'],
                    'to' => [['name' => 'example', 'email' => 'x@example.com']],
                    'replyTo' => ['name' => 'example', 'email' => 'x@example.com'],
                    'content' => ['value' => 'some contents', 'type' => 'text/html'],
                ],
            ],
        ];
    }
}
