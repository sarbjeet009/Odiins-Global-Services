<?php
/**
 * 404 Error Page Template
 */
get_header();
?>

<!-- 404 CONTENT HERO -->
<section class="section section-white" style="padding: 4.5rem 0; min-height: 60vh; display: flex; align-items: center;">
  <div class="container text-center" style="max-width: 750px; margin: 0 auto; text-align: center;">
    <div style="font-size: 4.5rem; margin-bottom: 0.5rem; line-height: 1;">🔍 404</div>
    <span class="section-badge" style="margin-bottom: 1rem;">Page Not Found</span>
    <h1 style="font-size: 2.2rem; margin-bottom: 1rem; color: var(--text-charcoal);">Looking for Odiins Services?</h1>
    <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.6; margin-bottom: 2rem;">
      The page you are trying to visit might have moved, or the link has changed. Don't worry, you can find exactly what you need below:
    </p>

    <!-- Quick Helpful Navigation Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2.5rem; text-align: left;">
      <a href="<?php echo esc_url(home_url('/services-employers/')); ?>" style="background: var(--bg-light-blue); padding: 1.25rem; border-radius: var(--radius-md); text-decoration: none; border: 1px solid var(--border-light); display: block; transition: all 0.2s;">
        <div style="font-size: 1.5rem; margin-bottom: 0.35rem;">🏢</div>
        <div style="font-weight: 700; color: var(--text-charcoal); margin-bottom: 0.25rem;">For Employers</div>
        <div style="font-size: 0.82rem; color: var(--text-muted);">Hire sales managers, staff &amp; manpower in Bhubaneswar &rarr;</div>
      </a>

      <a href="<?php echo esc_url(home_url('/services-job-seekers/')); ?>" style="background: var(--bg-light-blue); padding: 1.25rem; border-radius: var(--radius-md); text-decoration: none; border: 1px solid var(--border-light); display: block; transition: all 0.2s;">
        <div style="font-size: 1.5rem; margin-bottom: 0.35rem;">💼</div>
        <div style="font-weight: 700; color: var(--text-charcoal); margin-bottom: 0.25rem;">For Job Seekers</div>
        <div style="font-size: 0.82rem; color: var(--text-muted);">Find verified private jobs across Odisha districts &rarr;</div>
      </a>

      <a href="<?php echo esc_url(home_url('/services-customers/')); ?>" style="background: var(--bg-light-blue); padding: 1.25rem; border-radius: var(--radius-md); text-decoration: none; border: 1px solid var(--border-light); display: block; transition: all 0.2s;">
        <div style="font-size: 1.5rem; margin-bottom: 0.35rem;">🏠</div>
        <div style="font-weight: 700; color: var(--text-charcoal); margin-bottom: 0.25rem;">Home Help</div>
        <div style="font-size: 0.82rem; color: var(--text-muted);">Hire verified maid, cook, driver or home tutor &rarr;</div>
      </a>

      <a href="<?php echo esc_url(home_url('/bank-csp-odisha.html')); ?>" style="background: var(--bg-light-blue); padding: 1.25rem; border-radius: var(--radius-md); text-decoration: none; border: 1px solid var(--border-light); display: block; transition: all 0.2s;">
        <div style="font-size: 1.5rem; margin-bottom: 0.35rem;">🏦</div>
        <div style="font-weight: 700; color: var(--text-charcoal); margin-bottom: 0.25rem;">Bank CSP Operator</div>
        <div style="font-size: 0.82rem; color: var(--text-muted);">Apply for CSP customer service points across Odisha &rarr;</div>
      </a>
    </div>

    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-green btn-lg">🏠 Return to Odiins Home</a>
      <a href="tel:+919938079601" class="btn btn-outline-green btn-lg">📞 Call Us: +91 99380 79601</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
