<?php

namespace App;

use App\Contracts\ApplicationClientInterface;
use Illuminate\Foundation\Application;

class Client implements ApplicationClientInterface
{
    protected Application $app;

    protected $callback;

    protected \App\Data\Entities\Client|null $client;

    public function __construct($app)
    {
        $this->app = $app;
        $this->client = null;
    }

    public function check(): bool
    {
        return !is_null($this->client());
    }

    public function client(): \App\Data\Entities\Client|null
    {
        if (is_null($this->client)) {
            $callback = $this->callback;
            $this->setClient($callback($this->app['request']));
        }

        return $this->client;
    }

    public function id(): int|null
    {
        if ($this->check()) {
            return $this->client()->id;
        }

        return null;
    }

    public function setClient(\App\Data\Entities\Client|null $client = null): void
    {
        $this->client = $client;
    }

    public function viaRequest(callable $callback): void
    {
        $this->callback = $callback;
    }

}
