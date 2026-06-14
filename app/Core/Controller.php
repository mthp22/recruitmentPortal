<?php

namespace App\Core;

abstract class Controller
{
    public function __construct(
        protected readonly Request $request,
        protected readonly Response $response,
        protected readonly View $view
    ) {
    }

    protected function render(string $template, array $data = [], int $status = 200): void
    {
        $this->response->html($this->view->render($template, $data), $status);
    }

    protected function json(array $payload, int $status = 200): void
    {
        $this->response->json($payload, $status);
    }
}
