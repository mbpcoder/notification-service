<?php

namespace App\Data\Repositories\Line;

use App\Data\Entities\Line;
use App\Data\Factories\LineFactory;
use App\Data\Repositories\MySqlRepository;
use Illuminate\Support\Collection;

class MySqlLineRepository extends MySqlRepository implements ILineRepository
{
    public function __construct()
    {
        $this->table = 'lines';
        $this->primaryKey = 'id';
        $this->softDelete = false;
        $this->factory = new LineFactory();

        parent::__construct();
    }

    public function getOneById(int $id): null|Line
    {
        $line = $this->newQuery()
            ->where('id', $id)
            ->first();

        return $line ? $this->factory->makeEntityFromStdClass($line) : null;
    }

    public function getAllByIds(array $ids): Collection
    {
        $line = $this->newQuery()
            ->whereIn('id', $ids)
            ->get();

        return $this->factory->makeCollectionOfEntities($line);
    }

    public function getLineByProviderIdAndLineNumber(int $providerId, int $number): Line|null
    {
        $line = $this->newQuery()
            ->where('provider_id', $providerId)
            ->where('number', $number)
            ->first();

        return $line ? $this->factory->makeEntityFromStdClass($line) : null;
    }

    public function getDefaultLineByProviderId(int $providerId): Line|null
    {
        $line = $this->newQuery()
            ->where('provider_id', $providerId)
            ->where('is_default', true)
            ->first();

        return $line ? $this->factory->makeEntityFromStdClass($line) : null;
    }

    public function create(Line $line): Line
    {
        $line->createdAt = $this->now();
        $line->updatedAt = $this->now();

        $id = $this->newQuery()
            ->insertGetId([
                'provider_id' => $line->providerId,
                'number' => $line->number,
                'description' => $line->description,
                'is_active' => $line->isActive ?? false,
                'is_default' => $line->isDefault ?? false,
                'is_receivable' => $line->isReceivable ?? false,
                'created_at' => $line->createdAt,
                'updated_at' => $line->updatedAt,
            ]);

        $line->id = $id;

        return $line;
    }

    public function update(Line $line): int
    {
        $line->updatedAt = $this->now();

        return $this->newQuery()
            ->where($this->primaryKey, $line->getPrimaryKey())
            ->update([
                'provider_id' => $line->providerId,
                'number' => $line->number,
                'description' => $line->description,
                'is_active' => $line->isActive,
                'is_default' => $line->isDefault,
                'is_receivable' => $line->isReceivable,
                'updated_at' => $line->updatedAt,
            ]);
    }
}
