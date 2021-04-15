<?php

namespace Tests\Unit\Http\Middleware;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Middleware\RefineMailContent;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class RefineMailContentTest
 * @package Tests\Unit\Http\Middleware
 * @coversDefaultClass \App\Http\Middleware\RefineMailContent
 */
class RefineMailContentTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::handle
     */
    function it_should_refine_mail_content()
    {
        $contentType = 'text/html';
        $contentValue = $this->faker->randomHtml();
        /** @var Request|MockObject $request */
        $request = $this->createMock(Request::class);
        $refineMailContent = new RefineMailContent();

        $request->expects($this->atLeastOnce())
            ->method('input')
            ->withConsecutive(['content.value'], ['content.type'])
            ->willReturnOnConsecutiveCalls($contentValue, $contentType);
        $request->expects($this->once())
            ->method('merge')
            ->with([
                'content' => [
                    'value' => addslashes($contentValue),
                    'type' => $contentType,
                ],
            ]);


        $refineMailContent->handle(
            $request,
            function (Request $refinedRequest) use ($request) {
                $this->assertEquals($request, $refinedRequest);
            }
        );
    }
}
