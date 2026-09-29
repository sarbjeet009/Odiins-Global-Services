// ==============================================================================
// ODIINS PLATFORM - CENTRALIZED GOOGLE SPREADSHEET LEAD CAPTURE & CRM BACKEND
// Spreadsheet: https://docs.google.com/spreadsheets/d/1cwfI94iE50ohBeOD4eOxK5RrUfsJIxVEZ0ftGS6Leis/edit
// Webhook: https://script.google.com/macros/s/AKfycbxDILgSywLAoCkiHEs2s2GpBLPINg5kIEHKurjwMy60gJrckHlRIGrvwr5aJJOfd0je/exec
// Telegram Bot: @Odiins_bot (OdiinsLeadBot)
// ==============================================================================

var DEFAULT_TELEGRAM_BOT_TOKEN = "8971100286:AAGyn87yt6xgQr0N1GFv6G4QU7HR9HfJvpc";

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
    // B. TELEGRAM BOT WEBHOOK (MESSAGES & INLINE BUTTON CLICKS)
    // --------------------------------------------------------------------------
    if (data.message) {
      return handleTelegramMessage(data.message, sheet);
    }
    if (data.callback_query) {
      return handleTelegramCallback(data.callback_query, sheet);
    }

    // --------------------------------------------------------------------------
    // C. WEBSITE FORM LEAD SUBMISSION (NEW LEAD REGISTRATION)
    // --------------------------------------------------------------------------
    var timestamp = Utilities.formatDate(new Date(), "Asia/Kolkata", "yyyy-MM-dd HH:mm:ss");
    var category = resolveCategory(data.formType, data.requirement);
    var generatedId = data.id || ("OD-" + new Date().getTime().toString(36).toUpperCase());

    sheet.appendRow([
      timestamp,
      generatedId,
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

    // Send Instant Push Alert to Telegram Admin!
    try {
      sendTelegramNewLeadAlert({
        id: generatedId,
        name: data.name,
        phone: data.phone,
        category: category,
        district: data.district,
        location: data.areaCity || data.location,
        requirement: data.requirement || data.formType,
        adSource: data.adSource,
        message: data.message
      });
    } catch (teleErr) {
      Logger.log("Telegram alert error: " + teleErr.toString());
    }

    return ContentService.createTextOutput(JSON.stringify({ 
      result: "success", 
      message: "Lead recorded in Google Sheet and Telegram notified",
      id: generatedId 
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

  // D. Quick Telegram Diagnostics Check
  if (action === "telegramTest") {
    var chatIds = getAdminChatIds();
    return ContentService.createTextOutput(JSON.stringify({
      result: "success",
      botConfigured: Boolean(getTelegramToken()),
      registeredAdmins: chatIds
    })).setMimeType(ContentService.MimeType.JSON);
  }

  return ContentService.createTextOutput("Odiins Lead Capture, CRM & Telegram AI Webhook is Active & Ready!");
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

  if (type.indexOf("job") !== -1 || req.indexOf("sales manager") !== -1 || req.indexOf("data entry") !== -1 || req.indexOf("office peon") !== -1 || req.indexOf("telecaller") !== -1 || req.indexOf("delivery") !== -1 || req.indexOf("security") !== -1) {
    return "Job Seeker";
  }
  if (type.indexOf("employer") !== -1 || req.indexOf("manpower") !== -1 || req.indexOf("staffing") !== -1 || req.indexOf("corporate") !== -1) {
    return "Corporate / Employer";
  }
  if (type.indexOf("customer") !== -1 || req.indexOf("maid") !== -1 || req.indexOf("cook") !== -1 || req.indexOf("driver") !== -1 || req.indexOf("patient") !== -1 || req.indexOf("elderly") !== -1) {
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
// 4. TELEGRAM BOT AI COMMAND ASSISTANT (ZERO SERVER COST)
// ==============================================================================

function getTelegramToken() {
  return PropertiesService.getScriptProperties().getProperty("TELEGRAM_BOT_TOKEN") || DEFAULT_TELEGRAM_BOT_TOKEN;
}

function getAdminChatIds() {
  var props = PropertiesService.getScriptProperties();
  var saved = props.getProperty("TELEGRAM_ADMIN_CHATS");
  if (saved) {
    try {
      var arr = JSON.parse(saved);
      if (Array.isArray(arr) && arr.length > 0) return arr;
    } catch(e) {}
  }
  var single = props.getProperty("ADMIN_CHAT_ID");
  if (single) return [single];
  return [];
}

function registerAdminChatId(chatId) {
  if (!chatId) return;
  var strId = String(chatId);
  var props = PropertiesService.getScriptProperties();
  var list = getAdminChatIds();
  if (list.indexOf(strId) === -1) {
    list.push(strId);
    props.setProperty("TELEGRAM_ADMIN_CHATS", JSON.stringify(list));
    props.setProperty("ADMIN_CHAT_ID", strId); // backward compatibility
  }
}

function escapeHtml(text) {
  if (!text) return "";
  return String(text)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");
}

function sendTelegramRaw(chatId, text, replyMarkup) {
  var token = getTelegramToken();
  if (!token || !chatId) return;

  var payload = {
    chat_id: chatId,
    text: text,
    parse_mode: "HTML",
    disable_web_page_preview: true
  };
  if (replyMarkup) {
    payload.reply_markup = replyMarkup;
  }

  try {
    UrlFetchApp.fetch("https://api.telegram.org/bot" + token + "/sendMessage", {
      method: "post",
      contentType: "application/json",
      payload: JSON.stringify(payload),
      muteHttpExceptions: true
    });
  } catch (err) {
    Logger.log("Telegram send error: " + err.toString());
  }
}

function answerTelegramCallback(callbackQueryId, notificationText) {
  var token = getTelegramToken();
  if (!token || !callbackQueryId) return;
  try {
    UrlFetchApp.fetch("https://api.telegram.org/bot" + token + "/answerCallbackQuery", {
      method: "post",
      contentType: "application/json",
      payload: JSON.stringify({
        callback_query_id: callbackQueryId,
        text: notificationText || ""
      }),
      muteHttpExceptions: true
    });
  } catch (err) {}
}

/**
 * Instant Telegram push alert on new lead submission from website
 */
function sendTelegramNewLeadAlert(lead) {
  var chatIds = getAdminChatIds();
  if (!chatIds || chatIds.length === 0) return;

  var rawPhone = String(lead.phone || '').replace(/^'/, '');
  var cleanPhone = rawPhone.replace(/[^0-9]/g, '');
  if (cleanPhone.length === 10) cleanPhone = '91' + cleanPhone;

  var msg = "🚨 <b>NEW LEAD RECEIVED ON ODIINS!</b> ⚡\n" +
            "━━━━━━━━━━━━━━━━━━━━━\n" +
            "👤 <b>Name:</b> " + escapeHtml(lead.name || "N/A") + "\n" +
            "📞 <b>Phone:</b> <a href=\"tel:" + rawPhone + "\">" + escapeHtml(rawPhone || "N/A") + "</a>" +
            (cleanPhone ? " | <a href=\"https://wa.me/" + cleanPhone + "\">WhatsApp Chat</a>" : "") + "\n" +
            "💼 <b>Category:</b> " + escapeHtml(lead.category || "General") + "\n" +
            "🎯 <b>Requirement:</b> " + escapeHtml(lead.requirement || "General Enquiry") + "\n" +
            "📍 <b>District:</b> " + escapeHtml(lead.district || "Odisha") + 
            (lead.location ? " (" + escapeHtml(lead.location) + ")" : "") + "\n" +
            "🌐 <b>Traffic Source:</b> " + escapeHtml(lead.adSource || "Direct / Organic") + "\n" +
            "🆔 <b>Lead ID:</b> <code>" + escapeHtml(lead.id || "") + "</code>\n" +
            (lead.message ? "💬 <b>Message:</b> <i>" + escapeHtml(lead.message) + "</i>\n" : "") +
            "━━━━━━━━━━━━━━━━━━━━━\n" +
            "👉 <i>Quick Assign:</i> Reply\n<code>assign " + escapeHtml(lead.id) + " to Rajesh</code>";

  var keyboard = {
    inline_keyboard: [
      [
        { text: "📞 Call Lead", url: "tel:" + rawPhone },
        { text: "💬 WhatsApp", url: "https://wa.me/" + cleanPhone }
      ],
      [
        { text: "💼 Open Sales CRM", url: "https://www.odiins.in/crm" },
        { text: "🌐 Admin Center", url: "https://www.odiins.in/dashboard" }
      ]
    ]
  };

  for (var i = 0; i < chatIds.length; i++) {
    sendTelegramRaw(chatIds[i], msg, keyboard);
  }
}

/**
 * Handle Telegram inline button callbacks
 */
function handleTelegramCallback(callbackQuery, sheet) {
  var callbackId = callbackQuery.id;
  var data = callbackQuery.data || "";
  var chat = callbackQuery.message ? callbackQuery.message.chat : null;
  var chatId = chat ? chat.id : null;

  if (chatId) {
    registerAdminChatId(chatId);
  }

  answerTelegramCallback(callbackId, "Fetching data...");

  var fakeMessage = {
    chat: { id: chatId },
    from: callbackQuery.from,
    text: ""
  };

  if (data === "cb_today") fakeMessage.text = "today";
  else if (data === "cb_unassigned") fakeMessage.text = "unassigned";
  else if (data === "cb_summary") fakeMessage.text = "summary";
  else if (data === "cb_recent") fakeMessage.text = "recent";
  else fakeMessage.text = data;

  return handleTelegramMessage(fakeMessage, sheet);
}

/**
 * Natural language Telegram query & action handler
 */
function handleTelegramMessage(message, sheet) {
  var chatId = message.chat ? message.chat.id : null;
  if (!chatId) return ContentService.createTextOutput("OK");

  registerAdminChatId(chatId);

  var rawText = (message.text || "").trim();
  var text = rawText.toLowerCase();
  var rows = sheet.getDataRange().getValues();
  var totalLeads = Math.max(0, rows.length - 1);

  var reply = "";
  var keyboard = null;

  // 1. Menu / Welcome / Help
  if (text === "/start" || text === "hi" || text === "hello" || text === "help" || text === "/help" || text === "menu") {
    reply = "👋 <b>Namaskar Sarbjeet! Welcome to Odiins AI Command Assistant</b> 🚀\n\n" +
            "I am directly connected to your website, Google Sheet database, and live CRM.\n\n" +
            "⚡ <b>Available Actions:</b>\n" +
            "• 📊 <b>today</b> - Leads received today with contact details\n" +
            "• ⏳ <b>unassigned</b> - Unallocated leads needing attention\n" +
            "• 📈 <b>summary</b> / <b>total</b> - Complete pipeline & category breakdown\n" +
            "• 🕒 <b>recent</b> - Latest 5 leads submitted to Odiins\n" +
            "• 👤 <b>assign &lt;ID&gt; to &lt;Name&gt;</b> - Instant lead allocation\n" +
            "• 🔄 <b>status &lt;ID&gt; &lt;Status&gt;</b> - Update status (e.g. <code>status OD-MULF0FB0 In Progress</code>)\n" +
            "• 🔍 <b>Any Search</b> - Search candidate (<i>Boby</i>), phone (<i>9658620364</i>), location (<i>Puri</i>, <i>Sambalpur</i>), or role (<i>Sales Manager</i>, <i>Cook</i>, <i>CSP</i>)!\n\n" +
            "<i>Tap a quick button below or type your query:</i>";

    keyboard = {
      inline_keyboard: [
        [
          { text: "📊 Today's Leads", callback_data: "cb_today" },
          { text: "⏳ Unassigned Leads", callback_data: "cb_unassigned" }
        ],
        [
          { text: "📈 Pipeline Summary", callback_data: "cb_summary" },
          { text: "🕒 Recent 5 Leads", callback_data: "cb_recent" }
        ],
        [
          { text: "🌐 Open Admin CRM", url: "https://www.odiins.in/dashboard" },
          { text: "📱 Open Sales CRM", url: "https://www.odiins.in/crm" }
        ]
      ]
    };
  }

  // 2. Direct Lead Assignment via Telegram: "assign <LeadID> to <ExecutiveName>"
  else if (/^assign\s+/i.test(text)) {
    var match = rawText.match(/^assign\s+([A-Za-z0-9_-]+)(?:\s+to)?\s+(.+)$/i);
    if (!match) {
      reply = "⚠️ <b>Invalid assign format.</b>\nPlease use:\n<code>assign &lt;LeadID&gt; to &lt;ExecutiveName&gt;</code>\n\nExample: <code>assign OD-MULF0FB0 to Rajesh Nayak</code>";
    } else {
      var targetId = match[1].trim();
      var execName = match[2].trim();
      var foundRow = -1;
      var candidateName = "";
      var role = "";

      for (var i = 1; i < rows.length; i++) {
        if (String(rows[i][1]).trim().toLowerCase() === targetId.toLowerCase()) {
          foundRow = i + 1;
          candidateName = String(rows[i][3] || "");
          role = String(rows[i][7] || "");
          break;
        }
      }

      if (foundRow !== -1) {
        sheet.getRange(foundRow, 12).setValue(execName); // Column L: Assigned To
        sheet.getRange(foundRow, 11).setValue("In Progress"); // Column K: Status
        
        reply = "✅ <b>Lead Assigned Successfully!</b> 🎯\n\n" +
                "🆔 <b>Lead ID:</b> <code>" + escapeHtml(targetId) + "</code>\n" +
                "👤 <b>Candidate:</b> " + escapeHtml(candidateName) + "\n" +
                "💼 <b>Role:</b> " + escapeHtml(role) + "\n" +
                "⚡ <b>Assigned To:</b> <b>" + escapeHtml(execName) + "</b>\n" +
                "📊 <b>Status:</b> Updated to <i>In Progress</i>\n\n" +
                "<i>Sync completed across Google Sheet and CRM Dashboard.</i>";
      } else {
        reply = "❌ <b>Lead ID Not Found:</b> <code>" + escapeHtml(targetId) + "</code>\nPlease check the ID or type <code>unassigned</code> to list pending leads.";
      }
    }
  }

  // 3. Status Update: "status <LeadID> <NewStatus>"
  else if (/^status\s+/i.test(text)) {
    var sMatch = rawText.match(/^status\s+([A-Za-z0-9_-]+)\s+(.+)$/i);
    if (!sMatch) {
      reply = "⚠️ <b>Invalid status format.</b>\nPlease use:\n<code>status &lt;LeadID&gt; &lt;New / In Progress / Closed / Rejected&gt;</code>";
    } else {
      var sTargetId = sMatch[1].trim();
      var newStatus = sMatch[2].trim();
      var sFoundRow = -1;
      var sCandName = "";

      for (var j = 1; j < rows.length; j++) {
        if (String(rows[j][1]).trim().toLowerCase() === sTargetId.toLowerCase()) {
          sFoundRow = j + 1;
          sCandName = String(rows[j][3] || "");
          break;
        }
      }

      if (sFoundRow !== -1) {
        sheet.getRange(sFoundRow, 11).setValue(newStatus); // Column K: Status
        reply = "✅ <b>Status Updated!</b>\n\n" +
                "🆔 Lead: <code>" + escapeHtml(sTargetId) + "</code> (" + escapeHtml(sCandName) + ")\n" +
                "📊 New Status: <b>" + escapeHtml(newStatus) + "</b>";
      } else {
        reply = "❌ <b>Lead ID Not Found:</b> <code>" + escapeHtml(sTargetId) + "</code>";
      }
    }
  }

  // 4. Unassigned Leads Query
  else if (text.indexOf("unassigned") !== -1 || text.indexOf("pending") !== -1) {
    var unassignedList = [];
    for (var k = rows.length - 1; k >= 1; k--) {
      var assignee = String(rows[k][11] || "").trim();
      var st = String(rows[k][10] || "").trim();
      if (!assignee || assignee.toLowerCase() === "unassigned" || st.toLowerCase() === "new") {
        var rawPhoneU = String(rows[k][4] || "").replace(/^'/, '');
        unassignedList.push(
          "🆔 <code>" + rows[k][1] + "</code> — <b>" + escapeHtml(rows[k][3]) + "</b>\n" +
          "💼 " + escapeHtml(rows[k][7]) + " [" + escapeHtml(rows[k][2]) + "]\n" +
          "📍 " + escapeHtml(rows[k][5]) + " | 📞 " + rawPhoneU + "\n" +
          "👉 <i>Quick Assign:</i> <code>assign " + rows[k][1] + " to Rajesh</code>"
        );
        if (unassignedList.length >= 8) break;
      }
    }

    if (unassignedList.length > 0) {
      reply = "⏳ <b>Unassigned / Pending Leads (" + unassignedList.length + " shown):</b>\n\n" +
              unassignedList.join("\n\n---\n\n");
    } else {
      reply = "🎉 <b>All caught up!</b> There are no unassigned leads right now. All leads have an assigned executive.";
    }
  }

  // 5. Today's Leads Query
  else if (text.indexOf("today") !== -1) {
    var todayStr = Utilities.formatDate(new Date(), "Asia/Kolkata", "yyyy-MM-dd");
    var todayCount = 0;
    var todayCards = [];

    for (var m = rows.length - 1; m >= 1; m--) {
      var d = "";
      if (rows[m][0] instanceof Date) {
        d = Utilities.formatDate(rows[m][0], "Asia/Kolkata", "yyyy-MM-dd");
      } else {
        d = String(rows[m][0] || "").substring(0, 10);
      }

      if (d === todayStr) {
        todayCount++;
        var tPhone = String(rows[m][4] || "").replace(/^'/, '');
        var tCleanPhone = tPhone.replace(/[^0-9]/g, '');
        if (tCleanPhone.length === 10) tCleanPhone = '91' + tCleanPhone;

        todayCards.push(
          "👤 <b>" + escapeHtml(rows[m][3]) + "</b> (<code>" + rows[m][1] + "</code>)\n" +
          "💼 Role: " + escapeHtml(rows[m][7]) + " [" + escapeHtml(rows[m][2]) + "]\n" +
          "📞 Phone: <a href=\"tel:" + tPhone + "\">" + tPhone + "</a>" +
          (tCleanPhone ? " | <a href=\"https://wa.me/" + tCleanPhone + "\">WhatsApp</a>" : "") + "\n" +
          "📍 " + escapeHtml(rows[m][5]) + " | ⚡ " + escapeHtml(rows[m][10]) + " (" + (rows[m][11] || "Unassigned") + ")"
        );
      }
    }

    reply = "📊 <b>Today's Leads (" + todayStr + "): " + todayCount + "</b>\n\n" +
            (todayCards.length > 0 ? todayCards.join("\n\n---\n\n") : "No new leads recorded today yet. When candidates apply on odiins.in, you'll receive an instant notification here!");
  }

  // 6. Summary / Pipeline Stats
  else if (text.indexOf("total") !== -1 || text.indexOf("summary") !== -1 || text.indexOf("stats") !== -1 || text.indexOf("count") !== -1) {
    var newC = 0, inProgC = 0, closedC = 0, unassignedC = 0;
    var catCounts = {};

    for (var n = 1; n < rows.length; n++) {
      var sStatus = String(rows[n][10] || "New").trim();
      var sAssign = String(rows[n][11] || "").trim();
      var sCat = String(rows[n][2] || "Other").trim();

      if (sStatus === "New") newC++;
      else if (sStatus === "In Progress" || sStatus === "Contacted" || sStatus === "Interview Scheduled") inProgC++;
      else if (sStatus.indexOf("Closed") !== -1 || sStatus.indexOf("Placed") !== -1 || sStatus === "Converted") closedC++;

      if (!sAssign || sAssign.toLowerCase() === "unassigned") unassignedC++;

      catCounts[sCat] = (catCounts[sCat] || 0) + 1;
    }

    var topCats = Object.keys(catCounts).map(function(k){ return "• " + k + ": <b>" + catCounts[k] + "</b>"; }).join("\n");

    reply = "📈 <b>Odiins Global Services - Pipeline Summary</b>\n" +
            "━━━━━━━━━━━━━━━━━━━━━\n" +
            "📊 <b>Total Leads:</b> <b>" + totalLeads + "</b>\n" +
            "🆕 <b>New / Uncontacted:</b> <b>" + newC + "</b>\n" +
            "⏳ <b>Unassigned to Staff:</b> <b>" + unassignedC + "</b>\n" +
            "⚡ <b>In Progress / Active:</b> <b>" + inProgC + "</b>\n" +
            "🏆 <b>Closed / Placed:</b> <b>" + closedC + "</b>\n\n" +
            "📂 <b>By Category:</b>\n" + (topCats || "None") + "\n" +
            "━━━━━━━━━━━━━━━━━━━━━\n" +
            "💡 <i>Type 'unassigned' or 'today' for immediate action items.</i>";

    keyboard = {
      inline_keyboard: [
        [
          { text: "⏳ View Unassigned", callback_data: "cb_unassigned" },
          { text: "📊 View Today's Leads", callback_data: "cb_today" }
        ],
        [
          { text: "🌐 Open Admin CRM", url: "https://www.odiins.in/dashboard" }
        ]
      ]
    };
  }

  // 7. Recent 5 Leads
  else if (text.indexOf("recent") !== -1 || text.indexOf("latest") !== -1) {
    var recentCards = [];
    var count = 0;
    for (var r = rows.length - 1; r >= 1; r--) {
      var rDate = "";
      if (rows[r][0] instanceof Date) {
        rDate = Utilities.formatDate(rows[r][0], "Asia/Kolkata", "dd MMM, HH:mm");
      } else {
        rDate = String(rows[r][0] || "");
      }
      var rPhone = String(rows[r][4] || "").replace(/^'/, '');
      var rClean = rPhone.replace(/[^0-9]/g, '');
      if (rClean.length === 10) rClean = '91' + rClean;

      recentCards.push(
        "🆔 <code>" + rows[r][1] + "</code> — <b>" + escapeHtml(rows[r][3]) + "</b> (" + rDate + ")\n" +
        "💼 Role: " + escapeHtml(rows[r][7]) + " [" + escapeHtml(rows[r][2]) + "]\n" +
        "📍 District: " + escapeHtml(rows[r][5]) + (rows[r][6] ? " (" + escapeHtml(rows[r][6]) + ")" : "") + "\n" +
        "📞 Phone: <a href=\"tel:" + rPhone + "\">" + rPhone + "</a>" +
        (rClean ? " | <a href=\"https://wa.me/" + rClean + "\">WhatsApp</a>" : "") + "\n" +
        "⚡ Status: <b>" + escapeHtml(rows[r][10]) + "</b> | Assigned: <i>" + (rows[r][11] || "Unassigned") + "</i>"
      );
      count++;
      if (count >= 5) break;
    }

    reply = "🕒 <b>Latest 5 Leads on Odiins:</b>\n\n" + recentCards.join("\n\n---\n\n");
  }

  // 8. Natural Keyword Search across All Columns
  else {
    var searchTerms = text.split(/\s+/).filter(function(t){ return t.length > 1; });
    var matches = [];

    for (var q = rows.length - 1; q >= 1; q--) {
      var rowFullStr = rows[q].join(" ").toLowerCase();
      var isMatch = false;

      if (rowFullStr.indexOf(text) !== -1) {
        isMatch = true;
      } else if (searchTerms.length > 0) {
        isMatch = searchTerms.every(function(term){ return rowFullStr.indexOf(term) !== -1; });
      }

      if (isMatch) {
        var sPhone = String(rows[q][4] || "").replace(/^'/, '');
        var sClean = sPhone.replace(/[^0-9]/g, '');
        if (sClean.length === 10) sClean = '91' + sClean;

        matches.push(
          "👤 <b>" + escapeHtml(rows[q][3]) + "</b> (<code>" + rows[q][1] + "</code>)\n" +
          "📞 Phone: <a href=\"tel:" + sPhone + "\">" + sPhone + "</a>" +
          (sClean ? " | <a href=\"https://wa.me/" + sClean + "\">WhatsApp Chat</a>" : "") + "\n" +
          "💼 Role: " + escapeHtml(rows[q][7]) + " [" + escapeHtml(rows[q][2]) + "]\n" +
          "📍 Location: " + escapeHtml(rows[q][5]) + (rows[q][6] ? " (" + escapeHtml(rows[q][6]) + ")" : "") + "\n" +
          "⚡ Status: <b>" + escapeHtml(rows[q][10]) + "</b> | Assigned: <i>" + (rows[q][11] || "Unassigned") + "</i>" +
          (rows[q][12] ? "\n💬 Notes: <i>" + escapeHtml(rows[q][12]) + "</i>" : "")
        );
        if (matches.length >= 5) break;
      }
    }

    if (matches.length > 0) {
      reply = "🔍 <b>Found " + matches.length + " lead(s) for '" + escapeHtml(rawText) + "':</b>\n\n" +
              matches.join("\n\n---\n\n");
    } else {
      reply = "❓ No leads found matching '<b>" + escapeHtml(rawText) + "</b>'.\n\n" +
              "💡 <b>Search Tips:</b>\n" +
              "• Candidate Name: e.g. <i>Boby</i> or <i>Prakash</i>\n" +
              "• Phone Number: e.g. <i>9658620364</i>\n" +
              "• District/City: e.g. <i>Sambalpur</i>, <i>Puri</i>, <i>Bhubaneswar</i>\n" +
              "• Role / Service: e.g. <i>Sales Manager</i>, <i>Data Entry</i>, <i>Cook</i>, <i>CSP</i>\n" +
              "• Type <b>menu</b> or <b>/start</b> to see all options.";
    }
  }

  sendTelegramRaw(chatId, reply, keyboard);

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
