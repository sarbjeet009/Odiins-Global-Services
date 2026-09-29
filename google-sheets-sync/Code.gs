// ==============================================================================
// ODIINS PLATFORM - CENTRALIZED GOOGLE SPREADSHEET LEAD CAPTURE & CRM BACKEND
// Spreadsheet: https://docs.google.com/spreadsheets/d/1cwfI94iE50ohBeOD4eOxK5RrUfsJIxVEZ0ftGS6Leis/edit
// Webhook: https://script.google.com/macros/s/AKfycbxDILgSywLAoCkiHEs2s2GpBLPINg5kIEHKurjwMy60gJrckHlRIGrvwr5aJJOfd0je/exec
// ==============================================================================

/**
 * 1. REAL-TIME LEAD CAPTURE, CRM TWO-WAY UPDATES & TELEGRAM WEBHOOK (doPost)
 */
function doPost(e) {
  var lock = LockService.getScriptLock();
  lock.tryLock(10000);

  try {
    var ss = SpreadsheetApp.getActiveSpreadsheet();
    var sheet = ss.getSheetByName("Live Leads") || ss.getSheets()[0];
    
    // Auto-create professional headers if sheet is empty
    if (sheet.getLastRow() === 0) {
      setupLeadSheetHeaders(sheet);
    }

    var data;
    try {
      data = JSON.parse(e.postData.contents);
    } catch (err) {
      data = e.parameter || {};
    }

    // --------------------------------------------------------------------------
    // A. CRM 2-WAY SYNC: UPDATE EXISTING LEAD (STATUS / ASSIGNMENT / NOTES)
    // --------------------------------------------------------------------------
    if (data.action === "updateLead") {
      var leadId = data.id || data.leadId;
      var rows = sheet.getDataRange().getValues();
      var updated = false;

      for (var i = 1; i < rows.length; i++) {
        if (String(rows[i][1]).trim() === String(leadId).trim()) {
          var rowIndex = i + 1; // 1-based index
          if (data.status) {
            sheet.getRange(rowIndex, 11).setValue(data.status); // Column K: Status
          }
          if (data.assignedTo) {
            sheet.getRange(rowIndex, 12).setValue(data.assignedTo); // Column L: Assigned To
          }
          if (data.notes !== undefined) {
            sheet.getRange(rowIndex, 13).setValue(data.notes); // Column M: Notes
          }
          updated = true;
          break;
        }
      }

      return ContentService.createTextOutput(JSON.stringify({ 
        result: updated ? "success" : "not_found", 
        message: updated ? "Lead updated successfully" : "Lead ID not found",
        id: leadId 
      })).setMimeType(ContentService.MimeType.JSON);
    }

    // --------------------------------------------------------------------------
    // B. TELEGRAM BOT WEBHOOK (NATURAL LANGUAGE ASSISTANT QUERY)
    // --------------------------------------------------------------------------
    if (data.message && data.message.text && data.message.chat) {
      return handleTelegramMessage(data.message, sheet);
    }

    // --------------------------------------------------------------------------
    // C. WEBSITE FORM LEAD SUBMISSION (NEW LEAD REGISTRATION)
    // --------------------------------------------------------------------------
    var timestamp = Utilities.formatDate(new Date(), "Asia/Kolkata", "yyyy-MM-dd HH:mm:ss");
    var category = resolveCategory(data.formType, data.requirement);

    sheet.appendRow([
      timestamp,
      data.id || ("OD-" + new Date().getTime().toString(36).toUpperCase()),
      category,
      data.name || "",
      "'" + (data.phone || ""),
      data.district || "",
      data.areaCity || data.location || "",
      data.requirement || data.formType || "General Enquiry",
      data.adSource || "Direct / Organic",
      data.campaign || "Direct",
      data.status || "New",
      "", // Assigned Sales Executive (For CRM use)
      data.message || ""
    ]);

    return ContentService.createTextOutput(JSON.stringify({ 
      result: "success", 
      message: "Lead recorded in Google Sheet",
      id: data.id 
    })).setMimeType(ContentService.MimeType.JSON);

  } catch (error) {
    return ContentService.createTextOutput(JSON.stringify({ 
      result: "error", 
      error: error.toString() 
    })).setMimeType(ContentService.MimeType.JSON);
  } finally {
    lock.releaseLock();
  }
}

