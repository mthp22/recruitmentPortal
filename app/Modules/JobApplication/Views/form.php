<?php

use App\Modules\JobApplication\Models\JobApplication;

$application = $application instanceof JobApplication ? $application : new JobApplication();
$errors = is_array($errors ?? null) ? $errors : [];
$isReadOnly = ($mode ?? 'create') === 'view';
$submitLabel = ($mode ?? 'create') === 'edit' ? 'Update Application' : 'Save Application';

$selectOptions = [
    'gender' => ['Male', 'Female', 'Other', 'Prefer not to say'],
    'employment_equity' => ['African', 'Coloured', 'Indian', 'White', 'Other'],
    'availability' => ['Immediate', '2 weeks', '1 month', '3 months'],
    'qualification_type' => ['Certificate', 'Diploma', 'Degree', 'Honours', 'Masters', 'Doctorate'],
    'qualification_status' => ['Completed', 'In Progress', 'Deferred'],
    'salary_type' => ['Annual', 'Monthly', 'Hourly'],
    'sector' => ['Private', 'Public', 'NGO', 'Startup'],
    'function' => ['Administration', 'Finance', 'Human Resources', 'IT', 'Marketing', 'Operations', 'Sales'],
    'region' => ['Gauteng', 'Western Cape', 'KwaZulu-Natal', 'Eastern Cape', 'Free State', 'Limpopo', 'Mpumalanga', 'North West', 'Northern Cape'],
];

$fieldValues = $application->toArray();
$currentCv = $application->cvFilename;
?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1"><?= htmlspecialchars($title ?? 'Job Application Form', ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="text-muted mb-0">Complete the form below to submit or manage a job application.</p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=list">View Applications</a>
        <a class="btn btn-primary" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=view">New Application</a>
    </div>
</div>

<?php if (!empty($message)): ?>
    <div class="alert alert-warning"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div id="job-application-alert" class="alert d-none" role="alert"></div>

<form
    id="job-application-form"
    class="card shadow-sm border-0"
    method="post"
    enctype="multipart/form-data"
    action="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=save"
    data-ajax-url="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/ajax.php"
    data-module-ajax-url="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/jobapplication_ajax.php"
    data-view-url="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=view"
