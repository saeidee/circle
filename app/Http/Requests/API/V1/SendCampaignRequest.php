<?php

namespace App\Http\Requests\API\V1;

use App\Enums\EmailTypeEnums;
use App\Http\Requests\Request;

/**
 * Class SendCampaignRequest
 * @package App\Http\Requests\API\V1
 */
class SendCampaignRequest extends Request
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'campaign' => 'required|string',
            'subject' => 'required|string',
            'from.name' => 'required|string',
            'from.email' => 'required|email',
            'to' => 'required|array',
            'to.*.name' => 'required|string',
            'to.*.email' => 'required|email',
            'replyTo.name' => 'required|string',
            'replyTo.email' => 'required|email',
            'content.type' => 'required|in:' . implode(',', EmailTypeEnums::ALL),
            'content.value' => 'required|string',
        ];
    }
}
