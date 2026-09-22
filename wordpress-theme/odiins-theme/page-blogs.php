<?php
/**
 * Template Name: Odiins - Blogs & Insights
 */
get_header();
?>

<!-- BLOG HERO & FEATURED POST -->
  <section class="section section-alt" style="padding: 3rem 0;">
    <div class="container">
      <div class="section-header" style="margin-bottom: 2rem;">
        <span class="section-badge">Knowledge Hub</span>
        <h1>Odiins Blog &amp; Industry Guides</h1>
        <p>Actionable advice on hiring, job searching, and domestic care in Odisha.</p>
      </div>

      <!-- Featured Post Banner -->
      <div style="background:var(--bg-white); border-radius:var(--radius-lg); overflow:hidden; border:1px solid var(--border-light); box-shadow:var(--card-shadow-hover); display:grid; grid-template-columns:1fr; gap:0;">
        <div style="background:var(--bg-light-blue); padding:2.5rem; display:flex; flex-direction:column; justify-content:center;">
          <div style="display:inline-flex; align-items:center; gap:0.5rem; margin-bottom:0.75rem;">
            <span class="section-badge" style="background:var(--primary-green); color:#FFFFFF; border:none;">FEATURED GUIDE</span>
            <span style="font-size:0.8rem; color:var(--text-muted);">March 2026 • 7 min read</span>
          </div>
          <h2 style="font-size:1.85rem; margin-bottom:1rem; line-height:1.3;">
            <a href="<?php echo esc_url(home_url('/how-to-hire-sales-managers-manpower-in-bhubaneswar/')); ?>" style="color:var(--text-charcoal);">How to Hire Verified Sales Managers &amp; Manpower in Bhubaneswar: 2026 Employer Guide</a>
          </h2>
          <p style="color:var(--text-muted); font-size:1.05rem; line-height:1.6; margin-bottom:1.5rem;">
            From retail showrooms in Saheed Nagar to logistics hubs in Mancheswar and corporate offices in Patia, learn how top Odisha businesses source sales managers, back-office staff, and reduce turnover within 24 hours.
          </p>
          <div>
            <a href="<?php echo esc_url(home_url('/how-to-hire-sales-managers-manpower-in-bhubaneswar/')); ?>" class="btn btn-green">Read Complete Guide &rarr;</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- MAIN BLOG SECTION (Search, Filter, Grid & Sidebar) -->
  <section class="section section-white">
    <div class="container">
      <div class="blog-layout">
        
        <!-- Left: Search, Filters & 6 Blog Cards -->
        <main>
          <!-- Search Bar -->
          <div class="blog-search-box">
            <span class="blog-search-icon">🔍</span>
            <input type="text" id="blogSearchInput" class="form-control" placeholder="Search by role, district, or topic (e.g. Sales, Cook, Bhubaneswar)...">
          </div>

          <!-- Category Filters -->
          <div class="blog-filters">
            <button class="blog-filter-btn active" data-category="all">All Topics</button>
            <button class="blog-filter-btn" data-category="hiring-tips">Hiring Tips</button>
            <button class="blog-filter-btn" data-category="career-advice">Career Advice</button>
            <button class="blog-filter-btn" data-category="job-market">Odisha Job Market</button>
            <button class="blog-filter-btn" data-category="household-help">Household Help</button>
            <button class="blog-filter-btn" data-category="hr-msme">HR for Small Business</button>
          </div>

          <!-- 6 Placeholder Blog Cards -->
          <div class="blog-grid" id="blogGrid">
            
            <!-- Post 1: Hiring Tips -->
            <article class="blog-card" data-category="hiring-tips">
              <div class="blog-card-thumb">🏢</div>
              <div class="blog-card-content">
                <div class="blog-meta">
                  <span style="color:var(--primary-green); font-weight:700;">Hiring Tips</span>
                  <span>•</span>
                  <span>7 min read</span>
                </div>
                <h3>How to Hire Verified Sales Managers &amp; Manpower in Bhubaneswar</h3>
                <p>The definitive 2026 employer framework for evaluating Tier-1 sales leadership, back-office accountants, and conducting 4-step background checks.</p>
                <a href="<?php echo esc_url(home_url('/how-to-hire-sales-managers-manpower-in-bhubaneswar/')); ?>" style="color:var(--primary-green); font-weight:600; font-size:0.88rem; margin-top:auto;">Read Article &rarr;</a>
              </div>
            </article>

            <!-- Post 2: Household Help -->
            <article class="blog-card" data-category="household-help">
              <div class="blog-card-thumb">🏠</div>
              <div class="blog-card-content">
                <div class="blog-meta">
                  <span style="color:var(--primary-green); font-weight:700;">Household Help</span>
                  <span>•</span>
                  <span>3 min read</span>
                </div>
                <h3>How to Find a Verified, Trustworthy Cook or Maid in Cuttack &amp; Bhubaneswar</h3>
                <p>A practical step-by-step checklist on background verification, Aadhaar authentication, and setting clear household expectations.</p>
                <a href="<?php echo esc_url(home_url('/blog-detail/')); ?>" style="color:var(--primary-green); font-weight:600; font-size:0.88rem; margin-top:auto;">Read Article &rarr;</a>
              </div>
            </article>

            <!-- Post 3: Odisha Job Market -->
            <article class="blog-card" data-category="job-market">
              <div class="blog-card-thumb">📈</div>
              <div class="blog-card-content">
                <div class="blog-meta">
                  <span style="color:var(--primary-green); font-weight:700;">Odisha Job Market</span>
                  <span>•</span>
                  <span>5 min read</span>
                </div>
                <h3>5 Back-Office Roles Growing Rapidly Across Odisha's Tier-2 Cities</h3>
                <p>Why businesses in Rourkela, Sambalpur, Balasore, and Berhampur are actively recruiting billing, data entry, and accounting assistants.</p>
                <a href="<?php echo esc_url(home_url('/blog-detail/')); ?>" style="color:var(--primary-green); font-weight:600; font-size:0.88rem; margin-top:auto;">Read Article &rarr;</a>
              </div>
            </article>

            <!-- Post 4: Career Advice -->
            <article class="blog-card" data-category="career-advice">
              <div class="blog-card-thumb">🎯</div>
              <div class="blog-card-content">
                <div class="blog-meta">
                  <span style="color:var(--primary-green); font-weight:700;">Career Advice</span>
                  <span>•</span>
                  <span>4 min read</span>
                </div>
                <h3>Fresher to Professional: Practical Interview Tips for Odisha Job Seekers</h3>
                <p>How to handle telephonic rounds, dress for office interviews in Bhubaneswar, and present your local strengths with confidence.</p>
                <a href="<?php echo esc_url(home_url('/blog-detail/')); ?>" style="color:var(--primary-green); font-weight:600; font-size:0.88rem; margin-top:auto;">Read Article &rarr;</a>
              </div>
            </article>

            <!-- Post 5: Household Help -->
            <article class="blog-card" data-category="household-help">
              <div class="blog-card-thumb">🪔</div>
              <div class="blog-card-content">
                <div class="blog-meta">
                  <span style="color:var(--primary-green); font-weight:700;">Household Help</span>
                  <span>•</span>
                  <span>3 min read</span>
                </div>
                <h3>Festive Season Pandit Booking: Why Planning Early in Odisha Matters</h3>
                <p>Tips for arranging authentic Vedic rituals for Griha Pravesh, Ganesh Puja, and Kartika Brata with verified Brahmins.</p>
                <a href="<?php echo esc_url(home_url('/blog-detail/')); ?>" style="color:var(--primary-green); font-weight:600; font-size:0.88rem; margin-top:auto;">Read Article &rarr;</a>
              </div>
            </article>

            <!-- Post 6: HR for Small Business -->
            <article class="blog-card" data-category="hr-msme">
              <div class="blog-card-thumb">🤝</div>
              <div class="blog-card-content">
                <div class="blog-meta">
                  <span style="color:var(--primary-green); font-weight:700;">HR for Small Business</span>
                  <span>•</span>
                  <span>5 min read</span>
                </div>
                <h3>Why Hiring Local Manpower Reduces Attrition for Odisha Businesses</h3>
                <p>How employing people living within a 10 km radius enhances daily attendance, lowers absenteeism, and preserves institutional memory.</p>
                <a href="<?php echo esc_url(home_url('/blog-detail/')); ?>" style="color:var(--primary-green); font-weight:600; font-size:0.88rem; margin-top:auto;">Read Article &rarr;</a>
              </div>
            </article>
          </div>

          <!-- Bottom CTA Block on Every Post/Page -->
          <div class="blog-cta-box">
            <h3 style="font-size:1.35rem; margin-bottom:0.5rem;">Need staff or looking for a job? Talk to us.</h3>
            <p style="color:var(--text-muted); margin-bottom:1rem; font-size:0.95rem;">
              Our dedicated Odisha recruitment desk will understand your need and connect you within 24 hours.
            </p>
            <div style="display:flex; justify-content:center; gap:0.75rem; flex-wrap:wrap;">
              <a href="<?php echo esc_url(home_url('/services-job-seekers/')); ?>" class="btn btn-green btn-sm">I Need a Job</a>
              <a href="<?php echo esc_url(home_url('/services-employers/')); ?>" class="btn btn-green btn-sm">I Need Staff</a>
              <a href="<?php echo esc_url(home_url('/services-customers/')); ?>" class="btn btn-white btn-sm">I Need Home Help</a>
            </div>
          </div>
        </main>

        <!-- Right: Sidebar with Mini 2-Field Enquiry Form -->
        <aside>
          <div class="form-card" style="padding:1.5rem; position:sticky; top:90px;">
            <div class="form-card-header">
              <span class="section-badge" style="margin-bottom:0.4rem;">Quick Callback</span>
              <h3 style="font-size:1.2rem;">Get in Touch</h3>
              <p>Leave your phone number for an instant callback.</p>
            </div>

            <form id="sidebarMiniForm" novalidate>
              <input type="text" name="website_hp" class="hp-trap" tabindex="-1" autocomplete="off">
              
              <div class="form-group">
                <label class="form-label" for="sbName">Your Name *</label>
                <input type="text" id="sbName" name="name" class="form-control" placeholder="e.g. Rajesh Sahoo" required>
              </div>

              <div class="form-group">
                <label class="form-label" for="sbPhone">Phone Number *</label>
                <input type="tel" id="sbPhone" name="phone" class="form-control" placeholder="e.g. 99380 79601" required pattern="[0-9]{10}">
              </div>

              <button type="submit" class="btn btn-green btn-block">Request Call</button>
              <p class="form-consent">By submitting, you agree to be contacted by Odiins.</p>
            </form>

            <div class="form-success-banner">
              <div class="form-success-icon">✓</div>
              <h4 style="font-size:1rem;">Thank you! We'll call you within 24 hours.</h4>
            </div>

            <hr style="margin: 1.5rem 0; border:0; border-top:1px solid var(--border-light);">

            <div style="text-align:center;">
              <div style="font-size:0.85rem; font-weight:600; margin-bottom:0.5rem;">Prefer instant messaging?</div>
              <a href="https://wa.me/919938079601" target="_blank" class="btn btn-green btn-block btn-sm" style="background:#25D366;">
                💬 Chat on WhatsApp
              </a>
            </div>
          </div>
        </aside>

      </div>
    </div>
  </section>

<?php
get_footer();
?>
