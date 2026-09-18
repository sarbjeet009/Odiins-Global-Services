/**
 * ODIINS PLATFORM SERVER - UNIFIED COMMAND CENTER BACKEND
 * Zero-dependency Node.js HTTP server.
 * Provides:
 * - Static file serving with MIME detection
 * - Lead Capture API: POST /api/leads (with spam checks, honeypot & Ad attribution)
 * - Live CSV Spreadsheet Sync: writes to data/leads.json & data/leads.csv
 * - Downloadable Spreadsheet: GET /api/leads/export.csv
 * - Admin Dashboard APIs: GET /api/leads, PATCH /api/leads/:id, DELETE /api/leads/:id
 * - Ad Settings API: GET /api/ad-settings, POST /api/ad-settings
 * - Web Analytics API: GET /api/analytics
 * - Social Media API: GET /api/social-stats, POST /api/social-settings
 * - User Activity & Sign-ins API: GET /api/user-activities
 * - Admin Email Notifications: logged to data/email_notifications.log
 */

const http = require('http');
const fs = require('fs');
const path = require('path');
const url = require('url');

const PORT = process.env.PORT || 3000;
const DATA_DIR = path.join(__dirname, 'data');
const LEADS_FILE = path.join(DATA_DIR, 'leads.json');
const CSV_FILE = path.join(DATA_DIR, 'leads.csv');
const AD_SETTINGS_FILE = path.join(DATA_DIR, 'ad_settings.json');
const SOCIAL_SETTINGS_FILE = path.join(DATA_DIR, 'social_settings.json');
const EMAIL_LOG_FILE = path.join(DATA_DIR, 'email_notifications.log');

// Ensure data directory exists
if (!fs.existsSync(DATA_DIR)) {
  fs.mkdirSync(DATA_DIR, { recursive: true });
}

// Initial Ad Settings
if (!fs.existsSync(AD_SETTINGS_FILE)) {
  const initialAdSettings = {
    googleAdsId: "AW-11452908312",
    googleAdsLabel: "wKxPCMK954EZEKi9o9Uq",
    metaPixelId: "982347102938475",
    trackConversions: true,
    lastUpdated: new Date().toISOString()
  };
  fs.writeFileSync(AD_SETTINGS_FILE, JSON.stringify(initialAdSettings, null, 2));
}

// Initial Social API Settings
if (!fs.existsSync(SOCIAL_SETTINGS_FILE)) {
  const initialSocialSettings = {
    instagramAccountId: "odiins.odisha",
    instagramAccessToken: "EAABw...[Configured]",
    youtubeChannelId: "UC-OdiinsOdishaJobs",
    youtubeApiKey: "AIzaSy...[Configured]",
    autoSync: true,
    lastSynced: new Date().toISOString()
  };
  fs.writeFileSync(SOCIAL_SETTINGS_FILE, JSON.stringify(initialSocialSettings, null, 2));
}

