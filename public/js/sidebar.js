// Sidebar Toggle Functions
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    if (sidebar && overlay) {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }
}

function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleIcon = document.getElementById('sidebar-toggle-icon');
    const logoutBtn = document.getElementById('logout-btn');
    
    if (!sidebar || !mainContent) return;
    
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('sidebar-collapsed');
    
    if (toggleIcon) {
        if (sidebar.classList.contains('collapsed')) {
            toggleIcon.classList.remove('mdi-chevron-left');
            toggleIcon.classList.add('mdi-chevron-right');
        } else {
            toggleIcon.classList.remove('mdi-chevron-right');
            toggleIcon.classList.add('mdi-chevron-left');
        }
    }
    
    if (logoutBtn) {
        const logoutText = logoutBtn.querySelector('.logout-text');
        if (logoutText) {
            if (sidebar.classList.contains('collapsed')) {
                logoutText.style.display = 'none';
            } else {
                logoutText.style.display = 'inline';
            }
        }
    }
}

// Close sidebar on mobile when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.addEventListener('click', function() {
            toggleSidebar();
        });
    }
});

