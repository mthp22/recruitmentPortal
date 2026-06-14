<?php

use App\Modules\JobApplication\Models\JobApplication;

$applications = is_array($applications ?? null) ? $applications : [];
?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">Submitted Applications</h1>
        <p class="text-muted mb-0">Review existing job applications and open them for viewing or editing.</p>
    </div>
    <a class="btn btn-primary" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=view">New Application</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Cellphone</th>
                        <th>Qualification</th>
                        <th>Region</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($applications === []): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No applications found yet.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($applications as $application): ?>
                        <?php if (!$application instanceof JobApplication) { continue; } ?>
                        <tr>
                            <td><?= (int) $application->id ?></td>
                            <td><?= htmlspecialchars($application->name . ' ' . $application->surname, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($application->email, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($application->cellphone, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($application->qualification, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($application->region, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) $application->createdAt, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a class="btn btn-outline-primary" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=view&id=<?= (int) $application->id ?>">View</a>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=edit&id=<?= (int) $application->id ?>">Edit</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