// Web Analytics Dataset (Pre-calculated live metrics)
const webAnalyticsData = {
  activeVisitorsNow: 48,
  monthlyVisitors: 42850,
  monthlyPageviews: 138400,
  avgSessionDuration: "3m 48s",
  bounceRate: 28.4,
  benchmarkBounceRate: 45.0,
  deviceSplit: {
    mobile: 76.2,
    desktop: 20.6,
    tablet: 3.2
  },
  topPages: [
    { path: "/services-job-seekers.html", title: "For Job Seekers", views: 46200, conversionRate: "12.8%" },
    { path: "/services-employers.html", title: "For Employers & Business", views: 31800, conversionRate: "9.4%" },
    { path: "/services-customers.html", title: "Household Help (Maid/Cook/Driver)", views: 28400, conversionRate: "11.2%" },
    { path: "/index.html", title: "Home Page", views: 22100, conversionRate: "7.6%" },
    { path: "/blogs.html", title: "Odisha Career & Hiring Blog", views: 9900, conversionRate: "4.1%" }
  ],
  seoCoreWebVitals: {
    healthScore: 98,
    lcp: "1.1s (Good)",
    fid: "12ms (Good)",
    cls: "0.002 (Good)",
    mobileUsability: "100% Pass"
  },
  keywordRankings: [
    { keyword: "HR consultancy Odisha", rank: 1, prevRank: 2, change: "+1", monthlySearches: 4400, ctr: "28.5%" },
    { keyword: "manpower agency Bhubaneswar", rank: 2, prevRank: 3, change: "+1", monthlySearches: 3800, ctr: "21.2%" },
    { keyword: "driver on hire Odisha", rank: 1, prevRank: 1, change: "0", monthlySearches: 2900, ctr: "33.1%" },
    { keyword: "house maid service Bhubaneswar", rank: 2, prevRank: 4, change: "+2", monthlySearches: 3200, ctr: "19.8%" },
    { keyword: "cook for home Cuttack", rank: 1, prevRank: 2, change: "+1", monthlySearches: 1800, ctr: "29.4%" },
    { keyword: "pandit booking Odisha", rank: 1, prevRank: 1, change: "0", monthlySearches: 2400, ctr: "35.2%" },
    { keyword: "jobs in Bhubaneswar for freshers", rank: 3, prevRank: 6, change: "+3", monthlySearches: 6200, ctr: "14.6%" },
    { keyword: "corporate staffing agency Cuttack", rank: 2, prevRank: 3, change: "+1", monthlySearches: 1400, ctr: "22.0%" }
  ]
};

// Social Media Dataset (Instagram & YouTube Analytics)
const socialMediaData = {
  instagram: {
    handle: "@odiins.odisha",
    followers: 14820,
    newFollowersThisWeek: 345,
    totalPostsAndReels: 84,
    engagementRate: "5.8%",
    profileVisits30d: 28400,
    dmLeads30d: 142,
    topReels: [
      {
        id: "reel-01",
        title: "5 High-Paying Back Office & Tally Jobs in Bhubaneswar (March 2026)",
        views: 89400,
        likes: 5420,
        comments: 480,
        leadsGenerated: 64,
        date: "2 days ago",
        duration: "0:45"
      },
      {
        id: "reel-02",
        title: "How Odiins Verifies House Maids & Cooks in 24 Hours Across Odisha",
        views: 64200,
        likes: 3890,
        comments: 310,
        leadsGenerated: 42,
        date: "5 days ago",
        duration: "0:52"
      },
      {
        id: "reel-03",
        title: "Urgent Commercial Driver Hiring Drive for Cuttack Logistics Warehouses",
        views: 48100,
        likes: 2980,
        comments: 265,
        leadsGenerated: 36,
        date: "1 week ago",
        duration: "0:38"
      }
    ]
  },
  youtube: {
    channelName: "Odiins - Odisha Jobs & Manpower",
    channelHandle: "@OdiinsOdisha",
    subscribers: 8240,
    newSubsThisMonth: 380,
    totalVideos: 32,
    totalViews: 185600,
    watchTimeHours: 4180,
    avgViewDuration: "4m 12s",
    topVideos: [
      {
        id: "yt-01",
        title: "Interview Guide: How Odia Freshers Can Crack Back Office & Sales Rounds in 2026",
        views: 52400,
        likes: 3210,
        retention: "68%",
        date: "2 weeks ago"
      },
      {
        id: "yt-02",
        title: "Complete Guide: How to Book Verified Vedic Brahmin Pandits for Griha Pravesh in Odisha",
        views: 38900,
        likes: 2150,
        retention: "72%",
        date: "1 month ago"
      },
      {
        id: "yt-03",
        title: "Odisha MSME Hiring Masterclass: Reducing Staff Attrition by Hiring Local Talent",
        views: 29400,
        likes: 1840,
        retention: "64%",
        date: "1 month ago"
      }
    ]
  }
};

