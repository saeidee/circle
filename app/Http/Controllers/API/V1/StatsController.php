<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\Campaign\CampaignRepositoryInterface;

/**
 * Class StatsController
 * @package App\Http\Controllers\API\V1
 */
class StatsController extends Controller
{
    /**
     * @param CampaignRepositoryInterface $campaignRepository
     * @return JsonResponse
     */
    public function index(CampaignRepositoryInterface $campaignRepository): JsonResponse
    {
        return new JsonResponse(
            [
                'sent' => $campaignRepository->getSentCount(),
                'failed' => $campaignRepository->getFailedCount(),
                'queued' => $campaignRepository->getQueuedCount(),
            ],
            Response::HTTP_OK
        );
    }
}
