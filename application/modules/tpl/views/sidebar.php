<!-- BEGIN SIDEBAR -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="page-sidebar-wrapper">
  <div class="page-sidebar navbar-collapse collapse">

    <div class="sidebar-header">
      <div class="sidebar-logo">
        <a href="<?= base_url() ?>">
          <img src="<?php echo _ASSET_LOGO_INSIDE; ?>" alt="logo" class="sidebar-logo-img" />
        </a>
      </div>

      <!-- TOGGLER PINDAH KE SINI -->
      <div class="menu-toggler sidebar-toggler">
        <i class="fa fa-bars"></i>
      </div>
    </div>

    <?php echo $this->dynamic_menu->build_menu("", $check_menu); ?>

    <div class="sidebar-bottom-bg">
      <img src="<?= _ASSET_SIDEBAR_IMAGE ?>" class="sidebar-bottom-image" alt="Sidebar Image">
    </div>

  </div>
</div>
<!-- END SIDEBAR -->


<script>
  (function() {
    var overlay = document.getElementById('sidebarOverlay');

    // tombol toggle mana aja boleh:
    var togglers = document.querySelectorAll('.menu-toggler.sidebar-toggler, .menu-toggler.responsive-toggler');

    function toggleSidebar() {
      document.body.classList.toggle('sidebar-open');
    }

    togglers.forEach(function(btn) {
      btn.addEventListener('click', toggleSidebar);
    });

    if (overlay) {
      overlay.addEventListener('click', function() {
        document.body.classList.remove('sidebar-open');
      });
    }
  })();
</script>