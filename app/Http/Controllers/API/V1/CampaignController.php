<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\JsonResponse;
use App\Jobs\CampaignSenderManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Queue;
use App\Http\Resources\CampaignResource;
use App\ValueObjects\Payloads\CampaignPayload;
use App\Http\Requests\API\V1\SendCampaignRequest;
use App\Repositories\Campaign\CampaignRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class CampaignController
 * @package App\Http\Controllers\API\V1
 */
class CampaignController extends Controller
{
    /**
     * @param CampaignRepositoryInterface $campaignRepository
     * @return AnonymousResourceCollection
     */
    public function index(CampaignRepositoryInterface $campaignRepository): AnonymousResourceCollection
    {
        return CampaignResource::collection($campaignRepository->paginate());
    }

    /**
     * @param SendCampaignRequest $request
     * @return JsonResponse
     */
    public function store(SendCampaignRequest $request): JsonResponse
    {
        Queue::push(
            new CampaignSenderManager(
                config('app.initial_email_provider'),
                new CampaignPayload($request->validated())
            )
        );

        return $this->success('Successfully CampaignResource created');
    }
}