/**
 * 2. LIVE DATA ACCESS & QUERY API (doGet)
 */
function doGet(e) {
  var action = (e && e.parameter && e.parameter.action) || "";
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName("Live Leads") || ss.getSheets()[0];

  // A. Fetch All Leads for CRM Dashboard
  if (action === "getLeads") {
    var rows = sheet.getDataRange().getValues();
    if (rows.length <= 1) {
      return ContentService.createTextOutput(JSON.stringify({ result: "success", leads: [] }))
        .setMimeType(ContentService.MimeType.JSON);
    }
    
    var leads = [];
    for (var i = 1; i < rows.length; i++) {
      var r = rows[i];
      if (!r[1]) continue;
      
      var dateStr = "";
      if (r[0] instanceof Date) {
        dateStr = Utilities.formatDate(r[0], "Asia/Kolkata", "yyyy-MM-dd HH:mm");
      } else {
        dateStr = String(r[0] || "");
      }

      leads.push({
        date: dateStr,
        id: String(r[1]),
        category: String(r[2] || ""),
        name: String(r[3] || ""),
        phone: String(r[4] || "").replace(/^'/, ""),
        district: String(r[5] || ""),
        location: String(r[6] || ""),
        requirement: String(r[7] || ""),
        source: String(r[8] || ""),
        campaign: String(r[9] || ""),
        status: String(r[10] || "New"),
        assignedTo: String(r[11] || "Unassigned"),
        notes: String(r[12] || "")
      });
    }

    return ContentService.createTextOutput(JSON.stringify({ 
      result: "success", 
      count: leads.length, 
      leads: leads 
    })).setMimeType(ContentService.MimeType.JSON);
  }

  // B. Backfill Historical Leads
  if (action === "backfill") {
    backfillPastLeads();
    return ContentService.createTextOutput(JSON.stringify({ 
      result: "success", 
      message: "Successfully populated 15 historical leads with emerald green styling!" 
    })).setMimeType(ContentService.MimeType.JSON);
  }

  // C. Generate SEO Action Plan Sheet
  if (action === "seoplan") {
    createOrUpdateSEOPlanSheet();
    return ContentService.createTextOutput(JSON.stringify({ 
      result: "success", 
      message: "Successfully created SEO Action Plan sheet!" 
    })).setMimeType(ContentService.MimeType.JSON);
  }

  return ContentService.createTextOutput("Odiins Lead Capture & CRM Webhook is Active & Ready!");
}

/**
 * Helper: Sets up clean headers and visual styling
 */
function setupLeadSheetHeaders(sheet) {
  var headers = [
    "Date & Time (IST)",
    "Lead ID",
    "Category",
    "Name / Business",
    "Phone / WhatsApp",
    "District",
    "Location / City",
    "Requirement / Role",
    "Traffic Source",
    "Campaign",
    "Status",
    "Assigned To",
    "Notes / Message"
  ];
  sheet.appendRow(headers);
  
  var headerRange = sheet.getRange(1, 1, 1, headers.length);
  headerRange.setFontWeight("bold")
             .setBackground("#157347") // Odiins Emerald Green
             .setFontColor("#FFFFFF")
             .setFontFamily("Poppins");
  sheet.setFrozenRows(1);
}

/**
 * Helper: Clean category normalization
 */
function resolveCategory(formType, requirement) {
  var type = (formType || "").toLowerCase();
  var req = (requirement || "").toLowerCase();

  if (type.indexOf("job") !== -1 || req.indexOf("sales manager") !== -1 || req.indexOf("data entry") !== -1 || req.indexOf("office peon") !== -1 || req.indexOf("telecaller") !== -1) {
    return "Job Seeker";
  }
  if (type.indexOf("employer") !== -1 || req.indexOf("manpower") !== -1 || req.indexOf("staffing") !== -1) {
    return "Corporate / Employer";
  }
  if (type.indexOf("customer") !== -1 || req.indexOf("maid") !== -1 || req.indexOf("cook") !== -1 || req.indexOf("driver") !== -1 || req.indexOf("patient") !== -1) {
    return "Household / Home Help";
  }
  if (type.indexOf("csp") !== -1 || req.indexOf("bank csp") !== -1) {
    return "Bank CSP Operator";
  }
  return "Contact Enquiry";
}

// ==============================================================================
// 3. ONE-CLICK HISTORICAL BACKFILL FUNCTION
// ==============================================================================
function backfillPastLeads() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName("Live Leads") || ss.getSheets()[0];

  if (sheet.getLastRow() === 0) {
    setupLeadSheetHeaders(sheet);
  }

  var pastLeads = [
    ["2026-09-19 02:36:01", "OD-MU7G7DS1", "Household / Home Help", "Sarbjeet Parija", "'6372186709", "Bhubaneswar", "Bhubaneswar", "Customer", "Direct / Organic", "Direct", "In Progress", "Sarbjeet Parija", "yes!!"],
    ["2026-09-22 16:33:03", "OD-MUCKFD5W", "Bank CSP Operator", "Debasis Mohanty", "'9861234567", "Begunia, Khurda", "Begunia, Khurda", "Bank CSP Operator [Edu: Graduate | Shop: Own | Dist: <5 KM]", "Meta Ads (Instagram)", "bank_csp_odisha_campaign", "New", "Unassigned", "Applying for Khurda CSP Center vacancy"],
    ["2026-09-24 01:41:42", "OD-MUEJGSNP", "Job Seeker", "Sarbjeet Parija", "'09938079601", "Bhubaneswar", "Bhubaneswar", "Sales Manager", "Direct / Organic", "Direct", "Closed / Placed", "Sarbjeet Parija", "Platform test lead"],
    ["2026-09-24 04:08:45", "OD-MUEOPWFF", "Job Seeker", "Tripati Bissoyi", "'9337097014", "Nabarangpur", "Nabarangpur", "Telecaller", "Campaign (fb)", "120250275756890477", "New", "Priya Sharma", ""],
    ["2026-09-25 18:57:32", "OD-MUGZWQ9B", "Job Seeker", "Bhakta Prahalad dhal", "'6371452689", "Mayurbhanj", "Mayurbhanj", "Office Peon", "Campaign (fb)", "120250278490350477", "New", "Unassigned", ""],
    ["2026-09-25 19:26:58", "OD-MUH0YL5P", "Job Seeker", "RAHUL DAS", "'9090365066", "BERHAMPUR", "BERHAMPUR", "Office Peon", "Campaign (fb)", "120250278490350477", "New", "Unassigned", ""],
    ["2026-09-25 19:32:56", "OD-MUH168PV", "Job Seeker", "Harihar Meher", "'8906074375", "Bargarh", "Bargarh", "Data Entry", "Direct / Organic", "Direct", "New", "Priya Sharma", "Organic Applicant"],
    ["2026-09-25 20:50:25", "OD-MUH3XW4B", "Job Seeker", "Prakash Kumar sahoo", "'9658620364", "Puri , odisha", "Puri , odisha", "Sales Manager", "Campaign (fb)", "120250278490350477", "New", "Rajesh Nayak", ""],
    ["2026-09-25 20:56:42", "OD-MUH45ZCM", "Job Seeker", "Manasa Kumar Dangua", "'8149643766", "Berhampur Ganjam", "Berhampur Ganjam", "Data Entry", "Direct / Organic", "Direct", "New", "Priya Sharma", "Organic Applicant"],
    ["2026-09-25 23:13:13", "OD-MUH91J8V", "Job Seeker", "SUMANTA KUMAR PRADHAN", "'7787827076", "Keshapur", "Keshapur", "Office Peon", "Campaign (ig)", "120250278490350477", "New", "Unassigned", ""],
    ["2026-09-25 23:39:51", "OD-MUH9ZS5M", "Job Seeker", "Ajay Bibhar", "'6371555762", "Rourkela", "Rourkela", "Sales Manager", "Campaign (fb)", "120250278490350477", "New", "Rajesh Nayak", ""],
    ["2026-09-26 13:47:52", "OD-MUI4ACI8", "Job Seeker", "Prakash Kumar sahoo", "'9658620364", "Puri Odisha", "Puri Odisha", "Sales Manager", "Direct / Organic", "Direct", "New", "Rajesh Nayak", "Returning Organic Applicant"],
    ["2026-09-28 17:44:06", "OD-MUL7LUUY", "Job Seeker", "Sumanta kumar Mohanty", "'9040486845", "Bhubaneswar", "Bhubaneswar", "Sales Manager", "Campaign (fb)", "120250278490350477", "New", "Rajesh Nayak", ""],
    ["2026-09-28 20:49:14", "OD-MULE7XOG", "Job Seeker", "Sanjeet Kumar Das", "'9090222920", "Bhadrak", "Bhadrak", "Data Entry", "Direct / Organic", "Direct", "New", "Priya Sharma", "Organic Applicant"],
    ["2026-09-28 21:11:23", "OD-MULF0FB0", "Job Seeker", "Boby Patel", "'7894181615", "Sambalpur", "Sambalpur", "Sales Manager", "Direct / Organic", "Direct", "New", "Unassigned", "Organic Applicant"]
  ];

  for (var i = 0; i < pastLeads.length; i++) {
    sheet.appendRow(pastLeads[i]);
  }

  Logger.log("Successfully backfilled " + pastLeads.length + " historical leads into your Google Sheet!");
}

