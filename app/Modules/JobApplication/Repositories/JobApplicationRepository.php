<?php

namespace App\Modules\JobApplication\Repositories;

use App\Core\Database;
use App\Modules\JobApplication\Models\JobApplication;
use PDO;

final class JobApplicationRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public static function make(): self
    {
        return new self(Database::connection());
    }

    public function create(JobApplication $application): int
    {
        $sql = 'INSERT INTO job_applications (
            name, surname, nationality, id_number, cellphone, email, gender, employment_equity, availability,
            qualification_type, qualification, institution, qualification_year, qualification_status,
            salary_type, salary_rate, sector, `function`, region, location, cv_filename, created_at, updated_at
        ) VALUES (
            :name, :surname, :nationality, :id_number, :cellphone, :email, :gender, :employment_equity, :availability,
            :qualification_type, :qualification, :institution, :qualification_year, :qualification_status,
            :salary_type, :salary_rate, :sector, :function, :region, :location, :cv_filename, NOW(), NOW()
        )';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($this->mapBindings($application));

        return (int) $this->pdo->lastInsertId();
    }

    public function update(JobApplication $application): bool
    {
        $sql = 'UPDATE job_applications SET
            name = :name,
            surname = :surname,
            nationality = :nationality,
            id_number = :id_number,
            cellphone = :cellphone,
            email = :email,
            gender = :gender,
            employment_equity = :employment_equity,
            availability = :availability,
            qualification_type = :qualification_type,
            qualification = :qualification,
            institution = :institution,
            qualification_year = :qualification_year,
            qualification_status = :qualification_status,
            salary_type = :salary_type,
            salary_rate = :salary_rate,
            sector = :sector,
            `function` = :function,
            region = :region,
            location = :location,
            cv_filename = :cv_filename,
            updated_at = NOW()
            WHERE id = :id';

        $bindings = $this->mapBindings($application);
        $bindings['id'] = $application->id;

        $statement = $this->pdo->prepare($sql);

        return $statement->execute($bindings);
    }

    public function find(int $id): ?JobApplication
    {
        $statement = $this->pdo->prepare('SELECT * FROM job_applications WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return is_array($row) ? JobApplication::fromArray($row) : null;
    }

    public function all(): array
    {
        $statement = $this->pdo->query('SELECT * FROM job_applications ORDER BY created_at DESC');
        $rows = $statement->fetchAll();

        return array_map(static fn (array $row): JobApplication => JobApplication::fromArray($row), $rows);
    }

    private function mapBindings(JobApplication $application): array
    {
        return [
            'name' => $application->name,
            'surname' => $application->surname,
            'nationality' => $application->nationality,
            'id_number' => $application->idNumber,
            'cellphone' => $application->cellphone,
            'email' => $application->email,
            'gender' => $application->gender,
            'employment_equity' => $application->employmentEquity,
            'availability' => $application->availability,
            'qualification_type' => $application->qualificationType,
            'qualification' => $application->qualification,
            'institution' => $application->institution,
            'qualification_year' => $application->qualificationYear,
            'qualification_status' => $application->qualificationStatus,
            'salary_type' => $application->salaryType,
            'salary_rate' => $application->salaryRate,
            'sector' => $application->sector,
            'function' => $application->function,
            'region' => $application->region,
            'location' => $application->location,
            'cv_filename' => $application->cvFilename,
        ];
    }
}
