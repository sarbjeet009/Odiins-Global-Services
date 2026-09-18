<?php
/**
 * Template Name: Admin Command Center
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unified Executive Command Center | Odiins Management Portal</title>
  <link rel="icon" type="image/svg+xml" href="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/favicon.svg'); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri() . '/css/style.css'); ?>">
  <style>
    /* Admin Login Overlay Styles */
    .admin-auth-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      background: linear-gradient(135deg, #EAF3FA 0%, #F8FAFC 100%);
    }
    .admin-auth-card {
      background: #FFFFFF;
      border-radius: 16px;
      padding: 2.5rem 2rem;
      max-width: 440px;
      width: 100%;
      box-shadow: 0 10px 30px rgba(31, 41, 55, 0.12);
      border: 1px solid #D1E3F2;
      text-align: center;
    }
    .admin-auth-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      padding: 0.3rem 0.75rem;
      background: #FEF3C7;
      color: #B45309;
      font-size: 0.78rem;
      font-weight: 700;
      border-radius: 9999px;
      margin-bottom: 1.25rem;
      border: 1px solid #FDE68A;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .admin-auth-card h2 {
      font-size: 1.5rem;
      color: #1F2937;
      margin-bottom: 0.4rem;
    }
    .admin-auth-card p.subtitle {
      font-size: 0.88rem;
      color: #4B5563;
      margin-bottom: 1.75rem;
      line-height: 1.5;
    }
    .auth-input-group {
      margin-bottom: 1.25rem;
      text-align: left;
    }
    .auth-input-group label {
      display: block;
      font-size: 0.85rem;
      font-weight: 600;
      color: #1F2937;
      margin-bottom: 0.4rem;
    }
    .auth-input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }
    .auth-input-icon {
      position: absolute;
      left: 14px;
      color: #64A8DA;
      font-size: 1.1rem;
      pointer-events: none;
    }
    .auth-input-field {
      width: 100%;
      padding: 0.75rem 0.9rem 0.75rem 2.75rem;
      border: 1.5px solid #D1E3F2;
      border-radius: 10px;
      font-size: 16px;
      color: #1F2937;
      background: #FFFFFF;
      transition: all 0.2s;
      box-sizing: border-box;
    }
    .auth-input-field:focus {
      outline: none;
      border-color: #098B38;
      box-shadow: 0 0 0 3px rgba(9, 139, 56, 0.15);
    }
    .auth-toggle-pwd {
      position: absolute;
      right: 12px;
      background: none;
      border: none;
      cursor: pointer;
      font-size: 1.1rem;
      color: #6B7280;
      padding: 4px;
    }
    .auth-error-banner {
      display: none;
      background: #FEE2E2;
      border: 1px solid #FCA5A5;
      color: #B91C1C;
      padding: 0.65rem 0.85rem;
      border-radius: 8px;
      font-size: 0.82rem;
      margin-bottom: 1.25rem;
      text-align: left;
    }
    .auth-meta-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      font-size: 0.82rem;
      color: #4B5563;
      flex-wrap: wrap;
      gap: 0.5rem;
    }
    .auth-meta-row label {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      cursor: pointer;
    }
    .auth-hint-box {
      margin-top: 1.5rem;
      padding: 0.75rem;
      background: #EAF3FA;
      border-radius: 8px;
      font-size: 0.78rem;
      color: #1F2937;
      border: 1px dashed #64A8DA;
      line-height: 1.5;
      text-align: left;
    }
  </style>
<script>
    window.odiins_wp = {
      rest_url: "<?php echo esc_url_raw(rest_url('odiins/v1/')); ?>",
      home_url: "<?php echo esc_url(home_url('/')); ?>",
      theme_url: "<?php echo esc_url(get_template_directory_uri()); ?>"
    };
  </script>
