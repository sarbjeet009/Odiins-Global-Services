// ==============================================================================
// ODIINS PLATFORM - GOOGLE SPREADSHEET LEAD CAPTURE SCRIPT
// Spreadsheet: https://docs.google.com/spreadsheets/d/1_04IPlTACpbhlw0Cs3qhpJWVj2ypPY5sCKsgZTicsbM/edit
// Web App Deployment: https://script.google.com/macros/s/AKfycbwxblKhDvNFHne_A_5rXYGiqOE21Bg55Jnf6UBGtq4IQzPXFFd14Ncgrjl9NWF1lBlW/exec
// ==============================================================================

function doPost(e) {
  var lock = LockService.getScriptLock();
  lock.tryLock(10000);

  try {
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
    
    // Auto-create header row if sheet is fresh/empty
    if (sheet.getLastRow() === 0) {
      var headers = [
        "Date & Time (IST)",
        "Lead ID",
        "Full Name",
        "WhatsApp / Mobile",
        "District",
        "Block / Village / GP",
        "Qualification",
        "Shop Status",
        "Bank Distance",
        "Application Type",
        "Ad Platform",
        "Ad Campaign",
        "Status"
      ];
      sheet.appendRow(headers);
      sheet.getRange(1, 1, 1, headers.length).setFontWeight("bold").setBackground("#D1E7DD");
    }

    var data;
    try {
      data = JSON.parse(e.postData.contents);
    } catch (err) {
      data = e.parameter;
    }

    var timestamp = Utilities.formatDate(new Date(), "Asia/Kolkata", "yyyy-MM-dd HH:mm:ss");

    sheet.appendRow([
      timestamp,
      data.id || ("OD-" + new Date().getTime().toString(36).toUpperCase()),
      data.name || "",
      data.phone || "",
      data.district || "",
      data.areaCity || data.location || "",
      data.qualification || "",
      data.shopStatus || "",
      data.branchDistance || "",
      data.requirement || data.formType || "Bank CSP Operator",
      data.adSource || "Meta Ads",
      data.campaign || "bank_csp_odisha_campaign",
      data.status || "New"
    ]);

    return ContentService.createTextOutput(JSON.stringify({ result: "success" }))
      .setMimeType(ContentService.MimeType.JSON);

  } catch (error) {
    return ContentService.createTextOutput(JSON.stringify({ result: "error", error: error.toString() }))
      .setMimeType(ContentService.MimeType.JSON);
  } finally {
    lock.releaseLock();
  }
}

function doGet(e) {
  return ContentService.createTextOutput("Odiins Lead Capture Webhook is Active!");
}

// ==============================================================================
// 1-CLICK SEO ACTION PLAN GENERATOR (SHEET 2)
// Run this function once inside Apps Script to auto-populate "SEO Action Plan"
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
