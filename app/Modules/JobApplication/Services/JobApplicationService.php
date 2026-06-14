<?php

namespace App\Modules\JobApplication\Services;

use App\Core\Config;
use App\Modules\JobApplication\Models\JobApplication;
use App\Modules\JobApplication\Repositories\JobApplicationRepository;
use RuntimeException;

final class JobApplicationService
{
    private const REQUIRED_FIELDS = [
        'name',
        'surname',
        'nationality',
        'id_number',
        'cellphone',
        'email',
        'gender',
        'employment_equity',
        'availability',
        'qualification_type',
        'qualification',
        'institution',
        'qualification_year',
        'qualification_status',
        'salary_type',
        'salary_rate',
        'sector',
        'function',
        'region',
        'location',
    ];

    public function __construct(private readonly JobApplicationRepository $repository)
    {
    }

    public static function make(): self
    {
        return new self(JobApplicationRepository::make());
    }

    public function save(array $input, ?array $file = null): array
    {
        $errors = $this->validate($input, $file, empty($input['id']));

        if ($errors !== []) {
            return [
                'success' => false,
                'message' => 'Please correct the highlighted errors.',
                'errors' => $errors,
                'data' => $input,
            ];
        }

        $existing = null;
        if (!empty($input['id'])) {
            $existing = $this->repository->find((int) $input['id']);
            if ($existing === null) {
                return [
                    'success' => false,
                    'message' => 'The selected application could not be found.',
                    'errors' => ['id' => 'Application record not found.'],
                ];
            }
        }

        $application = JobApplication::fromArray($input);
        $application->cvFilename = $existing?->cvFilename;

        if ($file !== null && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $application->cvFilename = $this->storeCv($file);
        }

        if ($application->id !== null) {
            $this->repository->update($application);
            $saved = $this->repository->find($application->id);

            return [
                'success' => true,
                'message' => 'Application updated successfully.',
                'errors' => [],
                'data' => $saved?->toArray(),
            ];
        }

        $id = $this->repository->create($application);
        $saved = $this->repository->find($id);

        return [
            'success' => true,
            'message' => 'Application saved successfully.',
            'errors' => [],
            'data' => $saved?->toArray(),
        ];
    }

    public function find(int $id): ?JobApplication
    {
        return $this->repository->find($id);
    }

    public function all(): array
    {
        return $this->repository->all();
    }

    private function validate(array $input, ?array $file, bool $isCreate): array
    {
        $errors = [];

        foreach (self::REQUIRED_FIELDS as $field) {
            if (trim((string) ($input[$field] ?? '')) === '') {
                $errors[$field] = 'This field is required.';
            }
        }

        $email = (string) ($input['email'] ?? '');
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Enter a valid email address.';
        }

        $phone = preg_replace('/\s+/', '', (string) ($input['cellphone'] ?? ''));
        if ($phone !== '' && preg_match('/^\+?[0-9]{10,15}$/', $phone) !== 1) {
            $errors['cellphone'] = 'Enter a valid phone number.';
        }

        $terms = $input['terms_accepted'] ?? $input['terms'] ?? null;
        if (!in_array((string) $terms, ['1', 'true', 'on', 'yes'], true)) {
            $errors['terms_accepted'] = 'You must accept the terms and conditions.';
        }

        if ($isCreate && ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE)) {
            $errors['cv_filename'] = 'Please upload your CV.';
        }

        if ($file !== null && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $uploadErrors = $this->validateFile($file);
            if ($uploadErrors !== null) {
                $errors['cv_filename'] = $uploadErrors;
            }
        }

        return $errors;
    }

    private function validateFile(array $file): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return 'The CV upload failed. Please try again.';
        }

        $config = Config::uploads();
        if (($file['size'] ?? 0) > $config['max_size']) {
            return 'The CV file may not be larger than 4MB.';
        }

        $extension = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (!in_array($extension, $config['allowed_extensions'], true)) {
            return 'The selected CV file type is not allowed.';
        }

        $mimeType = mime_content_type((string) ($file['tmp_name'] ?? ''));
        if ($mimeType === false || !in_array($mimeType, $config['allowed_mime_types'], true)) {
            return 'The uploaded CV file is not valid.';
        }

        return null;
    }

    private function storeCv(array $file): string
    {
        $config = Config::uploads();
        $extension = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
        $filename = sprintf('%s.%s', bin2hex(random_bytes(16)), $extension);
        $destination = rtrim($config['cv_directory'], '/\\') . DIRECTORY_SEPARATOR . $filename;

        if (!is_dir($config['cv_directory']) && !mkdir($config['cv_directory'], 0775, true) && !is_dir($config['cv_directory'])) {
            throw new RuntimeException('The upload directory could not be created.');
        }

        if (!move_uploaded_file((string) $file['tmp_name'], $destination)) {
            throw new RuntimeException('The uploaded CV could not be saved.');
        }

        return $filename;
    }
}
