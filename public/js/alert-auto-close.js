/**
 * Auto-close alerts with animation
 * Automatically closes Bootstrap alerts after 5 seconds with fade out animation
 */
(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAutoCloseAlerts);
    } else {
        initAutoCloseAlerts();
    }

    function initAutoCloseAlerts() {
        // Find all Bootstrap alerts
        const alerts = document.querySelectorAll('.alert.alert-dismissible, .alert:not(.alert-permanent)');
        
        alerts.forEach(function(alert) {
            // Skip if alert already has auto-close handler
            if (alert.dataset.autoCloseInitialized) {
                return;
            }
            
            // Mark as initialized
            alert.dataset.autoCloseInitialized = 'true';
            
            // Add slide-in animation on show
            alert.style.animation = 'slideInDown 0.3s ease-out';
            
            // Set timeout to auto-close after 5 seconds
            const timeout = setTimeout(function() {
                closeAlert(alert);
            }, 5000); // 5 seconds
            
            // Clear timeout if user manually closes the alert
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    clearTimeout(timeout);
                });
            }
            
            // Pause auto-close on hover
            alert.addEventListener('mouseenter', function() {
                clearTimeout(timeout);
            });
            
            // Resume auto-close when mouse leaves (with 2 seconds delay)
            alert.addEventListener('mouseleave', function() {
                const resumeTimeout = setTimeout(function() {
                    closeAlert(alert);
                }, 2000);
                
                // Store resume timeout so we can clear it if user hovers again
                alert.dataset.resumeTimeout = resumeTimeout;
            });
        });
    }

    function closeAlert(alert) {
        // Add fade out animation
        alert.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        
        // Remove alert after animation
        setTimeout(function() {
            // Use Bootstrap's alert dismiss if available
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            } else {
                // Fallback: remove manually
                alert.remove();
            }
        }, 500);
    }

    // Re-initialize when new alerts are added dynamically
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1 && node.classList && node.classList.contains('alert')) {
                    initAutoCloseAlerts();
                }
            });
        });
    });

    // Observe document body for new alerts
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
})();

