<?php

namespace App\Repositories\Campaign;

use App\Models\Campaign;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CampaignRepository
 * @package App\Repositories\CampaignResource
 */
class CampaignRepository implements CampaignRepositoryInterface
{
    /** @var Campaign */
    private $campaign;

    /**
     * CampaignRepository constructor.
     * @param Campaign $campaign
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @param int $prePage
     * @return LengthAwarePaginator
     */
    public function paginate(int $prePage): LengthAwarePaginator
    {
        return $this->campaign->paginate($prePage);
    }

    /**
     * @param array $fields
     * @return void
     */
    public function create(array $fields): void
    {
        $this->campaign->create($fields);
    }

    /**
     * @param string $uuid
     * @return Campaign|null
     */
    public function findByUuid(string $uuid): ?Campaign
    {
        return $this->campaign->where('uuid', $uuid)->first();
    }
}
