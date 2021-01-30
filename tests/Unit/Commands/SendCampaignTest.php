<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use App\Jobs\CampaignSenderManager;
use Illuminate\Support\Facades\Queue;
use App\Console\Commands\SendCampaign;
use Illuminate\Foundation\Testing\WithFaker;
use App\ValueObjects\Payloads\CampaignPayload;

/**
 * Class SendCampaignTest
 * @package Tests\Unit\Commands
 * @coversDefaultClass \App\Console\Commands\SendCampaign
 */
class SendCampaignTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::handle
     */
    function it_should_warn_when_the_given_payload_was_invalid()
    {
        $payload = new CampaignPayload([]);
        $sendCampaign = $this->getMockBuilder(SendCampaign::class)
            ->onlyMethods(['preparePayload', 'warn'])
            ->getMock();

        $sendCampaign->expects($this->once())->method('preparePayload')->willReturn($payload);
        $sendCampaign->expects($this->once())
            ->method('warn')
            ->with('The json structure which you passed is different from our defined structure, please check readme.');

        $sendCampaign->handle();
    }

    /**
     * @test
     * @covers ::handle
     */
    function it_should_handle_sending_campaign()
    {
        Queue::fake();

        $payload = new CampaignPayload([
            'campaign' => $this->faker->name,
            'subject' => $this->faker->text(10),
            'from' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'to' => [['name' => $this->faker->name, 'email' => $this->faker->email]],
            'replyTo' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'content' => ['type' => 'text/html', 'value' => $this->faker->email],
        ]);
        $sendCampaign = $this->getMockBuilder(SendCampaign::class)
            ->onlyMethods(['preparePayload', 'info'])
            ->getMock();

        $sendCampaign->expects($this->once())->method('preparePayload')->willReturn($payload);
        $sendCampaign->expects($this->once())
            ->method('info')
            ->with('Great, we are preparing to send the campaign.');

        $sendCampaign->handle();

        Queue::assertPushed(
            CampaignSenderManager::class,
            function (CampaignSenderManager $senderManager) use ($payload) {
                $this->assertProperty($senderManager, 'providerName', config('app.initial_preferred_mail_provider'));
                $this->assertProperty($senderManager, 'campaignPayload', $payload);

                return true;
            }
        );
    }

    /**
     * @test
     * @covers ::preparePayload
     */
    function it_should_return_the_decoded_payload()
    {
        $payload = '{"campaign": "Urgent", "subject":"subject!","from": {},"replyTo": {},"to": [],"content": {}}';
        $sendCampaign = $this->getMockBuilder(SendCampaign::class)->onlyMethods(['argument'])->getMock();

        $sendCampaign->expects($this->once())->method('argument')->with('payload')->willReturn($payload);

        $this->assertInstanceOf(CampaignPayload::class, $this->invokeMethod($sendCampaign, 'preparePayload'));
    }
}