>
    <div class="card-body p-4">
        <input type="hidden" name="module" value="jobapplication">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($application->id ?? ''), ENT_QUOTES, 'UTF-8') ?>">

        <div class="row g-4">
            <section class="col-12">
                <h2 class="h5 mb-3">Personal Information</h2>
                <div class="row g-3">
                    <?php
                    $textFields = [
                        'name' => 'Name',
                        'surname' => 'Surname',
                        'nationality' => 'Nationality',
                        'id_number' => 'ID Number',
                        'cellphone' => 'Cellphone',
                        'email' => 'Email',
                    ];
                    foreach ($textFields as $field => $label):
                        $type = $field === 'email' ? 'email' : 'text';
                        $invalid = isset($errors[$field]) ? ' is-invalid' : '';
                    ?>
                        <div class="col-md-6">
                            <label class="form-label" for="<?= $field ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></label>
                            <input class="form-control<?= $invalid ?>" type="<?= $type ?>" id="<?= $field ?>" name="<?= $field ?>" value="<?= htmlspecialchars((string) ($fieldValues[$field] ?? ''), ENT_QUOTES, 'UTF-8') ?>" <?= $isReadOnly ? 'readonly' : '' ?>>
                            <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors[$field] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    <?php endforeach; ?>

                    <?php foreach (['gender' => 'Gender', 'employment_equity' => 'Employment Equity', 'availability' => 'Availability'] as $field => $label): ?>
                        <div class="col-md-4">
                            <label class="form-label" for="<?= $field ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></label>
                            <select class="form-select<?= isset($errors[$field]) ? ' is-invalid' : '' ?>" id="<?= $field ?>" name="<?= $field ?>" <?= $isReadOnly ? 'disabled' : '' ?>>
                                <option value="">Select <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php foreach ($selectOptions[$field] as $option): ?>
                                    <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" <?= ($fieldValues[$field] ?? '') === $option ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors[$field] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="col-12">
                <h2 class="h5 mb-3">Qualifications</h2>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="qualification_type">Qualification Type</label>
                        <select class="form-select<?= isset($errors['qualification_type']) ? ' is-invalid' : '' ?>" id="qualification_type" name="qualification_type" <?= $isReadOnly ? 'disabled' : '' ?>>
                            <option value="">Select Qualification Type</option>
                            <?php foreach ($selectOptions['qualification_type'] as $option): ?>
                                <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" <?= ($fieldValues['qualification_type'] ?? '') === $option ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors['qualification_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <?php foreach (['qualification' => 'Qualification', 'institution' => 'Institution', 'qualification_year' => 'Year'] as $field => $label): ?>
                        <div class="col-md-4">
                            <label class="form-label" for="<?= $field ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></label>
                            <input class="form-control<?= isset($errors[$field]) ? ' is-invalid' : '' ?>" type="text" id="<?= $field ?>" name="<?= $field ?>" value="<?= htmlspecialchars((string) ($fieldValues[$field] ?? ''), ENT_QUOTES, 'UTF-8') ?>" <?= $isReadOnly ? 'readonly' : '' ?>>
                            <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors[$field] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-md-4">
                        <label class="form-label" for="qualification_status">Status</label>
                        <select class="form-select<?= isset($errors['qualification_status']) ? ' is-invalid' : '' ?>" id="qualification_status" name="qualification_status" <?= $isReadOnly ? 'disabled' : '' ?>>
                            <option value="">Select Status</option>
                            <?php foreach ($selectOptions['qualification_status'] as $option): ?>
                                <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" <?= ($fieldValues['qualification_status'] ?? '') === $option ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors['qualification_status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                </div>
            </section>

            <section class="col-12">
                <h2 class="h5 mb-3">Salary</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="salary_type">Salary Type</label>
                        <select class="form-select<?= isset($errors['salary_type']) ? ' is-invalid' : '' ?>" id="salary_type" name="salary_type" <?= $isReadOnly ? 'disabled' : '' ?>>
                            <option value="">Select Salary Type</option>
                            <?php foreach ($selectOptions['salary_type'] as $option): ?>
                                <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" <?= ($fieldValues['salary_type'] ?? '') === $option ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors['salary_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="salary_rate">Salary Rate</label>
                        <input class="form-control<?= isset($errors['salary_rate']) ? ' is-invalid' : '' ?>" type="text" id="salary_rate" name="salary_rate" value="<?= htmlspecialchars((string) ($fieldValues['salary_rate'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" <?= $isReadOnly ? 'readonly' : '' ?>>
                        <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors['salary_rate'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                </div>
            </section>

            <section class="col-12">
                <h2 class="h5 mb-3">Career Preferences</h2>
                <div class="row g-3">
                    <?php foreach (['sector' => 'Sector', 'function' => 'Function', 'region' => 'Region'] as $field => $label): ?>
                        <div class="col-md-4">
                            <label class="form-label" for="<?= $field ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></label>
                            <select class="form-select<?= isset($errors[$field]) ? ' is-invalid' : '' ?>" id="<?= $field ?>" name="<?= $field ?>" <?= $isReadOnly ? 'disabled' : '' ?>>
                                <option value="">Select <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php foreach ($selectOptions[$field] as $option): ?>
                                    <option value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>" <?= ($fieldValues[$field] ?? '') === $option ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors[$field] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-md-6">
                        <label class="form-label" for="location">Location</label>
                        <input class="form-control<?= isset($errors['location']) ? ' is-invalid' : '' ?>" type="text" id="location" name="location" value="<?= htmlspecialchars((string) ($fieldValues['location'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" <?= $isReadOnly ? 'readonly' : '' ?>>
                        <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors['location'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                </div>
            </section>

            <section class="col-12">
                <h2 class="h5 mb-3">Documents</h2>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label" for="cv_filename">CV Upload</label>
                        <input class="form-control<?= isset($errors['cv_filename']) ? ' is-invalid' : '' ?>" type="file" id="cv_filename" name="cv_filename" <?= $isReadOnly ? 'disabled' : '' ?>>
                        <div class="form-text">Accepted formats: pdf, doc, docx, txt, bmp, png, jpeg, jpg. Max size: 4MB.</div>
                        <div class="invalid-feedback"><?= htmlspecialchars((string) ($errors['cv_filename'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Current CV</label>
                        <div class="form-control bg-body-secondary">
                            <?= htmlspecialchars($currentCv ?? 'No file uploaded yet.', ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-12">
                <h2 class="h5 mb-3">Terms and Conditions</h2>
                <div class="form-check">
                    <input class="form-check-input<?= isset($errors['terms_accepted']) ? ' is-invalid' : '' ?>" type="checkbox" value="1" id="terms_accepted" name="terms_accepted" <?= !empty($fieldValues['terms_accepted']) ? 'checked' : '' ?> <?= $isReadOnly ? 'disabled' : '' ?>>
                    <label class="form-check-label" for="terms_accepted">
                        I confirm that the information supplied is correct and complete.
                    </label>
                    <div class="invalid-feedback d-block"><?= htmlspecialchars((string) ($errors['terms_accepted'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            </section>
        </div>
    </div>

    <div class="card-footer bg-white border-0 p-4 pt-0">
        <div class="d-flex flex-wrap gap-2">
            <?php if ($isReadOnly && $application->id !== null): ?>
                <a class="btn btn-primary" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=edit&id=<?= $application->id ?>">Edit</a>
            <?php else: ?>
                <button class="btn btn-primary" type="submit"><?= htmlspecialchars($submitLabel, ENT_QUOTES, 'UTF-8') ?></button>
            <?php endif; ?>
            <button class="btn btn-outline-secondary" type="button" data-cancel-url="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/index.php?module=jobapplication&action=cancel&id=<?= (int) ($application->id ?? 0) ?>">Cancel</button>
        </div>
    </div>
</form>

<script src="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8') ?>/assets/js/jobapplication.js"></script>
