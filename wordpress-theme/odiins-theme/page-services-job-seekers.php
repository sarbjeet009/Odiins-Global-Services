<?php
/**
 * Template Name: Odiins - For Job Seekers
 */
get_header();
?>

<!-- LANDING HERO & SHORT FORM -->
  <section class="landing-hero" id="registerSection">
    <div class="container landing-grid">
      <!-- Left: Headline + 4 Benefits -->
      <div class="hero-content">
        <span class="section-badge">Verified Career Opportunities across Odisha</span>
        <h1 class="hero-headline">Find a Job That Fits You, Right Here in Odisha</h1>
        <p class="hero-sub">Don't leave your state to find dignity and growth. Odiins connects job seekers directly with hiring managers across Odisha's best companies.</p>

        <!-- 4 Benefit Bullets -->
        <div class="landing-benefits">
          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Openings Across Offices, Sales, Support &amp; Driving</h4>
              <p>Diverse positions suited for matriculates, +2 pass, graduates, and vocational certificate holders.</p>
            </div>
          </div>

          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Help for Freshers and Experienced Alike</h4>
              <p>Whether taking your first career step or looking for a pay raise, we help you prepare and get interview calls.</p>
            </div>
          </div>

          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Jobs in Your Own District</h4>
              <p>Work close to your family. Opportunities in Bhubaneswar, Cuttack, Puri, Berhampur, Sambalpur, and more.</p>
            </div>
          </div>

          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Direct Guidance &amp; Support</h4>
              <p>Interview preparation, profile matching, and genuine hiring by verified Odisha employers.</p>
            </div>
          </div>
        </div>

        <div class="hero-trust-line">
          <span>🛡️ Verified Employers</span>
          <span>•</span>
          <span>⚡ Direct Employer Connect</span>
          <span>•</span>
          <span>📍 Odisha Wide</span>
        </div>
      </div>

      <!-- Right: Short Form (4 Fields) -->
      <div class="form-card">
        <div class="form-card-header">
          <span class="section-badge" style="margin-bottom:0.4rem;">Quick Registration</span>
          <h3>Register in 30 Seconds</h3>
          <p>Fill these 4 details. Our placement coordinator will call you within 24 hours.</p>
        </div>

        <form id="jobSeekerForm" novalidate>
          <!-- Spam Honeypot Field -->
          <input type="text" name="website_hp" class="hp-trap" tabindex="-1" autocomplete="off">

          <!-- Field 1: Name* -->
          <div class="form-group">
            <label class="form-label" for="jsName">Your Full Name *</label>
            <input type="text" id="jsName" name="name" class="form-control" placeholder="e.g. Subhashree Mohanty" required>
          </div>

          <!-- Field 2: Phone/WhatsApp* -->
          <div class="form-group">
            <label class="form-label" for="jsPhone">Phone / WhatsApp Number *</label>
            <input type="tel" id="jsPhone" name="phone" class="form-control" placeholder="e.g. 98610 12345" required pattern="[0-9]{10}">
          </div>

          <!-- Field 3: District/City* -->
          <div class="form-group">
            <label class="form-label" for="jsDistrict">District / City in Odisha *</label>
            <input type="text" id="jsDistrict" name="district" class="form-control" placeholder="e.g. Bhubaneswar, Cuttack, Puri" required>
          </div>

          <!-- Field 4: Job Type* (Dropdown) -->
          <div class="form-group">
            <label class="form-label" for="jsJobType">Preferred Job Type *</label>
            <select id="jsJobType" name="jobType" class="form-control" required>
              <option value="" disabled selected>Select Job Role</option>
              <option value="Back Office">Back Office / Admin</option>
              <option value="Sales">Sales &amp; Marketing</option>
              <option value="Telecaller">Telecaller / BPO</option>
              <option value="Customer Support">Customer Support</option>
              <option value="Driver">Commercial / Personal Driver</option>
              <option value="Delivery">Delivery / Logistics</option>
              <option value="Security">Security Guard</option>
              <option value="Other">Other Openings</option>
            </select>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-green btn-block btn-lg">Register Now</button>

          <!-- Consent Line under Button -->
          <p class="form-consent">By submitting, you agree to be contacted by Odiins.</p>
        </form>

        <!-- Success Banner -->
        <div class="form-success-banner">
          <div class="form-success-icon">✓</div>
          <h4>Thank you! We'll call you within 24 hours.</h4>
          <p>Your registration is received. Our placement team in Odisha is reviewing matching openings for your profile.</p>
          <div style="margin-top: 1rem;">
            <a href="#" class="btn btn-green btn-sm instant-wa-btn" target="_blank">💬 Chat on WhatsApp Now</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- POPULAR ROLES FOR JOB SEEKERS -->
  <section class="section section-white">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">High Demand</span>
        <h2>Roles Currently Hiring Across Odisha</h2>
        <p>Positions with immediate openings and fast joining schedules.</p>
      </div>

      <div class="roles-grid">
        <div class="role-pill">
          <div class="role-pill-icon">💻</div>
          <span class="role-pill-name">Back Office / Computer Operator</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">📈</div>
          <span class="role-pill-name">Field Sales &amp; Counter Sales</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">🎧</div>
          <span class="role-pill-name">Odia / Hindi Telecallers</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">📊</div>
          <span class="role-pill-name">Tally &amp; Billing Assistants</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">🚗</div>
          <span class="role-pill-name">4-Wheeler &amp; Van Drivers</span>
        </div>
        <div class="role-pill">
          <div class="role-pill-icon">🛡️</div>
          <span class="role-pill-name">Security Staff &amp; Supervisors</span>
        </div>
      </div>
    </div>
  </section>

  <!-- QUICK FAQ -->
  <section class="section section-alt">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">Common Questions</span>
        <h2>Frequently Asked by Job Seekers</h2>
      </div>
      <div class="accordion">
        <div class="accordion-item">
          <div class="accordion-header">
            <h4>Will I have to pay any money at the time of interview?</h4>
            <span class="accordion-icon">▼</span>
          </div>
          <div class="accordion-content">
            <p>Never. Odiins never asks candidates to pay for interviews, offer letters, or registration. If any employer asks you for money, report it to our helpline immediately.</p>
          </div>
        </div>
        <div class="accordion-item">
          <div class="accordion-header">
            <h4>Can freshers with no experience apply?</h4>
            <span class="accordion-icon">▼</span>
          </div>
          <div class="accordion-content">
            <p>Yes, many Odisha companies actively seek freshers for retail sales, data entry, telecalling, and warehouse operations. We help guide freshers on basic interview preparation.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="final-cta">
    <div class="container">
      <h2>Step Into Your Next Role Today</h2>
      <p>Register once, and let our Odisha recruitment team connect you with employers looking for your skills.</p>
      <a href="#registerSection" class="btn btn-white btn-lg">Register Now &rarr;</a>
    </div>
  </section>

<?php
get_footer();
?>
