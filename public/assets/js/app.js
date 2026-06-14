class AppUi {
    static showAlert(element, message, type = 'success') {
        if (!element) {
            return;
        }

        element.className = `alert alert-${type}`;
        element.textContent = message;
        element.classList.remove('d-none');
    }

    static clearAlert(element) {
        if (!element) {
            return;
        }

        element.className = 'alert d-none';
        element.textContent = '';
    }
}

window.AppUi = AppUi;
