<?php

namespace App\Modules\JobApplication\Controllers;

use App\Core\Config;
use App\Core\Controller;
use App\Modules\JobApplication\Models\JobApplication;
use App\Modules\JobApplication\Services\JobApplicationService;

final class JobApplicationController extends Controller
{
    private ?JobApplicationService $service = null;

    public function __construct(
        \App\Core\Request $request,
        \App\Core\Response $response,
        \App\Core\View $view
    ) {
        parent::__construct($request, $response, $view);
    }

    public function view(): void
    {
        $id = (int) $this->request->get('id', 0);
        $application = $id > 0 ? $this->service()->find($id) : new JobApplication();
        $mode = $id > 0 ? 'view' : 'create';

        $this->render('Modules/JobApplication/Views/form', [
            'title' => 'Job Application Form',
            'application' => $application ?? new JobApplication(),
            'mode' => $mode,
            'errors' => [],
            'message' => null,
            'baseUrl' => Config::app()['base_url'],
        ]);
    }

    public function edit(): void
    {
        $id = (int) $this->request->get('id', 0);
        $application = $this->service()->find($id);

        if ($application === null) {
            $this->render('Modules/JobApplication/Views/form', [
                'title' => 'Job Application Form',
                'application' => new JobApplication(),
                'mode' => 'create',
                'errors' => ['id' => 'Application not found.'],
                'message' => 'The selected application could not be found.',
                'baseUrl' => Config::app()['base_url'],
            ], 404);

            return;
        }

        $this->render('Modules/JobApplication/Views/form', [
            'title' => 'Edit Job Application',
            'application' => $application,
            'mode' => 'edit',
            'errors' => [],
            'message' => null,
            'baseUrl' => Config::app()['base_url'],
        ]);
    }

    public function save(): void
    {
        $result = $this->service()->save($this->request->all(), $this->request->file('cv_filename'));

        if ($this->request->isAjax()) {
            $this->json($result, $result['success'] ? 200 : 422);

            return;
        }

        if ($result['success']) {
            $id = (int) ($result['data']['id'] ?? 0);
            $this->response->redirect(Config::app()['base_url'] . '/index.php?module=jobapplication&action=view&id=' . $id);

            return;
        }

        $this->render('Modules/JobApplication/Views/form', [
            'title' => 'Job Application Form',
            'application' => JobApplication::fromArray($this->request->all()),
            'mode' => empty($this->request->post('id')) ? 'create' : 'edit',
            'errors' => $result['errors'],
            'message' => $result['message'],
            'baseUrl' => Config::app()['base_url'],
        ], 422);
    }

    public function cancel(): void
    {
        if ($this->request->isAjax()) {
            $id = (int) $this->request->input('id', 0);
            $redirect = Config::app()['base_url'] . '/index.php?module=jobapplication&action=' . ($id > 0 ? 'view&id=' . $id : 'view');

            $this->json([
                'success' => true,
                'message' => 'Changes cancelled.',
                'errors' => [],
                'redirect' => $redirect,
            ]);

            return;
        }

        $id = (int) $this->request->get('id', 0);
        $target = Config::app()['base_url'] . '/index.php?module=jobapplication&action=' . ($id > 0 ? 'view&id=' . $id : 'view');
        $this->response->redirect($target);
    }

    public function listing(): void
    {
        $this->render('Modules/JobApplication/Views/list', [
            'title' => 'Applications',
            'applications' => $this->service()->all(),
            'baseUrl' => Config::app()['base_url'],
        ]);
    }

    public function ajax(): void
    {
        $action = strtolower((string) $this->request->post('action', $this->request->get('action', '')));

        if ($action === 'save') {
            $this->save();

            return;
        }

        if ($action === 'cancel') {
            $this->cancel();

            return;
        }

        $this->json([
            'success' => false,
            'message' => 'Unsupported AJAX action.',
            'errors' => [],
        ], 400);
    }

    private function service(): JobApplicationService
    {
        if ($this->service instanceof JobApplicationService) {
            return $this->service;
        }

        $this->service = JobApplicationService::make();

        return $this->service;
    }
}
