<?php

namespace Tests\Unit\Services\MailProviders;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\MailProviders\MailProviderSwitcher;

/**
 * Class MailProviderSwitcherTest
 * @package Tests\Unit\Services\MailProviders
 * @coversDefaultClass \App\Services\MailProviders\MailProviderSwitcher
 */
class MailProviderSwitcherTest extends TestCase
{
    use WithFaker;

    const MAIL_JET = 'mailjet';
    const SEND_GRID = 'sendgrid';

    /**
     * @test
     * @covers ::switch
     * @dataProvider mailSenderProvider
     * @param string $currentProvider
     * @param string $switchedProvider
     */
    function it_should_switch_mail_senders(string $currentProvider, string $switchedProvider)
    {
        $this->assertEquals($switchedProvider, (new MailProviderSwitcher())->switch($currentProvider));
    }

    /**
     * @return array|string[][]
     */
    public function mailSenderProvider(): array
    {
        return [
            [self::SEND_GRID, self::MAIL_JET],
            [self::MAIL_JET, self::SEND_GRID],
        ];
    }
}
