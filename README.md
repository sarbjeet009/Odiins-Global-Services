# Odiins | Odisha's Own HR, Manpower & Home Help Platform

A mobile-first, multi-page website and lead generation engine for **Odiins**, connecting businesses with staff, job seekers with local opportunities, and households with dependable domestic help across all 30 districts of Odisha.

## Key Features
- **Strict Brand Design System**: Complies with exact color palette:
  - Primary Green: `#098B38` (All main buttons, ticker, badges, footer, success states)
  - Primary Blue: `#64A8DA` (Section accents, icons, hover states, card highlights)
  - Light Blue: `#EAF3FA` (Alternating section backgrounds)
  - Main White: `#FFFFFF` (Backgrounds, cards)
  - Dark Charcoal: `#1F2937` (High-contrast typography; no white text on light blue)
- **15-Tier Structured Homepage**: Continuous ticker marquee, hero with 3 primary green CTAs, animated counter strip, 3 pillar cards, 11 business roles grid, 8 home services grid, 4-step workflow, 6 trust badges, media carousel, 30-district interactive selector, FAQs, and final conversion banner.
- **High-Converting Landing Pages**:
  - `services-job-seekers.html`: 4-field registration, zero fees, local district jobs.
  - `services-employers.html`: 4-field business requirement submission, pre-screened talent.
  - `services-customers.html`: 4-field home help request (maid, cook, driver, pandit).
- **About Us & Content**:
  - `about-vision-mission.html`: Vision, 5-point mission, values grid, local-first manifesto.
  - `about-media.html`: Filter tabs (News, Awards, Events, Videos), partner logo strip.
  - `blogs.html` & `blog-detail.html`: 6 Odisha-focused articles, search bar, category filters, 2-field sidebar callback.
  - `contact.html`: Map card, working hours, 4-field message form, direct WhatsApp/Call links.
- **Admin Lead Portal & Spreadsheet Sync (`dashboard.html`)**:
  - Live lead table with status tracking (`New`, `Contacted`, `In Progress`, `Closed`).
  - Search and filter by category or district.
  - One-tap WhatsApp chat & phone dialer.
  - One-click **Export to CSV / Excel Spreadsheet**.
  - Simulated admin email notification log.
- **Spam Protection & Persistence**:
  - Invisible honeypot field (`website_hp`)
  - Sub-second robot submission blocker
  - Dual-mode data storage: Persistent `leads.json` & `leads.csv` on server, with `localStorage` fallback in static browser previews.

## Running the Platform

To start the server:
```bash
npm start
```
Then visit:
- **Main Website**: [http://localhost:3000](http://localhost:3000)
- **Admin Lead Dashboard**: [http://localhost:3000/dashboard.html](http://localhost:3000/dashboard.html)
- **Export Leads Spreadsheet**: [http://localhost:3000/api/leads/export.csv](http://localhost:3000/api/leads/export.csv)
