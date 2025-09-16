/**
 * Custom JavaScript for Attendance Management System
 * jQuery-based interactive functionality and AJAX operations
 */

$(document).ready(function() {
    // Initialize the application
    initializeApp();
});

/**
 * Initialize application-wide functionality
 */
function initializeApp() {
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize form validations
    initializeFormValidations();
    
    // Initialize auto-refresh for dashboard
    initializeAutoRefresh();
    
    // Initialize keyboard shortcuts
    initializeKeyboardShortcuts();
    
    // Initialize theme preferences
    initializeTheme();
}

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize form validations
 */
function initializeFormValidations() {
    // Real-time validation for all forms
    $('form').on('input', 'input, select, textarea', function() {
        validateField($(this));
    });
    
    // Custom validation rules
    setupCustomValidations();
}

/**
 * Validate individual form field
 */
function validateField(field) {
    const value = field.val().trim();
    const fieldType = field.attr('type');
    const fieldName = field.attr('name');
    let isValid = true;
    let errorMessage = '';
    
    // Required field validation
    if (field.prop('required') && !value) {
        isValid = false;
        errorMessage = 'This field is required';
    }
    
    // Email validation
    else if (fieldType === 'email' && value && !isValidEmail(value)) {
        isValid = false;
        errorMessage = 'Please enter a valid email address';
    }
    
    // Phone validation
    else if (fieldName === 'phone' && value && !isValidPhone(value)) {
        isValid = false;
        errorMessage = 'Please enter a valid phone number';
    }
    
    // Name validation
    else if (fieldName === 'name' && value && value.length < 2) {
        isValid = false;
        errorMessage = 'Name must be at least 2 characters long';
    }
    
    // Update field UI
    updateFieldValidation(field, isValid, errorMessage);
    
    return isValid;
}

/**
 * Update field validation UI
 */
function updateFieldValidation(field, isValid, errorMessage) {
    const feedback = field.siblings('.invalid-feedback');
    
    if (isValid) {
        field.removeClass('is-invalid').addClass('is-valid');
        feedback.text('');
    } else {
        field.removeClass('is-valid').addClass('is-invalid');
        feedback.text(errorMessage);
    }
}

/**
 * Setup custom validation rules
 */
function setupCustomValidations() {
    // Date validation to prevent future dates for attendance
    $('input[type="date"]').on('change', function() {
        const selectedDate = new Date($(this).val());
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate > today) {
            showAlert('Future dates are not allowed for attendance', 'warning');
            $(this).val(formatDate(today));
        }
    });
}

/**
 * Initialize auto-refresh functionality
 */
function initializeAutoRefresh() {
    // Only on dashboard page
    if (window.location.pathname.includes('index.php') || window.location.pathname.endsWith('/')) {
        // Refresh every 5 minutes
        setInterval(function() {
            if (typeof loadDashboardData === 'function') {
                loadDashboardData();
            }
        }, 300000);
    }
}

/**
 * Initialize keyboard shortcuts
 */
function initializeKeyboardShortcuts() {
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S to save forms
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const activeForm = $('form:visible').first();
            if (activeForm.length) {
                activeForm.submit();
            }
        }
        
        // Escape to close modals
        if (e.key === 'Escape') {
            const visibleModal = $('.modal.show').last();
            if (visibleModal.length) {
                bootstrap.Modal.getInstance(visibleModal[0]).hide();
            }
        }
        
        // Ctrl/Cmd + F to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            const searchInput = $('#searchInput');
            if (searchInput.length) {
                e.preventDefault();
                searchInput.focus();
            }
        }
    });
}

/**
 * Initialize theme preferences
 */
function initializeTheme() {
    // Check for saved theme preference or default to light
    const savedTheme = localStorage.getItem('attendance-theme') || 'light';
    applyTheme(savedTheme);
}

/**
 * Apply theme
 */
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('attendance-theme', theme);
}

/**
 * Enhanced alert function with auto-dismiss and stacking
 */
function showAlert(message, type = 'info', duration = 5000, persistent = false) {
    const alertId = 'alert-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
    const iconMap = {
        'success': 'bi-check-circle-fill',
        'danger': 'bi-exclamation-triangle-fill',
        'warning': 'bi-exclamation-triangle-fill',
        'info': 'bi-info-circle-fill'
    };
    
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show slide-down" role="alert" id="${alertId}">
            <i class="bi ${iconMap[type]} me-2"></i>
            <strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    $('#alertContainer').append(alertHtml);
    
    // Auto-dismiss after duration (unless persistent)
    if (duration > 0 && !persistent) {
        setTimeout(() => {
            const alert = $('#' + alertId);
            if (alert.length) {
                alert.alert('close');
            }
        }, duration);
    }
    
    // Limit number of alerts (keep only last 5)
    const alerts = $('#alertContainer .alert');
    if (alerts.length > 5) {
        alerts.first().alert('close');
    }
}

/**
 * Enhanced loading state management
 */
function showLoading(element, text = 'Loading...') {
    if (!element.length) return;
    
    const originalContent = element.html();
    const originalWidth = element.outerWidth();
    
    element.data('original-content', originalContent);
    element.data('original-width', originalWidth);
    
    element.html(`
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        ${text}
    `);
    element.prop('disabled', true);
    element.css('width', originalWidth);
}

/**
 * Hide loading state
 */
function hideLoading(element) {
    if (!element.length) return;
    
    const originalContent = element.data('original-content');
    if (originalContent) {
        element.html(originalContent);
    }
    
    element.prop('disabled', false);
    element.css('width', 'auto');
}

/**
 * Advanced AJAX wrapper with error handling and retry logic
 */
