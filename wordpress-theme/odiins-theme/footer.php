  <!-- SITE FOOTER -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <!-- Col 1: Brand & Bio -->
        <div class="footer-col">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/logo-white.svg'); ?>" alt="Odiins Logo" width="140" height="50" loading="lazy" style="margin-bottom: 1rem;">
          <p>
            Odisha's dedicated HR, staffing, and domestic connection platform. Connecting local employers with pre-screened staff, job seekers with openings, and households with trusted help across all 30 districts.
          </p>
          <div class="footer-social-icons">
            <a href="#" class="social-icon-btn" aria-label="Facebook">f</a>
            <a href="#" class="social-icon-btn" aria-label="Instagram">📸</a>
            <a href="#" class="social-icon-btn" aria-label="LinkedIn">in</a>
            <a href="#" class="social-icon-btn" aria-label="X / Twitter">𝕏</a>
            <a href="#" class="social-icon-btn" aria-label="YouTube">▶</a>
          </div>
        </div>

        <!-- Col 2: Quick Links (with Admin Portal) -->
        <div class="footer-col">
          <h4>Quick Links</h4>
          <div class="footer-links">
            <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
            <a href="<?php echo esc_url(home_url('/about-vision-mission/')); ?>">Vision &amp; Mission</a>
            <a href="<?php echo esc_url(home_url('/about-media/')); ?>">Media &amp; Recognition</a>
            <a href="<?php echo esc_url(home_url('/blogs/')); ?>">Blogs &amp; Insights</a>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Us</a>
            <a href="<?php echo esc_url(home_url('/dashboard/')); ?>">🔒 Admin Portal</a>
          </div>
        </div>

        <!-- Col 3: Services -->
        <div class="footer-col">
          <h4>Services</h4>
          <div class="footer-links">
            <a href="<?php echo esc_url(home_url('/services-job-seekers/')); ?>">For Job Seekers</a>
            <a href="<?php echo esc_url(home_url('/services-employers/')); ?>">For Employers &amp; Business</a>
            <a href="<?php echo esc_url(home_url('/services-customers/')); ?>">For Customers (Home Help)</a>
            <a href="<?php echo esc_url(home_url('/services-employers/')); ?>">Corporate Staffing</a>
            <a href="<?php echo esc_url(home_url('/services-customers/')); ?>">Driver &amp; Cook on Hire</a>
            <a href="<?php echo esc_url(home_url('/services-customers/')); ?>">Pandit Booking Odisha</a>
          </div>
        </div>

        <!-- Col 4: Contact & Coverage -->
        <div class="footer-col">
          <h4>Contact &amp; Coverage</h4>
          <p>📞 <a href="tel:+919938079601" style="color:#FFFFFF;">+91 99380 79601</a></p>
          <p>✉️ <a href="mailto:corporate@odiins.in" style="color:#FFFFFF;">corporate@odiins.in</a></p>
          <p>📍 Plot 220/3482, lane no. 3, bairagi nagar, 751006</p>
          <p>⏰ Mon - Sat: 9:00 AM - 7:00 PM</p>
          <p style="font-size:0.8rem; color:rgba(255,255,255,0.8); margin-top:0.5rem;">
            Serving Bhubaneswar, Cuttack, Puri, Rourkela, Sambalpur, Berhampur &amp; all 30 districts of Odisha.
          </p>
        </div>
      </div>

      <!-- Bottom Bar & Legal Links -->
      <div class="footer-bottom">
        <div class="footer-bottom-links">
          <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a>
          <span>|</span>
          <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms</a>
          <span>|</span>
          <a href="<?php echo esc_url(home_url('/disclaimer/')); ?>">Disclaimer</a>
          <span>|</span>
          <a href="<?php echo esc_url(home_url('/dashboard/')); ?>" style="color:rgba(255,255,255,0.75);">🔒 Admin Login</a>
        </div>
        <p style="margin-bottom:0.75rem;">&copy; <?php echo date('Y'); ?> Odiins. All rights reserved.</p>
        <p class="footer-disclaimer">
          <strong>Disclaimer:</strong> Odiins is a connecting platform between employers, job seekers and households. We do not guarantee employment or hiring outcomes. Please verify details before engagement.
        </p>
      </div>
    </div>
  </footer>

  <!-- FLOATING WHATSAPP BUTTON -->
  <a href="https://wa.me/919938079601?text=Hello%20Odiins%2C%20I%20have%20an%20enquiry" target="_blank" rel="noopener" class="floating-whatsapp" aria-label="Chat on WhatsApp">
    💬
  </a>

  <!-- MOBILE STICKY BOTTOM ACTION BAR -->
  <div class="mobile-bottom-bar" aria-label="Quick Mobile Actions">
    <a href="tel:+919938079601" class="mobile-action-btn mobile-btn-call">
      <span>📞</span> Call Now
    </a>
    <a href="https://wa.me/919938079601?text=Hello%20Odiins%20Team%2C%20I%20have%20an%20urgent%20requirement" target="_blank" rel="noopener" class="mobile-action-btn mobile-btn-wa">
      <span>💬</span> WhatsApp
    </a>
  </div>

  <?php wp_footer(); ?>
</body>
</html>
