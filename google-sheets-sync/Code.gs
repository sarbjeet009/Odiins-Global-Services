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
