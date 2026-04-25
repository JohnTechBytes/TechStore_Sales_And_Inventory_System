<!DOCTYPE html>
<html lang="en" style="font-size: 14px;">
<head>
  <meta name="csrf-name" content="<?= csrf_token() ?>">
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
  <title>TechStore | Advanced Inventory & Sales Dashboard</title>
  <!-- Google Fonts: Inter + Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
  
  <!-- Ionicons -->
   <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
  <!-- AdminLTE plugins -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/jqvmap/jqvmap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/daterangepicker/daterangepicker.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/summernote/summernote-bs4.min.css') ?>">
  
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/toastr/toastr.min.css') ?>">
  
  <!-- AdminLTE core CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">
  
  <style>
    /* ---------- MODERN UI OVERHAUL ---------- */
    :root {
      --primary: #0f766e;
      --primary-dark: #0d5d56;
      --primary-light: #14b8a6;
      --secondary: #f59e0b;
      --dark-bg: #0f172a;
      --card-bg-light: #ffffff;
      --card-bg-dark: #1e293b;
      --text-light: #1e293b;
      --text-dark: #f1f5f9;
      --sidebar-bg: linear-gradient(180deg, #0f172a 0%, #0f2b3d 100%);
      --sidebar-text: #e2e8f0;
      --sidebar-hover: rgba(255,255,255,0.08);
      --sidebar-active: linear-gradient(95deg, #0f766e, #0d5d56);
    }
    
    body {
      font-family: 'Inter', sans-serif;
      background: #f1f5f9;
      transition: background 0.2s;
    }
    
    /* ---------- SIDEBAR – HIGH CONTRAST ---------- */
    .main-sidebar, .sidebar {
      background: var(--sidebar-bg) !important;
      box-shadow: 8px 0 20px rgba(0,0,0,0.15);
    }
    .brand-link {
      background: #0f172a !important;
      border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .brand-text {
      font-weight: 700;
      background: linear-gradient(135deg, #14b8a6, #f59e0b);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      font-size: 1.2rem;
    }
    .nav-sidebar .nav-link {
      color: #cbd5e6 !important;
      border-radius: 0.8rem;
      margin: 0.3rem 0.8rem;
      padding: 0.7rem 1rem;
      transition: all 0.2s;
      font-weight: 500;
    }
    .nav-sidebar .nav-link i {
      color: #94a3b8;
      margin-right: 0.5rem;
    }
    .nav-sidebar .nav-link:hover {
      background: var(--sidebar-hover);
      color: white !important;
    }
    .nav-sidebar .nav-link:hover i {
      color: #14b8a6;
    }
    .nav-sidebar .nav-link.active {
      background: var(--sidebar-active) !important;
      color: white !important;
      box-shadow: 0 4px 12px rgba(15,118,110,0.3);
    }
    .nav-sidebar .nav-link.active i {
      color: white !important;
    }
    
    /* ---------- DASHBOARD CARDS – MODERN ---------- */
    .card {
      border-radius: 1.25rem;
      border: none;
      background: var(--card-bg-light);
      box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.02);
      transition: transform 0.2s, box-shadow 0.2s;
      overflow: hidden;
    }
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 20px 30px -12px rgba(0,0,0,0.1);
    }
    .card-header {
      background: transparent;
      border-bottom: 1px solid rgba(0,0,0,0.06);
      font-weight: 600;
      padding: 1rem 1.25rem;
    }
    .small-box {
      border-radius: 1.25rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      transition: all 0.2s;
      background: white;
    }
    .small-box:hover {
      transform: translateY(-4px);
      box-shadow: 0 16px 28px rgba(0,0,0,0.1);
    }
    .small-box .inner h3 {
      font-weight: 800;
      font-size: 2rem;
    }
    
    /* ---------- BUTTONS ---------- */
    .btn {
      border-radius: 0.7rem;
      padding: 0.5rem 1.2rem;
      font-weight: 500;
      transition: all 0.2s;
      border: none;
    }
    .btn-primary {
      background: linear-gradient(135deg, #0f766e, #0d5d56);
      color: white;
    }
    .btn-primary:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 12px rgba(15,118,110,0.2);
    }
    
    /* ---------- DATA TABLES ---------- */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: linear-gradient(135deg, #0f766e, #0d5d56) !important;
      border: none !important;
      color: white !important;
      border-radius: 2rem !important;
    }
    
    /* ---------- DARK MODE ---------- */
    body.dark-mode {
      background: #0f172a;
      color: #e2e8f0;
    }
    body.dark-mode .card {
      background: #1e293b;
      color: #e2e8f0;
    }
    body.dark-mode .small-box {
      background: #1e293b;
    }
    body.dark-mode .table {
      color: #e2e8f0;
    }
    body.dark-mode .form-control {
      background: #334155;
      border-color: #475569;
      color: white;
    }
    body.dark-mode .main-footer {
      border-top-color: #334155;
    }
    
    /* ---------- SCROLLBAR ---------- */
    ::-webkit-scrollbar {
      width: 8px;
    }
    ::-webkit-scrollbar-track {
      background: #e2e8f0;
      border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
      background: #94a3b8;
      border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #0f766e;
    }
    
    /* ---------- UTILITIES ---------- */
    .badge-instock {
      background: #d1fae5;
      color: #065f46;
      padding: 0.3rem 0.8rem;
      border-radius: 2rem;
    }
    .main-footer {
      background: transparent;
      border-top: 1px solid rgba(0,0,0,0.05);
      font-size: 0.8rem;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?= $this->include('theme/navbar') ?>
  <?= $this->include('theme/sidebar') ?>
  
  <!-- Main content -->
  <?= $this->renderSection('content') ?>
  
  <footer class="main-footer no-print">
    <strong>Copyright &copy; <?= date('Y') ?> <a href="#" style="text-decoration: none; font-weight: 600;">TechStore</a> </strong>
    <span class="mx-1">|</span> All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <span class="badge bg-gradient-dark px-3 py-1 rounded-pill">v3.0 · Modern UI</span>
    </div>
  </footer>
  
  <aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
      <h5><i class="fas fa-palette me-2"></i>Preferences</h5>
      <hr>
      <div class="form-group">
        <label>Theme Style</label>
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="quickThemeToggle">
          <label class="custom-control-label" for="quickThemeToggle">Dark / Light mode</label>
        </div>
      </div>
      <div class="form-group">
        <label>Compact Mode (coming)</label>
        <input type="checkbox" class="form-control">
      </div>
    </div>
  </aside>
</div>

<!-- ==================== SCRIPTS (identical to original) ==================== -->
<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
<script> $.widget.bridge('uibutton', $.ui.button) </script>
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/chart.js/Chart.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/sparklines/sparkline.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jqvmap/jquery.vmap.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jquery-knob/jquery.knob.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/summernote/summernote-bs4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.js') ?>"></script>

<!-- DataTables + extensions -->
<script src="<?= base_url('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/toastr/toastr.min.js') ?>"></script>

<!-- Custom UI scripts (unchanged) -->
<script>
  $(document).ready(function(){
    if ($.fn.dataTable) {
      $('table.dataTable').each(function() {
        if ($(this).hasClass('dataTable') && $(this).DataTable()) {
          $(this).DataTable().columns.adjust().responsive.recalc();
        }
      });
    }
    $('td:contains("In Stock"), td:contains("in stock")').each(function(){
      if($(this).text().toLowerCase().includes('in stock')) {
        $(this).html('<span class="badge-instock"><i class="fas fa-check-circle me-1"></i>'+ $(this).text() +'</span>');
      }
    });
    $('.card').each(function(index){
      $(this).css('animation', 'fadeInUp 0.4s ease forwards');
      $(this).css('animation-delay', (index * 0.03) + 's');
      $(this).css('opacity', '0');
    });
    $('.card').on('animationend', function(){
      $(this).css('opacity', '1');
    });
    $('<style>@keyframes fadeInUp { from { opacity:0; transform: translateY(10px); } to { opacity:1; transform: translateY(0); } }</style>').appendTo('head');
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "timeOut": "4000",
      "extendedTimeOut": "1000",
      "preventDuplicates": true
    };
    if(typeof flashMessage !== 'undefined'){
      if(flashMessage.type && flashMessage.text){
        toastr[flashMessage.type](flashMessage.text);
      }
    }
    $('.info-box, .small-box').on('mouseenter', function(){
      $(this).find('.icon i').addClass('fa-beat-fade');
    }).on('mouseleave', function(){
      $(this).find('.icon i').removeClass('fa-beat-fade');
    });
  });
</script>

<!-- THEME TOGGLE (preserved) -->
<script>
const themeToggle = document.getElementById('themeToggle');
const navbar = document.getElementById('mainNavbar');
const sidebar = document.getElementById('mainSidebar');
const brandLink = document.getElementById('brandLink');

let savedTheme = localStorage.getItem('adminlteTheme');
if(savedTheme === 'dark'){
    document.body.classList.add('dark-mode');
    if(navbar) {
      navbar.classList.remove('navbar-warning');
      navbar.classList.add('navbar-dark','bg-dark');
    }
    if(sidebar) {
      sidebar.classList.remove('sidebar-light');
      sidebar.classList.add('sidebar-dark-primary');
    }
    if(brandLink) {
      brandLink.classList.remove('bg-warning');
      brandLink.classList.add('bg-dark');
    }
    if(themeToggle) themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
} else {
    if(navbar) navbar.classList.add('navbar-warning');
    if(sidebar) {
      sidebar.classList.remove('sidebar-dark-primary');
      sidebar.classList.add('sidebar-light');
    }
    if(brandLink) {
      brandLink.classList.remove('bg-dark');
      brandLink.classList.add('bg-warning');
    }
    if(themeToggle) themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
}

if(themeToggle){
  themeToggle.addEventListener('click', function(e){
    e.preventDefault();
    if(document.body.classList.contains('dark-mode')){
        document.body.classList.remove('dark-mode');
        if(navbar) {
          navbar.classList.remove('navbar-dark','bg-dark');
          navbar.classList.add('navbar-warning');
        }
        if(sidebar) {
          sidebar.classList.remove('sidebar-dark-primary');
          sidebar.classList.add('sidebar-light');
        }
        if(brandLink) {
          brandLink.classList.remove('bg-dark');
          brandLink.classList.add('bg-warning');
        }
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        localStorage.setItem('adminlteTheme','light');
    } else {
        document.body.classList.add('dark-mode');
        if(navbar) {
          navbar.classList.remove('navbar-warning');
          navbar.classList.add('navbar-dark','bg-dark');
        }
        if(sidebar) {
          sidebar.classList.remove('sidebar-light');
          sidebar.classList.add('sidebar-dark-primary');
        }
        if(brandLink) {
          brandLink.classList.remove('bg-warning');
          brandLink.classList.add('bg-dark');
        }
        themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        localStorage.setItem('adminlteTheme','dark');
    }
    $(document).trigger('themeChanged');
  });
}

$(document).ready(function(){
  if($('#quickThemeToggle').length){
    $('#quickThemeToggle').prop('checked', savedTheme === 'dark');
    $('#quickThemeToggle').on('change', function(){
      if($(this).is(':checked')){
        if(!document.body.classList.contains('dark-mode')) themeToggle.click();
      } else {
        if(document.body.classList.contains('dark-mode')) themeToggle.click();
      }
    });
  }
});
</script>

<!-- Additional UI polish -->
<script>
  document.addEventListener('DOMContentLoaded', function(){
    const filterInputs = document.querySelectorAll('.dataTables_filter input');
    filterInputs.forEach(input => {
      input.classList.add('form-control', 'form-control-sm');
      input.style.borderRadius = '30px';
      input.style.paddingLeft = '1rem';
      input.placeholder = 'Search sales, products...';
    });
    const lengthSelects = document.querySelectorAll('.dataTables_length select');
    lengthSelects.forEach(select => {
      select.classList.add('custom-select', 'custom-select-sm');
      select.style.borderRadius = '30px';
    });
    $('.badge-danger, .badge-warning').each(function(){
      if($(this).text().includes('Low') || $(this).text().includes('Out')){
        $(this).addClass('px-2 py-1 rounded-pill');
        $(this).css('font-weight','600');
      }
    });
    $('.info-box-number, .small-box .inner h3').css('font-weight','800');
  });
  $(window).on('load', function(){
    setTimeout(() => {
      toastr.info('📊 Inventory & Sales ready | Real-time analytics', 'TechStore', {timeOut: 3500});
    }, 500);
  });
</script>

<script>
$(document).ajaxComplete(function(){
  $('.dataTables_filter input').addClass('form-control form-control-sm').css('border-radius','30px');
  $('.dataTables_length select').addClass('custom-select custom-select-sm').css('border-radius','30px');
  $('td:contains("In Stock")').each(function(){
    if($(this).text().toLowerCase().includes('in stock') && !$(this).find('.badge-instock').length){
      $(this).html('<span class="badge-instock"><i class="fas fa-check-circle me-1"></i>'+ $(this).text() +'</span>');
    }
  });
});
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>