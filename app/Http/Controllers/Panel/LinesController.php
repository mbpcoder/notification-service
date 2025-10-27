<?php

namespace App\Http\Controllers\Panel;

use App\Data\Entities\Line;
use App\Data\Enums\HttpStatusEnum;
use App\Data\Repositories\Line\LineRepository;
use App\Data\Resources\LineResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\LineListRequestPanel;
use App\Http\Requests\Panel\LineStoreRequestPanel;
use Illuminate\Http\JsonResponse;

class LinesController extends Controller
{
    public function __construct(
        private readonly LineRepository $lineRepository,
        private readonly LineResource   $lineResource
    )
    {
        parent::__construct();

    }

    public function list(LineListRequestPanel $request): JsonResponse
    {
        [$total, $lines] = $this->lineRepository->getAll($request->offset(), $request->perPage);
        $this->response->value->add('total', $total);
        $this->response->value->add('lines', $this->lineResource->collectionToArray($lines));
        return $this->response->toJson();
    }

    public function store(LineStoreRequestPanel $request): JsonResponse
    {
        $line = new Line();
        $line->providerId = $request->providerId;
        $line->number = $request->number;
        $line->description = $request->description;
        $line->isActive = $request->isActive;
        $line->isDefault = $request->isDefault;
        $line->isReceivable = $request->isReceivable;

        $line = $this->lineRepository->create($line);

        $this->response->value->add('line', $this->lineResource->toArray($line));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();

    }

    public function update(LineStoreRequestPanel $request, $id): JsonResponse
    {
        $line = $this->lineRepository->getOneById($id);

        if ($line == null) {
            $this->response->code = HttpStatusEnum::NOT_FOUND;
            $this->response->message = __('Not Found');
            return $this->response->toJson();
        }

        $line->providerId = $request->providerId;
        $line->number = $request->number;
        $line->description = $request->description;
        $line->isActive = $request->isActive;
        $line->isDefault = $request->isDefault;
        $line->isReceivable = $request->isReceivable;

        $this->lineRepository->update($line);

        $this->response->value->add('line', $this->lineResource->toArray($line));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();
    }
}
