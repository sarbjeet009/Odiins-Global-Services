<?php
/**
 * Template Name: Odiins - Vision & Mission
 */
get_header();
?>

<!-- WHO WE ARE SECTION -->
  <section class="section section-white" style="padding-top: 3.5rem;">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">Who We Are</span>
        <h2>The Missing Bridge in Odisha's Growth Story</h2>
      </div>

      <div class="about-quote-card">
        <p style="font-size: 1.25rem; font-weight: 500; color: var(--text-charcoal); line-height: 1.8;">
          “Odiins is an Odisha-based HR and manpower platform built on a simple idea: our state has no shortage of talent or opportunity, only a missing bridge between them. We connect businesses with reliable staff, job seekers with work close to home, and families with dependable help.”
        </p>
      </div>
    </div>
  </section>

  <!-- VISION SECTION -->
  <section class="section section-alt">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">Our North Star</span>
        <h2>Our Vision</h2>
      </div>

      <div class="vision-card">
        <div style="font-size: 3rem; margin-bottom: 1rem;">🔭</div>
        <p style="font-size: 1.35rem; font-weight: 600; color: var(--text-charcoal); line-height: 1.7;">
          “To be Odisha's most trusted platform for connecting people with opportunity, so every skilled hand finds work, every business finds its people, and every family finds help it can rely on, right here at home.”
        </p>
      </div>
    </div>
  </section>

  <!-- MISSION SECTION (5 Bullets) -->
  <section class="section section-white">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">What Drives Us Every Day</span>
        <h2>Our 5-Point Mission</h2>
        <p>Tangible commitments that guide how we evaluate every partnership and placement.</p>
      </div>

      <div class="landing-benefits" style="max-width: 850px; margin: 0 auto;">
        <div class="benefit-bullet">
          <div class="benefit-bullet-icon">1.</div>
          <div>
            <h4>Bring Employers &amp; Job Seekers Together Through a Simple, Honest, Fast Process</h4>
            <p>Cut out bureaucracy, convoluted portals, and unnecessary delays so connections happen within 24 hours.</p>
          </div>
        </div>

        <div class="benefit-bullet">
          <div class="benefit-bullet-icon">2.</div>
          <div>
            <h4>Support Small Businesses, Not Just Large Corporates</h4>
            <p>Small shop owners, clinics, local retailers, and workshops in Odisha deserve top-quality manpower support just like conglomerates.</p>
          </div>
        </div>

        <div class="benefit-bullet">
          <div class="benefit-bullet-icon">3.</div>
          <div>
            <h4>Create Local Jobs So Fewer Odias Leave Home for Work</h4>
            <p>Empower our youth to earn dignity, respect, and career advancement within Odisha, staying close to parents and family roots.</p>
          </div>
        </div>

        <div class="benefit-bullet">
          <div class="benefit-bullet-icon">4.</div>
          <div>
            <h4>Make Household Help Easy to Find and Safe to Trust</h4>
            <p>Provide verified, respectful, and reliable support for homes—from experienced cooks and maids to devotional pandits.</p>
          </div>
        </div>

        <div class="benefit-bullet">
          <div class="benefit-bullet-icon">5.</div>
          <div>
            <h4>Treat Everyone With Respect and Transparency</h4>
            <p>Every candidate, blue-collar worker, household helper, and business owner is treated as an equal, valued human partner.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- VALUES (5 Cards) -->
  <section class="section section-alt">
    <div class="container">
      <div class="section-header">
        <span class="section-badge">Core Principles</span>
        <h2>Our Values</h2>
        <p>The cultural pillars embedded in every team member at Odiins.</p>
      </div>

      <div class="values-grid">
        <!-- Card 1: Trust -->
        <div class="value-card">
          <div class="value-card-icon">🛡️</div>
          <h4>Trust</h4>
          <p>Uncompromising honesty in background verifications, candidate resumes, and client promises.</p>
        </div>

        <!-- Card 2: Local First -->
        <div class="value-card">
          <div class="value-card-icon">🏛️</div>
          <h4>Local First</h4>
          <p>We believe in Odisha's immense economic potential and prioritize local economic resilience.</p>
        </div>

        <!-- Card 3: Speed & Care -->
        <div class="value-card">
          <div class="value-card-icon">⚡</div>
          <h4>Speed &amp; Care</h4>
          <p>Moving fast without ever compromising on human compassion, safety checks, or empathy.</p>
        </div>

        <!-- Card 4: Growth -->
        <div class="value-card">
          <div class="value-card-icon">🌱</div>
          <h4>Growth</h4>
          <p>Helping small businesses scale their workforce and helping workers advance their earning power.</p>
        </div>

        <!-- Card 5: Transparency -->
        <div class="value-card">
          <div class="value-card-icon">🔍</div>
          <h4>Transparency</h4>
          <p>Zero hidden charges, clear terms, upfront expectations, and honest direct conversations.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CLOSING LINE & CTAs -->
  <section class="section section-white">
    <div class="container">
      <div style="background: linear-gradient(135deg, var(--bg-light-blue) 0%, #FFFFFF 100%); border-radius: var(--radius-lg); padding: 3.5rem 2rem; border: 2px solid var(--border-light); text-align: center; max-width: 900px; margin: 0 auto;">
        <span class="section-badge">Our Sole Commitment</span>
        <h3 style="font-size: 1.75rem; color: var(--text-charcoal); line-height: 1.5; margin: 1rem auto 2rem auto; max-width: 750px;">
          “We're not trying to be everywhere. We're trying to be here, in every district of Odisha that needs us.”
        </h3>

        <!-- Buttons: [Work With Us] [Contact Us] -->
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
          <a href="<?php echo esc_url(home_url('/services-job-seekers/')); ?>" class="btn btn-green btn-lg">Work With Us &rarr;</a>
          <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-white btn-lg">Contact Us &rarr;</a>
        </div>
      </div>
    </div>
  </section>

<?php
get_footer();
?>
