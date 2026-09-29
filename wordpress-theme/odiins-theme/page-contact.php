<?php
/**
 * Template Name: Odiins - Contact Us
 */
get_header();
?>

<!-- PAGE HEADER -->
  <section class="section section-alt" style="padding: 3rem 0 2rem 0;">
    <div class="container text-center" style="text-align:center;">
      <span class="section-badge">Get in Touch</span>
      <h1 class="page-title">Contact Odiins</h1>
      <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">
        We're here to answer your queries, understand your requirements, and support you in Odia, Hindi, or English.
      </p>
    </div>
  </section>

  <!-- CONTACT GRID (Left: Info & Map | Right: Form) -->
  <section class="section section-white">
    <div class="container">
      <div class="contact-grid">
        
        <!-- Left: Details & Map -->
        <div class="contact-info-card">
          <div>
            <h2 style="font-size: 1.6rem; margin-bottom: 0.5rem;">Reach Our Odisha Team</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem;">
              Call, message, or visit our central coordination office in Bhubaneswar.
            </p>
          </div>

          <div class="contact-item">
            <div class="contact-item-icon">📍</div>
            <div>
              <h4>Central Office Address</h4>
              <p>Plot 220/3482, lane no. 3, bairagi nagar, 751006, Bhubaneswar, Odisha</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-item-icon">📞</div>
            <div>
              <h4>Helpline Phone</h4>
              <p><a href="tel:+919938079601" style="font-weight:600; color:var(--text-charcoal);">+91 99380 79601</a></p>
              <p style="font-size:0.8rem; color:var(--text-muted);">Toll-free candidate &amp; client assistance</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-item-icon">💬</div>
            <div>
              <h4>WhatsApp Support Desk</h4>
              <p><a href="https://wa.me/919938079601" target="_blank" style="font-weight:600; color:var(--primary-green);">+91 99380 79601 (Click to Chat)</a></p>
              <p style="font-size:0.8rem; color:var(--text-muted);">Fast responses during working hours</p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-item-icon">✉️</div>
            <div>
              <h4>Email Support</h4>
              <p><a href="mailto:corporate@odiins.in" style="color:var(--text-charcoal);">corporate@odiins.in</a></p>
            </div>
          </div>

          <div class="contact-item">
            <div class="contact-item-icon">⏰</div>
            <div>
              <h4>Working Hours</h4>
              <p>Monday – Saturday: 9:00 AM – 7:00 PM</p>
              <p style="font-size:0.8rem; color:var(--text-muted);">Sunday: Closed (WhatsApp queries attended on Monday morning)</p>
            </div>
          </div>

          <!-- Interactive Map Container -->
          <div class="contact-map-box">
            <div style="text-align:center; padding:1.5rem;">
              <div style="font-size:2rem; margin-bottom:0.35rem;">🗺️</div>
              <h4 style="font-size:1rem; margin-bottom:0.25rem;">Bhubaneswar Hub Map</h4>
              <p style="font-size:0.82rem; color:var(--text-muted);">Serving Bhubaneswar, Cuttack, Puri &amp; all 30 districts</p>
              <a href="https://maps.google.com/?q=Plot+220%2F3482%2C+lane+no.+3%2C+bairagi+nagar%2C+751006" target="_blank" class="btn btn-green btn-sm" style="margin-top:0.5rem;">
                Open in Google Maps &rarr;
              </a>
            </div>
          </div>

          <!-- Direct Call / WhatsApp Buttons -->
          <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-top:0.5rem;">
            <a href="https://wa.me/919938079601" target="_blank" class="btn btn-green" style="background:#25D366; flex:1;">
              💬 Chat on WhatsApp
            </a>
            <a href="tel:+919938079601" class="btn btn-green" style="flex:1;">
              📞 Call Now
            </a>
          </div>
        </div>

        <!-- Right: Short Enquiry Form -->
        <div class="form-card">
          <div class="form-card-header">
            <span class="section-badge" style="margin-bottom:0.4rem;">Send a Message</span>
            <h3>How Can We Help You?</h3>
            <p>Tell us what you're looking for, and our representative will reach back.</p>
          </div>

          <form id="contactForm" novalidate>
            <input type="text" name="website_hp" class="hp-trap" tabindex="-1" autocomplete="off">

            <!-- Field 1: Name* -->
            <div class="form-group">
              <label class="form-label" for="cntName">Your Name *</label>
              <input type="text" id="cntName" name="name" class="form-control" placeholder="e.g. Ramesh Chandra Das" required>
            </div>

            <!-- Field 2: Phone* -->
            <div class="form-group">
              <label class="form-label" for="cntPhone">Phone Number *</label>
              <input type="tel" id="cntPhone" name="phone" class="form-control" placeholder="e.g. 99380 79601" required pattern="[0-9]{10}">
            </div>

            <!-- Field 3: Email Address (Optional & 1-Click Google Auto-fill) -->
            <div class="form-group email-autofill-group">
              <div class="email-label-row">
                <label class="form-label" for="cntEmail">Email Address <span style="font-weight:400; color:var(--text-muted); font-size:0.75rem;">(Optional)</span></label>
                <button type="button" class="btn-google-autofill-inline" data-target="cntEmail" title="1-Click Auto-fill with Google">
                  <svg viewBox="0 0 24 24" width="13" height="13" style="display:block;flex-shrink:0;"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                  <span>Auto-fill with Google</span>
                </button>
              </div>
              <div class="email-input-wrapper">
                <input type="email" id="cntEmail" name="email" class="form-control google-email-input" placeholder="e.g. name@gmail.com (or tap to auto-fill)" autocomplete="email">
                <button type="button" class="email-input-icon-btn" data-target="cntEmail" title="1-Click Auto-fill with Google">
                  <svg viewBox="0 0 24 24" width="16" height="16"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                </button>
              </div>
              <div class="email-status-hint">
                ⚡ Tap box to auto-fill your logged-in Google email, type manually, or skip.
              </div>
            </div>

            <!-- Field 3: I am a...* -->
            <div class="form-group">
              <label class="form-label" for="cntCategory">I am a... *</label>
              <select id="cntCategory" name="category" class="form-control" required>
                <option value="Job Seeker">Job Seeker (Looking for work in Odisha)</option>
                <option value="Employer">Employer / Business Owner (Need staff)</option>
                <option value="Customer">Customer (Looking for household helper or cook)</option>
                <option value="General Partner">Partner / General Inquiry</option>
              </select>
            </div>

            <!-- Field 4: Location -->
            <div class="form-group">
              <label class="form-label" for="cntLocation">Your District / City</label>
              <input type="text" id="cntLocation" name="location" class="form-control" placeholder="e.g. Bhubaneswar, Rourkela, Berhampur">
            </div>

            <!-- Field 5: Message -->
            <div class="form-group">
              <label class="form-label" for="cntMessage">Message (Optional)</label>
              <textarea id="cntMessage" name="message" class="form-control" rows="3" placeholder="Tell us briefly about your requirement or role..."></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-green btn-block btn-lg">Submit Message</button>

            <!-- Consent Line -->
            <p class="form-consent">By submitting, you agree to be contacted by Odiins.</p>
          </form>

          <!-- Success Banner -->
          <div class="form-success-banner">
            <div class="form-success-icon">✓</div>
            <h4>Thank you! We'll call you within 24 hours.</h4>
            <p>Your message has reached our team. An executive will call you to discuss your requirement.</p>
            <div style="margin-top: 1rem;">
              <a href="#" class="btn btn-green btn-sm instant-wa-btn" target="_blank">💬 Chat on WhatsApp Now</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

<?php
get_footer();
?>
