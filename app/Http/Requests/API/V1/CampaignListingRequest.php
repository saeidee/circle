<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\Request;

/**
 * Class CampaignListingRequest
 * @package App\Http\Requests\API\V1
 */
class CampaignListingRequest extends Request
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'page' => 'int',
            'prePage' => 'int|max:100',
        ];
    }
}
