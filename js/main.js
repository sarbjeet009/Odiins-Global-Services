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
