<?php

namespace Label84\TagManager;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Http;

class MeasurementProtocol
{
    protected string $clientId;

    protected string|int|null $userId = null;

    protected bool $isDebugEnabled = false;

    public function __construct()
    {
        $this->clientId = session(config('tagmanager.measurement_protocol_client_id_session_key'), '');
    }

    public function event(string $name, ?array $params = null): array
    {
        $event = [
            'name' => $name,
        ];

        if ($params !== null) {
            $event['params'] = $params;
        }

        if (config('tagmanager.enabled') === false) {
            return [
                'status' => 'tagmanager disabled',
            ];
        }

        $response = Http::withHeaders([
            'content-type' => 'application/json',
        ])
            ->withQueryParameters([
                'measurement_id' => config('tagmanager.measurement_id'),
                'api_secret' => config('tagmanager.measurement_protocol_api_secret'),
            ])
            ->post($this->route(), array_merge(
                [
                    'client_id' => $this->clientId,
                    'events' => [$event],
                ],
                $this->getUserIdArray(),
            ));

        if ($this->isDebugEnabled) {
            return $response->json();
        }

        return [
            'status' => $response->successful() ? 'success' : 'error',
        ];
    }

    public function debug(): self
    {
        $this->isDebugEnabled = true;

        return $this;
    }

    public function user(User $user): self
    {
        $this->userId = "{$user->{config('tagmanager.user_id_key')}}";

        return $this;
    }

    private function route(): string
    {
        $url = 'www.google-analytics.com';

        if ($this->isDebugEnabled) {
            $url = 'www.google-analytics.com/debug';
        }

        return "https://{$url}/mp/collect";
    }

    private function getUserIdArray(): array
    {
        if ($this->userId === null) {
            return [];
        }

        return [
            'user_id' => $this->userId,
        ];
    }
}
