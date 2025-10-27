<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Arrayable;

class ResponseValue implements Arrayable
{
    /**
     * @var array
     */
    private array $value = [];


    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function add($name, $value): self
    {
        $this->value[$name] = $value;
        return $this;
    }


    /**
     *
     *
     * @param $name
     * @return bool
     */
    public function has($name): bool
    {
        return !empty($this->value[$name]);
    }


    /**
     *
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->value);
    }


    /**
     *
     *
     * @param $name
     * @return mixed|null
     */
    public function get($name): mixed
    {
        return $this->has($name) ? $this->value[$name] : null;
    }


    /**
     * Convert to Array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->value;
    }
}
