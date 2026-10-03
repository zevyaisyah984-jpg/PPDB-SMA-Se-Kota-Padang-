/**
 * Auto-Refresh Notification System
 * Checks for data updates every 30 seconds and notifies users
 */

// Configuration
const CHECK_INTERVAL = 30000; // 30 seconds
let lastUpdateTime = 0;

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    // Get initial timestamp
    lastUpdateTime = Math.floor(Date.now() / 1000);

    // Start checking for updates
    setInterval(checkForUpdates, CHECK_INTERVAL);
});

function checkForUpdates() {
    fetch(window.location.origin + '/api/check-updates', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.lastUpdate && data.lastUpdate > lastUpdateTime) {
                showUpdateNotification();
                lastUpdateTime = data.lastUpdate;
            }
        })
        .catch(err => {
            console.warn('Update check failed:', err);
        });
}

function showUpdateNotification() {
    // Remove existing notification
    const existing = document.getElementById('updateNotification');
    if (existing) existing.remove();

    // Create notification
    const notification = document.createElement('div');
    notification.id = 'updateNotification';
    notification.className = 'position-fixed top-0 start-50 translate-middle-x mt-3 animate-slide-down';
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        <div class="alert alert-primary shadow-lg rounded-4 d-flex align-items-center gap-3 mb-0 border-0" style="min-width: 400px;">
            <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold">Data Telah Diperbarui</div>
                <div class="small opacity-75">Klik refresh untuk melihat perubahan terbaru</div>
            </div>
            <button onclick="location.reload()" class="btn btn-sm btn-light rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </button>
            <button onclick="dismissNotification()" class="btn btn-sm btn-link text-muted p-1">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Auto-dismiss after 30 seconds
    setTimeout(() => dismissNotification(), 30000);
}

function dismissNotification() {
    const notification = document.getElementById('updateNotification');
    if (notification) {
        notification.classList.add('animate-fade-out');
        setTimeout(() => notification.remove(), 300);
    }
}