function makeRequest(options) {
    const defaults = {
        method: 'GET',
        dataType: 'json',
        timeout: 30000,
        retries: 3,
        retryDelay: 1000
    };
    
    const settings = $.extend({}, defaults, options);
    let attempts = 0;
    
    function attemptRequest() {
        attempts++;
        
        return $.ajax({
            url: settings.url,
            method: settings.method,
            data: settings.data,
            dataType: settings.dataType,
            contentType: settings.contentType,
            timeout: settings.timeout
        })
        .done(function(response) {
            if (settings.success) {
                settings.success(response);
            }
        })
        .fail(function(xhr, status, error) {
            if (attempts < settings.retries && status !== 'abort') {
                setTimeout(attemptRequest, settings.retryDelay * attempts);
            } else if (settings.error) {
                settings.error(xhr, status, error);
            } else {
                showAlert('Connection error. Please try again.', 'danger');
            }
        });
    }
    
    return attemptRequest();
}

/**
 * Utility Functions
 */

/**
 * Validate email format
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Validate phone format
 */
function isValidPhone(phone) {
    const phoneRegex = /^[\d\-\+\(\)\s]{10,}$/;
    return phoneRegex.test(phone);
}

/**
 * Format date for display
 */
function formatDate(date, format = 'short') {
    if (!(date instanceof Date)) {
        date = new Date(date);
    }
    
    if (format === 'short') {
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    } else if (format === 'long') {
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long'
        });
    } else if (format === 'input') {
        return date.toISOString().split('T')[0];
    }
    
    return date.toLocaleDateString();
}

/**
 * Format time for display
 */
function formatTime(date) {
    if (!(date instanceof Date)) {
        date = new Date(date);
    }
    
    return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Enhanced confirm dialog
 */
function confirmAction(message, title = 'Confirm Action', confirmText = 'Confirm', cancelText = 'Cancel') {
    return new Promise((resolve) => {
        // Create modal for confirmation
        const modalId = 'confirmModal-' + Date.now();
        const modalHtml = `
            <div class="modal fade" id="${modalId}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>${message}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${cancelText}</button>
                            <button type="button" class="btn btn-danger" id="confirmButton">${confirmText}</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('body').append(modalHtml);
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        
        $('#' + modalId).on('click', '#confirmButton', function() {
            modal.hide();
            resolve(true);
        });
        
        $('#' + modalId).on('hidden.bs.modal', function() {
            $(this).remove();
            resolve(false);
        });
        
        modal.show();
    });
}

/**
 * Local storage helpers
 */
const storage = {
    set: function(key, value) {
        try {
            localStorage.setItem('attendance-' + key, JSON.stringify(value));
        } catch (e) {
            console.warn('LocalStorage not available');
        }
    },
    
    get: function(key, defaultValue = null) {
        try {
            const item = localStorage.getItem('attendance-' + key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (e) {
            console.warn('LocalStorage not available');
            return defaultValue;
        }
    },
    
    remove: function(key) {
        try {
            localStorage.removeItem('attendance-' + key);
        } catch (e) {
            console.warn('LocalStorage not available');
        }
    }
};

/**
 * Convert array to CSV
 */
function arrayToCSV(data) {
    if (!data.length) return '';
    
    const headers = Object.keys(data[0]);
    const csvHeaders = headers.join(',');
    
    const csvRows = data.map(row => {
        return headers.map(header => {
            const value = row[header];
            return typeof value === 'string' && value.includes(',') 
                ? `"${value}"` 
                : value;
        }).join(',');
    });
    
    return [csvHeaders, ...csvRows].join('\n');
}

/**
 * Debounce function for search inputs
 */
function debounce(func, wait, immediate) {
    let timeout;
    return function executedFunction() {
        const context = this;
        const args = arguments;
        
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        
        if (callNow) func.apply(context, args);
    };
}

/**
 * Performance monitoring
 */
const performance = {
    start: function(label) {
        console.time(label);
    },
    
    end: function(label) {
        console.timeEnd(label);
    },
    
    measure: function(label, fn) {
        this.start(label);
        const result = fn();
        this.end(label);
        return result;
    }
};

/**
 * Error handling and reporting
 */
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    showAlert('An unexpected error occurred. Please refresh the page.', 'danger', 0, true);
});

window.addEventListener('unhandledrejection', function(e) {
    console.error('Unhandled Promise Rejection:', e.reason);
    showAlert('A network error occurred. Please check your connection.', 'danger');
});

/**
 * Service Worker registration for offline functionality (optional)
 */
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        // Uncomment to enable service worker
        // navigator.serviceWorker.register('/sw.js');
    });
}

/**
 * Print functionality
 */
function printPage(title = 'Attendance Report') {
    const originalTitle = document.title;
    document.title = title;
    window.print();
    document.title = originalTitle;
}

/**
 * Initialize page-specific functionality
 */
$(document).ready(function() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    
    switch(currentPage) {
        case 'index.php':
        case '':
            // Dashboard specific functionality
            initializeDashboard();
            break;
        case 'students.php':
            // Students page specific functionality
            initializeStudentsPage();
            break;
        case 'attendance.php':
            // Attendance page specific functionality
            initializeAttendancePage();
            break;
        case 'reports.php':
            // Reports page specific functionality
            initializeReportsPage();
            break;
    }
});

function initializeDashboard() {
    // Dashboard specific initialization
    console.log('Dashboard initialized');
}

function initializeStudentsPage() {
    // Students page specific initialization
    console.log('Students page initialized');
}

function initializeAttendancePage() {
    // Attendance page specific initialization
    console.log('Attendance page initialized');
}

function initializeReportsPage() {
    // Reports page specific initialization
    console.log('Reports page initialized');
}