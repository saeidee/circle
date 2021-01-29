<?php

namespace App\Repositories\Campaign;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface CampaignRepositoryInterface
 * @package App\Repositories\Campaign
 */
interface CampaignRepositoryInterface
{
    /**
     * @return LengthAwarePaginator
     */
    public function paginate(): LengthAwarePaginator;

    /**
     * @param array $fields
     * @return void
     */
    public function create(array $fields): void;
}
