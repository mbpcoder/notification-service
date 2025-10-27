<?php

namespace App\Contracts;

interface ApplicationClientInterface
{
    public function check(): bool;

    public function client(): \App\Data\Entities\Client|null;

    public function id(): int|null;

    public function setClient(\App\Data\Entities\Client $client): void;

    public function viaRequest(callable $callback): void;
}
