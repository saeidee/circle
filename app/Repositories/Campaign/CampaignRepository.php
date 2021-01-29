<?php

namespace App\Repositories\Campaign;

use App\Models\Campaign;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CampaignRepository
 * @package App\Repositories\Campaign
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
     * @return LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator
    {
        return $this->campaign->paginate();
    }

    /**
     * @param array $fields
     * @return void
     */
    public function create(array $fields): void
    {
        $this->campaign->create($fields);
    }
}
