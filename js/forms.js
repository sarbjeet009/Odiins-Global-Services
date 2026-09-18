/**
 * ODIINS - Form Handling, Lead Capture & Spam Protection Engine
 * Implements:
 * 1. Spam defense: Honeypot trap + time-to-submit verification (<1.5s is bot)
 * 2. Short 4-field validation
 * 3. Submission to POST /api/leads with persistent JSON/CSV spreadsheet storage
 * 4. Graceful localStorage fallback if server is offline or in static preview
 * 5. Instant WhatsApp prefill connection option
 * 6. Direct UI response: "Thank you! We'll call you within 24 hours."
 */

document.addEventListener('DOMContentLoaded', () => {
  initFormTimers();
  initFormSubmissions();
  initNewsletterForm();
});

// Record load timestamp on every form to detect robotic sub-second submissions
function initFormTimers() {
  document.querySelectorAll('form').forEach((form) => {
    form.setAttribute('data-load-time', Date.now().toString());
  });
}

function initFormSubmissions() {
  const forms = [
    { id: 'jobSeekerForm', type: 'Job Seeker' },
    { id: 'employerForm', type: 'Employer' },
    { id: 'customerForm', type: 'Customer' },
    { id: 'contactForm', type: 'Contact Enquiry' },
    { id: 'sidebarMiniForm', type: 'Quick Enquiry' }
  ];

  forms.forEach(({ id, type }) => {
    const form = document.getElementById(id);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      // 1. Spam Check: Honeypot
      const hpField = form.querySelector('input[name="website_hp"]');
      if (hpField && hpField.value.trim() !== '') {
        console.warn('Spam trap triggered (honeypot). Request rejected.');
        showSubmissionSuccess(form, type, { name: 'Enquiry', phone: '' });
        return;
      }

      // 2. Spam Check: Submission speed (<1200ms usually bot)
      const loadTime = parseInt(form.getAttribute('data-load-time') || '0', 10);
      if (Date.now() - loadTime < 1200) {
        console.warn('Spam trap triggered (submission too fast).');
        showSubmissionSuccess(form, type, { name: 'Enquiry', phone: '' });
        return;
      }

      // Extract form data
      const formData = new FormData(form);
      const leadData = {
        id: 'OD-' + Date.now().toString(36).toUpperCase(),
        timestamp: new Date().toISOString(),
        formType: type,
        status: 'New',
        name: (formData.get('name') || formData.get('businessName') || '').toString().trim(),
        phone: (formData.get('phone') || '').toString().trim(),
        location: (formData.get('location') || formData.get('district') || formData.get('areaCity') || formData.get('city') || 'Odisha').toString().trim(),
        requirement: (formData.get('jobType') || formData.get('position') || formData.get('serviceNeeded') || formData.get('category') || 'General Manpower').toString().trim(),
        message: (formData.get('message') || '').toString().trim()
      };

      // Validation
      if (!leadData.name) {
        alert('Please enter your name or business name.');
        return;
      }

      const cleanPhone = leadData.phone.replace(/[^0-9]/g, '');
      if (cleanPhone.length < 10) {
        alert('Please enter a valid 10-digit phone number or WhatsApp number.');
        return;
      }

      const submitBtn = form.querySelector('button[type="submit"]');
      const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Submit';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Sending...';
      }

      try {
        // Send to backend API
        const response = await fetch('/api/leads', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(leadData)
        });

        if (response.ok) {
          const resJson = await response.json();
          saveLeadToLocalStorage(leadData); // Keep local copy
          showSubmissionSuccess(form, type, leadData);
        } else {
          // If server returns error, save locally
          saveLeadToLocalStorage(leadData);
          showSubmissionSuccess(form, type, leadData);
        }
      } catch (err) {
        // Offline or static file mode: store in localStorage
        console.log('Backend not reached, stored lead locally:', err);
        saveLeadToLocalStorage(leadData);
        showSubmissionSuccess(form, type, leadData);
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }
      }
    });
  });
}

function showSubmissionSuccess(form, type, leadData) {
  // Hide form fields or display success card
  const successBanner = form.parentElement.querySelector('.form-success-banner');
  if (successBanner) {
    form.style.display = 'none';
    successBanner.classList.add('active');

    // Add instant WhatsApp connect button
    const waConnectBtn = successBanner.querySelector('.instant-wa-btn');
    if (waConnectBtn) {
      const waMsg = encodeURIComponent(`Hello Odiins! I just submitted an enquiry for ${leadData.requirement} in ${leadData.location}. My name is ${leadData.name}.`);
      waConnectBtn.href = `https://wa.me/917008012345?text=${waMsg}`;
    }
  } else {
    // Alert or replacement
    form.innerHTML = `
      <div class="form-success-banner active" style="display:block;">
        <div class="form-success-icon">✓</div>
        <h4>Thank you! We'll call you within 24 hours.</h4>
        <p>Our Odisha team has received your enquiry. We are shortlisting the best options for you right now.</p>
        <div style="margin-top: 1.25rem;">
          <a href="https://wa.me/917008012345?text=${encodeURIComponent('Hello Odiins, I submitted an enquiry for ' + leadData.requirement)}" target="_blank" class="btn btn-green btn-sm" style="display:inline-flex;">
            <span>💬 Chat on WhatsApp Now</span>
          </a>
        </div>
      </div>
    `;
  }

  if (window.showToast) {
    window.showToast("Thank you! We'll call you within 24 hours.", 'success', 6000);
  }
}

// Local Storage Fallback & Mirroring
function saveLeadToLocalStorage(lead) {
  try {
    const existing = JSON.parse(localStorage.getItem('odiins_leads') || '[]');
    existing.unshift(lead);
    localStorage.setItem('odiins_leads', JSON.stringify(existing));
  } catch (e) {
    console.error('LocalStorage write error:', e);
  }
}

// Newsletter Subscription
function initNewsletterForm() {
  const form = document.getElementById('newsletterForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = form.querySelector('input[type="email"]');
    if (!input || !input.value) return;

    const email = input.value.trim();
    if (!email.includes('@')) {
      alert('Please enter a valid email address.');
      return;
    }

    try {
      await fetch('/api/leads', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id: 'NL-' + Date.now().toString(36).toUpperCase(),
          timestamp: new Date().toISOString(),
          formType: 'Newsletter',
          name: 'Subscriber',
          phone: '',
          email: email,
          location: 'Odisha',
          requirement: 'Job Alerts & Hiring Tips'
        })
      });
    } catch (e) {}

    input.value = '';
    if (window.showToast) {
      window.showToast('Subscribed! You will receive Odisha job alerts and hiring tips.', 'success');
    } else {
      alert('Subscribed! You will receive Odisha job alerts and hiring tips.');
    }
  });
}