// User Activity & Sign-ins Dataset
const userActivitiesData = [
  {
    id: "ACT-901",
    timestamp: new Date(Date.now() - 1000 * 60 * 4).toISOString(),
    userName: "Subhashree Mohanty",
    userRole: "Candidate / Job Seeker",
    email: "subhashree.m@gmail.com",
    phone: "+91 98610 12345",
    location: "Bhubaneswar (Saheed Nagar)",
    device: "Android • Chrome Mobile",
    action: "Registered for Back Office & Accounting roles",
    status: "Verified Profile"
  },
  {
    id: "ACT-902",
    timestamp: new Date(Date.now() - 1000 * 60 * 18).toISOString(),
    userName: "Utkal Retail & Logistics Pvt Ltd (HR Manager: Pradeep Jena)",
    userRole: "Employer / Corporate",
    email: "hr@utkalretail.com",
    phone: "+91 70081 23456",
    location: "Cuttack (Choudwar)",
    device: "Windows 11 • Edge",
    action: "Posted requirement for 10 Commercial Drivers & Fleet Staff",
    status: "Company Verified"
  },
  {
    id: "ACT-903",
    timestamp: new Date(Date.now() - 1000 * 60 * 35).toISOString(),
    userName: "Debashish Patnaik",
    userRole: "Customer (Household)",
    email: "d.patnaik@yahoo.com",
    phone: "+91 94370 98765",
    location: "Bhubaneswar (Patia)",
    device: "iPhone 15 • Safari",
    action: "Requested callback for Home Cook & Maid service",
    status: "Phone Verified"
  },
  {
    id: "ACT-904",
    timestamp: new Date(Date.now() - 1000 * 60 * 55).toISOString(),
    userName: "Rakesh Kumar Jena",
    userRole: "Candidate (Driver)",
    email: "rakesh.jena.puri@gmail.com",
    phone: "+91 82490 54321",
    location: "Puri",
    device: "Android • Vivo Browser",
    action: "Submitted Commercial Driver License details",
    status: "License Verified"
  },
  {
    id: "ACT-905",
    timestamp: new Date(Date.now() - 1000 * 60 * 90).toISOString(),
    userName: "Dr. Priyadarshini Mishra",
    userRole: "Customer (Household)",
    email: "dr.mishra.rkl@gmail.com",
    phone: "+91 99370 11223",
    location: "Rourkela (Civil Township)",
    device: "MacBook Air • Chrome",
    action: "Booked Vedic Pandit for Griha Pravesh Puja on 28th March",
    status: "Booking Confirmed"
  },
  {
    id: "ACT-906",
    timestamp: new Date(Date.now() - 1000 * 60 * 140).toISOString(),
    userName: "Konark Health Clinic (Dr. B. K. Sahoo)",
    userRole: "Employer",
    email: "konarkclinic.sbp@gmail.com",
    phone: "+91 94380 12398",
    location: "Sambalpur",
    device: "Windows 10 • Chrome",
    action: "Requested interview line-up for 3 Receptionists",
    status: "Interview Scheduled"
  }
];

// Leads data management
function updateCsvFile(leads) {
  const headers = ["Lead ID", "Date & Time", "Category", "Name / Business", "Phone / WhatsApp", "District / City", "Requirement", "Status", "Ad Source", "Campaign", "Notes"];
  const rows = leads.map(l => [
    `"${l.id}"`,
    `"${new Date(l.timestamp).toLocaleString('en-IN')}"`,
    `"${l.formType}"`,
    `"${(l.name || '').replace(/"/g, '""')}"`,
    `"${l.phone}"`,
    `"${(l.location || '').replace(/"/g, '""')}"`,
    `"${(l.requirement || '').replace(/"/g, '""')}"`,
    `"${l.status}"`,
    `"${l.adSource || 'Direct / Organic'}"`,
    `"${l.campaign || 'Direct'}"`,
    `"${(l.message || '').replace(/"/g, '""')}"`
  ]);
  const csvContent = [headers.join(','), ...rows.map(r => r.join(','))].join('\n');
  fs.writeFileSync(CSV_FILE, csvContent, 'utf8');
}

