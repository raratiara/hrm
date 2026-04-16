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

    <!-- SIDEBAR TABS -->
    <div class="sidebar-menu-tabs">
      <a class="sidebar-tab-btn active" data-tab="internal">Internal</a>
      <a class="sidebar-tab-btn" data-tab="outsource">Outsource</a>
    </div>

    <div class="sidebar-tab-content" id="sidebar-tab-internal">
      <?php echo $this->dynamic_menu->build_menu("internal", $check_menu); ?>
    </div>
    <div class="sidebar-tab-content" id="sidebar-tab-outsource">
      <?php echo $this->dynamic_menu->build_menu("outsource", $check_menu); ?>
    </div>

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

    // TAB SWITCHING
    var tabBtns = document.querySelectorAll('.sidebar-tab-btn');
    var tabContents = document.querySelectorAll('.sidebar-tab-content');
    var sidebarEl = document.querySelector('.page-sidebar');

    // Ukur tinggi sidebar SEKARANG saat kedua tab masih visible,
    // lalu simpan sebagai min-height agar Metronic tetap hitung scroll dengan benar
    if (sidebarEl) {
      sidebarEl.style.minHeight = sidebarEl.offsetHeight + 'px';
    }

    function activateTab(tabName) {
      tabBtns.forEach(function(btn) {
        btn.classList.toggle('active', btn.dataset.tab === tabName);
      });
      tabContents.forEach(function(content) {
        content.style.display = (content.id === 'sidebar-tab-' + tabName) ? '' : 'none';
      });
      if (typeof Layout !== 'undefined' && Layout.fixContentHeight) {
        Layout.fixContentHeight();
      }
    }

    tabBtns.forEach(function(btn) {
      btn.addEventListener('click', function() {
        localStorage.setItem('sidebarActiveTab', this.dataset.tab);
        activateTab(this.dataset.tab);
      });
    });

    // Auto-detect: prioritaskan pilihan tersimpan, fallback ke tab yang punya menu aktif
    var savedTab = localStorage.getItem('sidebarActiveTab');
    var internalHasActive = document.querySelector('#sidebar-tab-internal .nav-item.active');
    var outsourceHasActive = document.querySelector('#sidebar-tab-outsource .nav-item.active');

    var defaultTab;
    if (savedTab) {
      defaultTab = savedTab;
    } else if (internalHasActive && !outsourceHasActive) {
      defaultTab = 'internal';
    } else if (outsourceHasActive && !internalHasActive) {
      defaultTab = 'outsource';
    } else {
      defaultTab = 'internal';
    }
    activateTab(defaultTab);

    // Setelah semua script load: tampilkan tab tersembunyi sebentar untuk ukur tinggi penuh,
    // lalu update minHeight & panggil fixContentHeight agar scroll tidak hilang
    window.addEventListener('load', function() {
      if (!sidebarEl) return;
      var hiddenTab = null;
      tabContents.forEach(function(tab) {
        if (tab.style.display === 'none') hiddenTab = tab;
      });
      if (!hiddenTab) return;

      hiddenTab.style.display = '';        // tampilkan sebentar (dalam 1 JS task, tidak kepaint)
      var h = sidebarEl.offsetHeight;     // paksa reflow, ukur tinggi kedua tab
      hiddenTab.style.display = 'none';   // sembunyikan lagi sebelum browser paint

      if (h > 0) sidebarEl.style.minHeight = h + 'px';
      if (typeof Layout !== 'undefined' && Layout.fixContentHeight) {
        Layout.fixContentHeight();
      }
    });
  })();
</script>