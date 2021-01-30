<?php

namespace App\Http\Resources;

use App\Enums\CampaignStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CampaignResource
 * @package App\Http\Resources
 * @property int id
 * @property int uuid
 * @property string name
 * @property string type
 * @property string content
 * @property int status
 * @property string provider
 */
class CampaignResource extends JsonResource
{
    const STATUSES = [
        CampaignStatus::FAILED => 'failed',
        CampaignStatus::QUEUED => 'queued',
        CampaignStatus::SENT => 'sent',
    ];

    /**
     * @param Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type,
            'content' => $this->content,
            'status' => self::STATUSES[$this->status],
            'provider' => $this->provider,
        ];
    }
}
