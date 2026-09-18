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
        <span class="section-badge">Manpower &amp; Staffing for Odisha Enterprises</span>
        <h1 class="hero-headline">Hire the Right People, Without the Hassle</h1>
        <p class="hero-sub">Finding reliable candidates in Odisha shouldn't take weeks of sifting through irrelevant resumes. Odiins shortlists verified candidates who are ready to work.</p>

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
            <input type="text" id="empPosition" name="position" class="form-control" placeholder="e.g. 2 Sales Executives, 1 Accountant" required>
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
        <a href="tel:+917008012345" class="btn btn-outline-white btn-lg">📞 Call Corporate Desk</a>
      </div>
    </div>
  </section>

<?php
get_footer();
?>