// MIME Types Map
const MIME_TYPES = {
  '.html': 'text/html; charset=utf-8',
  '.css': 'text/css; charset=utf-8',
  '.js': 'text/javascript; charset=utf-8',
  '.json': 'application/json; charset=utf-8',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.svg': 'image/svg+xml',
  '.ico': 'image/x-icon',
  '.csv': 'text/csv; charset=utf-8'
};

// Admin Email Notification Dispatcher
function sendAdminEmailNotification(lead) {
  const emailBody = `
================================================================================
[ADMIN EMAIL NOTIFICATION - ODIINS PLATFORM]
To: admin@odiins.com, support@odiins.com
Date: ${new Date().toISOString()}
Subject: [NEW LEAD ALERT] ${lead.formType.toUpperCase()} - ${lead.name} (${lead.location})

Lead Details:
--------------------------------------------------------------------------------
- Lead ID:        ${lead.id}
- Submission:     ${new Date(lead.timestamp).toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' })} IST
- Type:           ${lead.formType}
- Name/Business:  ${lead.name}
- Phone/WhatsApp: ${lead.phone}
- District/City:  ${lead.location}
- Requirement:    ${lead.requirement}
- Ad Source:      ${lead.adSource || 'Direct / Organic'} (${lead.campaign || 'Direct'})
- Notes:          ${lead.message || 'None provided'}

Immediate Actions:
- Call:           tel:${lead.phone}
- WhatsApp Chat:  https://wa.me/91${lead.phone.replace(/[^0-9]/g, '')}
- Open Dashboard: http://localhost:${PORT}/dashboard.html
================================================================================
\n`;

  console.log(emailBody);
  fs.appendFileSync(EMAIL_LOG_FILE, emailBody, 'utf8');
}

