<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CampaignResource
 * @package App\Models
 * @property int id
 * @property string uuid
 * @property string name
 * @property string content
 * @property string type
 * @property int status
 * @property string provider
 */
class Campaign extends Model
{
    protected $table = 'campaigns';
    protected $fillable = ['uuid', 'name', 'content', 'type', 'status', 'provider'];

    /**
     * @param string $provider
     * @return void
     */
    public function markAsSent(string $provider): void
    {
        $this->provider = $provider;
        $this->status = CampaignStatus::SENT;

        $this->save();
    }

    /**
     * @param string $provider
     * @return void
     */
    public function markAsFailed(string $provider): void
    {
        $this->provider = $provider;
        $this->status = CampaignStatus::FAILED;

        $this->save();
    }
}
