<?php
/**
 * Template Name: Odiins - For Customers (Home Help)
 */
get_header();
?>

<!-- LANDING HERO & SHORT FORM -->
  <section class="landing-hero" id="customerFormSection">
    <div class="container landing-grid">
      <!-- Left: Headline + 4 Benefits -->
      <div class="hero-content">
        <span class="section-badge">Safe &amp; Dependable Household Services</span>
        <h1 class="hero-headline">Trusted Help for Your Home, Just Ask</h1>
        <p class="hero-sub">Finding dependable help for your family shouldn't be stressful or uncertain. Odiins connects households with verified, experienced helpers you can welcome with peace of mind.</p>

        <!-- 4 Benefit Bullets -->
        <div class="landing-benefits">
          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Maid, Cook, Driver, Tutor, Electrician &amp; Pandit</h4>
              <p>One platform catering to all your household support needs, from daily chores to sacred ceremonies.</p>
            </div>
          </div>

          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>We Connect You With the Right Person</h4>
              <p>We understand your dietary preferences, working hours, and language needs before making a match.</p>
            </div>
          </div>

          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Quick Call Back</h4>
              <p>No waiting in queues or endless searching on unmoderated forums. We respond within 24 hours.</p>
            </div>
          </div>

          <div class="benefit-bullet">
            <div class="benefit-bullet-icon">✓</div>
            <div>
              <h4>Details Kept 100% Private</h4>
              <p>Your family’s contact and address details are shared only with the shortlisted candidate after your approval.</p>
            </div>
          </div>
        </div>

        <div class="hero-trust-line">
          <span>🏠 3,800+ Families Served</span>
          <span>•</span>
          <span>🔒 Privacy Guaranteed</span>
          <span>•</span>
          <span>🛡️ ID Verified</span>
        </div>
      </div>

      <!-- Right: Short Form (4 Fields) -->
      <div class="form-card">
        <div class="form-card-header">
          <span class="section-badge" style="margin-bottom:0.4rem;">Household Request</span>
          <h3>Request a Call Back</h3>
          <p>Tell us what help you need. We'll connect you within 24 hours.</p>
        </div>

        <form id="customerLeadForm" class="lead-form" novalidate>
          <input type="hidden" name="formType" value="Customer">
          <!-- Honeypot -->
          <div style="display:none;" aria-hidden="true">
            <input type="text" name="website_hp" tabindex="-1" autocomplete="off">
          </div>

          <!-- Field 1: Name* -->
          <div class="form-group">
            <label class="form-label" for="custName">Your Name *</label>
            <input type="text" id="custName" name="name" class="form-control" placeholder="e.g. Debashish Patnaik" required>
          </div>

          <!-- Field 2: Phone/WhatsApp* -->
          <div class="form-group">
            <label class="form-label" for="custPhone">Phone / WhatsApp Number *</label>
            <input type="tel" id="custPhone" name="phone" class="form-control" placeholder="e.g. 94370 98765" required pattern="[0-9]{10}">
          </div>

          <!-- Field 3: Email Address (Optional & 1-Click Google Auto-fill) -->
          <div class="form-group email-autofill-group">
            <div class="email-label-row">
              <label class="form-label" for="custEmail">Email Address <span style="font-weight:400; color:var(--text-muted); font-size:0.75rem;">(Optional)</span></label>
              <button type="button" class="btn-google-autofill-inline" data-target="custEmail" title="1-Click Auto-fill with Google">
                <svg viewBox="0 0 24 24" width="13" height="13" style="display:block;flex-shrink:0;"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                <span>Auto-fill with Google</span>
              </button>
            </div>
            <div class="email-input-wrapper">
              <input type="email" id="custEmail" name="email" class="form-control google-email-input" placeholder="e.g. name@gmail.com (or tap to auto-fill)" autocomplete="email">
              <button type="button" class="email-input-icon-btn" data-target="custEmail" title="1-Click Auto-fill with Google">
                <svg viewBox="0 0 24 24" width="16" height="16"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
              </button>
            </div>
            <div class="email-status-hint">
              ⚡ Tap box to auto-fill your logged-in Google email, type manually, or skip.
            </div>
          </div>

          <!-- Field 3: Service Needed* (Dropdown) -->
          <div class="form-group">
            <label class="form-label" for="custService">Service Needed *</label>
            <select id="custService" name="serviceNeeded" class="form-control" required>
              <option value="" disabled selected>Select Service Needed</option>
              <option value="House Maid">House Maid (Cleaning / Dishwashing)</option>
              <option value="Cook">Home Cook (Odia / Veg / Non-Veg)</option>
              <option value="Driver">Personal Driver (Daily / Monthly)</option>
              <option value="Home Tutor">Home Tutor (CBSE / ICSE / State Board)</option>
              <option value="Electrician / Plumber">Electrician &amp; Plumber (Home Maintenance)</option>
              <option value="Security Guard">Security Guard / Watchman</option>
              <option value="Babysitter">Babysitter / Nanny</option>
              <option value="Elder Care">Elder Care &amp; Patient Care</option>
              <option value="Cleaner">Deep Home Cleaner</option>
              <option value="Pandit">Pandit for Puja / Ceremonies</option>
              <option value="Other">Other Home Help</option>
            </select>
          </div>

          <!-- Field 4: Area & City* -->
          <div class="form-group">
            <label class="form-label" for="custArea">Area &amp; City in Odisha *</label>
            <input type="text" id="custArea" name="areaCity" class="form-control" placeholder="e.g. Patia, Bhubaneswar / CDA, Cuttack" required>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-green btn-block btn-lg">Request a Call Back</button>

          <!-- Consent Line under Button -->
          <p class="form-consent">By submitting, you agree to be contacted by Odiins.</p>
        </form>

        <!-- Success Banner -->
        <div class="form-success-banner">
          <div class="form-success-icon">✓</div>
          <h4>Thank you! We'll call you within 24 hours.</h4>
          <p>We have received your home help request. Our customer care desk is matching available verified helpers in your locality.</p>
          <div style="margin-top: 1rem;">
            <a href="#" class="btn btn-green btn-sm instant-wa-btn" target="_blank">💬 Connect on WhatsApp</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- HOME HELP CATEGORIES DETAIL -->
  <section class="section section-white">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">Home Solutions</span>
        <h2>How We Help Your Household Run Smoothly</h2>
      </div>

      <div class="cards-grid-3">
        <div class="feature-card">
          <div class="feature-card-icon">👩‍🍳</div>
          <h3>Experienced Home Cooks</h3>
          <p>Authentic Odia homely cooking (dalma, santula, machha jhola), North Indian, vegetarian satvik meals, or tailored diets for elderly and children.</p>
          <a href="#customerFormSection" class="btn btn-green btn-sm">Book Cook &rarr;</a>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon">🧹</div>
          <h3>Reliable House Maids</h3>
          <p>Part-time or full-time maids for daily sweeping, mopping, dusting, utensil cleaning, and laundry with verified background credentials.</p>
          <a href="#customerFormSection" class="btn btn-green btn-sm">Hire Maid &rarr;</a>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon">🪔</div>
          <h3>Pandits for Family Pujas</h3>
          <p>Vedic-trained Brahmins for Griha Pravesh, Satyanarayan Puja, Birthday rituals, Shraddha, Rudrabhishek, and special home sankalpa.</p>
          <a href="#customerFormSection" class="btn btn-green btn-sm">Book Pandit &rarr;</a>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon">📚</div>
          <h3>Verified Home Tutors</h3>
          <p>Qualified, background-checked tutors for Class 1 to 12 (CBSE, ICSE, Odisha State Board), foundational science, mathematics, English, and competitive exams.</p>
          <a href="#customerFormSection" class="btn btn-green btn-sm">Find Tutor &rarr;</a>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon">🔧</div>
          <h3>Electricians &amp; Plumbers</h3>
          <p>Experienced household technicians for electrical wiring, switchboard repairs, plumbing leakage, water pumps, bathroom fittings, and home maintenance.</p>
          <a href="#customerFormSection" class="btn btn-green btn-sm">Book Technician &rarr;</a>
        </div>

        <div class="feature-card">
          <div class="feature-card-icon">🛡️</div>
          <h3>Security Guards &amp; Watchmen</h3>
          <p>Trained, disciplined gatekeepers and day/night security guards for residential societies, individual bungalows, apartments, and farmhouses in Odisha.</p>
          <a href="#customerFormSection" class="btn btn-green btn-sm">Hire Security &rarr;</a>
        </div>
      </div>
    </div>
  </section>

  <!-- SAFETY PROMISE -->
  <section class="section section-alt">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">Family First</span>
        <h2>Our Three-Point Safety Standard</h2>
        <p>Every domestic helper introduced through Odiins undergoes our safety check.</p>
      </div>
      <div class="steps-grid">
        <div class="step-card">
          <div class="step-number">1</div>
          <h4>Identity Verification</h4>
          <p>Government ID checks including Aadhaar Card and residential address verification.</p>
        </div>
        <div class="step-card">
          <div class="step-number">2</div>
          <h4>Reference Checks</h4>
          <p>Prior employment history and behavioral feedback from previous households in Odisha.</p>
        </div>
        <div class="step-card">
          <div class="step-number">3</div>
          <h4>Personal Orientation</h4>
          <p>Guidance on punctuality, hygiene, family boundaries, and household communication.</p>
        </div>
        <div class="step-card">
          <div class="step-number">4</div>
          <h4>Replacement Support</h4>
          <p>If the helper is not a suitable fit for your household, we assist with quick re-matching.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="final-cta">
    <div class="container">
      <h2>Give Your Family the Support It Deserves</h2>
      <p>Submit your home requirement in 30 seconds, or speak directly with our household care desk.</p>
      <div class="final-cta-buttons">
        <a href="#customerFormSection" class="btn btn-white btn-lg">Request Call Back &rarr;</a>
        <a href="tel:+919938079601" class="btn btn-outline-white btn-lg">📞 Call Home Desk</a>
      </div>
    </div>
  </section>

<?php
get_footer();
?>
