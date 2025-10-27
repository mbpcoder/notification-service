<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ApplicationClientInterface;
use App\Data\Entities\Sms;
use App\Data\Enums\HttpStatusEnum;
use App\Data\Enums\SmsStatusEnum;
use App\Data\Repositories\Client\ClientRepository;
use App\Data\Repositories\Line\LineRepository;
use App\Data\Repositories\Provider\ProviderRepository;
use App\Data\Repositories\Sms\SmsRepository;
use App\Data\Resources\SmsResource;
use App\Exceptions\EntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendSmsRequest;
use App\Http\Responses\Response;
use App\Services\LimitationService;

class SmsController extends Controller
{
    public function __construct(
        private readonly ClientRepository  $clientRepository,
        private readonly ProviderRepository $providerRepository,
        private readonly LineRepository     $lineRepository,
        private readonly LimitationService $limitationService,
        private readonly SmsRepository     $smsRepository,
        private readonly SmsResource       $smsResource
    )
    {
        parent::__construct();

    }

    public function send(SendSmsRequest $request): Response|\Illuminate\Http\JsonResponse
    {
        $provider = null;
        $line = null;

        if ($request->provider !== null) {
            $provider = $this->providerRepository->getOneBySlug($request->input('provider'));

        } else {
            $provider = $this->providerRepository->getOneDefault();
        }

        if ($provider === null) {
            throw new EntityNotFoundException();
        }

        if ($request->has('line')) {
            $line = $this->lineRepository->getLineByProviderIdAndLineNumber($provider->id, $request->input('line'));

            if ($line === null) {
                throw new EntityNotFoundException(__('Line Not Found'));
            }
        } else {
            // Set Default Line
            $line = $this->lineRepository->getDefaultLineByProviderId($provider->id);

            if ($line === null) {
                throw new EntityNotFoundException(__('Default line Not Found'));
            }
        }

        $apiClient = app(ApplicationClientInterface::class);
        $client = $apiClient->client();


        $sms = new Sms();
        $sms->departmentId = $client->departmentId;
        $sms->clientId = $client->id;
        $sms->providerId = $provider->id;
        $sms->mobile = $request->mobile;
        $sms->message = $request->message;
        $sms->lineId = $line->id;
        $sms->status = SmsStatusEnum::PENDING;
        $sms->dueAt = $request->dueAt;
        $sms->expiredAt = $request->expiredAt;

        // calculate limitation
        if (!$this->limitationService->isWithinLimit($sms)) {
            $this->response->code = HttpStatusEnum::FORBIDDEN;
            $this->response->message = __('Limit Exceeded');
            return $this->response->toJson();
        }


        $sms = $this->smsRepository->create($sms);

        $this->response->code = HttpStatusEnum::OK;
        $this->response->value->add('sms', $this->smsResource->toArray($sms));
        return $this->response->toJson();
    }
}
