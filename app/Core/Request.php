<?php

namespace App\Core;

final class Request
{
    public function __construct(
        private readonly array $get,
        private readonly array $post,
        private readonly array $files,
        private readonly array $server
    ) {
    }

    public static function capture(): self
    {
        return new self($_GET, $_POST, $_FILES, $_SERVER);
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function input(string $key, mixed $default = null): mixed
    {
        $value = $this->post[$key] ?? $this->get[$key] ?? $default;

        if (!is_string($value)) {
            return $value;
        }

        return trim($value);
    }

    public function post(string $key, mixed $default = null): mixed
    {
        $value = $this->post[$key] ?? $default;

        if (!is_string($value)) {
            return $value;
        }

        return trim($value);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->get[$key] ?? $default;

        if (!is_string($value)) {
            return $value;
        }

        return trim($value);
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    public function only(array $keys): array
    {
        $values = [];

        foreach ($keys as $key) {
            $values[$key] = $this->input($key);
        }

        return $values;
    }

    public function file(string $key): ?array
    {
        $file = $this->files[$key] ?? null;

        return is_array($file) ? $file : null;
    }

    public function isAjax(): bool
    {
        $requestedWith = $this->server['HTTP_X_REQUESTED_WITH'] ?? '';
        $accept = $this->server['HTTP_ACCEPT'] ?? '';

        return strtolower($requestedWith) === 'xmlhttprequest' || str_contains($accept, 'application/json');
    }
}
