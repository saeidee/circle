<?php

namespace App\Repositories\Campaign;

use App\Models\Campaign;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface CampaignRepositoryInterface
 * @package App\Repositories\CampaignResource
 */
interface CampaignRepositoryInterface
{
    /**
     * @param int $prePage
     * @return LengthAwarePaginator
     */
    public function paginate(int $prePage): LengthAwarePaginator;

    /**
     * @param array $fields
     * @return void
     */
    public function create(array $fields): void;

    /**
     * @param string $uuid
     * @return Campaign|null
     */
    public function findByUuid(string $uuid): ?Campaign;
}
