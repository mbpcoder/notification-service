<?php

namespace App\Http\Controllers\Panel;

use App\Data\Entities\Department;
use App\Data\Enums\HttpStatusEnum;
use App\Data\Repositories\Department\DepartmentRepository;
use App\Data\Resources\DepartmentResource;
use App\Exceptions\EntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\DepartmentListRequestPanel;
use App\Http\Requests\Panel\DepartmentStoreRequestPanel;
use Illuminate\Http\JsonResponse;

class DepartmentsController extends Controller
{
    public function __construct(
        private readonly DepartmentRepository $departmentRepository,
        private readonly DepartmentResource   $departmentResource,

    )
    {
        parent::__construct();
    }

    public function list(DepartmentListRequestPanel $request): JsonResponse
    {
        [$total, $providers] = $this->departmentRepository->getAll($request->offset(), $request->perPage);
        $this->response->value->add('total', $total);
        $this->response->value->add('departments', $this->departmentResource->collectionToArray($providers));
        return $this->response->toJson();
    }

    public function store(DepartmentStoreRequestPanel $request): JsonResponse
    {
        $department = new Department();
        $department->name = $request->name;

        $department = $this->departmentRepository->create($department);

        $this->response->value->add('department', $this->departmentResource->toArray($department));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();

    }

    public function update(DepartmentStoreRequestPanel $request, $id): JsonResponse
    {
        $provider = $this->departmentRepository->getOneById($id);

        if ($provider == null) {
            throw new EntityNotFoundException();
        }

        $provider->name = $request->name;

        $this->departmentRepository->update($provider);

        $this->response->value->add('provider', $this->departmentResource->toArray($provider));
        $this->response->code = HttpStatusEnum::OK;
        return $this->response->toJson();
    }
}
