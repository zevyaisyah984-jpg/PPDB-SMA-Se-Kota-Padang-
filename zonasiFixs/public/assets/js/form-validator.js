/**
 * FormValidator.js
 * Real-time form validation with visual feedback
 */

class FormValidator {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        if (!this.form) return;

        this.inputs = this.form.querySelectorAll('input[required], select[required], textarea[required]');
        this.submitBtn = this.form.querySelector('button[type="submit"]');

        this.patterns = {
            nisn: /^[0-9]{10}$/,
            nik: /^[0-9]{16}$/,
            email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            phone: /^[0-9]{10,13}$/,
            password: /^.{8,}$/ // Min 8 chars
        };

        this.init();
    }

    init() {
        this.inputs.forEach(input => {
            // Real-time validation on input
            input.addEventListener('input', () => this.validateInput(input));
            input.addEventListener('blur', () => this.validateInput(input));
        });

        // Initial check
        this.updateSubmitButton();

        // Form submit prevention if invalid
        this.form.addEventListener('submit', (e) => {
            if (!this.validateAll()) {
                e.preventDefault();
                this.shakeSubmitBtn();
            }
        });
    }

    validateInput(input) {
        const type = input.dataset.validate || input.type;
        const value = input.value.trim();
        let isValid = true;
        let message = '';

        // Basic required check
        if (input.hasAttribute('required') && !value) {
            isValid = false;
            message = 'Wajib diisi';
        }
        // Pattern check
        else if (value && this.patterns[type]) {
            if (!this.patterns[type].test(value)) {
                isValid = false;
                switch (type) {
                    case 'nisn': message = 'NISN harus 10 digit angka'; break;
                    case 'nik': message = 'NIK harus 16 digit angka'; break;
                    case 'email': message = 'Format email tidak valid'; break;
                    case 'password': message = 'Minimal 8 karakter'; break;
                    case 'phone': message = 'Nomor HP tidak valid (10-13 angka)'; break;
                }
            }
        }
        // Match password check
        else if (input.dataset.match) {
            const target = this.form.querySelector(input.dataset.match);
            if (target && value !== target.value) {
                isValid = false;
                message = 'Password tidak cocok';
            }
        }

        this.showFeedback(input, isValid, message);
        this.updateSubmitButton();
        return isValid;
    }

    showFeedback(input, isValid, message) {
        const wrapper = input.parentElement; // Assumes .form-floating or .input-group
        let feedback = wrapper.querySelector('.validation-feedback');

        // Create feedback element if not exists
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'validation-feedback';
            wrapper.appendChild(feedback);
        }

        input.classList.remove('is-valid', 'is-invalid');
        feedback.className = 'validation-feedback';

        if (input.value && isValid) {
            input.classList.add('is-valid');
            feedback.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i>';
            feedback.classList.add('valid');
        } else if (!isValid && (input.value || document.activeElement === input)) {
            input.classList.add('is-invalid');
            feedback.textContent = message;
            feedback.classList.add('invalid');
        } else {
            feedback.innerHTML = '';
        }
    }

    validateAll() {
        let allValid = true;
        this.inputs.forEach(input => {
            if (!this.validateInput(input)) allValid = false;
        });
        return allValid;
    }

    updateSubmitButton() {
        const allValid = Array.from(this.inputs).every(input => {
            // Check if valid class exists OR (required but no invalid class and has value)
            return input.classList.contains('is-valid');
        });

        if (this.submitBtn) {
            if (allValid) {
                this.submitBtn.removeAttribute('disabled');
                this.submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                this.submitBtn.setAttribute('disabled', 'true');
                this.submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    shakeSubmitBtn() {
        if (!this.submitBtn) return;
        this.submitBtn.classList.add('shake-animation');
        setTimeout(() => this.submitBtn.classList.remove('shake-animation'), 500);
    }
}

// Auto-init
document.addEventListener('DOMContentLoaded', () => {
    new FormValidator('#registerForm');
    new FormValidator('#loginForm');
});
