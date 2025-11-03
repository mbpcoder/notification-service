<?php

namespace App\Http\Requests;

use App\Channels\Sms\SmsManager;
use App\Channels\Sms\SmsProvider;
use App\Data\Enums\HttpStatusEnum;
use App\Data\Repositories\Provider\ProviderRepository;
use App\Http\Controllers\Controller;
use App\Http\Responses\Response;
use Illuminate\Http\Request;
use function App\Http\Controllers\__;

class WebhookController extends Controller
{

    public function __construct(
        private readonly ProviderRepository $providerRepository,
        Response                            $response
    )
    {
        parent::__construct($response);
    }

    public function updateStatus(Request $request, string $providerSlug)
    {
        $provider = $this->providerRepository->getOneBySlug($providerSlug);

        if ($provider === null) {
            $this->response->message = __('Provider not found');
            $this->response->code = HttpStatusEnum::NOT_FOUND;
            return $this->response->toJson();
        }


        $smsProvider = new SmsProvider();
        $smsProvider->className = $provider->className;
        $smsChannel = new SmsManager($smsProvider);
        $status = $smsChannel->parseStatusUpdateWebhook($request->all());

    }

}
