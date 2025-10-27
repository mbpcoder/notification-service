<?php

namespace App\Http\Responses;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Validation\Validator;

class Error implements Arrayable, ArrayAccess
{
    /**
     * @var array list of errors send in response
     */
    private array $errors = [];


    /**
     * @param $offset
     * @param $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->errors[] = $value;
        } else {
            $this->errors[$offset] = $value;
        }
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->errors[$offset]);
    }

    /**
     * @param $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->errors[$offset]);
    }

    /**
     * @param $offset
     * @return mixed|null
     */
    public function offsetGet($offset): mixed
    {
        return $this->errors[$offset] ?? null;
    }


    /**
     * @param Validator|null $validator
     */
    public function addValidator(Validator $validator = null): void
    {
        if ($validator) {
            foreach ($validator->errors()->messages() as $name => $messages) {
                $this->add('validation.' . $name, $messages[0]);
            }
        }
    }


    /**
     * @param string $name
     * @param string $message
     * @return $this
     */
    public function add(string $name, string $message): self
    {
        $index = $this->indexOf($name);

        if ($index >= 0) {
            $this->errors[$index]['message'] .= "\n $message";
        } else {
            $this->errors[] = ['name' => $name, 'message' => $message];
        }

        return $this;
    }

    /**
     * return index of error name
     *
     * @param string $name
     * @return int
     */
    public function indexOf(string $name): int
    {
        for ($index = 0; $index < $this->count(); $index++) {
            if ($this->errors[$index]['name'] === $name) {
                return $index;
            }
        }
        return -1;
    }


    /**
     * Get count of errors
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->errors);
    }


    /**
     *
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->errors;
    }
}
