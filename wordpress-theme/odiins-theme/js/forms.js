/**
 * ODIINS - Form Handling, Lead Capture, Spam Shield & Ad Attribution Engine
 * Features:
 * 1. Google Ads (gclid) & Meta Ads (fbclid / UTM) automated attribution sniffer
 * 2. Spam defense: Honeypot trap + time-to-submit verification
 * 3. Short 4-field validation
 * 4. Submission to POST /api/leads with persistent JSON/CSV spreadsheet sync
 * 5. Conversion event firing for Google Ads (gtag) & Meta Pixel (fbq)
 * 6. Dual-mode localStorage fallback
 * 7. Instant WhatsApp prefill connection
 */

document.addEventListener('DOMContentLoaded', () => {
  initAdTracking();
  initFormTimers();
  initFormSubmissions();
  initNewsletterForm();
});

// Sniff and persist UTM parameters and Click IDs across page navigations
function initAdTracking() {
  try {
    const params = new URLSearchParams(window.location.search);
    const gclid = params.get('gclid');
    const fbclid = params.get('fbclid');
    const utmSource = params.get('utm_source');
    const utmMedium = params.get('utm_medium');
    const utmCampaign = params.get('utm_campaign');

    if (gclid || fbclid || utmSource || utmCampaign) {
      const attribution = {
        gclid: gclid || '',
        fbclid: fbclid || '',
        utmSource: (utmSource || '').toLowerCase(),
        utmMedium: utmMedium || '',
        utmCampaign: utmCampaign || 'Direct',
        capturedAt: new Date().toISOString()
      };
      sessionStorage.setItem('odiins_attribution', JSON.stringify(attribution));
    }
  } catch (e) {
    console.warn('Ad tracking sniffer error:', e);
  }
}

function getStoredAdAttribution() {
  try {
    const raw = sessionStorage.getItem('odiins_attribution');
    if (raw) return JSON.parse(raw);
  } catch (e) {}
  return null;
}

function resolveAdSource(attr) {
  if (!attr) return { adSource: 'Direct / Organic', campaign: 'Direct', clickId: '' };
  
  if (attr.gclid || attr.utmSource.includes('google')) {
    return {
      adSource: 'Google Ads',
      campaign: attr.utmCampaign || 'google_search_campaign',
      clickId: attr.gclid || ''
    };
  }
  
  if (attr.fbclid || attr.utmSource.includes('facebook') || attr.utmSource.includes('meta') || attr.utmSource.includes('instagram')) {
    return {
      adSource: 'Meta Ads',
      campaign: attr.utmCampaign || 'meta_social_campaign',
      clickId: attr.fbclid || ''
    };
  }

  return {
    adSource: attr.utmSource ? `Campaign (${attr.utmSource})` : 'Direct / Organic',
    campaign: attr.utmCampaign || 'Direct',
    clickId: ''
  };
}

