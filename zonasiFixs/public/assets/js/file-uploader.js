/**
 * FileUploader.js
 * Drag & Drop File Upload with Preview and Validation
 */

class FileUploader {
    constructor(inputSelector, options = {}) {
        this.input = document.querySelector(inputSelector);
        if (!this.input) return;

        this.options = {
            maxSize: 2 * 1024 * 1024, // 2MB default
            allowedTypes: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'],
            ...options
        };

        this.wrapper = this.createWrapper();
        this.previewArea = this.wrapper.querySelector('.upload-preview');
        this.dropZone = this.wrapper.querySelector('.drop-zone');

        this.init();
    }

    createWrapper() {
        const wrapper = document.createElement('div');
        wrapper.className = 'file-upload-wrapper';

        // Hide original input
        this.input.style.display = 'none';
        this.input.parentNode.insertBefore(wrapper, this.input);
        wrapper.appendChild(this.input);

        // Create Drop Zone UI
        const dropZone = document.createElement('div');
        dropZone.className = 'drop-zone';
        dropZone.innerHTML = `
            <div class="upload-icon">
                <i class="bi bi-cloud-arrow-up"></i>
            </div>
            <div class="upload-text">
                <h6>Drag & Drop atau Klik</h6>
                <p>Format: JPG, PNG, PDF (Max 2MB)</p>
            </div>
        `;
        wrapper.appendChild(dropZone);

        // Preview Area
        const preview = document.createElement('div');
        preview.className = 'upload-preview';
        wrapper.appendChild(preview);

        return wrapper;
    }

    init() {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            this.dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            this.dropZone.addEventListener(eventName, () => this.dropZone.classList.add('highlight'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            this.dropZone.addEventListener(eventName, () => this.dropZone.classList.remove('highlight'), false);
        });

        this.dropZone.addEventListener('drop', (e) => this.handleDrop(e), false);
        this.dropZone.addEventListener('click', () => this.input.click());
        this.input.addEventListener('change', () => this.handleFiles(this.input.files));
    }

    handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        this.input.files = files; // Sync with input
        this.handleFiles(files);
    }

    handleFiles(files) {
        if (files.length === 0) return;

        const file = files[0];

        // Validate Size
        if (file.size > this.options.maxSize) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            this.input.value = ''; // Reset
            this.clearPreview();
            return;
        }

        // Validate Type
        if (!this.options.allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung! Gunakan JPG, PNG, atau PDF.');
            this.input.value = ''; // Reset
            this.clearPreview();
            return;
        }

        this.showPreview(file);
    }

    showPreview(file) {
        this.dropZone.classList.add('has-file');
        this.previewArea.innerHTML = ''; // Clear previous

        const reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onloadend = () => {
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item fade-in';

            let icon = 'bi-file-earmark-text';
            if (file.type.startsWith('image/')) {
                icon = 'bi-file-image';
                previewItem.innerHTML = `
                    <img src="${reader.result}" alt="Preview" class="preview-img">
                `;
            } else {
                previewItem.innerHTML = `
                    <div class="file-icon"><i class="bi ${icon}"></i></div>
                `;
            }

            previewItem.innerHTML += `
                <div class="file-info">
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">${this.formatSize(file.size)}</span>
                </div>
                <button type="button" class="btn-remove"><i class="bi bi-x"></i></button>
            `;

            this.previewArea.appendChild(previewItem);

            // Remove handler
            previewItem.querySelector('.btn-remove').onclick = (e) => {
                e.stopPropagation(); // Prevent triggering file dialog
                this.input.value = '';
                this.clearPreview();
            };
        }
    }

    clearPreview() {
        this.previewArea.innerHTML = '';
        this.dropZone.classList.remove('has-file');
    }

    formatSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
}
