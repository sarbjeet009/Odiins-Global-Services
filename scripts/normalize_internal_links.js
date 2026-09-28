const fs = require('fs');
const path = require('path');

const rootDir = path.resolve(__dirname, '..');

const replacements = [
  { from: /href="index\.html"/g, to: 'href="/"' },
  { from: /href="services-job-seekers\.html"/g, to: 'href="/services-job-seekers"' },
  { from: /href="services-employers\.html"/g, to: 'href="/services-employers"' },
  { from: /href="services-customers\.html"/g, to: 'href="/services-customers"' },
  { from: /href="about-vision-mission\.html"/g, to: 'href="/about-vision-mission"' },
  { from: /href="about-media\.html"/g, to: 'href="/about-media"' },
  { from: /href="blogs\.html"/g, to: 'href="/blogs"' },
  { from: /href="contact\.html"/g, to: 'href="/contact"' },
  { from: /href="privacy-policy\.html"/g, to: 'href="/privacy-policy"' },
  { from: /href="terms\.html"/g, to: 'href="/terms"' },
  { from: /href="disclaimer\.html"/g, to: 'href="/disclaimer"' },
  { from: /href="bank-csp-odisha\.html"/g, to: 'href="/bank-csp-odisha"' },
  { from: /href="how-to-hire-sales-managers-manpower-in-bhubaneswar\.html"/g, to: 'href="/how-to-hire-sales-managers-manpower-in-bhubaneswar"' },
  { from: /href="top-in-demand-private-jobs-in-bhubaneswar-odisha\.html"/g, to: 'href="/top-in-demand-private-jobs-in-bhubaneswar-odisha"' },
  { from: /href="guide-to-hiring-verified-maids-cooks-tutors-bhubaneswar\.html"/g, to: 'href="/guide-to-hiring-verified-maids-cooks-tutors-bhubaneswar"' },
  { from: /href="patient-care-home-nursing-services-in-bhubaneswar\.html"/g, to: 'href="/patient-care-home-nursing-services-in-bhubaneswar"' },
  { from: /href="showroom-retail-manpower-staffing-solutions-bhubaneswar\.html"/g, to: 'href="/showroom-retail-manpower-staffing-solutions-bhubaneswar"' },
  { from: /href="how-to-apply-bank-csp-operator-odisha\.html"/g, to: 'href="/how-to-apply-bank-csp-operator-odisha"' },
  { from: /href="staffing-and-manpower-solutions-in-bhubaneswar\.html"/g, to: 'href="/staffing-and-manpower-solutions-in-bhubaneswar"' },
  { from: /href="staffing-services-in-bhubaneswar\.html"/g, to: 'href="/services-employers"' },
  { from: /href="staffing-services-bhubaneswar\.html"/g, to: 'href="/services-employers"' },
  { from: /href="blog-detail\.html"/g, to: 'href="/blogs"' },
];

const files = fs.readdirSync(rootDir).filter(f => f.endsWith('.html'));

let totalReplacements = 0;

files.forEach(file => {
  const filePath = path.join(rootDir, file);
  let content = fs.readFileSync(filePath, 'utf8');
  let original = content;

  replacements.forEach(r => {
    content = content.replace(r.from, r.to);
  });

  if (content !== original) {
    fs.writeFileSync(filePath, content, 'utf8');
    console.log(`✅ Normalized links in: ${file}`);
    totalReplacements++;
  }
});

console.log(`Done! Updated ${totalReplacements} HTML files with clean canonical internal links.`);