// Record load timestamp to detect robotic sub-second submissions
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
    { id: 'sidebarMiniForm', type: 'Quick Enquiry' },
    { id: 'cspForm', type: 'Bank CSP Operator' }
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

      // Resolve Ad Source
      const attribution = resolveAdSource(getStoredAdAttribution());

      // Extract form data
      const formData = new FormData(form);
      const district = (formData.get('district') || '').toString().trim();
      const areaCity = (formData.get('areaCity') || formData.get('city') || '').toString().trim();
      let location = (formData.get('location') || '').toString().trim();
      if (!location) {
        location = district && areaCity ? `${areaCity}, ${district}` : (district || areaCity || 'Odisha');
      }

      let requirement = (formData.get('jobType') || formData.get('position') || formData.get('serviceNeeded') || formData.get('category') || '').toString().trim();
      if (type === 'Bank CSP Operator') {
        const qual = (formData.get('qualification') || '').toString().trim();
        const shop = (formData.get('shopStatus') || '').toString().trim();
        const dist = (formData.get('branchDistance') || '').toString().trim();
        requirement = `Bank CSP Operator [Edu: ${qual || '12th+'} | Shop: ${shop || 'Yes'} | Dist: ${dist || '<15km'}]`;
      } else if (!requirement) {
        requirement = 'General Manpower';
      }

      const leadData = {
        id: 'OD-' + Date.now().toString(36).toUpperCase(),
        timestamp: new Date().toISOString(),
        formType: type,
        status: 'New',
        name: (formData.get('name') || formData.get('businessName') || '').toString().trim(),
        phone: (formData.get('phone') || '').toString().trim(),
        location: location,
        requirement: requirement,
        message: (formData.get('message') || '').toString().trim(),
        adSource: attribution.adSource,
        campaign: attribution.campaign,
        clickId: attribution.clickId
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
        const postUrl = (typeof window !== 'undefined' && window.odiins_wp && window.odiins_wp.rest_url) ? (window.odiins_wp.rest_url + 'leads') : '/api/leads';
        const response = await fetch(postUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(leadData)
        });

        if (response.ok) {
          fireAdConversions(leadData);
          saveLeadToLocalStorage(leadData);
          showSubmissionSuccess(form, type, leadData);
        } else {
          saveLeadToLocalStorage(leadData);
          showSubmissionSuccess(form, type, leadData);
        }
      } catch (err) {
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

// Trigger Google Ads Conversion & Meta Pixel Lead Event
function fireAdConversions(leadData) {
  try {
    // 1. Google Ads Conversion
    if (typeof window.gtag === 'function') {
      window.gtag('event', 'conversion', {
        'send_to': 'AW-11452908312/wKxPCMK954EZEKi9o9Uq',
        'event_category': leadData.formType,
        'event_label': leadData.requirement,
        'value': 1.0,
        'currency': 'INR'
      });
      console.log('Google Ads Conversion Event fired successfully.');
    }

    // 2. Meta Pixel Lead Event
    if (typeof window.fbq === 'function') {
      window.fbq('track', 'Lead', {
        content_name: leadData.requirement,
        content_category: leadData.formType,
        value: 1.0,
        currency: 'INR'
      });
      console.log('Meta Pixel Lead Event fired successfully.');
    }
  } catch (e) {
    console.warn('Ad conversion trigger notice:', e);
  }
}

function showSubmissionSuccess(form, type, leadData) {
  const successBanner = form.parentElement.querySelector('.form-success-banner');
  if (successBanner) {
    form.style.display = 'none';
    successBanner.classList.add('active');

    const waConnectBtn = successBanner.querySelector('.instant-wa-btn');
    if (waConnectBtn) {
      const waMsg = encodeURIComponent(`Hello Odiins! I just submitted an enquiry for ${leadData.requirement} in ${leadData.location}. My name is ${leadData.name}.`);
      waConnectBtn.href = `https://wa.me/919938079601?text=${waMsg}`;
    }
  } else {
    form.innerHTML = `
      <div class="form-success-banner active" style="display:block;">
        <div class="form-success-icon">✓</div>
        <h4>Thank you! We'll call you within 24 hours.</h4>
        <p>Our Odisha team has received your enquiry. We are shortlisting the best options for you right now.</p>
        <div style="margin-top: 1.25rem;">
          <a href="https://wa.me/919938079601?text=${encodeURIComponent('Hello Odiins, I submitted an enquiry for ' + leadData.requirement)}" target="_blank" class="btn btn-green btn-sm" style="display:inline-flex;">
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

function saveLeadToLocalStorage(lead) {
  try {
    const existing = JSON.parse(localStorage.getItem('odiins_leads') || '[]');
    existing.unshift(lead);
    localStorage.setItem('odiins_leads', JSON.stringify(existing));
  } catch (e) {
    console.error('LocalStorage write error:', e);
  }
}

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
      const postUrl = (typeof window !== 'undefined' && window.odiins_wp && window.odiins_wp.rest_url) ? (window.odiins_wp.rest_url + 'leads') : '/api/leads';
      await fetch(postUrl, {
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
          requirement: 'Job Alerts & Hiring Tips',
          adSource: 'Website Organic',
          campaign: 'footer_newsletter'
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
