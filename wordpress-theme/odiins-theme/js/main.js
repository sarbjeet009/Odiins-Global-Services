/**
 * ODIINS - Main Interactive Scripts
 * Handles mobile drawer, marquee ticker, counter animations, accordions,
 * district filters, media tabs, blog search, cookie consent, and Odia toggle.
 */

document.addEventListener('DOMContentLoaded', () => {
  initMobileDrawer();
  initCounters();
  initAccordion();
  initDistrictChips();
  initMediaTabs();
  initBlogFilters();
  initCookieBanner();
  initOdiaToggle();
  initMarqueePause();
  initHeroInteractiveParallax();
  initCard3DTilt();
  initScrollParallax();
});

/* 1. Mobile Menu Drawer */
function initMobileDrawer() {
  const toggleBtn = document.getElementById('navToggleBtn');
  const closeBtn = document.getElementById('drawerCloseBtn');
  const drawer = document.getElementById('mobileDrawer');
  const overlay = document.getElementById('drawerOverlay');

  if (!toggleBtn || !drawer || !overlay) return;

  function openDrawer() {
    drawer.classList.add('open');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    drawer.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  toggleBtn.addEventListener('click', openDrawer);
  if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
  overlay.addEventListener('click', closeDrawer);

  // Close on Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && drawer.classList.contains('open')) {
      closeDrawer();
    }
  });
}

/* 2. Animated Counters */
function initCounters() {
  const counterElements = document.querySelectorAll('.counter-number[data-target]');
  if (!counterElements.length) return;

  let animated = false;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting && !animated) {
        animated = true;
        counterElements.forEach((el) => {
          const target = parseInt(el.getAttribute('data-target'), 10);
          const suffix = el.getAttribute('data-suffix') || '';
          const duration = 1800; // ms
          const stepTime = 25;
          const totalSteps = duration / stepTime;
          const increment = target / totalSteps;
          let current = 0;

          const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
              current = target;
              clearInterval(timer);
            }
            el.textContent = Math.floor(current).toLocaleString('en-IN') + suffix;
          }, stepTime);
        });
      }
    });
  }, { threshold: 0.25 });

  const strip = document.querySelector('.counter-strip');
  if (strip) observer.observe(strip);
}

/* 3. FAQ Accordion */
function initAccordion() {
  const headers = document.querySelectorAll('.accordion-header');
  headers.forEach((header) => {
    header.addEventListener('click', () => {
      const item = header.parentElement;
      const content = header.nextElementSibling;
      const isOpen = item.classList.contains('active');

      // Close other accordions
      document.querySelectorAll('.accordion-item').forEach((other) => {
        if (other !== item) {
          other.classList.remove('active');
          const otherContent = other.querySelector('.accordion-content');
          if (otherContent) otherContent.style.maxHeight = null;
        }
      });

      if (!isOpen) {
        item.classList.add('active');
        content.style.maxHeight = content.scrollHeight + 'px';
      } else {
        item.classList.remove('active');
        content.style.maxHeight = null;
      }
    });
  });
}

/* 4. District Chips & Expand All 30 Districts */
function initDistrictChips() {
  const chips = document.querySelectorAll('.district-chip');
  chips.forEach((chip) => {
    chip.addEventListener('click', (e) => {
      e.preventDefault();
      chips.forEach((c) => c.classList.remove('active'));
      chip.classList.add('active');
      const district = chip.getAttribute('data-district') || chip.textContent.trim();
      showToast(`Serving ${district} with verified candidates and trusted staff!`, 'success');
    });
  });

  const viewAllBtn = document.getElementById('viewAllDistrictsBtn');
  const extraDistricts = document.getElementById('extraDistricts');
  if (viewAllBtn && extraDistricts) {
    viewAllBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (extraDistricts.style.display === 'none' || !extraDistricts.style.display) {
        extraDistricts.style.display = 'flex';
        viewAllBtn.textContent = 'Show Fewer Districts';
      } else {
        extraDistricts.style.display = 'none';
        viewAllBtn.textContent = 'View All 30 Districts of Odisha';
      }
    });
  }
}

