<?php

namespace Tests\Unit\Factories;

use App\Factories\RequestFactory;
use GuzzleHttp\Psr7\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\MailProviders\MailSenderInterface;

/**
 * Class RequestFactoryTest
 * @package Tests\Unit\Factories
 * @coversDefaultClass \App\Factories\RequestFactory
 */
class RequestFactoryTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::make
     */
    function it_should_make_request()
    {
        $url = $this->faker->url;
        $parameters = collect(['from' => $this->faker->name]);
        $httpMethod = $this->faker->randomElement(['post', 'put']);
        $headers = [$this->faker->name => $this->faker->text(10)];
        $mailSender = $this->createMock(MailSenderInterface::class);

        $mailSender->expects($this->once())->method('getHttpMethod')->willReturn($httpMethod);
        $mailSender->expects($this->once())->method('getUrl')->willReturn($url);
        $mailSender->expects($this->once())->method('getHeaders')->willReturn($headers);
        $mailSender->expects($this->once())->method('getParameters')->willReturn($parameters);

        $this->assertInstanceOf(Request::class, (new RequestFactory())->make($mailSender));
    }
}
