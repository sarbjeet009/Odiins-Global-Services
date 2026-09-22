<?php
/**
 * Template Name: Odiins - For Employers & Business
 */
get_header();
?>

<!-- LANDING HERO & SHORT FORM -->
  <section class="landing-hero" id="employerFormSection">
    <div class="container landing-grid">
      <!-- Left: Headline + 4 Benefits -->
      <div class="hero-content">
        <span class="section-badge">Manpower &amp; Staffing Solutions across Odisha</span>
        <h1 class="hero-headline">Hire Verified Talent in Odisha — From Ground Staff to Sales Managers</h1>
        <p class="hero-sub">Powering retail showrooms, logistics hubs, healthcare, and corporate offices with vetted candidates living right in your district. Fast matching with zero screening headaches.</p>

        <!-- 4 Benefit Bullets -->
        <div class="landing-benefits">
          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Saves Screening Time</h4>
              <p>We filter through hundreds of profiles to send you only candidates that match your exact skillset, salary, and location criteria.</p>
            </div>
          </div>

          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Local Candidates Ready to Join</h4>
              <p>Candidates living within your city or district with zero relocation delays and strong local community ties.</p>
            </div>
          </div>

          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>One Hire or Bulk Recruitment</h4>
              <p>Whether you need a single accountant for your shop or 20 delivery riders for your warehouse fleet, we scale to your demand.</p>
            </div>
          </div>

          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Single Point of Contact</h4>
              <p>Your dedicated Odiins recruitment manager coordinates candidate shortlisting, calls, and interview line-ups seamlessly.</p>
            </div>
          </div>
        </div>

        <div class="hero-trust-line">
          <span>🏢 1,250+ Odisha Businesses</span>
          <span>•</span>
          <span>⚡ 24h Shortlist</span>
          <span>•</span>
          <span>🛡️ Verified Profiles</span>
        </div>
      </div>

      <!-- Right: Short Form (4 Fields) -->
      <div class="form-card">
        <div class="form-card-header">
          <span class="section-badge" style="margin-bottom:0.4rem;">Business Requirement</span>
          <h3>Submit Your Hiring Need</h3>
          <p>Tell us what roles you need. Our team will contact you within 24 hours.</p>
        </div>

        <form id="employerForm" novalidate>
          <input type="text" name="website_hp" class="hp-trap" tabindex="-1" autocomplete="off">

          <!-- Field 1: Business Name* -->
          <div class="form-group">
            <label class="form-label" for="empBizName">Business / Company Name *</label>
            <input type="text" id="empBizName" name="businessName" class="form-control" placeholder="e.g. Utkal Retail &amp; Logistics" required>
          </div>

          <!-- Field 2: Phone/WhatsApp* -->
          <div class="form-group">
            <label class="form-label" for="empPhone">Phone / WhatsApp Number *</label>
            <input type="tel" id="empPhone" name="phone" class="form-control" placeholder="e.g. 70081 23456" required pattern="[0-9]{10}">
          </div>

          <!-- Field 3: Position Needed* -->
          <div class="form-group">
            <label class="form-label" for="empPosition">Position Needed *</label>
            <input type="text" id="empPosition" name="position" class="form-control" placeholder="e.g. 1 Sales Manager, 2 Sales Executives, 1 Accountant" required>
          </div>

          <!-- Field 4: City/District* -->
          <div class="form-group">
            <label class="form-label" for="empCity">City / District in Odisha *</label>
            <input type="text" id="empCity" name="city" class="form-control" placeholder="e.g. Bhubaneswar (Patia), Cuttack" required>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-green btn-block btn-lg">Submit Requirement</button>

          <!-- Consent Line under Button -->
          <p class="form-consent">By submitting, you agree to be contacted by Odiins.</p>
        </form>

        <!-- Success Banner -->
        <div class="form-success-banner">
          <div class="form-success-icon">✓</div>
          <h4>Thank you! We'll call you within 24 hours.</h4>
          <p>Our corporate staffing desk has received your requirement. An account manager will reach out with candidate profiles.</p>
          <div style="margin-top: 1rem;">
            <a href="#" class="btn btn-green btn-sm instant-wa-btn" target="_blank">💬 Discuss on WhatsApp</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- WHO WE SUPPLY TO -->
  <section class="section section-white">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">Industry Coverage</span>
        <h2>Industries We Power Across Odisha</h2>
        <p>From local retail stores to expanding logistics networks.</p>
      </div>

      <div class="roles-grid">
        <div class="role-pill">
          <div class="role-pill-icon">🏬</div>
          <span class="role-pill-name">Retail &amp; Showrooms</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">🚚</div>
          <span class="role-pill-name">Logistics &amp; Delivery</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">🏨</div>
          <span class="role-pill-name">Hotels &amp; Hospitality</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">🏥</div>
          <span class="role-pill-name">Clinics &amp; Healthcare</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">🏢</div>
          <span class="role-pill-name">Corporate Offices</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">🏭</div>
          <span class="role-pill-name">MSME Warehouses</span>
        </div>
      </div>
    </div>
  </section>

  <!-- 3-TIER ROLES WE SOURCE & STAFF -->
  <section class="section section-alt" style="padding-top: 3.5rem; padding-bottom: 3.5rem;">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">Comprehensive Staffing Spectrum</span>
        <h2>Roles We Source &amp; Staff Across Odisha</h2>
        <p>From strategic managerial leadership to frontline operations and back-office teams.</p>
      </div>

      <!-- Tier 1: Leadership & Management -->
      <div style="margin-bottom: 2.5rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
          <span style="background: #FEF3C7; color: #92400E; font-size: 0.85rem; font-weight: 800; padding: 0.35rem 0.85rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px;">Level 1</span>
          <h3 style="margin: 0; font-size: 1.3rem; color: var(--text-charcoal); font-weight: 700;">Leadership &amp; Managerial Roles</h3>
        </div>
        <div class="roles-grid">
          <div class="role-pill">
            <div class="role-pill-icon">👔</div>
            <span class="role-pill-name"><strong>Sales Managers</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">B2B, FMCG &amp; Retail</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏬</div>
            <span class="role-pill-name"><strong>Store Managers</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Retail &amp; Showrooms</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📦</div>
            <span class="role-pill-name"><strong>Warehouse Leads</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Floor &amp; Shift In-Charges</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏨</div>
            <span class="role-pill-name"><strong>Hospitality In-Charges</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Hotels &amp; Banquets</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🎧</div>
            <span class="role-pill-name"><strong>Team Leaders</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Telecalling &amp; Support</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📑</div>
            <span class="role-pill-name"><strong>HR &amp; Admin Officers</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Operations &amp; Payroll</small>
          </div>
        </div>
      </div>

      <!-- Tier 2: Professional & Back Office -->
      <div style="margin-bottom: 2.5rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
          <span style="background: #E0E7FF; color: #3730A3; font-size: 0.85rem; font-weight: 800; padding: 0.35rem 0.85rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px;">Level 2</span>
          <h3 style="margin: 0; font-size: 1.3rem; color: var(--text-charcoal); font-weight: 700;">Professional &amp; Back Office Staff</h3>
        </div>
        <div class="roles-grid">
          <div class="role-pill">
            <div class="role-pill-icon">📊</div>
            <span class="role-pill-name"><strong>Accountants</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Tally, GST &amp; Billing</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📈</div>
            <span class="role-pill-name"><strong>Sales Executives</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Field &amp; Counter Sales</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">⌨️</div>
            <span class="role-pill-name"><strong>Data Entry Operators</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">MIS &amp; Excel Specialists</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📞</div>
            <span class="role-pill-name"><strong>Telecallers &amp; CRM</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Lead Generation &amp; BPO</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏢</div>
            <span class="role-pill-name"><strong>Front Desk Reception</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Offices, Clinics &amp; Hotels</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📎</div>
            <span class="role-pill-name"><strong>Office Peon &amp; Helpers</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Filing &amp; Daily Support</small>
          </div>
        </div>
      </div>

      <!-- Tier 3: Operations, Security & Fleet -->
      <div>
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
          <span style="background: #DCFCE7; color: #166534; font-size: 0.85rem; font-weight: 800; padding: 0.35rem 0.85rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px;">Level 3</span>
          <h3 style="margin: 0; font-size: 1.3rem; color: var(--text-charcoal); font-weight: 700;">Operations, Security &amp; Fleet Logistics</h3>
        </div>
        <div class="roles-grid">
          <div class="role-pill">
            <div class="role-pill-icon">🛡️</div>
            <span class="role-pill-name"><strong>Security Guards</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Trained &amp; Police-Checked</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🚚</div>
            <span class="role-pill-name"><strong>Delivery Fleet (2-W)</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">E-Commerce &amp; Courier</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🚗</div>
            <span class="role-pill-name"><strong>Commercial Drivers</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Light &amp; Heavy Vehicle</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏗️</div>
            <span class="role-pill-name"><strong>Warehouse Loaders</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Material Handling Labor</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏥</div>
            <span class="role-pill-name"><strong>Hospital Ward Attendants</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Clinics &amp; Nursing Homes</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🧹</div>
            <span class="role-pill-name"><strong>Housekeeping Staff</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Commercial Janitorial</small>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- EMPLOYER FAQ SECTION -->
  <section class="section section-white" style="border-top: 1px solid var(--border-light); padding-top: 3.5rem; padding-bottom: 3.5rem;">
    <div class="container" style="max-width: 850px; margin: 0 auto;">
      <div class="section-header">
        <span class="section-badge">Employer FAQs</span>
        <h2>Frequently Asked Questions About Hiring in Odisha</h2>
        <p>Everything you need to know about partnering with Odiins for staff and managerial recruitment.</p>
      </div>

      <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div style="background: var(--bg-light); border-radius: var(--radius-md); padding: 1.5rem; border: 1px solid var(--border-light);">
          <h4 style="margin-top: 0; color: var(--text-charcoal); font-size: 1.1rem; font-weight: 700;">How quickly can Odiins provide candidates for our business in Bhubaneswar?</h4>
          <p style="margin-bottom: 0; color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">For frontline roles like delivery executives, retail sales, and security guards, we provide verified candidate shortlists within 24 to 48 hours. For specialized managerial roles such as Sales Managers or Tally Accountants, candidate interviews are typically lined up within 3 to 5 business days.</p>
        </div>

        <div style="background: var(--bg-light); border-radius: var(--radius-md); padding: 1.5rem; border: 1px solid var(--border-light);">
          <h4 style="margin-top: 0; color: var(--text-charcoal); font-size: 1.1rem; font-weight: 700;">Can Odiins recruit Sales Managers and Store In-Charges across Odisha?</h4>
          <p style="margin-bottom: 0; color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">Yes. Odiins has an active network of mid-level and senior managerial professionals across retail showrooms, FMCG distribution, logistics, and real estate across Bhubaneswar, Cuttack, Rourkela, Berhampur, and Balasore.</p>
        </div>

        <div style="background: var(--bg-light); border-radius: var(--radius-md); padding: 1.5rem; border: 1px solid var(--border-light);">
          <h4 style="margin-top: 0; color: var(--text-charcoal); font-size: 1.1rem; font-weight: 700;">Does Odiins conduct background verification and ID checks?</h4>
          <p style="margin-bottom: 0; color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">Yes. Every candidate profile submitted to an employer undergoes strict ID verification (Aadhaar &amp; PAN check), past employment reference check, and address verification to ensure trustworthy placements.</p>
        </div>

        <div style="background: var(--bg-light); border-radius: var(--radius-md); padding: 1.5rem; border: 1px solid var(--border-light);">
          <h4 style="margin-top: 0; color: var(--text-charcoal); font-size: 1.1rem; font-weight: 700;">What areas and industrial districts of Odisha do you cover?</h4>
          <p style="margin-bottom: 0; color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">While our primary corporate desk is in Bhubaneswar (covering Patia, Nayapalli, Saheed Nagar, Rasulgarh, Khandagiri) and Cuttack (Choudwar, Jagatpur), we supply commercial manpower across all 30 districts of Odisha including Jajpur, Angul, Jharsuguda, and Ganjam.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- EMPLOYER TESTIMONIAL / COMMITMENT -->
  <section class="section section-alt">
    <div class="container">
      <div style="background:var(--bg-white); border-radius:var(--radius-lg); padding:2.5rem; box-shadow:var(--card-shadow); border:1px solid var(--border-light); max-width:850px; margin:0 auto; text-align:center;">
        <span style="font-size:2.5rem; color:var(--primary-green);">“</span>
        <p style="font-size:1.15rem; font-weight:500; color:var(--text-charcoal); line-height:1.7; margin-bottom:1.25rem;">
          Our commitment to Odisha employers is simple: fast turnaround, pre-screened honesty, and local candidates who stay. No corporate red tape, just genuine business partnership.
        </p>
        <div style="font-weight:700; color:var(--text-charcoal);">Odiins Employer Desk</div>
        <div style="font-size:0.85rem; color:var(--text-muted);">Bhubaneswar &amp; Cuttack Hub</div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="final-cta">
    <div class="container">
      <h2>Build Your Team With Verified Odisha Talent</h2>
      <p>Submit your hiring requirement now, or call our corporate hiring desk for an immediate consultation.</p>
      <div class="final-cta-buttons">
        <a href="#employerFormSection" class="btn btn-white btn-lg">Submit Requirement &rarr;</a>
        <a href="tel:+919938079601" class="btn btn-outline-white btn-lg">📞 Call Corporate Desk</a>
      </div>
    </div>
  </section>

<?php
get_footer();
?>