/* 5. Media & Recognition Filter Tabs */
function initMediaTabs() {
  const tabs = document.querySelectorAll('.media-tab');
  const cards = document.querySelectorAll('.media-card');
  if (!tabs.length) return;

  tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      tabs.forEach((t) => t.classList.remove('active'));
      tab.classList.add('active');
      const filter = tab.getAttribute('data-filter');

      cards.forEach((card) => {
        const category = card.getAttribute('data-category');
        if (filter === 'all' || category === filter) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
}

/* 6. Blog Search & Category Filters */
function initBlogFilters() {
  const filterBtns = document.querySelectorAll('.blog-filter-btn');
  const blogCards = document.querySelectorAll('.blog-card');
  const searchInput = document.getElementById('blogSearchInput');
  if (!blogCards.length) return;

  let currentCategory = 'all';
  let searchQuery = '';

  function filterBlogs() {
    blogCards.forEach((card) => {
      const category = card.getAttribute('data-category') || '';
      const title = (card.querySelector('h3') ? card.querySelector('h3').textContent : '').toLowerCase();
      const excerpt = (card.querySelector('p') ? card.querySelector('p').textContent : '').toLowerCase();

      const matchesCat = (currentCategory === 'all' || category === currentCategory);
      const matchesSearch = !searchQuery || title.includes(searchQuery) || excerpt.includes(searchQuery);

      if (matchesCat && matchesSearch) {
        card.style.display = 'flex';
      } else {
        card.style.display = 'none';
      }
    });
  }

  filterBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      filterBtns.forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');
      currentCategory = btn.getAttribute('data-category');
      filterBlogs();
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      searchQuery = e.target.value.toLowerCase().trim();
      filterBlogs();
    });
  }
}

/* 7. Cookie Consent Banner */
function initCookieBanner() {
  const banner = document.getElementById('cookieBanner');
  const acceptBtn = document.getElementById('acceptCookieBtn');
  if (!banner || !acceptBtn) return;

  const consented = localStorage.getItem('odiins_cookie_consent');
  if (!consented) {
    banner.classList.add('show');
  }

  acceptBtn.addEventListener('click', () => {
    localStorage.setItem('odiins_cookie_consent', 'true');
    banner.classList.remove('show');
    showToast('Cookie preferences saved.', 'info');
  });
}

/* 8. Odia Language Toggle Placeholder */
function initOdiaToggle() {
  const toggles = document.querySelectorAll('.lang-toggle');
  toggles.forEach((toggle) => {
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      showToast('ଓଡ଼ିଆ ଭାଷା ସଂସ୍କରଣ ଖୁବ ଶୀଘ୍ର ଉପଲବ୍ଧ ହେବ! ଆମ ଟିମ୍ ଓଡ଼ିଆରେ କଥାବାର୍ତ୍ତା କରନ୍ତି। (Odia language portal is coming soon! Our support team speaks fluent Odia.)', 'success', 5000);
    });
  });
}

/* 9. Marquee Pause on Hover/Touch */
function initMarqueePause() {
  const track = document.querySelector('.ticker-track');
  if (!track) return;

  track.addEventListener('mouseenter', () => {
    track.style.animationPlayState = 'paused';
  });
  track.addEventListener('mouseleave', () => {
    track.style.animationPlayState = 'running';
  });
}

/* Helper: Toast Notifications */
function showToast(message, type = 'info', duration = 3500) {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.innerHTML = `
    <span>${type === 'success' ? '✓' : 'ℹ'}</span>
    <span>${message}</span>
  `;

  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'all 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, duration);
}

// Global expose
window.showToast = showToast;

/* ==========================================================================
   10. INTERACTIVE HERO PARALLAX ENGINE (3D Tilt & Layer Depth)
   ========================================================================== */
