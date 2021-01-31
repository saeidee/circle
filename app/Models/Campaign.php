<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
 * @method Builder queued()
 * @method Builder failed()
 * @method Builder sent()
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

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeQueued(Builder $query): Builder
    {
        return $query->where('status', CampaignStatus::QUEUED);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', CampaignStatus::FAILED);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', CampaignStatus::SENT);
    }
}