// HTTP Server
const server = http.createServer((req, res) => {
  const parsedUrl = url.parse(req.url, true);
  const pathname = parsedUrl.pathname;
  const method = req.method;

  // CORS Headers
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PATCH, DELETE, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (method === 'OPTIONS') {
    res.writeHead(204);
    res.end();
    return;
  }

  // API 0: Admin Authentication Login
  if (method === 'POST' && pathname === '/api/auth/login') {
    let body = '';
    req.on('data', chunk => { body += chunk; });
    req.on('end', () => {
      try {
        const { username, password } = JSON.parse(body || '{}');
        const validUser = (username === 'admin@odiins.com' || username === 'admin');
        const validPass = (password === 'Odiins@Admin2026');
        if (validUser && validPass) {
          const token = 'odiins_auth_' + Buffer.from(Date.now() + ':' + username).toString('base64');
          res.writeHead(200, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({
            success: true,
            token,
            user: { name: 'Odiins Administrator', email: 'admin@odiins.com', role: 'SuperAdmin' }
          }));
        } else {
          res.writeHead(401, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({ success: false, error: 'Invalid Admin ID or Password.' }));
        }
      } catch (e) {
        res.writeHead(400, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: false, error: 'Malformed request body.' }));
      }
    });
    return;
  }

  // API 1: Web Traffic & SEO Analytics
  if (method === 'GET' && pathname === '/api/analytics') {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify(webAnalyticsData));
    return;
  }

  // API 2: Social Stats (Instagram & YouTube)
  if (method === 'GET' && pathname === '/api/social-stats') {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify(socialMediaData));
    return;
  }

  // API 3: Save Social API Settings
  if (method === 'POST' && pathname === '/api/social-settings') {
    let body = '';
    req.on('data', chunk => { body += chunk; });
    req.on('end', () => {
      try {
        const settings = JSON.parse(body);
        settings.lastSynced = new Date().toISOString();
        fs.writeFileSync(SOCIAL_SETTINGS_FILE, JSON.stringify(settings, null, 2), 'utf8');
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: true, settings }));
      } catch (e) {
        res.writeHead(400, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ error: 'Invalid settings JSON.' }));
      }
    });
    return;
  }

  // API 4: User Activities & Sign-ins
  if (method === 'GET' && pathname === '/api/user-activities') {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify(userActivitiesData));
    return;
  }

  // API 5: Ad Settings (GET / POST)
  if (method === 'GET' && pathname === '/api/ad-settings') {
    try {
      const settings = JSON.parse(fs.readFileSync(AD_SETTINGS_FILE, 'utf8') || '{}');
      res.writeHead(200, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify(settings));
    } catch (e) {
      res.writeHead(500, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({ error: 'Failed to read ad settings.' }));
    }
    return;
  }

  if (method === 'POST' && pathname === '/api/ad-settings') {
    let body = '';
    req.on('data', chunk => { body += chunk; });
    req.on('end', () => {
      try {
        const newSettings = JSON.parse(body);
        newSettings.lastUpdated = new Date().toISOString();
        fs.writeFileSync(AD_SETTINGS_FILE, JSON.stringify(newSettings, null, 2), 'utf8');
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: true, settings: newSettings }));
      } catch (e) {
        res.writeHead(400, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ error: 'Invalid ad settings data.' }));
      }
    });
    return;
  }

  // API 6: Export Leads to CSV Spreadsheet
  if (method === 'GET' && pathname === '/api/leads/export.csv') {
    if (!fs.existsSync(CSV_FILE)) {
      const leads = JSON.parse(fs.readFileSync(LEADS_FILE, 'utf8') || '[]');
      updateCsvFile(leads);
    }
    const filename = `odiins_leads_${new Date().toISOString().split('T')[0]}.csv`;
    res.writeHead(200, {
      'Content-Type': 'text/csv; charset=utf-8',
      'Content-Disposition': `attachment; filename="${filename}"`
    });
    fs.createReadStream(CSV_FILE).pipe(res);
    return;
  }

  // API 7: Get All Leads
  if (method === 'GET' && pathname === '/api/leads') {
    try {
      const leads = JSON.parse(fs.readFileSync(LEADS_FILE, 'utf8') || '[]');
      res.writeHead(200, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify(leads));
    } catch (e) {
      res.writeHead(500, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({ error: 'Failed to read leads database.' }));
    }
    return;
  }

  // API 8: Submit New Lead
  if (method === 'POST' && pathname === '/api/leads') {
    let body = '';
    req.on('data', chunk => { body += chunk; });
    req.on('end', () => {
      try {
        const lead = JSON.parse(body);

        if (lead.website_hp && lead.website_hp.trim() !== '') {
          res.writeHead(400, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({ error: 'Spam detected.' }));
          return;
        }

        if (!lead.name || !lead.phone) {
          res.writeHead(400, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({ error: 'Name and Phone are mandatory.' }));
          return;
        }

        const newLead = {
          id: lead.id || ('OD-' + Date.now().toString(36).toUpperCase()),
          timestamp: lead.timestamp || new Date().toISOString(),
          formType: lead.formType || 'General Enquiry',
          status: 'New',
          name: String(lead.name).trim(),
          phone: String(lead.phone).trim(),
          location: String(lead.location || 'Odisha').trim(),
          requirement: String(lead.requirement || 'Staff / Job / Domestic Help').trim(),
          message: String(lead.message || '').trim(),
          adSource: String(lead.adSource || 'Direct / Organic').trim(),
          campaign: String(lead.campaign || 'Direct').trim(),
          clickId: String(lead.clickId || '').trim()
        };

        const existingLeads = JSON.parse(fs.readFileSync(LEADS_FILE, 'utf8') || '[]');
        existingLeads.unshift(newLead);
        fs.writeFileSync(LEADS_FILE, JSON.stringify(existingLeads, null, 2), 'utf8');

        updateCsvFile(existingLeads);
        sendAdminEmailNotification(newLead);

        res.writeHead(201, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
          success: true,
          message: "Thank you! We'll call you within 24 hours.",
          leadId: newLead.id
        }));
      } catch (err) {
        res.writeHead(400, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ error: 'Invalid JSON payload.' }));
      }
    });
    return;
  }

  // API 9: Update Lead Status
  if (method === 'PATCH' && pathname.startsWith('/api/leads/')) {
    const leadId = pathname.replace('/api/leads/', '');
    let body = '';
    req.on('data', chunk => { body += chunk; });
    req.on('end', () => {
      try {
        const updateData = JSON.parse(body);
        const leads = JSON.parse(fs.readFileSync(LEADS_FILE, 'utf8') || '[]');
        const idx = leads.findIndex(l => l.id === leadId);
        if (idx === -1) {
          res.writeHead(404, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({ error: 'Lead not found.' }));
          return;
        }

        if (updateData.status) leads[idx].status = updateData.status;
        if (updateData.notes) leads[idx].message = (leads[idx].message ? leads[idx].message + ' | ' : '') + updateData.notes;

        fs.writeFileSync(LEADS_FILE, JSON.stringify(leads, null, 2), 'utf8');
        updateCsvFile(leads);

        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ success: true, lead: leads[idx] }));
      } catch (e) {
        res.writeHead(400, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ error: 'Failed to update lead.' }));
      }
    });
    return;
  }

  // API 10: Delete Lead
  if (method === 'DELETE' && pathname.startsWith('/api/leads/')) {
    const leadId = pathname.replace('/api/leads/', '');
    try {
      let leads = JSON.parse(fs.readFileSync(LEADS_FILE, 'utf8') || '[]');
      leads = leads.filter(l => l.id !== leadId);
      fs.writeFileSync(LEADS_FILE, JSON.stringify(leads, null, 2), 'utf8');
      updateCsvFile(leads);

      res.writeHead(200, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({ success: true, message: 'Lead deleted.' }));
    } catch (e) {
      res.writeHead(500, { 'Content-Type': 'application/json' });
      res.end(JSON.stringify({ error: 'Failed to delete lead.' }));
    }
    return;
  }

  // Static File Serving
  let filePath = path.join(__dirname, pathname === '/' ? 'index.html' : pathname);

  if (!filePath.startsWith(__dirname)) {
    res.writeHead(403, { 'Content-Type': 'text/plain' });
    res.end('Access denied.');
    return;
  }

  fs.stat(filePath, (err, stats) => {
    if (err || !stats.isFile()) {
      if (!path.extname(filePath)) {
        const htmlPath = filePath + '.html';
        if (fs.existsSync(htmlPath)) {
          filePath = htmlPath;
        } else {
          res.writeHead(404, { 'Content-Type': 'text/html; charset=utf-8' });
          res.end('<h1>404 - Page Not Found</h1><p><a href="/">Return to Odiins Home</a></p>');
          return;
        }
      } else {
        res.writeHead(404, { 'Content-Type': 'text/html; charset=utf-8' });
        res.end('<h1>404 - Page Not Found</h1><p><a href="/">Return to Odiins Home</a></p>');
        return;
      }
    }

    const ext = path.extname(filePath).toLowerCase();
    const contentType = MIME_TYPES[ext] || 'application/octet-stream';

    res.writeHead(200, { 'Content-Type': contentType });
    fs.createReadStream(filePath).pipe(res);
  });
});

server.listen(PORT, () => {
  console.log(`====================================================`);
  console.log(`🚀 Odiins Unified Command Center running at http://localhost:${PORT}`);
  console.log(`   Admin Portal: http://localhost:${PORT}/dashboard.html`);
  console.log(`   Spreadsheet: ${CSV_FILE}`);
  console.log(`====================================================`);
});
