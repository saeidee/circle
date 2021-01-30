<?php

namespace Tests\Unit\Requests\API\V1;

use Tests\TestCase;
use App\Http\Requests\API\V1\SendCampaignRequest;

/**
 * Class SendCampaignRequestTest
 * @package Tests\Unit\Requests\API\V1
 * @coversDefaultClass \App\Http\Requests\API\V1\SendCampaignRequest
 */
class SendCampaignRequestTest extends TestCase
{
    const TEXT = 'text/plain';
    const HTML = 'text/html';
    const EMAIL_TYPES = [self::TEXT, self::HTML];

    /**
     * @test
     * @covers ::rules
     * @dataProvider rulesProvider
     * @param string $field
     * @param string|array $rule
     */
    function it_should_validate_rules(string $field, $rule)
    {
        $this->assertSame($rule, (new SendCampaignRequest())->rules()[$field]);
    }

    /**
     * @test
     * @covers ::rules
     */
    function it_should_assert_count_validation_rules()
    {
        $this->assertCount(count($this->rulesProvider()), (new SendCampaignRequest())->rules());
    }

    /**
     * @return array
     */
    public function rulesProvider(): array
    {
        return [
            ['campaign', 'required|string'],
            ['subject', 'required|string'],
            ['from.name', 'required|string'],
            ['from.email', 'required|email'],
            ['to', 'required|array'],
            ['to.*.name', 'required|string'],
            ['to.*.email', 'required|email'],
            ['replyTo.name', 'required|string'],
            ['replyTo.email', 'required|email'],
            ['content.type', 'required|in:' . implode(',', self::EMAIL_TYPES)],
            ['content.value', 'required|string'],
        ];
    }
}