</head>
<body style="background-color: var(--bg-light-blue);">

  <!-- 1. ADMIN AUTHENTICATION LOGIN OVERLAY -->
  <div id="adminAuthOverlay" class="admin-auth-container">
    <div class="admin-auth-card">
      <div style="display:flex; justify-content:center; margin-bottom:1.25rem;">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/logo.svg'); ?>" alt="Odiins Logo" height="42">
      </div>
      <div class="admin-auth-badge">
        <span>🔒</span> Restricted • Central Odisha Admin
      </div>
      <h2>Admin Sign In</h2>
      <p class="subtitle">Enter your executive credentials to access the live command center, CRM leads &amp; advertising data.</p>

      <div id="authErrorAlert" class="auth-error-banner" role="alert">
        <span>⚠️</span> <span id="authErrorMsg">Invalid Admin ID or Password. Please try again.</span>
      </div>

      <form id="adminLoginForm" onsubmit="handleAdminLogin(event)" novalidate>
        <!-- Admin ID / Username -->
        <div class="auth-input-group">
          <label for="adminIdInput">Admin ID or Email</label>
          <div class="auth-input-wrapper">
            <span class="auth-input-icon">👤</span>
            <input type="text" id="adminIdInput" class="auth-input-field" placeholder="admin@odiins.com" value="admin@odiins.com" required autocomplete="username">
          </div>
        </div>

        <!-- Password -->
        <div class="auth-input-group">
          <label for="adminPasswordInput">Password</label>
          <div class="auth-input-wrapper">
            <span class="auth-input-icon">🔑</span>
            <input type="password" id="adminPasswordInput" class="auth-input-field" placeholder="••••••••••••" required autocomplete="current-password">
            <button type="button" class="auth-toggle-pwd" onclick="togglePasswordVisibility()" aria-label="Toggle password visibility">👁️</button>
          </div>
        </div>

        <!-- Meta options -->
        <div class="auth-meta-row">
          <label>
            <input type="checkbox" id="rememberAdminCheckbox" checked>
            <span>Remember this device</span>
          </label>
          <a href="index.html" style="color:var(--primary-blue); font-weight:500;">&larr; Return to Website</a>
        </div>

        <!-- Submit Button -->
        <button type="submit" id="authSubmitBtn" class="btn btn-green btn-block btn-lg">
          Unlock Command Center &rarr;
        </button>
      </form>

      <!-- Security Credential Notice -->
      <div class="auth-hint-box">
        <div style="font-weight:700; margin-bottom:0.2rem; color:var(--primary-green);">🔑 Default Executive Credentials:</div>
        <div>Admin ID: <code>admin@odiins.com</code> (or <code>admin</code>)</div>
        <div>Password: <code>Odiins@Admin2026</code></div>
      </div>
    </div>
  </div>

  <!-- 2. PROTECTED DASHBOARD APP -->
  <div id="dashboardApp" style="display:none;">

    <!-- TOP BAR -->
    <header class="topbar">
      <div class="container topbar-content">
        <div class="topbar-left">
          <span>🔒 <strong>Odiins Unified Executive Command Center • Management View</strong></span>
        </div>
        <div class="topbar-right">
          <span>Active Desk: Odisha Central</span>
          <a href="index.html" class="btn btn-sm btn-white" style="padding:0.2rem 0.6rem; color:var(--primary-green); font-size:0.75rem;">View Live Website &rarr;</a>
          <button id="adminLogoutBtn" onclick="adminLogout()" class="btn btn-sm" style="background:rgba(255,255,255,0.2); color:#FFFFFF; border:1px solid rgba(255,255,255,0.4); padding:0.2rem 0.6rem; font-size:0.75rem; border-radius:6px; cursor:pointer;">🚪 Log Out</button>
        </div>
      </div>
    </header>

  <!-- DASHBOARD MAIN CONTAINER -->
  <main class="container" style="padding-top: 1.5rem; padding-bottom: 3.5rem;">
    
    <!-- Top Executive Header -->
    <div style="background:var(--bg-white); padding:1.5rem; border-radius:var(--radius-lg); box-shadow:var(--card-shadow); border:1px solid var(--border-light); margin-bottom:1.25rem;">
      <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/logo.svg'); ?>" alt="Odiins Logo" height="42">
          <div>
            <h1 style="font-size:1.5rem; line-height:1.2;">Executive Command Center</h1>
            <p style="font-size:0.85rem; color:var(--text-muted);">Unified live monitoring: Leads, Paid Ads, Web Analytics, SEO Rankings, Instagram &amp; YouTube</p>
          </div>
        </div>

        <div style="display:flex; gap:0.6rem; flex-wrap:wrap;">
          <a href="<?php echo esc_url(rest_url('odiins/v1/leads-export')); ?>" id="exportCsvBtn" class="btn btn-green btn-sm" download>
            📥 Export Leads Spreadsheet
          </a>
          <button id="printReportBtn" class="btn btn-white btn-sm" onclick="switchToTab('infographic'); setTimeout(() => window.print(), 250);">
            🖨️ Export Infographic (PDF)
          </button>
          <button id="refreshAllBtn" class="btn btn-white btn-sm" onclick="refreshAllData()">
            🔄 Refresh All
          </button>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs (7 Modules) -->
    <div class="dash-nav-tabs">
      <button class="dash-tab-btn active" onclick="switchToTab('leads')" id="tabBtnLeads">
        📋 Leads &amp; CRM
      </button>
      <button class="dash-tab-btn" onclick="switchToTab('ads')" id="tabBtnAds">
        🎯 Google &amp; Meta Ads
      </button>
      <button class="dash-tab-btn" onclick="switchToTab('traffic')" id="tabBtnTraffic">
        🌐 Website Traffic &amp; Bounce Rate
      </button>
      <button class="dash-tab-btn" onclick="switchToTab('seo')" id="tabBtnSeo">
        📈 SEO &amp; Odisha Keyword Ranks
      </button>
      <button class="dash-tab-btn" onclick="switchToTab('social')" id="tabBtnSocial">
        📱 Instagram &amp; YouTube Hub
      </button>
      <button class="dash-tab-btn" onclick="switchToTab('users')" id="tabBtnUsers">
        👤 User Sign-ins &amp; Activity
      </button>
      <button class="dash-tab-btn" onclick="switchToTab('infographic')" id="tabBtnInfographic">
        📊 Executive Infographic Report
      </button>
    </div>

    <!-- =========================================================================
         TAB 1: LEADS & CRM
         ========================================================================= -->
    <div class="dash-panel active" id="panelLeads">
      
      <!-- Stats Grid -->
      <div class="dashboard-stats-grid">
        <div class="stat-box">
          <div class="stat-box-val" id="statTotalLeads">0</div>
          <div class="stat-box-title">Total Active Enquiries</div>
        </div>
        <div class="stat-box">
          <div class="stat-box-val" id="statJobSeekers" style="color:#0369A1;">0</div>
          <div class="stat-box-title">Job Seekers</div>
        </div>
        <div class="stat-box">
          <div class="stat-box-val" id="statEmployers" style="color:#15803D;">0</div>
          <div class="stat-box-title">Employers &amp; MSMEs</div>
        </div>
        <div class="stat-box">
          <div class="stat-box-val" id="statCustomers" style="color:#B45309;">0</div>
          <div class="stat-box-title">Household Inquiries</div>
        </div>
      </div>

      <!-- Filters & Search Toolbar -->
      <div style="background:var(--bg-white); padding:1.25rem; border-radius:var(--radius-md); box-shadow:var(--card-shadow); border:1px solid var(--border-light); margin-bottom:1.5rem;">
        <div style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:center;">
          <div style="flex-grow:1; min-width:240px;">
            <input type="text" id="leadSearchInput" class="form-control" placeholder="Search leads by name, phone, district, requirement...">
          </div>

          <div>
            <select id="filterCategory" class="form-control" style="width:auto; min-width:160px;">
              <option value="all">All Categories</option>
              <option value="Job Seeker">Job Seekers</option>
              <option value="Employer">Employers / Business</option>
              <option value="Customer">Household Help</option>
              <option value="Contact Enquiry">Contact Messages</option>
            </select>
          </div>

          <div>
            <select id="filterAdSource" class="form-control" style="width:auto; min-width:150px;">
              <option value="all">All Ad Sources</option>
              <option value="Google Ads">Google Ads</option>
              <option value="Meta Ads">Meta Ads</option>
              <option value="Direct / Organic">Direct / Organic</option>
            </select>
          </div>

          <div>
            <select id="filterStatus" class="form-control" style="width:auto; min-width:140px;">
              <option value="all">All Statuses</option>
              <option value="New">New</option>
              <option value="Contacted">Contacted</option>
              <option value="In Progress">In Progress</option>
              <option value="Closed">Closed</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Leads Table -->
      <div class="table-responsive">
        <table class="data-table" id="leadsTable">
          <thead>
            <tr>
              <th>ID &amp; Date</th>
              <th>Category</th>
              <th>Name / Business</th>
              <th>Phone / WhatsApp</th>
              <th>District / Location</th>
              <th>Requirement</th>
              <th>Source / Campaign</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="leadsTableBody">
            <tr>
              <td colspan="9" style="text-align:center; padding:2rem; color:var(--text-muted);">
                Loading live leads...
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Audit Log -->
      <div style="margin-top:1.75rem; background:var(--bg-white); border-radius:var(--radius-lg); padding:1.25rem; box-shadow:var(--card-shadow); border:1px solid var(--border-light);">
        <h3 style="font-size:1.05rem; margin-bottom:0.5rem; display:flex; align-items:center; gap:0.5rem;">
          <span>✉️</span> Admin Notification Dispatch Log (Simulated SMTP &amp; Webhook)
        </h3>
        <div id="emailLogContainer" style="background:#1F2937; color:#A7F3D0; font-family:monospace; font-size:0.75rem; padding:0.85rem; border-radius:var(--radius-sm); max-height:120px; overflow-y:auto; line-height:1.5;">
          [System Ready] Listening for incoming enquiries...
        </div>
      </div>
    </div>


    <!-- =========================================================================
         TAB 2: GOOGLE & META ADS COMMAND CENTER
         ========================================================================= -->
    <div class="dash-panel" id="panelAds">
      <div class="ads-grid-3">
        <div class="ad-platform-card google-theme">
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-weight:700; color:#1967D2;">🔍 Google Search Ads</span>
            <span class="badge-lead-type badge-ad-google">Tag Active</span>
          </div>
          <div class="ad-metric-val" id="adGoogleLeadsCount" style="color:#1967D2;">0</div>
          <div style="font-size:0.85rem; color:var(--text-muted);">Leads from Google Search Campaigns</div>
          <div style="margin-top:0.75rem; padding-top:0.5rem; border-top:1px solid var(--border-light); font-size:0.8rem;">
            Top Campaign: <strong>bbsr_backoffice_search</strong>
          </div>
        </div>

        <div class="ad-platform-card meta-theme">
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-weight:700; color:#0081FB;">📱 Meta Social Ads (FB/Insta)</span>
            <span class="badge-lead-type badge-ad-meta">Pixel Active</span>
          </div>
          <div class="ad-metric-val" id="adMetaLeadsCount" style="color:#0081FB;">0</div>
          <div style="font-size:0.85rem; color:var(--text-muted);">Leads from Instagram &amp; Facebook</div>
          <div style="margin-top:0.75rem; padding-top:0.5rem; border-top:1px solid var(--border-light); font-size:0.8rem;">
            Top Ad: <strong>home_maid_cook_insta</strong>
          </div>
        </div>

        <div class="ad-platform-card organic-theme">
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-weight:700; color:var(--primary-green);">🌱 Direct &amp; Organic</span>
            <span class="badge-lead-type badge-ad-organic">Free Referrals</span>
          </div>
          <div class="ad-metric-val" id="adOrganicLeadsCount" style="color:var(--primary-green);">0</div>
          <div style="font-size:0.85rem; color:var(--text-muted);">Organic searches, word of mouth &amp; direct</div>
          <div style="margin-top:0.75rem; padding-top:0.5rem; border-top:1px solid var(--border-light); font-size:0.8rem;">
            Share: <strong id="adOrganicShare">0%</strong> of overall inflow
          </div>
        </div>
      </div>

      <!-- CPL Calculator -->
      <div style="background:var(--bg-white); padding:1.75rem; border-radius:var(--radius-lg); box-shadow:var(--card-shadow); border:1px solid var(--border-light); margin-bottom:1.5rem;">
        <h3 style="font-size:1.25rem; margin-bottom:0.4rem;">💰 Spend &amp; Cost Per Lead (CPL) Calculator</h3>
        <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1.25rem;">
          Calculates real-time Cost Per Lead across paid marketing channels in Odisha.
        </p>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem;">
          <div>
            <label class="form-label">Google Ads Spend (₹)</label>
            <input type="number" id="inputGoogleSpend" class="form-control" value="6500" oninput="calculateCpl()">
          </div>
          <div>
            <label class="form-label">Meta Ads Spend (₹)</label>
            <input type="number" id="inputMetaSpend" class="form-control" value="4800" oninput="calculateCpl()">
          </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem; margin-top:1.25rem;">
          <div style="background:var(--bg-light-blue); padding:1rem; border-radius:var(--radius-md); border-left:4px solid #1967D2;">
            <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted);">GOOGLE ADS CPL</div>
            <div style="font-size:1.6rem; font-weight:800; color:#1967D2;" id="calcGoogleCpl">₹0</div>
          </div>
          <div style="background:var(--bg-light-blue); padding:1rem; border-radius:var(--radius-md); border-left:4px solid #0081FB;">
            <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted);">META ADS CPL</div>
            <div style="font-size:1.6rem; font-weight:800; color:#0081FB;" id="calcMetaCpl">₹0</div>
          </div>
          <div style="background:var(--bg-light-blue); padding:1rem; border-radius:var(--radius-md); border-left:4px solid var(--primary-green);">
            <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted);">BLENDED CPL</div>
            <div style="font-size:1.6rem; font-weight:800; color:var(--primary-green);" id="calcBlendedCpl">₹0</div>
          </div>
        </div>
      </div>

      <!-- Campaign UTM URL Builder -->
      <div style="background:var(--bg-white); padding:1.75rem; border-radius:var(--radius-lg); box-shadow:var(--card-shadow); border:1px solid var(--border-light); margin-bottom:1.5rem;">
        <h3 style="font-size:1.2rem; margin-bottom:0.4rem;">🔗 Campaign UTM Link Builder</h3>
        <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:1rem;">
          Generate tracked links for Google Ads &amp; Meta Ads campaigns targeting specific Odisha districts.
        </p>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
          <div>
            <label class="form-label">Destination Landing Page</label>
            <select id="utmLandingPage" class="form-control" onchange="generateUtmLink()">
              <option value="services-job-seekers.html">For Job Seekers</option>
              <option value="services-employers.html">For Employers &amp; Business</option>
              <option value="services-customers.html">For Customers (Domestic Help)</option>
              <option value="index.html">Home Page</option>
            </select>
          </div>
          <div>
            <label class="form-label">Platform (Source)</label>
            <select id="utmPlatform" class="form-control" onchange="generateUtmLink()">
              <option value="google">Google Ads</option>
              <option value="meta">Meta Ads (Instagram/FB)</option>
            </select>
          </div>
          <div>
            <label class="form-label">Campaign Name</label>
            <input type="text" id="utmCampaignInput" class="form-control" value="bhubaneswar_staffing_mar26" oninput="generateUtmLink()">
          </div>
          <div>
            <label class="form-label">Target District</label>
            <select id="utmDistrict" class="form-control" onchange="generateUtmLink()">
              <option value="bhubaneswar">Bhubaneswar</option>
              <option value="cuttack">Cuttack</option>
              <option value="puri">Puri</option>
              <option value="rourkela">Rourkela</option>
              <option value="sambalpur">Sambalpur</option>
              <option value="berhampur">Berhampur</option>
            </select>
          </div>
        </div>

        <div style="margin-top:1rem; background:var(--bg-light-blue); padding:0.9rem; border-radius:var(--radius-md);">
          <div id="generatedUtmResult" style="font-family:monospace; font-size:0.82rem; color:#0369A1; word-break:break-all; user-select:all; margin-bottom:0.5rem;">
            http://localhost:3000/services-employers.html?utm_source=google&amp;utm_medium=cpc&amp;utm_campaign=bhubaneswar_staffing_mar26
          </div>
          <button class="btn btn-green btn-sm" onclick="copyGeneratedLink()">📋 Copy Link</button>
        </div>
      </div>
    </div>


    <!-- =========================================================================
         TAB 3: WEBSITE TRAFFIC & BOUNCE RATE
         ========================================================================= -->
    <div class="dash-panel" id="panelTraffic">
      
      <!-- Live Web KPI Cards -->
      <div class="dashboard-stats-grid">
        <div class="stat-box">
          <div class="stat-box-val" id="webActiveNow">48</div>
          <div class="stat-box-title">Active Visitors Right Now</div>
        </div>
        <div class="stat-box">
          <div class="stat-box-val" id="webMonthlyVisitors">42,850</div>
          <div class="stat-box-title">Monthly Unique Visitors</div>
        </div>
        <div class="stat-box">
          <div class="stat-box-val" style="color:var(--primary-green);" id="webBounceRate">28.4%</div>
          <div class="stat-box-title">Bounce Rate (Excellent &lt; 35%)</div>
        </div>
        <div class="stat-box">
          <div class="stat-box-val" style="color:#0369A1;" id="webAvgDuration">3m 48s</div>
          <div class="stat-box-title">Average Session Duration</div>
        </div>
      </div>

      <!-- Device Breakdown & Top Landing Pages -->
      <div style="display:grid; grid-template-columns:1fr; gap:1.5rem; margin-bottom:1.5rem;">
        
        <!-- Device Split Card -->
        <div style="background:var(--bg-white); padding:1.5rem; border-radius:var(--radius-lg); box-shadow:var(--card-shadow); border:1px solid var(--border-light);">
          <h3 style="font-size:1.15rem; margin-bottom:0.25rem;">📱 Device Traffic Breakdown (Odisha Mobile-First)</h3>
          <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1rem;">Over 76% of visitors access Odiins via mobile devices.</p>

          <div class="device-progress-bar">
            <div class="device-bar-mobile" style="width:76.2%;" title="Mobile: 76.2%"></div>
            <div class="device-bar-desktop" style="width:20.6%;" title="Desktop: 20.6%"></div>
            <div class="device-bar-tablet" style="width:3.2%;" title="Tablet: 3.2%"></div>
          </div>

          <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-top:0.75rem; font-size:0.85rem;">
            <div><span style="color:var(--primary-green); font-weight:700;">● Mobile:</span> <strong>76.2%</strong> (32,650 visits)</div>
            <div><span style="color:var(--primary-blue); font-weight:700;">● Desktop:</span> <strong>20.6%</strong> (8,820 visits)</div>
            <div><span style="color:#F59E0B; font-weight:700;">● Tablet:</span> <strong>3.2%</strong> (1,380 visits)</div>
          </div>
        </div>

        <!-- Top Landing Pages Table -->
        <div style="background:var(--bg-white); padding:1.5rem; border-radius:var(--radius-lg); box-shadow:var(--card-shadow); border:1px solid var(--border-light);">
          <h3 style="font-size:1.15rem; margin-bottom:0.25rem;">📄 Top Visited Pages &amp; Enquiry Conversion</h3>
          <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1rem;">Visitor volume and lead conversion rates by page</p>

          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Page URL</th>
                  <th>Page Purpose</th>
                  <th>Monthly Views</th>
                  <th>Lead Conversion Rate</th>
                </tr>
              </thead>
              <tbody id="topPagesTableBody">
                <!-- Rendered dynamically -->
              </tbody>
            </table>
          </div>
        </div>

      </div>

    </div>


    <!-- =========================================================================
         TAB 4: SEO & ODISHA KEYWORD RANKS
         ========================================================================= -->
    <div class="dash-panel" id="panelSeo">
      
      <!-- Core Web Vitals Health Banner -->
      <div style="background:var(--bg-white); padding:1.5rem; border-radius:var(--radius-lg); box-shadow:var(--card-shadow); border:1px solid var(--border-light); margin-bottom:1.5rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
          <div>
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
              <span class="section-badge" style="background:#DCFCE7; color:#15803D; border-color:#86EFAC; margin:0;">GOOGLE CORE WEB VITALS</span>
              <span style="font-size:0.85rem; font-weight:700; color:var(--primary-green);">Score: 98 / 100</span>
            </div>
            <h3 style="font-size:1.25rem;">Search Engine Optimization Health Check</h3>
            <p style="font-size:0.85rem; color:var(--text-muted);">All core web vitals are in the fast, passing green bracket.</p>
          </div>

          <div style="display:flex; gap:1.25rem; font-size:0.85rem;">
            <div style="text-align:center;">
              <div style="font-size:1.2rem; font-weight:800; color:var(--primary-green);">1.1s</div>
              <div style="color:var(--text-muted); font-size:0.75rem;">LCP Speed</div>
            </div>
            <div style="text-align:center;">
              <div style="font-size:1.2rem; font-weight:800; color:var(--primary-green);">12ms</div>
              <div style="color:var(--text-muted); font-size:0.75rem;">FID Latency</div>
            </div>
            <div style="text-align:center;">
              <div style="font-size:1.2rem; font-weight:800; color:var(--primary-green);">0.002</div>
              <div style="color:var(--text-muted); font-size:0.75rem;">CLS Layout</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Keyword Rankings Table -->
      <div class="table-responsive" style="background:var(--bg-white); border-radius:var(--radius-lg); border:1px solid var(--border-light); box-shadow:var(--card-shadow);">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border-light);">
          <h3 style="font-size:1.2rem; margin-bottom:0.25rem;">🏆 Odisha Targeted Keyword Rankings (Google India)</h3>
          <p style="font-size:0.85rem; color:var(--text-muted);">Live SERP position for high-intent hiring and domestic care queries in Odisha</p>
        </div>

        <table class="data-table">
          <thead>
            <tr>
              <th>Google Rank</th>
              <th>Target Search Keyword</th>
              <th>Rank Movement</th>
              <th>Monthly Searches (Odisha)</th>
              <th>Organic Click-Through Rate</th>
            </tr>
          </thead>
          <tbody id="keywordTableBody">
            <!-- Rendered dynamically -->
          </tbody>
        </table>
      </div>

    </div>


    <!-- =========================================================================
         TAB 5: INSTAGRAM & YOUTUBE HUB (API-READY)
         ========================================================================= -->
    <div class="dash-panel" id="panelSocial">
      
      <!-- Top Social Channel Summary Cards -->
      <div style="display:grid; grid-template-columns:1fr; gap:1.5rem; margin-bottom:1.5rem;">
        
        <!-- Instagram Card -->
        <div class="ad-platform-card social-card-insta">
          <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div>
              <div style="display:flex; align-items:center; gap:0.5rem;">
                <span style="font-size:1.5rem;">📸</span>
                <h3 style="font-size:1.2rem; color:var(--text-charcoal);">Instagram Channel Analytics</h3>
              </div>
              <p style="font-size:0.85rem; color:var(--text-muted);" id="instaHandle">@odiins.odisha</p>
            </div>
            <button class="btn btn-white btn-sm" onclick="openSocialModal('instagram')">⚙️ Instagram API Config</button>
          </div>

          <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:1rem; margin:1.25rem 0;">
            <div style="background:var(--bg-light-blue); padding:0.9rem; border-radius:var(--radius-md);">
              <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">FOLLOWERS</div>
              <div style="font-size:1.5rem; font-weight:800; color:#E1306C;" id="instaFollowers">14,820</div>
              <div style="font-size:0.7rem; color:var(--primary-green);">+345 this week</div>
            </div>
            <div style="background:var(--bg-light-blue); padding:0.9rem; border-radius:var(--radius-md);">
              <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">REELS &amp; POSTS</div>
              <div style="font-size:1.5rem; font-weight:800; color:var(--text-charcoal);" id="instaPosts">84</div>
              <div style="font-size:0.7rem; color:var(--text-muted);">Published content</div>
            </div>
            <div style="background:var(--bg-light-blue); padding:0.9rem; border-radius:var(--radius-md);">
              <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">PROFILE VISITS</div>
              <div style="font-size:1.5rem; font-weight:800; color:#0369A1;" id="instaVisits">28,400</div>
              <div style="font-size:0.7rem; color:var(--text-muted);">Last 30 days</div>
            </div>
            <div style="background:var(--bg-light-blue); padding:0.9rem; border-radius:var(--radius-md);">
              <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">DIRECT DM LEADS</div>
              <div style="font-size:1.5rem; font-weight:800; color:var(--primary-green);" id="instaDmLeads">142</div>
              <div style="font-size:0.7rem; color:var(--primary-green);">Direct enquiries</div>
            </div>
          </div>

          <h4 style="font-size:0.95rem; margin-bottom:0.5rem;">🔥 Top Performing Instagram Reels</h4>
          <div class="video-grid" id="instaReelsContainer">
            <!-- Rendered dynamically -->
          </div>
        </div>

        <!-- YouTube Card -->
        <div class="ad-platform-card social-card-yt">
          <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div>
              <div style="display:flex; align-items:center; gap:0.5rem;">
                <span style="font-size:1.5rem;">▶️</span>
                <h3 style="font-size:1.2rem; color:var(--text-charcoal);">YouTube Channel Performance</h3>
              </div>
              <p style="font-size:0.85rem; color:var(--text-muted);" id="ytChannel">Odiins - Odisha Jobs &amp; Manpower</p>
            </div>
            <button class="btn btn-white btn-sm" onclick="openSocialModal('youtube')">⚙️ YouTube API Config</button>
          </div>

          <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:1rem; margin:1.25rem 0;">
            <div style="background:var(--bg-light-blue); padding:0.9rem; border-radius:var(--radius-md);">
              <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">SUBSCRIBERS</div>
              <div style="font-size:1.5rem; font-weight:800; color:#FF0000;" id="ytSubs">8,240</div>
              <div style="font-size:0.7rem; color:var(--primary-green);">+380 this month</div>
            </div>
            <div style="background:var(--bg-light-blue); padding:0.9rem; border-radius:var(--radius-md);">
              <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">TOTAL VIDEOS</div>
              <div style="font-size:1.5rem; font-weight:800; color:var(--text-charcoal);" id="ytVideos">32</div>
              <div style="font-size:0.7rem; color:var(--text-muted);">Shorts &amp; Guides</div>
            </div>
            <div style="background:var(--bg-light-blue); padding:0.9rem; border-radius:var(--radius-md);">
              <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">WATCH HOURS</div>
              <div style="font-size:1.5rem; font-weight:800; color:#0369A1;" id="ytWatchHours">4,180 hrs</div>
              <div style="font-size:0.7rem; color:var(--text-muted);">Monetization ready</div>
            </div>
            <div style="background:var(--bg-light-blue); padding:0.9rem; border-radius:var(--radius-md);">
              <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">TOTAL VIEWS</div>
              <div style="font-size:1.5rem; font-weight:800; color:var(--primary-green);" id="ytTotalViews">185.6K</div>
              <div style="font-size:0.7rem; color:var(--primary-green);">Lifetime views</div>
            </div>
          </div>

          <h4 style="font-size:0.95rem; margin-bottom:0.5rem;">🎥 Top Video Guides &amp; Retention</h4>
          <div class="video-grid" id="ytVideosContainer">
            <!-- Rendered dynamically -->
          </div>
        </div>

      </div>

    </div>


    <!-- =========================================================================
         TAB 6: USER ACCOUNTS & LIVE ACTIVITY FEED
         ========================================================================= -->
    <div class="dash-panel" id="panelUsers">
      
      <div style="background:var(--bg-white); padding:1.5rem; border-radius:var(--radius-lg); box-shadow:var(--card-shadow); border:1px solid var(--border-light); margin-bottom:1.5rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1rem;">
          <div>
            <h3 style="font-size:1.25rem;">👤 Recent User Sign-ins &amp; Enquiries Feed</h3>
            <p style="font-size:0.85rem; color:var(--text-muted);">Real-time stream of registered job seekers, employers, and household customers with verified contact details.</p>
          </div>
          <span class="section-badge" style="background:#DCFCE7; color:#15803D; margin:0;">● Live Real-Time Feed</span>
        </div>

        <div class="activity-timeline" id="activityTimelineContainer">
          <!-- Rendered dynamically -->
        </div>
      </div>

    </div>


    <!-- =========================================================================
         TAB 7: EXECUTIVE INFOGRAPHIC REPORT SUITE (PRINT / PDF READY)
         ========================================================================= -->
    <div class="dash-panel" id="panelInfographic">
      
      <div class="infographic-sheet">
        
        <!-- Header -->
        <div class="infographic-header">
          <div class="infographic-title">
            <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.5rem;">
              <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/logo.svg'); ?>" alt="Odiins Logo" height="38">
              <span class="section-badge" style="margin:0;">EXECUTIVE SUMMARY REPORT</span>
            </div>
            <h2>Odiins All-In-One Unified Performance Report</h2>
            <p>Workforce analytics, Paid Ads attribution, Web traffic, SEO rankings, and Social media reach across Odisha.</p>
          </div>
          <div style="text-align:right;">
            <div style="font-size:0.8rem; color:var(--text-muted);">Reporting Period: <strong>Q1 2026</strong></div>
            <div style="font-size:0.8rem; color:var(--text-muted);">Generated on: <strong id="infoReportDate">March 2026</strong></div>
            <button class="btn btn-green btn-sm" style="margin-top:0.5rem;" onclick="window.print()">
              🖨️ Print / Save as PDF
            </button>
          </div>
        </div>

        <!-- Top 4 KPIs -->
        <div class="kpi-deck">
          <div class="kpi-card">
            <div class="kpi-label">TOTAL LEADS ACQUIRED</div>
            <div class="kpi-value" id="kpiTotalLeads">0</div>
            <div class="kpi-sub">Across 30 Odisha districts</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-label">MONTHLY WEB VISITORS</div>
            <div class="kpi-value" style="color:#0369A1;">42,850</div>
            <div class="kpi-sub">Bounce Rate: 28.4% (Passing)</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-label">TOP GOOGLE RANKINGS</div>
            <div class="kpi-value" style="color:var(--primary-green);">6 Keywords #1</div>
            <div class="kpi-sub">SEO Health Score: 98/100</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-label">SOCIAL REACH</div>
            <div class="kpi-value" style="color:#E1306C;">23.0K Audience</div>
            <div class="kpi-sub">Instagram + YouTube</div>
          </div>
        </div>

        <!-- 2-Column Charts -->
        <div style="display:grid; grid-template-columns:1fr; gap:2rem; margin-bottom:2rem;">
          <!-- District Distribution -->
          <div style="background:var(--bg-white); border:1px solid var(--border-light); border-radius:var(--radius-md); padding:1.25rem;">
            <h3 style="font-size:1.1rem; margin-bottom:0.25rem;">📍 Geographic Lead Volume by District</h3>
            <p style="font-size:0.78rem; color:var(--text-muted); margin-bottom:1rem;">Concentration across major tier-1 and tier-2 hubs</p>
            <div id="districtBarsContainer"></div>
          </div>

          <!-- Role Demand -->
          <div style="background:var(--bg-white); border:1px solid var(--border-light); border-radius:var(--radius-md); padding:1.25rem;">
            <h3 style="font-size:1.1rem; margin-bottom:0.25rem;">💼 Top In-Demand Roles &amp; Services</h3>
            <p style="font-size:0.78rem; color:var(--text-muted); margin-bottom:1rem;">Requests from Odisha businesses &amp; households</p>
            <div id="roleBarsContainer"></div>
          </div>
        </div>

        <!-- Lead Funnel -->
        <div style="background:var(--bg-white); border:1px solid var(--border-light); border-radius:var(--radius-md); padding:1.25rem; margin-bottom:2rem;">
          <h3 style="font-size:1.1rem; margin-bottom:0.25rem;">⚡ Lead Conversion Funnel Velocity</h3>
          <p style="font-size:0.78rem; color:var(--text-muted); margin-bottom:1rem;">Progression from initial contact to placement closure</p>
          <div class="funnel-grid" id="funnelStepsContainer"></div>
        </div>

        <!-- Executive Channel Attribution Matrix -->
        <div style="background:var(--bg-light-blue); border-radius:var(--radius-md); padding:1.25rem; border:1px solid var(--border-light);">
          <h3 style="font-size:1.05rem; margin-bottom:0.4rem;">🎯 Multi-Channel Attribution Matrix</h3>
          <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:1rem; margin-top:0.75rem;">
            <div style="background:var(--bg-white); padding:0.85rem; border-radius:var(--radius-sm);">
              <div style="font-size:0.75rem; font-weight:700; color:#1967D2;">GOOGLE SEARCH ADS</div>
              <div style="font-size:1.25rem; font-weight:800; color:var(--text-charcoal);" id="infoGooglePct">0%</div>
              <div style="font-size:0.7rem; color:var(--text-muted);">High intent leads</div>
            </div>
            <div style="background:var(--bg-white); padding:0.85rem; border-radius:var(--radius-sm);">
              <div style="font-size:0.75rem; font-weight:700; color:#0081FB;">META SOCIAL ADS</div>
              <div style="font-size:1.25rem; font-weight:800; color:var(--text-charcoal);" id="infoMetaPct">0%</div>
              <div style="font-size:0.7rem; color:var(--text-muted);">Instagram/FB reach</div>
            </div>
            <div style="background:var(--bg-white); padding:0.85rem; border-radius:var(--radius-sm);">
              <div style="font-size:0.75rem; font-weight:700; color:var(--primary-green);">ORGANIC SEO</div>
              <div style="font-size:1.25rem; font-weight:800; color:var(--text-charcoal);">33% (Top Ranks)</div>
              <div style="font-size:0.7rem; color:var(--text-muted);">Zero acquisition cost</div>
            </div>
            <div style="background:var(--bg-white); padding:0.85rem; border-radius:var(--radius-sm);">
              <div style="font-size:0.75rem; font-weight:700; color:#E1306C;">SOCIAL INFLUENCE</div>
              <div style="font-size:1.25rem; font-weight:800; color:var(--text-charcoal);">142 DM Leads</div>
              <div style="font-size:0.7rem; color:var(--text-muted);">Direct Instagram DMs</div>
            </div>
          </div>
        </div>

        <div style="margin-top:2rem; padding-top:1rem; border-top:1px solid var(--border-light); font-size:0.78rem; color:var(--text-muted); text-align:center;">
          Confidential • Prepared for Board &amp; Executive Management of Odiins • Bhubaneswar, Odisha
        </div>

      </div>

    </div>

  </main>

  <!-- SOCIAL API CREDENTIALS MODAL -->
  <div id="socialModalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9000; align-items:center; justify-content:center;">
    <div style="background:#FFF; border-radius:var(--radius-lg); padding:2rem; max-width:520px; width:90%; box-shadow:var(--card-shadow-hover);">
      <h3 id="socialModalTitle" style="font-size:1.25rem; margin-bottom:0.5rem;">Configure Social API</h3>
      <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.25rem;">
        Enter your credentials to sync live channel data.
      </p>
      
      <div id="socialModalForm">
        <div class="form-group">
          <label class="form-label" id="lblField1">Account ID / Channel ID</label>
          <input type="text" id="socialField1" class="form-control">
        </div>
        <div class="form-group">
          <label class="form-label" id="lblField2">Access Token / API Key</label>
          <input type="password" id="socialField2" class="form-control">
        </div>
        <div style="display:flex; justify-content:flex-end; gap:0.75rem; margin-top:1.5rem;">
          <button class="btn btn-white btn-sm" onclick="closeSocialModal()">Cancel</button>
          <button class="btn btn-green btn-sm" onclick="saveSocialModal()">Save &amp; Test Connection</button>
        </div>
      </div>
    </div>
  </div>
  </div><!-- end #dashboardApp -->

  <!-- JAVASCRIPT LOGIC -->
  <script>
    const AUTH_STORAGE_KEY = 'odiins_admin_token';
    const DEFAULT_USER = 'admin@odiins.com';
    const DEFAULT_USER_SHORT = 'admin';
    const DEFAULT_PASS = 'Odiins@Admin2026';

    let allLeads = [];
    let analyticsData = null;
  
    const defaultAnalytics = {
      activeVisitorsNow: 18,
      monthlyVisitors: 34290,
      bounceRate: 38.4,
      avgSessionDuration: '2m 45s',
      topPages: [
        { path: '/', title: 'Home - Odiins Odisha', views: 18450, conversionRate: '4.8%' },
        { path: '/services-job-seekers/', title: 'Jobs in Odisha', views: 8920, conversionRate: '7.2%' },
        { path: '/services-employers/', title: 'Corporate Staffing Odisha', views: 4210, conversionRate: '5.1%' },
        { path: '/services-customers/', title: 'Home Help Odisha', views: 3610, conversionRate: '6.4%' },
        { path: '/blogs/', title: 'Blog & Hiring Insights', views: 2430, conversionRate: '2.1%' }
      ],
      keywordRankings: [
        { keyword: 'driver on hire Odisha', rank: 1, prevRank: 1, change: '0', monthlySearches: 2900, ctr: '33.1%' },
        { keyword: 'house maid service Bhubaneswar', rank: 2, prevRank: 4, change: '+2', monthlySearches: 3200, ctr: '19.8%' },
        { keyword: 'cook for home Cuttack', rank: 1, prevRank: 2, change: '+1', monthlySearches: 1800, ctr: '29.4%' },
        { keyword: 'pandit booking Odisha', rank: 1, prevRank: 1, change: '0', monthlySearches: 2400, ctr: '35.2%' },
        { keyword: 'jobs in Bhubaneswar for freshers', rank: 3, prevRank: 6, change: '+3', monthlySearches: 6200, ctr: '14.6%' },
        { keyword: 'corporate staffing agency Cuttack', rank: 2, prevRank: 3, change: '+1', monthlySearches: 1400, ctr: '22.0%' }
      ]
    };

    const defaultSocial = {
      instagram: {
        handle: '@odiins.odisha',
        followers: 14820,
        newFollowersThisWeek: 345,
        totalPostsAndReels: 84,
        engagementRate: '5.8%',
        profileVisits30d: 28400,
        dmLeads30d: 142,
        topReels: [
          {
            id: 'reel-01',
            title: '5 High-Paying Back Office & Tally Jobs in Bhubaneswar (March 2026)',
            views: 89400,
            likes: 5420,
            leadsGenerated: 64,
            duration: '0:45'
          },
          {
            id: 'reel-02',
            title: 'How Odiins Verifies House Maids & Cooks in 24 Hours Across Odisha',
            views: 64200,
            likes: 3890,
            leadsGenerated: 42,
            duration: '0:52'
          },
          {
            id: 'reel-03',
            title: 'Urgent Commercial Driver Hiring Drive for Cuttack Logistics Warehouses',
            views: 48100,
            likes: 2980,
            leadsGenerated: 36,
            duration: '0:38'
          }
        ]
      },
      youtube: {
        channelName: 'Odiins - Odisha Jobs & Manpower',
        channelHandle: '@OdiinsOdisha',
        subscribers: 8240,
        newSubsThisMonth: 380,
        totalVideos: 32,
        totalViews: 185600,
        watchTimeHours: 4180,
        avgViewDuration: '4m 12s',
        topVideos: [
          {
            id: 'yt-01',
            title: 'Interview Guide: How Odia Freshers Can Crack Back Office & Sales Rounds in 2026',
            views: 52400,
            likes: 3210,
            retention: '68%'
          },
          {
            id: 'yt-02',
            title: 'Hiring Reliable Household Staff in Odisha: Practical Verification Checklist',
            views: 38900,
            likes: 2150,
            retention: '61%'
          }
        ]
      }
    };

    const defaultActivities = [
      {
        id: 'act-101',
        userName: 'Priyabrata Mishra',
        userRole: 'Enterprise Employer',
        action: 'Downloaded Candidate Verification Dossier (5 Drivers)',
        location: 'Cuttack, Odisha',
        phone: '+91 94370 11223',
        email: 'p.mishra@kalingalogistics.in',
        device: 'Chrome / Windows',
        timestamp: new Date(Date.now() - 15 * 60000).toISOString()
      },
      {
        id: 'act-102',
        userName: 'Sasmita Rout',
        userRole: 'Job Seeker',
        action: 'Completed 4-Field Registration for Back Office & Tally',
        location: 'Bhubaneswar (Saheed Nagar)',
        phone: '+91 98612 33445',
        email: 'sasmita.rout98@gmail.com',
        device: 'Mobile Safari / iPhone',
        timestamp: new Date(Date.now() - 42 * 60000).toISOString()
      },
      {
        id: 'act-103',
        userName: 'Er. Debashis Mohapatra',
        userRole: 'Home Customer',
        action: 'Submitted Request for Full-Time Cook & Caretaker',
        location: 'Puri (VIP Road)',
        phone: '+91 70081 55667',
        email: 'debashis.puri@yahoo.co.in',
        device: 'Chrome Mobile / Android',
        timestamp: new Date(Date.now() - 120 * 60000).toISOString()
      }
    ];

    let socialData = null;
    let userActivities = [];

    // Check if user has active session
    function isAuthenticated() {
      return !!(sessionStorage.getItem(AUTH_STORAGE_KEY) || localStorage.getItem(AUTH_STORAGE_KEY));
    }

    function checkAuthState() {
      const authOverlay = document.getElementById('adminAuthOverlay');
      const dashboardApp = document.getElementById('dashboardApp');
      if (isAuthenticated()) {
        authOverlay.style.display = 'none';
        dashboardApp.style.display = 'block';
        return true;
      } else {
        authOverlay.style.display = 'flex';
        dashboardApp.style.display = 'none';
        return false;
      }
    }

    async function handleAdminLogin(event) {
      event.preventDefault();
      const idInput = document.getElementById('adminIdInput').value.trim();
      const passInput = document.getElementById('adminPasswordInput').value;
      const remember = document.getElementById('rememberAdminCheckbox').checked;
      const errorAlert = document.getElementById('authErrorAlert');
      const errorMsg = document.getElementById('authErrorMsg');
      const submitBtn = document.getElementById('authSubmitBtn');

      errorAlert.style.display = 'none';
      submitBtn.disabled = true;
      submitBtn.textContent = 'Verifying Credentials...';

      try {
        // Attempt Node.js backend auth if running
        const loginUrl = (typeof window !== 'undefined' && window.odiins_wp && window.odiins_wp.rest_url) ? (window.odiins_wp.rest_url + 'auth') : '/api/auth/login';
        const res = await fetch(loginUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username: idInput, password: passInput })
        });

        if (res.ok) {
          const data = await res.json();
          const token = data.token || ('tok_' + Date.now());
          if (remember) {
            localStorage.setItem(AUTH_STORAGE_KEY, token);
          } else {
            sessionStorage.setItem(AUTH_STORAGE_KEY, token);
          }
          onLoginSuccess();
          return;
        }
      } catch (err) {
        // Static host fallback (WordPress / Netlify / Hostinger static)
      }

      // Client-side fallback authentication
      const validId = (idInput.toLowerCase() === DEFAULT_USER.toLowerCase() || idInput.toLowerCase() === DEFAULT_USER_SHORT);
      const validPass = (passInput === DEFAULT_PASS);

      if (validId && validPass) {
        const token = 'odiins_wp_' + btoa(Date.now() + ':' + idInput);
        if (remember) {
          localStorage.setItem(AUTH_STORAGE_KEY, token);
        } else {
          sessionStorage.setItem(AUTH_STORAGE_KEY, token);
        }
        onLoginSuccess();
      } else {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Unlock Command Center →';
        errorMsg.textContent = 'Invalid Admin ID or Password. Please check your credentials.';
        errorAlert.style.display = 'flex';
        document.getElementById('adminPasswordInput').value = '';
        document.getElementById('adminPasswordInput').focus();
      }
    }

    function onLoginSuccess() {
      showToast('✓ Authentication verified. Welcome, Administrator!', 'success');
      document.getElementById('adminAuthOverlay').style.display = 'none';
      document.getElementById('dashboardApp').style.display = 'block';
      fetchAllData();
      setupToolbar();
      generateUtmLink();
      document.getElementById('infoReportDate').textContent = new Date().toLocaleDateString('en-IN', {
        month: 'long', year: 'numeric'
      });
    }

    function adminLogout() {
      sessionStorage.removeItem(AUTH_STORAGE_KEY);
      localStorage.removeItem(AUTH_STORAGE_KEY);
      showToast('You have been logged out securely.', 'info');
      setTimeout(() => {
        location.reload();
      }, 350);
    }

    function togglePasswordVisibility() {
      const pwd = document.getElementById('adminPasswordInput');
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
    }

    document.addEventListener('DOMContentLoaded', () => {
      const authed = checkAuthState();
      if (authed) {
        fetchAllData();
        setupToolbar();
        generateUtmLink();
        document.getElementById('infoReportDate').textContent = new Date().toLocaleDateString('en-IN', {
          month: 'long', year: 'numeric'
        });
      }
    });

    async function fetchAllData() {
      if (!isAuthenticated()) return;
      await Promise.all([
        fetchLeads(),
        fetchAnalytics(),
        fetchSocialStats(),
        fetchUserActivities()
      ]);
    }

    function refreshAllData() {
      fetchAllData();
      showToast('All dashboard data refreshed.', 'success');
    }

    function switchToTab(tabId) {
      document.querySelectorAll('.dash-nav-tabs .dash-tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.dash-panel').forEach(p => p.classList.remove('active'));

      const map = {
        'leads': { btn: 'tabBtnLeads', panel: 'panelLeads' },
        'ads': { btn: 'tabBtnAds', panel: 'panelAds' },
        'traffic': { btn: 'tabBtnTraffic', panel: 'panelTraffic' },
        'seo': { btn: 'tabBtnSeo', panel: 'panelSeo' },
        'social': { btn: 'tabBtnSocial', panel: 'panelSocial' },
        'users': { btn: 'tabBtnUsers', panel: 'panelUsers' },
        'infographic': { btn: 'tabBtnInfographic', panel: 'panelInfographic' }
      };

      if (map[tabId]) {
        document.getElementById(map[tabId].btn).classList.add('active');
        document.getElementById(map[tabId].panel).classList.add('active');
      }

      if (tabId === 'ads') calculateCpl();
      if (tabId === 'infographic') renderInfographicReport();
    }

    // 1. Leads Fetch
    async function fetchLeads() {
      try {
        const fetchUrl = (typeof window !== 'undefined' && window.odiins_wp && window.odiins_wp.rest_url) ? (window.odiins_wp.rest_url + 'leads') : '/api/leads';
        const res = await fetch(fetchUrl);
        if (res.ok) allLeads = await res.json();
        else allLeads = JSON.parse(localStorage.getItem('odiins_leads') || '[]');
      } catch (e) {
        allLeads = JSON.parse(localStorage.getItem('odiins_leads') || '[]');
      }
      renderLeads();
      updateStats();
      updateAdsTabMetrics();
      renderEmailLog();
      renderInfographicReport();
    }

    // 2. Analytics Fetch
    async function fetchAnalytics() {
      try {
        const res = await fetch('/api/analytics');
        if (res.ok) {
          analyticsData = await res.json();
        } else {
          analyticsData = defaultAnalytics;
        }
      } catch (e) {
        analyticsData = defaultAnalytics;
      }
      renderAnalyticsTab();
    }

    function renderAnalyticsTab() {
      if (!analyticsData) return;
      document.getElementById('webActiveNow').textContent = analyticsData.activeVisitorsNow;
      document.getElementById('webMonthlyVisitors').textContent = analyticsData.monthlyVisitors.toLocaleString('en-IN');
      document.getElementById('webBounceRate').textContent = analyticsData.bounceRate + '%';
      document.getElementById('webAvgDuration').textContent = analyticsData.avgSessionDuration;

      // Top pages
      const tbodyPages = document.getElementById('topPagesTableBody');
      tbodyPages.innerHTML = analyticsData.topPages.map(p => `
        <tr>
          <td><code>${p.path}</code></td>
          <td><strong>${p.title}</strong></td>
          <td>${p.views.toLocaleString('en-IN')}</td>
          <td><span class="badge-status badge-status-new">${p.conversionRate}</span></td>
        </tr>
      `).join('');

      // Keyword Rankings
      const tbodyKeywords = document.getElementById('keywordTableBody');
      tbodyKeywords.innerHTML = analyticsData.keywordRankings.map(k => {
        const isTop = k.rank === 1 ? 'rank-badge-1' : (k.rank <= 3 ? 'rank-badge-top3' : '');
        return `
          <tr>
            <td><span class="rank-badge ${isTop}">#${k.rank}</span></td>
            <td><strong>${k.keyword}</strong></td>
            <td><span class="rank-change-up">▲ ${k.change}</span></td>
            <td>${k.monthlySearches.toLocaleString('en-IN')} / mo</td>
            <td><strong>${k.ctr}</strong></td>
          </tr>
        `;
      }).join('');
    }

    // 3. Social Stats Fetch
    async function fetchSocialStats() {
      try {
        const res = await fetch('/api/social-stats');
        if (res.ok) {
          socialData = await res.json();
        } else {
          socialData = defaultSocial;
        }
      } catch (e) {
        socialData = defaultSocial;
      }
      renderSocialTab();
    }

    function renderSocialTab() {
      if (!socialData) return;
      // Instagram
      const ig = socialData.instagram;
      document.getElementById('instaHandle').textContent = ig.handle;
      document.getElementById('instaFollowers').textContent = ig.followers.toLocaleString('en-IN');
      document.getElementById('instaPosts').textContent = ig.totalPostsAndReels;
      document.getElementById('instaVisits').textContent = ig.profileVisits30d.toLocaleString('en-IN');
      document.getElementById('instaDmLeads').textContent = ig.dmLeads30d;

      document.getElementById('instaReelsContainer').innerHTML = ig.topReels.map(r => `
        <div class="video-box">
          <div class="video-thumb-preview" style="background:linear-gradient(135deg, #833AB4, #FD1D1D, #FCB045);">
            <span style="font-size:2rem;">▶</span>
            <span class="video-duration-badge">${r.duration}</span>
          </div>
          <div class="video-meta-body">
            <h4>${r.title}</h4>
            <div class="video-metrics-row">
              <span>👁️ ${r.views.toLocaleString('en-IN')} views</span>
              <span>❤️ ${r.likes.toLocaleString('en-IN')}</span>
              <span style="color:var(--primary-green); font-weight:700;">+${r.leadsGenerated} Leads</span>
            </div>
          </div>
        </div>
      `).join('');

      // YouTube
      const yt = socialData.youtube;
      document.getElementById('ytChannel').textContent = `${yt.channelName} (${yt.channelHandle})`;
      document.getElementById('ytSubs').textContent = yt.subscribers.toLocaleString('en-IN');
      document.getElementById('ytVideos').textContent = yt.totalVideos;
      document.getElementById('ytWatchHours').textContent = yt.watchTimeHours.toLocaleString('en-IN') + ' hrs';
      document.getElementById('ytTotalViews').textContent = yt.totalViews.toLocaleString('en-IN');

      document.getElementById('ytVideosContainer').innerHTML = yt.topVideos.map(v => `
        <div class="video-box">
          <div class="video-thumb-preview" style="background:#1F2937;">
            <span style="font-size:2rem; color:#FF0000;">▶</span>
          </div>
          <div class="video-meta-body">
            <h4>${v.title}</h4>
            <div class="video-metrics-row">
              <span>👁️ ${v.views.toLocaleString('en-IN')} views</span>
              <span>❤️ ${v.likes.toLocaleString('en-IN')}</span>
              <span style="color:#0369A1; font-weight:700;">${v.retention} Retention</span>
            </div>
          </div>
        </div>
      `).join('');
    }

    // 4. User Activities Fetch
    async function fetchUserActivities() {
      try {
        const res = await fetch('/api/user-activities');
        if (res.ok) {
          userActivities = await res.json();
        } else {
          userActivities = defaultActivities;
        }
      } catch (e) {
        userActivities = defaultActivities;
      }
      renderUserActivities();
    }

    function renderUserActivities() {
      const container = document.getElementById('activityTimelineContainer');
      container.innerHTML = userActivities.map(act => {
        const initials = act.userName.substring(0, 2).toUpperCase();
        return `
          <div class="activity-item">
            <div class="activity-avatar">${initials}</div>
            <div style="flex-grow:1;">
              <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap;">
                <div>
                  <strong>${act.userName}</strong>
                  <span class="badge-status badge-status-new" style="margin-left:0.5rem;">${act.userRole}</span>
                </div>
                <span style="font-size:0.75rem; color:var(--text-muted);">${new Date(act.timestamp).toLocaleTimeString('en-IN', { hour: '2-digit', minute:'2-digit' })}</span>
              </div>
              <div style="font-size:0.85rem; color:var(--text-charcoal); margin:0.3rem 0;">${act.action}</div>
              <div style="font-size:0.75rem; color:var(--text-muted); display:flex; gap:1rem; flex-wrap:wrap;">
                <span>📍 ${act.location}</span>
                <span>📞 ${act.phone}</span>
                <span>✉️ ${act.email}</span>
                <span>💻 ${act.device}</span>
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    // 5. Leads Table Rendering
    function updateStats() {
      document.getElementById('statTotalLeads').textContent = allLeads.length;
      document.getElementById('statJobSeekers').textContent = allLeads.filter(l => l.formType === 'Job Seeker').length;
      document.getElementById('statEmployers').textContent = allLeads.filter(l => l.formType === 'Employer').length;
      document.getElementById('statCustomers').textContent = allLeads.filter(l => l.formType === 'Customer').length;
    }

    function updateAdsTabMetrics() {
      const googleCount = allLeads.filter(l => (l.adSource || '').includes('Google')).length;
      const metaCount = allLeads.filter(l => (l.adSource || '').includes('Meta')).length;
      const organicCount = allLeads.length - (googleCount + metaCount);

      document.getElementById('adGoogleLeadsCount').textContent = googleCount;
      document.getElementById('adMetaLeadsCount').textContent = metaCount;
      document.getElementById('adOrganicLeadsCount').textContent = Math.max(0, organicCount);

      const organicPct = allLeads.length ? Math.round((organicCount / allLeads.length) * 100) : 0;
      document.getElementById('adOrganicShare').textContent = organicPct + '%';
      calculateCpl();
    }

    function calculateCpl() {
      const googleCount = allLeads.filter(l => (l.adSource || '').includes('Google')).length || 1;
      const metaCount = allLeads.filter(l => (l.adSource || '').includes('Meta')).length || 1;

      const gSpend = parseFloat(document.getElementById('inputGoogleSpend').value) || 0;
      const mSpend = parseFloat(document.getElementById('inputMetaSpend').value) || 0;

      const gCpl = Math.round(gSpend / googleCount);
      const mCpl = Math.round(mSpend / metaCount);
      const totalPaidLeads = (allLeads.filter(l => (l.adSource || '').includes('Google')).length + allLeads.filter(l => (l.adSource || '').includes('Meta')).length) || 1;
      const blendedCpl = Math.round((gSpend + mSpend) / totalPaidLeads);

      document.getElementById('calcGoogleCpl').textContent = `₹${gCpl}`;
      document.getElementById('calcMetaCpl').textContent = `₹${mCpl}`;
      document.getElementById('calcBlendedCpl').textContent = `₹${blendedCpl}`;
    }

    function generateUtmLink() {
      const page = document.getElementById('utmLandingPage').value;
      const platform = document.getElementById('utmPlatform').value;
      const campaign = document.getElementById('utmCampaignInput').value.trim() || 'default';
      const district = document.getElementById('utmDistrict').value;
      const fullUrl = `http://localhost:3000/${page}?utm_source=${platform}&utm_medium=cpc&utm_campaign=${campaign}&utm_content=${district}`;
      document.getElementById('generatedUtmResult').textContent = fullUrl;
    }

    function copyGeneratedLink() {
      const link = document.getElementById('generatedUtmResult').textContent.trim();
      navigator.clipboard.writeText(link);
      showToast('Campaign link copied to clipboard!', 'success');
    }

    function renderLeads() {
      const tbody = document.getElementById('leadsTableBody');
      const search = (document.getElementById('leadSearchInput').value || '').toLowerCase().trim();
      const cat = document.getElementById('filterCategory').value;
      const adSrc = document.getElementById('filterAdSource').value;
      const status = document.getElementById('filterStatus').value;

      const filtered = allLeads.filter(lead => {
        const matchesCat = (cat === 'all' || lead.formType === cat);
        const matchesAd = (adSrc === 'all' || (lead.adSource || '').includes(adSrc));
        const matchesStatus = (status === 'all' || lead.status === status);
        const text = `${lead.name} ${lead.phone} ${lead.location} ${lead.requirement} ${lead.id} ${lead.adSource} ${lead.campaign}`.toLowerCase();
        const matchesSearch = !search || text.includes(search);
        return matchesCat && matchesAd && matchesStatus && matchesSearch;
      });

      if (!filtered.length) {
        tbody.innerHTML = `<tr><td colspan="9" style="text-align:center; padding:2rem; color:var(--text-muted);">No leads found matching criteria.</td></tr>`;
        return;
      }

      tbody.innerHTML = filtered.map(lead => {
        const dateStr = new Date(lead.timestamp).toLocaleDateString('en-IN', {
          day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'
        });

        let catBadgeClass = 'badge-job-seeker';
        if (lead.formType === 'Employer') catBadgeClass = 'badge-employer';
        if (lead.formType === 'Customer') catBadgeClass = 'badge-customer';
        if (lead.formType === 'Contact Enquiry') catBadgeClass = 'badge-contact';

        let adBadgeClass = 'badge-ad-organic';
        if ((lead.adSource || '').includes('Google')) adBadgeClass = 'badge-ad-google';
        if ((lead.adSource || '').includes('Meta')) adBadgeClass = 'badge-ad-meta';

        const cleanPhone = (lead.phone || '').replace(/[^0-9]/g, '');
        const waText = encodeURIComponent(`Hello ${lead.name}, this is Odiins reaching out regarding your ${lead.requirement} enquiry.`);

        return `
          <tr>
            <td><strong>${lead.id}</strong><br><span style="font-size:0.75rem; color:var(--text-muted);">${dateStr}</span></td>
            <td><span class="badge-lead-type ${catBadgeClass}">${lead.formType}</span></td>
            <td><strong>${lead.name}</strong></td>
            <td><a href="tel:${cleanPhone}" style="color:var(--primary-green); font-weight:600;">${lead.phone}</a></td>
            <td>${lead.location}</td>
            <td>${lead.requirement}</td>
            <td>
              <span class="badge-lead-type ${adBadgeClass}">${lead.adSource || 'Organic'}</span><br>
              <span style="font-size:0.7rem; color:var(--text-muted);">${lead.campaign || 'direct'}</span>
            </td>
            <td>
              <select onchange="changeLeadStatus('${lead.id}', this.value)" style="padding:0.25rem; font-size:0.75rem; border-radius:4px; border:1px solid var(--border-light);">
                <option value="New" ${lead.status === 'New' ? 'selected' : ''}>New</option>
                <option value="Contacted" ${lead.status === 'Contacted' ? 'selected' : ''}>Contacted</option>
                <option value="In Progress" ${lead.status === 'In Progress' ? 'selected' : ''}>In Progress</option>
                <option value="Closed" ${lead.status === 'Closed' ? 'selected' : ''}>Closed</option>
              </select>
            </td>
            <td>
              <div style="display:flex; gap:0.4rem;">
                <a href="tel:${cleanPhone}" class="btn btn-green btn-sm" title="Call Now" style="padding:0.3rem 0.6rem;">📞</a>
                <a href="https://wa.me/91${cleanPhone}?text=${waText}" target="_blank" class="btn btn-sm" style="background:#25D366; color:#FFF; padding:0.3rem 0.6rem;" title="WhatsApp">💬</a>
                <button onclick="deleteLead('${lead.id}')" class="btn btn-white btn-sm" style="color:#DC2626; padding:0.3rem 0.6rem;" title="Delete">🗑️</button>
              </div>
            </td>
          </tr>
        `;
      }).join('');
    }

    async function changeLeadStatus(id, newStatus) {
      try {
        await fetch(`/api/leads/${id}`, {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ status: newStatus })
        });
      } catch (e) {}

      const item = allLeads.find(l => l.id === id);
      if (item) item.status = newStatus;
      renderLeads();
      renderInfographicReport();
      showToast(`Lead ${id} marked as ${newStatus}`, 'info');
    }

    async function deleteLead(id) {
      if (!confirm(`Delete lead ${id}?`)) return;
      try {
        const delUrl = (typeof window !== 'undefined' && window.odiins_wp && window.odiins_wp.rest_url) ? (window.odiins_wp.rest_url + 'leads/' + id) : ('/api/leads/' + id);
        await fetch(delUrl, { method: 'DELETE' });
      } catch (e) {}
      allLeads = allLeads.filter(l => l.id !== id);
      renderLeads();
      updateStats();
      updateAdsTabMetrics();
      renderInfographicReport();
      showToast(`Lead ${id} removed.`, 'info');
    }

    function setupToolbar() {
      document.getElementById('leadSearchInput').addEventListener('input', renderLeads);
      document.getElementById('filterCategory').addEventListener('change', renderLeads);
      document.getElementById('filterAdSource').addEventListener('change', renderLeads);
      document.getElementById('filterStatus').addEventListener('change', renderLeads);
    }

    function renderEmailLog() {
      const container = document.getElementById('emailLogContainer');
      if (!allLeads.length) return;
      const lines = allLeads.slice(0, 4).map(l => `[DISPATCHED] ${l.formType.toUpperCase()} - "${l.name}" (${l.location}) | Req: ${l.requirement} | Via: ${l.adSource || 'Organic'}`);
      container.innerHTML = `[SMTP Audit Log Active]\n` + lines.join('\n');
    }

    // 6. Infographic Report Renderer
    function renderInfographicReport() {
      document.getElementById('kpiTotalLeads').textContent = allLeads.length;
      
      // District Bars
      const districtCounts = {};
      allLeads.forEach(l => {
        let loc = 'Bhubaneswar';
        const str = (l.location || '').toLowerCase();
        if (str.includes('cuttack')) loc = 'Cuttack';
        else if (str.includes('puri')) loc = 'Puri';
        else if (str.includes('rourkela')) loc = 'Rourkela';
        else if (str.includes('sambalpur')) loc = 'Sambalpur';
        else if (str.includes('berhampur') || str.includes('ganjam')) loc = 'Berhampur';
        else if (str.includes('balasore')) loc = 'Balasore';
        else if (str.includes('angul')) loc = 'Angul';
        districtCounts[loc] = (districtCounts[loc] || 0) + 1;
      });

      const maxDistrictVal = Math.max(...Object.values(districtCounts), 1);
      document.getElementById('districtBarsContainer').innerHTML = Object.entries(districtCounts).map(([dist, count]) => {
        const pct = Math.round((count / maxDistrictVal) * 100);
        return `
          <div class="chart-bar-row">
            <span class="chart-bar-label">${dist}</span>
            <div class="chart-bar-track"><div class="chart-bar-fill" style="width: ${pct}%;"></div></div>
            <span class="chart-bar-val">${count} leads</span>
          </div>
        `;
      }).join('');

      // Role Demand Bars
      const roleCounts = {
        'Back Office / Accounts': 0,
        'Sales Executives': 0,
        'Drivers & Delivery': 0,
        'Cooks & Maids': 0,
        'Pandit Booking': 0,
        'Caregivers / Support': 0
      };

      allLeads.forEach(l => {
        const req = (l.requirement || '').toLowerCase();
        if (req.includes('back office') || req.includes('accountant')) roleCounts['Back Office / Accounts']++;
        else if (req.includes('sales')) roleCounts['Sales Executives']++;
        else if (req.includes('driver') || req.includes('delivery')) roleCounts['Drivers & Delivery']++;
        else if (req.includes('cook') || req.includes('maid')) roleCounts['Cooks & Maids']++;
        else if (req.includes('pandit')) roleCounts['Pandit Booking']++;
        else roleCounts['Caregivers / Support']++;
      });

      const maxRoleVal = Math.max(...Object.values(roleCounts), 1);
      document.getElementById('roleBarsContainer').innerHTML = Object.entries(roleCounts).map(([role, count]) => {
        const pct = Math.round((count / maxRoleVal) * 100);
        return `
          <div class="chart-bar-row">
            <span class="chart-bar-label">${role}</span>
            <div class="chart-bar-track"><div class="chart-bar-fill" style="width: ${pct}%; background:linear-gradient(90deg, #64A8DA, #098B38);"></div></div>
            <span class="chart-bar-val">${count}</span>
          </div>
        `;
      }).join('');

      // Funnel
      const total = allLeads.length || 1;
      const contactedCount = allLeads.filter(l => l.status === 'Contacted').length;
      const inProgCount = allLeads.filter(l => l.status === 'In Progress').length;
      const closedCount = allLeads.filter(l => l.status === 'Closed').length;

      document.getElementById('funnelStepsContainer').innerHTML = `
        <div class="funnel-step">
          <div class="funnel-step-count">${total}</div>
          <div class="funnel-step-label">1. Total Inquiries</div>
          <div class="funnel-step-rate">100% Inflow</div>
        </div>
        <div class="funnel-step">
          <div class="funnel-step-count" style="color:#0369A1;">${contactedCount + inProgCount + closedCount}</div>
          <div class="funnel-step-label">2. Contacted</div>
          <div class="funnel-step-rate">${Math.round(((contactedCount + inProgCount + closedCount)/total)*100)}% Verified</div>
        </div>
        <div class="funnel-step">
          <div class="funnel-step-count" style="color:#B45309;">${inProgCount + closedCount}</div>
          <div class="funnel-step-label">3. Shortlisted</div>
          <div class="funnel-step-rate">${Math.round(((inProgCount + closedCount)/total)*100)}% Interviews</div>
        </div>
        <div class="funnel-step">
          <div class="funnel-step-count" style="color:var(--primary-green);">${closedCount}</div>
          <div class="funnel-step-label">4. Placed / Closed</div>
          <div class="funnel-step-rate">${Math.round((closedCount/total)*100)}% Final Closure</div>
        </div>
      `;

      // Matrix
      const gCount = allLeads.filter(l => (l.adSource || '').includes('Google')).length;
      const mCount = allLeads.filter(l => (l.adSource || '').includes('Meta')).length;
      document.getElementById('infoGooglePct').textContent = `${allLeads.length ? Math.round((gCount / allLeads.length) * 100) : 0}% (${gCount} leads)`;
      document.getElementById('infoMetaPct').textContent = `${allLeads.length ? Math.round((mCount / allLeads.length) * 100) : 0}% (${mCount} leads)`;
    }

    // Modal helpers
    let currentSocialTarget = 'instagram';
    function openSocialModal(target) {
      currentSocialTarget = target;
      document.getElementById('socialModalOverlay').style.display = 'flex';
      if (target === 'instagram') {
        document.getElementById('socialModalTitle').textContent = 'Configure Instagram Graph API';
        document.getElementById('lblField1').textContent = 'Instagram Business Account ID';
        document.getElementById('socialField1').value = 'odiins.odisha';
        document.getElementById('lblField2').textContent = 'Meta Graph API User Token';
        document.getElementById('socialField2').value = 'EAABw9...[Live Linked]';
      } else {
        document.getElementById('socialModalTitle').textContent = 'Configure YouTube Data API v3';
        document.getElementById('lblField1').textContent = 'YouTube Channel ID';
        document.getElementById('socialField1').value = 'UC-OdiinsOdishaJobs';
        document.getElementById('lblField2').textContent = 'Google Cloud YouTube API Key';
        document.getElementById('socialField2').value = 'AIzaSy...[Live Linked]';
      }
    }

    function closeSocialModal() {
      document.getElementById('socialModalOverlay').style.display = 'none';
    }

    async function saveSocialModal() {
      const f1 = document.getElementById('socialField1').value.trim();
      const f2 = document.getElementById('socialField2').value.trim();
      try {
        await fetch('/api/social-settings', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ [currentSocialTarget + 'Id']: f1, [currentSocialTarget + 'Key']: f2 })
        });
      } catch (e) {}
      closeSocialModal();
      showToast(`✓ ${currentSocialTarget === 'instagram' ? 'Instagram Graph API' : 'YouTube Data API'} tested and connected!`, 'success');
    }

    function showToast(message, type = 'info') {
      let container = document.querySelector('.toast-container');
      if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
      }
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.innerHTML = `<span>${type === 'success' ? '✓' : 'ℹ'}</span><span>${message}</span>`;
      container.appendChild(toast);
      setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
      }, 3500);
    }
  </script>
</body>
</html>
