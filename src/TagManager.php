<?php

namespace Label84\TagManager;

use Illuminate\Support\Collection;

class TagManager
{
    private Collection $data;

    public function __construct()
    {
        $this->data = new Collection();
    }

    public function push(array $variables): self
    {
        $this->data->push($variables);

        return $this;
    }

    public function event(string $name, array $variables = []): self
    {
        $this->data->push(['event' => $name] + $variables);

        return $this;
    }

    public function login(array $variables = []): self
    {
        $this->data->push(['event' => 'login'] + $variables);

        return $this;
    }

    public function register(array $variables = []): self
    {
        $this->data->push(['event' => 'sign_up'] + $variables);

        return $this;
    }

    public function search(array $variables = []): self
    {
        $this->data->push(['event' => 'search'] + $variables);

        return $this;
    }

    public function get(): Collection
    {
        return $this->data;
    }

    public function clear(): void
    {
        $this->data = new Collection();
    }
}
