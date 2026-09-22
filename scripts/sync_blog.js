const https = require('https');
const fs = require('fs');
const path = require('path');

const blogFile = process.argv[2] || 'data/blog_articles/blog-01.json';
const webhookUrl = process.argv[3] || process.env.BLOG_WEBHOOK_URL;

if (!webhookUrl) {
  console.error("❌ Error: Please provide the Google Sheet Webhook URL.");
  console.log("Usage: node scripts/sync_blog.js data/blog_articles/blog-01.json <WEBHOOK_URL>");
  process.exit(1);
}

const payload = JSON.parse(fs.readFileSync(path.resolve(blogFile), 'utf8'));

console.log(`📤 Pushing ${payload.blogId}: "${payload.title}" to Google Sheet...`);

const postData = JSON.stringify(payload);

function makeRequest(targetUrl) {
  const urlObj = new URL(targetUrl);
  const options = {
    hostname: urlObj.hostname,
    path: urlObj.pathname + urlObj.search,
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Content-Length': Buffer.byteLength(postData)
    }
  };

  const req = https.request(options, (res) => {
    if (res.statusCode >= 300 && res.statusCode < 400 && res.headers.location) {
      https.get(res.headers.location, (redirectRes) => {
        let body = '';
        redirectRes.on('data', chunk => body += chunk);
        redirectRes.on('end', () => {
          console.log("✅ Successfully synced to Google Sheet!");
          console.log("Response:", body);
        });
      });
      return;
    }

    let responseData = '';
    res.on('data', chunk => responseData += chunk);
    res.on('end', () => {
      console.log("✅ Successfully synced to Google Sheet!");
      console.log("Response:", responseData);
    });
  });

  req.on('error', (err) => {
    console.error("❌ Sync Error:", err.message);
  });

  req.write(postData);
  req.end();
}

makeRequest(webhookUrl);
