function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
  }
  
  function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    const app = document.getElementById('main-content');
    const icon = document.getElementById('sidebar-toggle-icon');
  
    sidebar.classList.toggle('collapsed');
    app.classList.toggle('sidebar-collapsed');
  
    if (sidebar.classList.contains('collapsed')) {
      icon.classList.remove('mdi-chevron-left');
      icon.classList.add('mdi-chevron-right');
    } else {
      icon.classList.remove('mdi-chevron-right');
      icon.classList.add('mdi-chevron-left');
    }
  }
  