<?php

namespace App\Core;

use App\Modules\JobApplication\Controllers\JobApplicationController;

final class Router
{
    public function __construct(
        private readonly Request $request,
        private readonly Response $response,
        private readonly View $view
    ) {
    }

    public function dispatch(): void
    {
        $module = strtolower((string) $this->request->input('module', 'jobapplication'));
        $action = strtolower((string) $this->request->input('action', 'view'));

        if ($module !== 'jobapplication') {
            $this->response->html('Module not found.', 404);

            return;
        }

        $controller = new JobApplicationController($this->request, $this->response, $this->view);

        $map = [
            'view' => 'view',
            'save' => 'save',
            'edit' => 'edit',
            'cancel' => 'cancel',
            'list' => 'listing',
            'ajax' => 'ajax',
        ];

        $method = $map[$action] ?? null;

        if ($method === null || !method_exists($controller, $method)) {
            $this->response->html('Action not found.', 404);

            return;
        }

        $controller->{$method}();
    }
}
