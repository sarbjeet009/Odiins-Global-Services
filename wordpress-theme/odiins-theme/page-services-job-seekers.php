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
              <option value="Sales Manager">Sales Manager / Branch In-Charge</option>
              <option value="Store Manager">Store / Showroom Manager</option>
              <option value="Accountant">Accountant (Tally / GST / Billing)</option>
              <option value="Data Entry">Data Entry Operator / MIS</option>
              <option value="Sales Executive">Field Sales &amp; Marketing Executive</option>
              <option value="Telecaller">Telecaller / BPO / Customer Care</option>
              <option value="Front Desk">Receptionist / Front Desk</option>
              <option value="Office Peon">Office Peon / Support Staff</option>
              <option value="Delivery Fleet">Delivery Fleet Rider (2-Wheeler)</option>
              <option value="Driver">Commercial / Personal Driver</option>
              <option value="Security Guard">Security Guard / Supervisor</option>
              <option value="Warehouse">Warehouse Staff / Logistics</option>
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

  <!-- 3-TIER ROLES WE ARE HIRING FOR -->
  <section class="section section-white" style="padding-top: 3.5rem; padding-bottom: 3.5rem;">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">Comprehensive Career Openings</span>
        <h2>Private Job Openings Across Odisha</h2>
        <p>From strategic managerial leadership to professional office roles and field operations.</p>
      </div>

      <!-- Tier 1: Leadership & Management -->
      <div style="margin-bottom: 2.5rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
          <span style="background: #FEF3C7; color: #92400E; font-size: 0.85rem; font-weight: 800; padding: 0.35rem 0.85rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px;">Level 1</span>
          <h3 style="margin: 0; font-size: 1.3rem; color: var(--text-charcoal); font-weight: 700;">Leadership &amp; Managerial Careers</h3>
        </div>
        <div class="roles-grid">
          <div class="role-pill">
            <div class="role-pill-icon">👔</div>
            <span class="role-pill-name"><strong>Sales Managers</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">B2B, FMCG &amp; Retail</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏬</div>
            <span class="role-pill-name"><strong>Store / Showroom Managers</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Retail Outlets</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📦</div>
            <span class="role-pill-name"><strong>Warehouse Shift Leads</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Logistics Hubs</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏨</div>
            <span class="role-pill-name"><strong>Hospitality In-Charges</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Hotels &amp; Banquets</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🎧</div>
            <span class="role-pill-name"><strong>Team Leaders &amp; Supervisors</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Telecalling &amp; BPO</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📑</div>
            <span class="role-pill-name"><strong>HR &amp; Operations Officers</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Corporate Units</small>
          </div>
        </div>
      </div>

      <!-- Tier 2: Professional & Back Office -->
      <div style="margin-bottom: 2.5rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem;">
          <span style="background: #E0E7FF; color: #3730A3; font-size: 0.85rem; font-weight: 800; padding: 0.35rem 0.85rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px;">Level 2</span>
          <h3 style="margin: 0; font-size: 1.3rem; color: var(--text-charcoal); font-weight: 700;">Professional &amp; Back Office Positions</h3>
        </div>
        <div class="roles-grid">
          <div class="role-pill">
            <div class="role-pill-icon">📊</div>
            <span class="role-pill-name"><strong>Accountants &amp; Billing Staff</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Tally &amp; GST Invoicing</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📈</div>
            <span class="role-pill-name"><strong>Sales Executives</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Field &amp; Counter Sales</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">⌨️</div>
            <span class="role-pill-name"><strong>Data Entry / MIS Operators</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Computer &amp; Excel Work</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📞</div>
            <span class="role-pill-name"><strong>Telecallers &amp; Customer Care</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Odia, Hindi &amp; English</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏢</div>
            <span class="role-pill-name"><strong>Front Desk Receptionists</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Clinics, Offices &amp; Hotels</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">📎</div>
            <span class="role-pill-name"><strong>Office Peons &amp; Assistants</strong></span>
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
            <span class="role-pill-name"><strong>Security Guards &amp; Supervisors</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Day &amp; Night Shifts</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🚚</div>
            <span class="role-pill-name"><strong>Delivery Fleet Riders</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">E-Commerce &amp; Food Delivery</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🚗</div>
            <span class="role-pill-name"><strong>Commercial Drivers</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Car, Van &amp; Commercial Fleet</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏗️</div>
            <span class="role-pill-name"><strong>Warehouse &amp; Logistics Staff</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Packaging &amp; Loading</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🏥</div>
            <span class="role-pill-name"><strong>Hospital Ward Attendants</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Nursing Homes &amp; Clinics</small>
          </div>
          <div class="role-pill">
            <div class="role-pill-icon">🧹</div>
            <span class="role-pill-name"><strong>Housekeeping &amp; Janitorial</strong></span>
            <small style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem;">Commercial Properties</small>
          </div>
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
            <p>Never pay for an interview or offer letter. Employers are strictly prohibited from charging candidates for interview scheduling or placement. If any company asks you for money, report it to our helpline immediately.</p>
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
