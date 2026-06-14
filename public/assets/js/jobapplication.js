class JobApplicationForm {
    constructor(form) {
        this.form = form;
        this.alert = document.getElementById('job-application-alert');
        this.cancelButton = this.form.querySelector('[data-cancel-url]');
        this.endpoint = this.form.dataset.ajaxUrl || '/ajax.php';
        this.viewUrl = this.form.dataset.viewUrl || '/index.php?module=jobapplication&action=view';
        this.bindEvents();
    }

    bindEvents() {
        this.form.addEventListener('submit', (event) => {
            event.preventDefault();
            this.submit();
        });

        if (this.cancelButton) {
            this.cancelButton.addEventListener('click', () => this.cancel());
        }
    }

    async submit() {
        this.clearValidation();
        AppUi.clearAlert(this.alert);

        const formData = new FormData(this.form);
        formData.set('module', 'jobapplication');
        formData.set('action', 'save');

        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const payload = await response.json();
            if (!response.ok || payload.success !== true) {
                this.applyValidation(payload.errors || {});
                AppUi.showAlert(this.alert, payload.message || 'Unable to save the application.', 'danger');
                return;
            }

            AppUi.showAlert(this.alert, payload.message || 'Application saved successfully.', 'success');
            if (payload.data && payload.data.id) {
                window.setTimeout(() => {
                    window.location.href = `${this.viewUrl}&id=${payload.data.id}`;
                }, 900);
            }
        } catch (error) {
            AppUi.showAlert(this.alert, 'A network error occurred while saving the application.', 'danger');
        }
    }

    async cancel() {
        AppUi.clearAlert(this.alert);

        const formData = new FormData();
        const idField = this.form.querySelector('[name="id"]');
        formData.set('module', 'jobapplication');
        formData.set('action', 'cancel');
        formData.set('id', idField ? idField.value : '');

        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const payload = await response.json();
            if (!response.ok || payload.success !== true) {
                AppUi.showAlert(this.alert, payload.message || 'Unable to cancel changes.', 'danger');
                return;
            }

            window.location.href = payload.redirect || this.cancelButton.dataset.cancelUrl;
        } catch (error) {
            window.location.href = this.cancelButton.dataset.cancelUrl;
        }
    }

    applyValidation(errors) {
        Object.entries(errors).forEach(([field, message]) => {
            const input = this.form.querySelector(`[name="${field}"]`);
            if (!input) {
                return;
            }

            input.classList.add('is-invalid');
            const feedback = input.parentElement.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = message;
            }
        });
    }

    clearValidation() {
        this.form.querySelectorAll('.is-invalid').forEach((element) => element.classList.remove('is-invalid'));
        this.form.querySelectorAll('.invalid-feedback').forEach((element) => {
            if (element.closest('.form-check')) {
                return;
            }

            element.textContent = '';
        });
        const termsFeedback = this.form.querySelector('#terms_accepted')?.parentElement.querySelector('.invalid-feedback');
        if (termsFeedback) {
            termsFeedback.textContent = '';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('job-application-form');
    if (form) {
        new JobApplicationForm(form);
    }
});
