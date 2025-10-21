<?php // front/admin/partials/footer.php ?>

  </main> <footer class="admin-footer mt-5 text-center text-muted">
    <p>&copy; <?= date('Y') ?> MundialFan - Panel de Administración</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/js/dark-mode.js"></script>
  <script src="/js/navbar-mobile.js"></script>
  
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const userProfile = document.querySelector('.user-profile');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    if (userProfile && dropdownMenu) {
      userProfile.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        // Usamos la API de Bootstrap 5 para el toggle
        var bsDropdown = new bootstrap.Dropdown(userProfile);
        bsDropdown.toggle();
      });
      
      // Bootstrap ya maneja el cierre al hacer click fuera
    }
  });
  </script>
  
</body>
</html>