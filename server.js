/**
 * ODIINS PLATFORM SERVER
 * Zero-dependency Node.js HTTP server.
 * Provides:
 * - Static file serving with MIME detection
 * - Lead Capture API: POST /api/leads (with spam checks & honeypot validation)
 * - Live CSV Spreadsheet Sync: writes to data/leads.json & data/leads.csv
 * - Downloadable Spreadsheet: GET /api/leads/export.csv
 * - Admin Dashboard APIs: GET /api/leads, PATCH /api/leads/:id, DELETE /api/leads/:id
 * - Simulated Admin Email Notification: logs alerts to data/email_notifications.log
 */

const http = require('http');
const fs = require('fs');
const path = require('path');
const url = require('url');

const PORT = process.env.PORT || 3000;
const DATA_DIR = path.join(__dirname, 'data');
const LEADS_FILE = path.join(DATA_DIR, 'leads.json');
const CSV_FILE = path.join(DATA_DIR, 'leads.csv');
const EMAIL_LOG_FILE = path.join(DATA_DIR, 'email_notifications.log');

// Ensure data directory exists
if (!fs.existsSync(DATA_DIR)) {
  fs.mkdirSync(DATA_DIR, { recursive: true });
}

// Initial sample data if leads.json doesn't exist
if (!fs.existsSync(LEADS_FILE)) {
  const initialLeads = [
    {
      id: "OD-K92A1",
      timestamp: new Date(Date.now() - 3600000 * 3).toISOString(),
      formType: "Job Seeker",
      status: "New",
      name: "Subhashree Mohanty",
      phone: "9861012345",
      location: "Bhubaneswar (Saheed Nagar)",
      requirement: "Back Office / Accountant",
      message: "B.Com graduate with 2 years experience in Tally and GST filing."
    },
    {
      id: "OD-L81B4",
      timestamp: new Date(Date.now() - 3600000 * 8).toISOString(),
      formType: "Employer",
      status: "Contacted",
      name: "Utkal Retail & Logistics Pvt Ltd",
      phone: "7008123456",
      location: "Cuttack (Choudwar)",
      requirement: "Warehouse Delivery & Drivers (10 Candidates)",
      message: "Urgent requirement for commercial vehicle drivers and dispatch staff."
    },
    {
      id: "OD-M72C9",
      timestamp: new Date(Date.now() - 3600000 * 18).toISOString(),
      formType: "Customer",
      status: "In Progress",
      name: "Debashish Patnaik",
      phone: "9437098765",
      location: "Bhubaneswar (Patia)",
      requirement: "Cook & House Maid",
      message: "Looking for Odia-style breakfast and dinner cook for family of 4."
    },
    {
      id: "OD-N63D2",
      timestamp: new Date(Date.now() - 3600000 * 26).toISOString(),
      formType: "Job Seeker",
      status: "Contacted",
      name: "Rakesh Kumar Jena",
      phone: "8249054321",
      location: "Puri",
      requirement: "Driver (Commercial / Personal)",
      message: "Valid 4-wheeler commercial license, 5 years driving experience."
    },
    {
      id: "OD-P54E8",
      timestamp: new Date(Date.now() - 3600000 * 40).toISOString(),
      formType: "Customer",
      status: "Closed",
      name: "Dr. Priyadarshini Mishra",
      phone: "9937011223",
      location: "Rourkela (Civil Township)",
      requirement: "Pandit for Griha Pravesh Puja",
      message: "Need experienced Brahmin pandit for new home puja ceremony."
    }
  ];
  fs.writeFileSync(LEADS_FILE, JSON.stringify(initialLeads, null, 2));
  updateCsvFile(initialLeads);
}

// Convert JSON array of leads to CSV format
function updateCsvFile(leads) {
  const headers = ["Lead ID", "Date & Time", "Category", "Name / Business", "Phone / WhatsApp", "District / City", "Requirement", "Status", "Notes"];
  const rows = leads.map(l => [
    `"${l.id}"`,
    `"${new Date(l.timestamp).toLocaleString('en-IN')}"`,
    `"${l.formType}"`,
    `"${(l.name || '').replace(/"/g, '""')}"`,
    `"${l.phone}"`,
    `"${(l.location || '').replace(/"/g, '""')}"`,
    `"${(l.requirement || '').replace(/"/g, '""')}"`,
    `"${l.status}"`,
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

// Admin Email Notification Dispatcher (Simulated with persistent audit log)
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
- Message/Notes:  ${lead.message || 'None provided'}

Immediate Actions:
- Call Candidate: tel:${lead.phone}
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

  // CORS Headers for seamless local testing
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PATCH, DELETE, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

  if (method === 'OPTIONS') {
    res.writeHead(204);
    res.end();
    return;
  }

  // API: Export Leads to CSV Spreadsheet
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

  // API: Get All Leads
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

  // API: Submit New Lead
  if (method === 'POST' && pathname === '/api/leads') {
    let body = '';
    req.on('data', chunk => { body += chunk; });
    req.on('end', () => {
      try {
        const lead = JSON.parse(body);

        // Spam verification
        if (lead.website_hp && lead.website_hp.trim() !== '') {
          res.writeHead(400, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({ error: 'Spam detected. Submission blocked.' }));
          return;
        }

        if (!lead.name || !lead.phone) {
          res.writeHead(400, { 'Content-Type': 'application/json' });
          res.end(JSON.stringify({ error: 'Name and Phone are mandatory.' }));
          return;
        }

        // Standardize lead object
        const newLead = {
          id: lead.id || ('OD-' + Date.now().toString(36).toUpperCase()),
          timestamp: lead.timestamp || new Date().toISOString(),
          formType: lead.formType || 'General Enquiry',
          status: 'New',
          name: String(lead.name).trim(),
          phone: String(lead.phone).trim(),
          location: String(lead.location || 'Odisha').trim(),
          requirement: String(lead.requirement || 'Staff / Job / Domestic Help').trim(),
          message: String(lead.message || '').trim()
        };

        // Read, prepend, and write
        const existingLeads = JSON.parse(fs.readFileSync(LEADS_FILE, 'utf8') || '[]');
        existingLeads.unshift(newLead);
        fs.writeFileSync(LEADS_FILE, JSON.stringify(existingLeads, null, 2), 'utf8');

        // Update CSV Spreadsheet
        updateCsvFile(existingLeads);

        // Dispatch Admin Email Notification
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

  // API: Update Lead Status (PATCH /api/leads/:id)
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

  // API: Delete Lead (DELETE /api/leads/:id)
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
  
  // Security check: ensure path is within __dirname
  if (!filePath.startsWith(__dirname)) {
    res.writeHead(403, { 'Content-Type': 'text/plain' });
    res.end('Access denied.');
    return;
  }

  fs.stat(filePath, (err, stats) => {
    if (err || !stats.isFile()) {
      // Fallback: check if .html extension was omitted
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
  console.log(`🚀 Odiins Platform Server is running at:`);
  console.log(`   http://localhost:${PORT}`);
  console.log(`   Admin Lead Dashboard: http://localhost:${PORT}/dashboard.html`);
  console.log(`   Leads CSV Spreadsheet: ${CSV_FILE}`);
  console.log(`====================================================`);
});
