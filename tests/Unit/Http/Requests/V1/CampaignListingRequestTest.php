<?php

namespace Tests\Unit\Http\Requests\API\V1;

use Tests\TestCase;
use App\Http\Requests\API\V1\CampaignListingRequest;

/**
 * Class CampaignListingRequest
 * @package Tests\Unit\Requests\API\V1
 * @coversDefaultClass \App\Http\Requests\API\V1\CampaignListingRequest
 */
class CampaignListingRequestTest extends TestCase
{
    /**
     * @test
     * @covers ::rules
     * @dataProvider rulesProvider
     * @param string $field
     * @param string|array $rule
     */
    function it_should_validate_rules(string $field, $rule)
    {
        $this->assertSame($rule, (new CampaignListingRequest())->rules()[$field]);
    }

    /**
     * @test
     * @covers ::rules
     */
    function it_should_assert_count_validation_rules()
    {
        $this->assertCount(count($this->rulesProvider()), (new CampaignListingRequest())->rules());
    }

    /**
     * @return array
     */
    public function rulesProvider(): array
    {
        return [
            ['page', 'int'],
            ['prePage', 'int|max:100'],
        ];
    }
}
