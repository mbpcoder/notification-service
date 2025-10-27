<?php

namespace App\Data\Repositories;

use App\Data\Entities\Entity;
use App\Data\Enums\GriewFilterOperator;
use App\Data\Factories\IFactory;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class MySqlRepository
{
    private null|ConnectionInterface $alternativeDbConnection;

    protected string $primaryKey = 'id';

    protected string $table = '';

    protected bool $softDelete = false;

    private bool $withTrashed = false;

    protected IFactory $factory;

    public function __construct()
    {
        $this->alternativeDbConnection = null;
    }

    public function now(): string
    {
        return date('Y-m-d H:i:s');
    }


    /**
     * @param int $offset
     * @param int $count
     * @return array<int, Collection>
     */
    public function getAll(int $offset, int $count): array
    {
        $query = $this->newQuery();
        $total = $query->count();
        $entities = $query->offset($offset)
            ->limit($count)
            ->orderByDesc('id')
            ->get();
        return [$total, $this->factory->makeCollectionOfEntities($entities)];
    }

    public function getAllForGridView(int $offset = 0, int $count = 0, array $orders = [], array $filters = []): array
    {
        $query = $this->newQuery();

        [$query, $total] = $this->processGridViewQuery($query, $offset, $count, $orders, $filters);
        $entities = $query->get();

        return [$this->factory->makeCollectionOfEntities($entities), $total];
    }

    protected function processGridViewQuery(Builder $query, int $offset = 0, int $count = 0, array $orders = [], array $filters = []): array
    {
        if ($orders) {
            $query = $this->processOrder($query, $orders);
        }

        if ($filters) {
            $query = $this->processFilter($query, $filters);
        }

        $total = $query->count();

        if ($count) {
            $query->offset($offset);
            $query->limit($count);
        }

        return [$query, $total];
    }

    protected function processOrder(Builder $query, array $orders): Builder
    {
        foreach ($orders as $order) {
            $query->orderBy($order->name, $order->type);
        }
        return $query;
    }

    protected function processFilter(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter) {
            switch (strtolower(Str::snake($filter->operator))) {
                case GriewFilterOperator::IS_NULL->value:
                    $query->whereNull($filter->name);
                    break;
                case GriewFilterOperator::IS_NOT_NULL->value:
                    $query->whereNotNull($filter->name);
                    break;
                case GriewFilterOperator::IS_EQUAL_TO->value:
                    if (is_string($filter->operand1) && Str::contains($filter->operand1, '|')) {
                        // create in functionality with equal string
                        $arr = array_filter(explode('|', $filter->operand1));
                        $query->whereIn($filter->name, $arr);
                    } else {
                        $query->where($filter->name, '=', $filter->operand1);
                    }
                    break;
                case GriewFilterOperator::IS_NOT_EQUAL_TO->value:
                    if (is_string($filter->operand1) && Str::contains($filter->operand1, '|')) {
                        // create in functionality with equal string
                        $arr = array_filter(explode('|', $filter->operand1));
                        $query->whereNotIn($filter->name, $arr);
                    } else {
                        $query->where($filter->name, '<>', $filter->operand1);
                    }
                    break;
                case GriewFilterOperator::START_WITH->value:
                    $query->where($filter->name, 'LIKE', $filter->operand1 . '%');
                    break;
                case GriewFilterOperator::DOES_NOT_CONTAINS->value:
                    $query->where($filter->name, 'NOT LIKE', '%' . $filter->operand1 . '%');
                    break;
                case GriewFilterOperator::CONTAINS->value:
                    $query->where($filter->name, 'LIKE', '%' . $filter->operand1 . '%');
                    break;
                case GriewFilterOperator::ENDS_WITH->value:
                    $query->where($filter->name, 'LIKE', '%' . $filter->operand1);
                    break;
                case GriewFilterOperator::IN->value:
                    $query->whereIn($filter->name, $filter->operand1);
                    break;
                case GriewFilterOperator::NOT_IN->value:
                    $query->whereNotIn($filter->name, $filter->operand1);
                    break;
                case GriewFilterOperator::BETWEEN->value:
                    $query->whereBetween($filter->name, array($filter->operand1, $filter->operand2));
                    break;
                case GriewFilterOperator::IS_AFTER_THAN_OR_EQUAL_TO->value:
                case GriewFilterOperator::IS_GREATER_THAN_OR_EQUAL_TO->value:
                    $query->where($filter->name, '>=', $filter->operand1);
                    break;
                case GriewFilterOperator::IS_AFTER_THAN->value:
                case GriewFilterOperator::IS_GREATER_THAN->value:
                    $query->where($filter->name, '>', $filter->operand1);
                    break;
                case GriewFilterOperator::IS_LESS_THAN_OR_EQUAL_TO->value:
                case GriewFilterOperator::IS_BEFORE_THAN_OR_EQUAL_TO->value:
                    $query->where($filter->name, '<=', $filter->operand1);
                    break;
                case GriewFilterOperator::IS_LESS_THAN->value:
                case GriewFilterOperator::IS_BEFORE_THAN->value:
                    $query->where($filter->name, '<', $filter->operand1);
                    break;
            }
        }

        return $query;
    }

    public function newQuery(): Builder
    {
        if (is_null($this->alternativeDbConnection)) {
            $query = app('db')->table($this->table);
        } else {
            $query = $this->alternativeDbConnection->table($this->table);
        }

//        if ($this->softDelete) {
//            if (!$this->withTrashed) {
//                $query = $query->whereNull('deleted_at');
//            } else {
//                $this->withTrashed = false;
//            }
//        }

        return $query;
    }

    public function raw($str)
    {
        if (is_null($this->alternativeDbConnection)) {
            return app('db')->raw($str);
        }
        return $this->alternativeDbConnection->raw($str);
    }

    public function exists($columnValue, $columnName = null): bool
    {
        if (is_null($columnName)) {
            $columnName = $this->primaryKey;
        }
        return $this->newQuery()->where($columnName, $columnValue)->exists();
    }

    /**
     * this is for validation purpose look at AppServiceProvider
     */
    public function valueExists($attribute, $value, $ignoredPrimaryKey = null): bool
    {
        $query = $this->newQuery();

        if ($this->softDelete) {
            $query->whereNull('deleted_at');
        }

        $query->where($attribute, $value);

        if (!is_null($ignoredPrimaryKey)) {
            $query->where($this->primaryKey, '<>', $ignoredPrimaryKey);
        }

        return $query->exists();
    }

    public function updateOrCreate(Entity $model): void
    {
        if ($this->exists($model->getPrimaryKey())) {
            $this->update($model);
        } else {
            $this->create($model);
        }
    }

    /**
     * @param Entity $model
     */
    public function createIfNotExists(Entity $model): void
    {
        if (!$this->exists($model->getPrimaryKey())) {
            $this->create($model);
        }
    }

    /**
     * It returns maximum row Id
     * @return int|mixed
     */
    public function getMaxId(): int
    {
        $entity = $this->newQuery()->orderByDesc($this->primaryKey)->first();
        if ($entity) {
            return $entity->id;
        }
        return 0;

    }
}