// ==============================================================================
// 4. TELEGRAM BOT NATURAL LANGUAGE ASSISTANT (ZERO SERVER COST)
// ==============================================================================
function handleTelegramMessage(message, sheet) {
  var chatId = message.chat.id;
  var text = (message.text || "").trim().toLowerCase();
  var rows = sheet.getDataRange().getValues();
  var totalLeads = Math.max(0, rows.length - 1);

  var reply = "";

  if (text === "/start" || text === "hi" || text === "hello" || text === "help") {
    reply = "👋 *Namaskar Sarbjeet! Welcome to Odiins Lead AI Bot.*\n\n" +
            "I am directly connected to your website and leads database. Ask me anything:\n\n" +
            "• *today* - Count & details of leads received today\n" +
            "• *total* - Total leads breakdown by status\n" +
            "• *bhubaneswar* or *puri* or *sambalpur* - Leads by location\n" +
            "• *sales* or *csp* or *maid* - Leads by role or category\n" +
            "• Or simply type any applicant name (e.g. *Boby Patel*) to see their details!";
  } else if (text.indexOf("today") !== -1) {
    var todayStr = Utilities.formatDate(new Date(), "Asia/Kolkata", "yyyy-MM-dd");
    var todayCount = 0;
    var todayLeads = [];
    for (var i = 1; i < rows.length; i++) {
      var d = String(rows[i][0]);
      if (d.indexOf(todayStr) !== -1) {
        todayCount++;
        todayLeads.push("• *" + rows[i][3] + "* (" + rows[i][7] + ") - 📞 `" + String(rows[i][4]).replace(/^'/, '') + "` [" + rows[i][5] + "]");
      }
    }
    reply = "📊 *Today's Leads (" + todayStr + "): " + todayCount + "*\n\n" +
            (todayLeads.length > 0 ? todayLeads.join("\n") : "No new leads recorded today yet.");
  } else if (text.indexOf("total") !== -1 || text.indexOf("summary") !== -1 || text.indexOf("count") !== -1) {
    var newCount = 0, inProg = 0, closed = 0;
    for (var i = 1; i < rows.length; i++) {
      var s = String(rows[i][10]);
      if (s === "New") newCount++;
      else if (s === "In Progress") inProg++;
      else if (s.indexOf("Closed") !== -1) closed++;
    }
    reply = "📈 *Odiins Leads Overview:*\n\n" +
            "• Total Leads: *" + totalLeads + "*\n" +
            "• New / Uncontacted: *" + newCount + "*\n" +
            "• In Progress: *" + inProg + "*\n" +
            "• Closed / Placed: *" + closed + "*";
  } else {
    // Search query across all rows
    var matches = [];
    for (var i = rows.length - 1; i >= 1; i--) {
      var rowStr = (rows[i].join(" ")).toLowerCase();
      if (rowStr.indexOf(text) !== -1) {
        matches.push(
          "👤 *" + rows[i][3] + "* (ID: `" + rows[i][1] + "`)\n" +
          "📞 Phone: `" + String(rows[i][4]).replace(/^'/, '') + "`\n" +
          "📍 Location: " + rows[i][5] + (rows[i][6] ? " (" + rows[i][6] + ")" : "") + "\n" +
          "💼 Role: " + rows[i][7] + " [" + rows[i][2] + "]\n" +
          "⚡ Status: *" + rows[i][10] + "* | Assigned: " + (rows[i][11] || "Unassigned") +
          (rows[i][12] ? "\n💬 Notes: _" + rows[i][12] + "_" : "")
        );
        if (matches.length >= 5) break;
      }
    }
    if (matches.length > 0) {
      reply = "🔍 *Found " + matches.length + " matching leads for '" + message.text + "':*\n\n" + matches.join("\n\n---\n\n");
    } else {
      reply = "❓ No leads found matching '" + message.text + "'. Try searching by district (e.g. *Bhubaneswar*, *Puri*), candidate name (*Boby*), or role (*Sales Manager*).";
    }
  }

  // Send reply via Telegram Bot API
  var botToken = PropertiesService.getScriptProperties().getProperty("TELEGRAM_BOT_TOKEN");
  if (botToken) {
    try {
      UrlFetchApp.fetch("https://api.telegram.org/bot" + botToken + "/sendMessage", {
        method: "post",
        contentType: "application/json",
        payload: JSON.stringify({
          chat_id: chatId,
          text: reply,
          parse_mode: "Markdown"
        })
      });
    } catch (e) {
      Logger.log("Telegram send error: " + e.toString());
    }
  }

  return ContentService.createTextOutput(JSON.stringify({ result: "success", reply: reply }))
    .setMimeType(ContentService.MimeType.JSON);
}

// ==============================================================================
// 5. 1-CLICK SEO ACTION PLAN GENERATOR (SHEET 2)
// ==============================================================================
function createOrUpdateSEOPlanSheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName("SEO Action Plan");
  if (!sheet) {
    sheet = ss.insertSheet("SEO Action Plan");
  } else {
    sheet.clear();
  }

  var headers = [
    "Task ID", "Category", "Platform", "Target URL / Group", 
    "Target Keywords", "Action Required", "Ready-to-Use Content / Template", 
    "Priority / Impact", "Status"
  ];
  sheet.appendRow(headers);
  sheet.getRange(1, 1, 1, headers.length)
    .setFontWeight("bold")
    .setBackground("#157347")
    .setFontColor("#FFFFFF");

  var tasks = [
    ["SEO-01", "Community SEO (Reddit)", "Reddit r/Bhubaneswar", "https://www.reddit.com/r/Bhubaneswar/", "maid bhubaneswar, cook patia, domestic help rates", "Post educational guide on realistic salary rates and safety checks for domestic helpers in Bhubaneswar", "TITLE: Current salary & rate breakdown for hiring full-time cooks & maids in Patia / Khandagiri / Chandrasekharpur (2026 Guide) | BODY: Salary benchmarks (₹3500-5000 for 2-time cooking; ₹8000-12000 full day). Police & Aadhaar safety checks. Sign-off: We manage verified helpers across Bhubaneswar at Odiins (odiins.in).", "High", "To Do"],
    ["SEO-02", "Community SEO (Reddit)", "Reddit r/Bhubaneswar", "https://www.reddit.com/r/Bhubaneswar/", "jobs in bhubaneswar, fresher hiring odisha, sales executive", "Post advice thread for job seekers in Bhubaneswar on how to get hired in retail, banking & corporate sales without paying placement fees", "TITLE: Reality of private job hiring in Bhubaneswar: What companies in Infocity, Rasulgarh & Mancheswar actually look for | BODY: Share tips on resumes, common interview mistakes, and highlight that Odiins offers zero-fee direct interview placement for verified local openings at odiins.in.", "High", "To Do"],
    ["SEO-03", "Community SEO (Reddit)", "Reddit r/Odisha", "https://www.reddit.com/r/Odisha/", "manpower odisha, staffing company bhubaneswar, skilled labor", "Participate in discussions about youth employment, skill development, and local businesses in Odisha", "Share genuine insights into emerging service & retail jobs across Cuttack, Bhubaneswar, and Puri. Link to odiins.in when users ask about job openings or hiring staff.", "Medium", "To Do"],
    ["SEO-04", "Q&A SEO (Quora)", "Quora", "https://www.quora.com/search?q=maid+service+bhubaneswar", "best maid service in bhubaneswar, verified cook bhubaneswar", "Answer top 3 questions on finding verified domestic help in Bhubaneswar", "Write a 3-paragraph answer detailing safety, verification, and replacement guarantees. Include direct link to https://odiins.in/services-customers.html.", "High", "To Do"],
    ["SEO-05", "Q&A SEO (Quora)", "Quora", "https://www.quora.com/search?q=placement+consultancy+bhubaneswar", "best placement consultancy in bhubaneswar, job consultancy odisha", "Answer top 3 questions on reliable job consultancies in Bhubaneswar for freshers and experienced candidates", "Detail why candidates should avoid fee-charging consultants and choose verified corporate staffing partners like Odiins Global Services (https://odiins.in/services-job-seekers.html).", "High", "To Do"],
    ["SEO-06", "Directory Backlink", "Google Business Profile (GBP)", "https://business.google.com/", "staffing agency bhubaneswar, manpower consultancy", "Complete Google Business Profile 100%, upload office photos of Khandagiri, add services & website link", "Name: Odiins Global Services Pvt Ltd | Category: Employment Agency, Staffing Agency | Address: Plot No. 1215/1500, Bank of India Building, Khandagiri, Bhubaneswar 751030 | Phone: +91 99380 79601 | Website: https://odiins.in", "Critical", "In Progress"],
    ["SEO-07", "Directory Backlink", "Justdial", "https://www.justdial.com/Free-Listing", "placement services bhubaneswar, domestic help services", "Create free business listing under 'Placement Services' and 'Domestic Help Services'", "Enter exact NAP details, description of services (Corporate Staffing, Domestic Helpers, Skilled Manpower), and link to https://odiins.in", "High", "To Do"],
    ["SEO-08", "Directory Backlink", "IndiaMART", "https://seller.indiamart.com/","manpower supply agency odisha, corporate staffing service", "Create free seller profile under 'Manpower Supply Services'", "List services: Sales Force Staffing, Office Assistants, Security & Facility Support across Odisha. Link: https://odiins.in/services-employers.html", "High", "To Do"],
    ["SEO-09", "Directory Backlink", "TradeIndia", "https://www.tradeindia.com/", "manpower solutions, staffing recruitment consultants", "Register free company profile for Odiins Global Services", "Select HR & Recruitment category, enter Khandagiri address, phone, and website URL https://odiins.in", "Medium", "To Do"],
    ["SEO-10", "Directory Backlink", "Sulekha", "https://www.sulekha.com/", "maid services bhubaneswar, patient care services", "Create free listing for domestic staffing & home care in Bhubaneswar", "Target categories: Cook Services, Maid Services, Patient Care Attendants, Baby Care. Address: Khandagiri, Bhubaneswar. Link: https://odiins.in/services-customers.html", "Medium", "To Do"],
    ["SEO-11", "Social Media Groups", "Facebook Groups", "https://www.facebook.com/groups/", "jobs in bhubaneswar, odisha vacancy, sales jobs", "Join 5 top Bhubaneswar job groups and post verified weekly openings", "GROUP EXAMPLES: 'Bhubaneswar Jobs', 'Odisha Job Alert', 'Jobs in Cuttack & Bhubaneswar'. Post 2 job listings per week linking directly to https://odiins.in/services-job-seekers.html.", "High", "To Do"],
    ["SEO-12", "B2B Professional", "LinkedIn Company Page", "https://www.linkedin.com/company/setup/new/", "corporate staffing bhubaneswar, b2b hiring odisha", "Create official LinkedIn Company Page for Odiins Global Services and post weekly HR tips", "Headline: 'Empowering Odisha Workforce with Verified Staffing & Career Opportunities'. Link company website to https://odiins.in.", "High", "To Do"]
  ];

  for (var i = 0; i < tasks.length; i++) {
    sheet.appendRow(tasks[i]);
  }
}