function initHeroInteractiveParallax() {
  const hero = document.getElementById('heroSection');
  const cardStack = document.getElementById('heroCardStack');
  const badge1 = document.getElementById('heroBadge1');
  const badge2 = document.getElementById('heroBadge2');
  const bgLayer = document.getElementById('heroBgLayer');
  const orbs = document.querySelectorAll('.parallax-orb');

  if (!hero || !cardStack) return;

  // Honor prefers-reduced-motion accessibility setting
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }

  let isVisible = true;
  let mouseX = 0;
  let mouseY = 0;
  let targetTiltX = 0;
  let targetTiltY = 0;
  let currentTiltX = 0;
  let currentTiltY = 0;
  let isHovered = false;
  let rafId = null;

  // Viewport Observer: Pause RAF loop when hero is offscreen to guarantee 0% idle CPU
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      isVisible = entry.isIntersecting;
      if (isVisible) {
        startLoop();
      } else {
        stopLoop();
      }
    });
  }, { threshold: 0.05 });
  observer.observe(hero);

  function startLoop() {
    if (!rafId) {
      rafId = requestAnimationFrame(updateLoop);
    }
  }

  function stopLoop() {
    if (rafId) {
      cancelAnimationFrame(rafId);
      rafId = null;
    }
  }

  // Pointer tracking in hero area (Desktop)
  hero.addEventListener('pointermove', (e) => {
    isHovered = true;
    const rect = hero.getBoundingClientRect();
    const relX = (e.clientX - rect.left) / rect.width - 0.5; // -0.5 to 0.5
    const relY = (e.clientY - rect.top) / rect.height - 0.5;

    // Smooth responsive tilt (max 10-12 deg)
    targetTiltX = -relY * 12;
    targetTiltY = relX * 14;

    mouseX = relX;
    mouseY = relY;
  }, { passive: true });

  hero.addEventListener('pointerleave', () => {
    isHovered = false;
    targetTiltX = 0;
    targetTiltY = 0;
    mouseX = 0;
    mouseY = 0;
  });

  // Mobile DeviceOrientation (Gyroscope Tilt) Support
  if (window.DeviceOrientationEvent && 'ontouchstart' in window) {
    window.addEventListener('deviceorientation', (e) => {
      if (!isVisible || e.gamma === null || e.beta === null) return;
      const gamma = Math.max(-30, Math.min(30, e.gamma));
      const beta = Math.max(15, Math.min(65, e.beta)) - 40; // centered at ~40deg phone hold angle
      targetTiltY = (gamma / 30) * 8;
      targetTiltX = -(beta / 25) * 6;
      mouseX = gamma / 60;
      mouseY = beta / 50;
    }, { passive: true });
  }

  function updateLoop() {
    if (!isVisible) {
      rafId = null;
      return;
    }

    // Smooth Lerp (factor 0.08 for fluid inertia)
    const factor = isHovered ? 0.08 : 0.05;
    currentTiltX += (targetTiltX - currentTiltX) * factor;
    currentTiltY += (targetTiltY - currentTiltY) * factor;

    // 1. 3D Card Stack Tilt
    cardStack.style.transform = `perspective(1000px) rotateX(${currentTiltX.toFixed(2)}deg) rotateY(${currentTiltY.toFixed(2)}deg)`;

    // 2. Multi-plane Badges with elevated Z-space and subtle counter-parallax
    if (badge1) {
      const b1X = -currentTiltY * 0.9;
      const b1Y = -currentTiltX * 0.9;
      badge1.style.transform = `translateZ(34px) translate3d(${b1X.toFixed(1)}px, ${b1Y.toFixed(1)}px, 0)`;
    }
    if (badge2) {
      const b2X = -currentTiltY * 1.3;
      const b2Y = -currentTiltX * 1.3;
      badge2.style.transform = `translateZ(42px) translate3d(${b2X.toFixed(1)}px, ${b2Y.toFixed(1)}px, 0)`;
    }

    // 3. Hero background layer subtle inverse drift
    if (bgLayer) {
      const bgX = -currentTiltY * 0.7;
      const bgY = -currentTiltX * 0.7;
      bgLayer.style.transform = `translate3d(${bgX.toFixed(1)}px, ${bgY.toFixed(1)}px, 0)`;
    }

    // 4. Ambient glowing orbs multi-depth drift
    if (orbs.length) {
      orbs.forEach((orb, idx) => {
        const speed = parseFloat(orb.getAttribute('data-speed')) || (idx === 0 ? 0.06 : (idx === 1 ? -0.04 : 0.08));
        const orbX = mouseX * speed * 260;
        const orbY = mouseY * speed * 260;
        orb.style.transform = `translate3d(${orbX.toFixed(1)}px, ${orbY.toFixed(1)}px, 0)`;
      });
    }

    rafId = requestAnimationFrame(updateLoop);
  }

  startLoop();
}

/* ==========================================================================
   11. INTERACTIVE 3D TILT & CURSOR SPOTLIGHT FOR CARDS
   ========================================================================== */
function initCard3DTilt() {
  const cards = document.querySelectorAll('.feature-card, .step-card');
  if (!cards.length) return;

  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }

  cards.forEach((card) => {
    let rect = null;

    card.addEventListener('pointerenter', () => {
      rect = card.getBoundingClientRect();
    });

    card.addEventListener('pointermove', (e) => {
      if (!rect) rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      // Update cursor spotlight position for reflective sheen
      card.style.setProperty('--mouse-x', `${x}px`);
      card.style.setProperty('--mouse-y', `${y}px`);

      // 3D tilt calculation relative to card center
      const normX = (x / rect.width) - 0.5;
      const normY = (y / rect.height) - 0.5;
      const tiltX = -normY * 12; // degrees
      const tiltY = normX * 12;

      card.style.setProperty('--tilt-x', `${tiltX.toFixed(2)}deg`);
      card.style.setProperty('--tilt-y', `${tiltY.toFixed(2)}deg`);
    }, { passive: true });

    card.addEventListener('pointerleave', () => {
      rect = null;
      card.style.setProperty('--tilt-x', '0deg');
      card.style.setProperty('--tilt-y', '0deg');
    });
  });
}

/* ==========================================================================
   12. MULTI-PLANE SCROLL PARALLAX (Desktop & Mobile)
   ========================================================================== */
function initScrollParallax() {
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    return;
  }

  const heroBg = document.getElementById('heroBgLayer');
  if (!heroBg) return;

  let ticking = false;

  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        const scrollY = window.pageYOffset || document.documentElement.scrollTop;
        if (scrollY <= 700) {
          heroBg.style.backgroundPositionY = `calc(100% + ${(scrollY * 0.22).toFixed(1)}px)`;
        }
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
}

