<?php

namespace Tests\Unit\Services\Support;

use Tests\TestCase;
use GuzzleHttp\ClientInterface;
use App\Services\Support\Requester;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequesterTest
 * @package Tests\Unit\Services\Support
 * @coversDefaultClass \App\Services\Support\Requester
 */
class RequesterTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::makeRequest
     */
    function it_should_make_the_request()
    {
        $client = $this->createMock(ClientInterface::class);
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $requestService = new Requester($client, $request);

        $client->expects($this->once())->method('send')->with($request)->willReturn($response);

        $this->assertEquals($response, $requestService->makeRequest());
    }
}
