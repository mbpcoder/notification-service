<?php

namespace App\Http\Controllers\Api;

use App\Data\Entities\Message;
use App\Data\Entities\Sms;
use App\Data\Enums\HttpStatusEnum;
use App\Data\Enums\SmsStatusEnum;
use App\Data\Factories\SmsFactory;
use App\Data\Repositories\Credential\CredentialRepository;
use App\Data\Repositories\Message\MessageRepository;
use App\Data\Repositories\Sms\SmsRepository;
use App\Data\Resources\SmsResource;
use App\Exceptions\EntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendBulkSmsRequest;
use App\Http\Requests\Api\SendSmsOTPRequest;
use App\Http\Requests\Api\SendSmsRequest;
use App\Http\Responses\Response;
use App\Jobs\SendSmsJob;
use App\Services\LimitationService;
use App\Services\LineService;
use App\Services\ProviderService;
use Illuminate\Http\JsonResponse;
use Random\RandomException;

class SmsController extends Controller
{
    public function __construct(
        private readonly CredentialRepository $credentialRepository,
        private readonly MessageRepository $messageRepository,
        private readonly LimitationService    $limitationService,
        private readonly ProviderService      $providerService,
        private readonly LineService          $lineService,
        private readonly SmsRepository        $smsRepository,
        private readonly SmsResource          $smsResource
    )
    {
        parent::__construct();

    }

    public function send(SendSmsRequest $request): Response|JsonResponse
    {
        $provider = $this->providerService->getProvider($request->provider);

        $line = $this->lineService->getLine($provider->id, $request->line);

        $apiClient = apiClient();

        $sms = new Sms();
        $sms->departmentId = $apiClient->departmentId;
        $sms->clientId = $apiClient->id;
        $sms->providerId = $provider->id;
        $sms->mobile = $request->mobile;
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


        $contentMd5 = md5($request->message);
        $message = $this->messageRepository->getOneByContentMd5($contentMd5);

        if ($message === null) {
            $message = new Message();
            $message->content = $request->message;
            $message->contentMd5 = $contentMd5;
            $message = $this->messageRepository->create($message);
        }

        $sms->messageId = $message->id;

        $sms = $this->smsRepository->create($sms);

        $this->response->code = HttpStatusEnum::OK;
        $this->response->value->add('sms', $this->smsResource->toArray($sms));
        return $this->response->toJson();
    }

    /**
     * @throws EntityNotFoundException
     */
    public function sendBulkSms(SendBulkSmsRequest $request, SmsFactory $smsFactory): JsonResponse
    {
        $provider = $this->providerService->getProvider($request->sendBulkSmsDto->provider);
        $line = $this->lineService->getLine($provider->id, $request->sendBulkSmsDto->line);

        $contentMd5 = md5($request->sendBulkSmsDto->message);
        $message = $this->messageRepository->getOneByContentMd5($contentMd5);
        if ($message === null) {
            $message = new Message();
            $message->content = $request->sendBulkSmsDto->message;
            $message->contentMd5 = $contentMd5;
            $message = $this->messageRepository->create($message);
        }

        $bulkSms = $smsFactory->makeCollectionFromBulkDto($request->sendBulkSmsDto, $provider->id, $line->id, $message->id);

//        if (!$this->limitationService->isWithinLimit($bulkSms)) {
//            $this->response->code = HttpStatusEnum::FORBIDDEN;
//            $this->response->message = __('Limit Exceeded');
//            return $this->response->toJson();
//        }

        $this->smsRepository->bulkInsert($bulkSms);

        $this->response->code = HttpStatusEnum::OK;
        $this->response->value->add('sms_count', $bulkSms->count());

        return $this->response->toJson();
    }

    /**
     * @throws RandomException
     * @throws EntityNotFoundException
     */
    public function sendOtp(SendSmsOTPRequest $request): Response|JsonResponse
    {
        $provider = $this->providerService->getProvider($request->provider);

        $line = $this->lineService->getLine($provider->id, $request->line);

        $apiClient = apiClient();

        $otpCode = random_int(1000, 9999);

        $messageContent = $request->message ?? __('Your verification code is') . ' ' . $otpCode;

        $contentMd5 = md5($messageContent);
        $message = $this->messageRepository->getOneByContentMd5($contentMd5);
        if ($message === null) {
            $message = new Message();
            $message->content = $messageContent;
            $message->contentMd5 = $contentMd5;
            $message = $this->messageRepository->create($message);
        }

        $sms = new Sms();
        $sms->departmentId = $apiClient->departmentId;
        $sms->clientId = $apiClient->id;
        $sms->providerId = $provider->id;
        $sms->messageId = $message->id;
        $sms->mobile = $request->mobile;
        $sms->lineId = $line->id;
        $sms->status = SmsStatusEnum::SENDING;
        $sms->expiredAt = date('Y-m-d H:i:s', strtotime('+2 minutes'));

        // calculate limitation
        if (!$this->limitationService->isWithinLimit($sms)) {
            $this->response->code = HttpStatusEnum::FORBIDDEN;
            $this->response->message = __('Limit Exceeded');
            return $this->response->toJson();
        }

        $sms = $this->smsRepository->create($sms);

        $credential = $this->credentialRepository->getAllByProviderIdsOrLineIds([$provider->id], [$line->id])->first();

        dispatch(new SendSmsJob($sms, $message, $provider, $line, $credential));

        $this->response->code = HttpStatusEnum::OK;
        $this->response->value->add('sms', $this->smsResource->toArray($sms));
        $this->response->value->add('otp', $otpCode);
        return $this->response->toJson();
    }
}
