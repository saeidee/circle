<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\Support\StatsService;

/**
 * Class StatsController
 * @package App\Http\Controllers\API\V1
 */
class StatsController extends Controller
{
    /**
     * @param StatsService $statsService
     * @return array
     */
    public function index(StatsService $statsService): array
    {
        return $statsService->stats();
    }
}
