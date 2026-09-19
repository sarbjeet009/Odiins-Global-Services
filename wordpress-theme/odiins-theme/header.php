<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/favicon.svg'); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

  <!-- 1. TOP BAR -->
  <header class="topbar">
    <div class="container topbar-content">
      <div class="topbar-left">
        <span>📍 <strong>Serving all 30 districts of Odisha</strong></span>
        <span class="topbar-badge">Odisha's Verified Network</span>
      </div>
      <div class="topbar-right">
        <a href="tel:+919938079601">📞 +91 99380 79601</a>
        <a href="mailto:corporate@odiins.in">✉️ corporate@odiins.in</a>
        <button class="lang-toggle" title="Change Language">ଓଡ଼ିଆ | English</button>
      </div>
    </div>
  </header>

  <!-- 2. STICKY NAVBAR -->
  <nav class="navbar">
    <div class="container navbar-inner">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-logo" aria-label="Odiins Home">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/logo.svg'); ?>" alt="Odiins Logo" height="64">
      </a>

      <!-- Desktop Navigation Links -->
      <ul class="nav-menu">
        <li><a href="<?php echo esc_url(home_url('/')); ?>" class="nav-link">Home</a></li>
        <li class="nav-dropdown">
          <a href="#" class="nav-dropdown-btn">Services ▾</a>
          <div class="dropdown-menu">
            <a href="<?php echo esc_url(home_url('/services-job-seekers/')); ?>" class="dropdown-item">💼 For Job Seekers</a>
            <a href="<?php echo esc_url(home_url('/services-employers/')); ?>" class="dropdown-item">🏢 For Employers &amp; Business</a>
            <a href="<?php echo esc_url(home_url('/services-customers/')); ?>" class="dropdown-item">🏠 For Customers (Home Help)</a>
          </div>
        </li>
        <li class="nav-dropdown">
          <a href="#" class="nav-dropdown-btn">About Us ▾</a>
          <div class="dropdown-menu">
            <a href="<?php echo esc_url(home_url('/about-vision-mission/')); ?>" class="dropdown-item">🎯 Vision &amp; Mission</a>
            <a href="<?php echo esc_url(home_url('/about-media/')); ?>" class="dropdown-item">📰 Media &amp; Recognition</a>
          </div>
        </li>
        <li><a href="<?php echo esc_url(home_url('/blogs/')); ?>" class="nav-link">Blogs</a></li>
        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>" class="nav-link">Contact</a></li>
      </ul>

      <!-- Action Buttons -->
      <div class="navbar-actions">
        <a href="<?php echo esc_url(home_url('/services-job-seekers/')); ?>" class="btn btn-green btn-sm">Get Started</a>
        <button class="nav-toggle-btn" id="navToggleBtn" aria-label="Open Navigation Menu">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </nav>

  <!-- Mobile Drawer Menu (No Admin link) -->
  <div class="drawer-overlay" id="drawerOverlay"></div>
  <aside class="mobile-drawer" id="mobileDrawer">
    <div class="drawer-header">
      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/logo.svg'); ?>" alt="Odiins Logo" height="42">
      <button class="drawer-close" id="drawerCloseBtn" aria-label="Close Menu">&times;</button>
    </div>
    <div class="drawer-links">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="drawer-link">Home</a>
      <span class="drawer-link" style="color:var(--primary-green); font-size:0.9rem; padding-bottom:0;">SERVICES:</span>
      <div class="drawer-sublinks">
        <a href="<?php echo esc_url(home_url('/services-job-seekers/')); ?>" class="drawer-sublink">💼 For Job Seekers</a>
        <a href="<?php echo esc_url(home_url('/services-employers/')); ?>" class="drawer-sublink">🏢 For Employers</a>
        <a href="<?php echo esc_url(home_url('/services-customers/')); ?>" class="drawer-sublink">🏠 For Customers (Home Help)</a>
      </div>
      <span class="drawer-link" style="color:var(--primary-green); font-size:0.9rem; padding-bottom:0;">ABOUT US:</span>
      <div class="drawer-sublinks">
        <a href="<?php echo esc_url(home_url('/about-vision-mission/')); ?>" class="drawer-sublink">🎯 Vision &amp; Mission</a>
        <a href="<?php echo esc_url(home_url('/about-media/')); ?>" class="drawer-sublink">📰 Media &amp; Recognition</a>
      </div>
      <a href="<?php echo esc_url(home_url('/blogs/')); ?>" class="drawer-link">Blogs</a>
      <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="drawer-link">Contact Us</a>
    </div>
    <div class="drawer-footer">
      <a href="tel:+919938079601" class="btn btn-green btn-block">📞 Call +91 99380 79601</a>
    </div>
  </aside>
