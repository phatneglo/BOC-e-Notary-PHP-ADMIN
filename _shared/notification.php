<script type="text/html" class="ew-js-template" data-name="notificationsDropdown" data-method="appendTo" data-target="#ew-navbar-end" data-seq="9">
<li class="nav-item dropdown me-2">
  <!-- Notification Bell -->
  <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-bell"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
      0
    </span>
  </a>
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="notificationDropdown">
    <div class="d-flex justify-content-between align-items-center px-3 py-2">
      <span class="text-bold">Notifications</span>
      <button class="btn btn-xs btn-link text-sm" onclick="markAllNotificationsRead(event)">Mark all read</button>
    </div>
    <div class="dropdown-divider"></div>
    <div id="notificationList" style="max-height: 300px; overflow-y: auto;">
      <div class="d-flex justify-content-center py-3">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
    <div class="dropdown-divider"></div>
    <a href="/notifications" class="dropdown-item dropdown-footer text-center">See All Notifications</a>
  </div>
</li>
</script>

<script>
const NotificationManager = {
    updateInterval: null,
    lastUpdate: null,

    init() {
        this.updateNotifications();
        this.startAutoUpdate();
        this.setupEventListeners();
    },

    setupEventListeners() {
        // Listen for new notifications from MQTT
        document.addEventListener('appNotification', () => this.updateNotifications());

        // Handle visibility change
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                this.updateNotifications();
                this.startAutoUpdate();
            } else {
                this.stopAutoUpdate();
            }
        });
    },

    startAutoUpdate() {
        if (!this.updateInterval) {
            this.updateInterval = setInterval(() => this.updateNotifications(), 30000);
        }
    },

    stopAutoUpdate() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
    },

    async updateNotifications() {
        try {
            const response = await fetch('/api/notifications/recent', {
                headers: {
                    'Authorization': 'Bearer ' + ew.API_JWT_TOKEN,
                    'Cache-Control': 'no-cache'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');
            
            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Error fetching notifications');

            this.updateUI(result.data);
            this.lastUpdate = new Date();
            
        } catch (error) {
            console.error('Error updating notifications:', error);
            this.showErrorState();
        }
    },

    // Update the updateUI method in NotificationManager
    updateUI(notifications) {
        const notificationList = document.getElementById('notificationList');
        const badge = document.getElementById('notificationBadge');
        
        // Update badge
        const unreadCount = notifications.filter(n => !n.is_read).length;
        badge.textContent = unreadCount;
        badge.style.display = unreadCount > 0 ? 'block' : 'none';
        
        // Update list
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="dropdown-item text-center text-muted py-3">
                    <i class="fas fa-bell-slash mb-2"></i>
                    <p class="mb-0">No notifications</p>
                </div>
            `;
            return;
        }

        notificationList.innerHTML = notifications.map(notification => `
            <div class="dropdown-item ${notification.is_read ? 'text-muted' : 'fw-bold bg-light'}" 
                data-notification-id="${notification.id}"
                data-notification-link="${notification.link || '#'}"
                role="button">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <i class="fas fa-${this.getIcon(notification.type)} ${this.getIconColor(notification.type)}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="text-sm ${notification.is_read ? '' : 'text-dark'}">${notification.subject}</div>
                        <div class="text-xs text-truncate" style="max-width: 200px;">${notification.body}</div>
                        <div class="text-xs text-muted d-flex justify-content-between align-items-center">
                            <span>${notification.time_ago}</span>
                            <span class="badge bg-${this.getSourceColor(notification.from_system)}">${notification.from_system}</span>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        // Add click event listeners to notification items
        notificationList.querySelectorAll('.dropdown-item[data-notification-id]').forEach(item => {
            item.addEventListener('click', async (e) => {
                e.preventDefault();
                const notificationId = item.dataset.notificationId;
                const link = item.dataset.notificationLink;
                await this.markAsRead(e, notificationId);
                if (link && link !== '#') {
                    window.location.href = link;
                }
            });
        });
    },

    // Update the markAsRead method
    async markAsRead(event, notificationId) {
        try {
            const response = await fetch('/api/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + ew.API_JWT_TOKEN,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: notificationId })
            });

            if (!response.ok) throw new Error('Network response was not ok');
            
            const result = await response.json();
            if (!result.success) throw new Error('Failed to mark notification as read');

            // Update UI
            this.updateNotifications();

        } catch (error) {
            console.error('Error marking notification as read:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to mark notification as read',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    },

    showErrorState() {
        const notificationList = document.getElementById('notificationList');
        notificationList.innerHTML = `
            <div class="dropdown-item text-center text-danger py-3">
                <i class="fas fa-exclamation-circle mb-2"></i>
                <p class="mb-0">Error loading notifications</p>
            </div>
        `;
    },

    getIcon(type) {
        switch(type) {
            case 'system': return 'cog';
            case 'personal': return 'user';
            case 'role': return 'users';
            default: return 'bell';
        }
    },

    getIconColor(type) {
        switch(type) {
            case 'system': return 'text-info';
            case 'personal': return 'text-success';
            case 'role': return 'text-primary';
            default: return 'text-warning';
        }
    },

    getSourceColor(source) {
        switch(source?.toLowerCase()) {
            case 'ads': return 'dark';          // Administrative System
            case 'uac': return 'primary';       // User Access Control Management
            case 'ams': return 'secondary';     // Archives Management System
            case 'asm': return 'info';          // Asset Management System
            case 'lms': return 'success';       // Library Management System
            case 'pms': return 'warning';       // Project Management System
            case 'mms': return 'danger';        // Museum Management System
            case 'dms': return 'purple';        // Document Management System
            case 'preserba': return 'indigo';   // Preservation and Renewal System
            case 'sms': return 'orange';        // Survey Management System
            case 'hrs': return 'teal';          // HR System
            case 'system': return 'gray';       // System notifications
            default: return 'secondary';
        }
    },


    async markAllRead(event) {
        event.preventDefault();
        event.stopPropagation();
        
        try {
            const response = await fetch('/api/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + ew.API_JWT_TOKEN
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');
            
            const result = await response.json();
            if (!result.success) throw new Error('Failed to mark all notifications as read');

            this.updateNotifications();

        } catch (error) {
            console.error('Error marking all notifications as read:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to mark all notifications as read',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    }
};

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize NotificationManager
    NotificationManager.init();
    // Theme observer for app icons
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === "attributes" && mutation.attributeName === "data-bs-theme") {
                updateAppIcons();
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-bs-theme']
    });

    // Helper function to update app icons
    function updateAppIcons() {
        const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        const appIcons = document.querySelectorAll('.app-icon');
        appIcons.forEach(icon => {
            const src = icon.getAttribute('src');
            if (isDarkMode) {
                icon.setAttribute('src', src.replace('.png', '-light.png'));
            } else {
                icon.setAttribute('src', src.replace('-light.png', '.png'));
            }
        });
    }
});

// Global function for marking all notifications as read
function markAllNotificationsRead(event) {
    NotificationManager.markAllRead(event);
}
</script>