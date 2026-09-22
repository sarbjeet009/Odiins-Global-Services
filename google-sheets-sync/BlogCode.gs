// ==============================================================================
// ODIINS BLOG & SEO CONTENT HUB - GOOGLE APPS SCRIPT WEB APP
// Automatically receives published blog articles and logs all details + full text
// ==============================================================================

function doPost(e) {
  var lock = LockService.getScriptLock();
  lock.tryLock(10000);

  try {
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
    
    // Auto-create header row with formatting if sheet is fresh/empty
    if (sheet.getLastRow() === 0) {
      var headers = [
        "Timestamp (IST)",
        "Blog ID",
        "Category",
        "Article Title (H1)",
        "Live URL Slug",
        "Primary Keyword",
        "Secondary Keywords",
        "Meta Title Tag",
        "Meta Description Tag",
        "Word Count",
        "Target Audience",
        "Internal Links (SEO Juice)",
        "Primary Conversion CTA",
        "Status",
        "Complete Published Article Text"
      ];
      sheet.appendRow(headers);
      
      // Style Header
      var headerRange = sheet.getRange(1, 1, 1, headers.length);
      headerRange.setFontWeight("bold");
      headerRange.setBackground("#2D6A4F"); // Odiins Dark Green
      headerRange.setFontColor("#FFFFFF");
      headerRange.setWrap(true);
      sheet.setFrozenRows(1);
      sheet.setRowHeight(1, 40);
      
      // Set Column Widths for readability
      sheet.setColumnWidth(1, 150); // Timestamp
      sheet.setColumnWidth(2, 100); // Blog ID
      sheet.setColumnWidth(3, 160); // Category
      sheet.setColumnWidth(4, 300); // Title
      sheet.setColumnWidth(5, 300); // URL
      sheet.setColumnWidth(6, 180); // Primary Keyword
      sheet.setColumnWidth(7, 220); // Secondary Keywords
      sheet.setColumnWidth(8, 280); // Meta Title
      sheet.setColumnWidth(9, 320); // Meta Description
      sheet.setColumnWidth(10, 110); // Word Count
      sheet.setColumnWidth(11, 220); // Target Audience
      sheet.setColumnWidth(12, 260); // Internal Links
      sheet.setColumnWidth(13, 220); // Primary CTA
      sheet.setColumnWidth(14, 110); // Status
      sheet.setColumnWidth(15, 600); // Full Published Text
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
      data.blogId || "BLOG-01",
      data.category || "",
      data.title || "",
      data.url || "",
      data.primaryKeyword || "",
      data.secondaryKeywords || "",
      data.metaTitle || "",
      data.metaDescription || "",
      data.wordCount || "",
      data.targetAudience || "",
      data.internalLinks || "",
      data.primaryCta || "",
      data.status || "Published",
      data.articleBody || ""
    ]);

    var lastRow = sheet.getLastRow();
    // Wrap text on the article body column
    sheet.getRange(lastRow, 15).setWrap(true);

    return ContentService.createTextOutput(JSON.stringify({ 
      result: "success", 
      message: "Blog article logged successfully",
      row: lastRow 
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

function doGet(e) {
  return ContentService.createTextOutput("Odiins Content Hub Webhook is Active!");
}
