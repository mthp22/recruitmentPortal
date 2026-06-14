<?php

namespace App\Modules\JobApplication\Models;

final class JobApplication
{
    public ?int $id = null;
    public string $name = '';
    public string $surname = '';
    public string $nationality = '';
    public string $idNumber = '';
    public string $cellphone = '';
    public string $email = '';
    public string $gender = '';
    public string $employmentEquity = '';
    public string $availability = '';
    public string $qualificationType = '';
    public string $qualification = '';
    public string $institution = '';
    public string $qualificationYear = '';
    public string $qualificationStatus = '';
    public string $salaryType = '';
    public string $salaryRate = '';
    public string $sector = '';
    public string $function = '';
    public string $region = '';
    public string $location = '';
    public ?string $cvFilename = null;
    public bool $termsAccepted = false;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    public static function fromArray(array $data): self
    {
        $application = new self();
        $application->id = isset($data['id']) && $data['id'] !== '' ? (int) $data['id'] : null;
        $application->name = (string) ($data['name'] ?? '');
        $application->surname = (string) ($data['surname'] ?? '');
        $application->nationality = (string) ($data['nationality'] ?? '');
        $application->idNumber = (string) ($data['id_number'] ?? '');
        $application->cellphone = (string) ($data['cellphone'] ?? '');
        $application->email = (string) ($data['email'] ?? '');
        $application->gender = (string) ($data['gender'] ?? '');
        $application->employmentEquity = (string) ($data['employment_equity'] ?? '');
        $application->availability = (string) ($data['availability'] ?? '');
        $application->qualificationType = (string) ($data['qualification_type'] ?? '');
        $application->qualification = (string) ($data['qualification'] ?? '');
        $application->institution = (string) ($data['institution'] ?? '');
        $application->qualificationYear = (string) ($data['qualification_year'] ?? '');
        $application->qualificationStatus = (string) ($data['qualification_status'] ?? '');
        $application->salaryType = (string) ($data['salary_type'] ?? '');
        $application->salaryRate = (string) ($data['salary_rate'] ?? '');
        $application->sector = (string) ($data['sector'] ?? '');
        $application->function = (string) ($data['function'] ?? '');
        $application->region = (string) ($data['region'] ?? '');
        $application->location = (string) ($data['location'] ?? '');
        $application->cvFilename = isset($data['cv_filename']) && $data['cv_filename'] !== '' ? (string) $data['cv_filename'] : null;
        $application->termsAccepted = self::toBoolean($data['terms_accepted'] ?? $data['terms'] ?? false);
        $application->createdAt = isset($data['created_at']) ? (string) $data['created_at'] : null;
        $application->updatedAt = isset($data['updated_at']) ? (string) $data['updated_at'] : null;

        return $application;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'nationality' => $this->nationality,
            'id_number' => $this->idNumber,
            'cellphone' => $this->cellphone,
            'email' => $this->email,
            'gender' => $this->gender,
            'employment_equity' => $this->employmentEquity,
            'availability' => $this->availability,
            'qualification_type' => $this->qualificationType,
            'qualification' => $this->qualification,
            'institution' => $this->institution,
            'qualification_year' => $this->qualificationYear,
            'qualification_status' => $this->qualificationStatus,
            'salary_type' => $this->salaryType,
            'salary_rate' => $this->salaryRate,
            'sector' => $this->sector,
            'function' => $this->function,
            'region' => $this->region,
            'location' => $this->location,
            'cv_filename' => $this->cvFilename,
            'terms_accepted' => $this->termsAccepted ? 1 : 0,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    private static function toBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array((string) $value, ['1', 'true', 'on', 'yes'], true);
    }
}
