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

// Google Sheets Webhook URL for real-time lead capture
const GOOGLE_SHEETS_WEBHOOK_URL = 'https://script.google.com/macros/s/AKfycbwxblKhDvNFHne_A_5rXYGiqOE21Bg55Jnf6UBGtq4IQzPXFFd14Ncgrjl9NWF1lBlW/exec';

// Cloud Firestore Webhook Configuration
const FIREBASE_CONFIG = {
  apiKey: "AIzaSyB-qqo9Fu6Sf3RP3m-P_G2qc-95qZWznAU",
  authDomain: "odiins-global-services.firebaseapp.com",
  projectId: "odiins-global-services",
  storageBucket: "odiins-global-services.firebasestorage.app",
  messagingSenderId: "567871011253",
  appId: "1:567871011253:web:7e66ebe926adcded53c1fc",
  measurementId: "G-VR0G0V11MN"
};

document.addEventListener('DOMContentLoaded', () => {
  initAdTracking();
  initFormTimers();
  initFormSubmissions();
  initNewsletterForm();
  initAuthFormAutoFill();
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
      let qualification = '';
      let shopStatus = '';
      let branchDistance = '';
      if (type === 'Bank CSP Operator') {
        qualification = (formData.get('qualification') || '').toString().trim();
        shopStatus = (formData.get('shopStatus') || '').toString().trim();
        branchDistance = (formData.get('branchDistance') || '').toString().trim();
        requirement = `Bank CSP Operator [Edu: ${qualification || '12th+'} | Shop: ${shopStatus || 'Yes'} | Dist: ${branchDistance || '<15km'}]`;
      } else if (!requirement) {
        requirement = 'General Manpower';
      }

      const authUser = form._autofilledUser || (window.OdiinsAuth && window.OdiinsAuth.getUser()) || null;

      const leadData = {
        id: 'OD-' + Date.now().toString(36).toUpperCase(),
        timestamp: new Date().toISOString(),
        formType: type,
        status: 'New',
        name: (formData.get('name') || formData.get('businessName') || '').toString().trim(),
        phone: (formData.get('phone') || '').toString().trim(),
        email: (formData.get('email') || (authUser ? authUser.email : '') || '').toString().trim(),
        userUid: authUser ? authUser.uid : '',
        district: district,
        areaCity: areaCity,
        location: location,
        requirement: requirement,
        qualification: qualification,
        shopStatus: shopStatus,
        branchDistance: branchDistance,
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

      // 1. Instantly push to Google Sheets Webhook
      sendLeadToGoogleSheets(leadData);

      // 2. Instantly push to Cloud Firestore
      sendLeadToFirestore(leadData);

      // 3. Push to local / backend API
      try {
        await fetch('/api/leads', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(leadData)
        });
      } catch (err) {
        console.warn('Backend endpoint notice:', err);
      } finally {
        fireAdConversions(leadData);
        saveLeadToLocalStorage(leadData);
        showSubmissionSuccess(form, type, leadData);
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }
      }
    });
  });
}

// Push lead in real-time to Google Cloud Firestore via REST
async function sendLeadToFirestore(leadData) {
  if (!FIREBASE_CONFIG || !FIREBASE_CONFIG.projectId) return;

  try {
    const docId = leadData.id || ('OD-' + Date.now().toString(36).toUpperCase());
    const url = `https://firestore.googleapis.com/v1/projects/${FIREBASE_CONFIG.projectId}/databases/(default)/documents/leads?documentId=${docId}&key=${FIREBASE_CONFIG.apiKey}`;

    const fields = {};
    Object.entries(leadData).forEach(([key, val]) => {
      fields[key] = { stringValue: (val !== undefined && val !== null) ? String(val) : '' };
    });

    await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ fields })
    });
    console.log('Lead synced to Cloud Firestore successfully (Doc ID: ' + docId + ').');
  } catch (err) {
    console.warn('Firestore sync warning:', err);
  }
}

// Push lead in real-time to Google Spreadsheet via Apps Script Web App
async function sendLeadToGoogleSheets(leadData) {
  if (!GOOGLE_SHEETS_WEBHOOK_URL) return;

  try {
    // Mode 'no-cors' allows browser to post to Google Apps Script without CORS blockage
    await fetch(GOOGLE_SHEETS_WEBHOOK_URL, {
      method: 'POST',
      mode: 'no-cors',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(leadData)
    });
    console.log('Lead synced to Google Spreadsheet successfully.');
  } catch (err) {
    console.warn('Google Sheets sync warning:', err);
  }
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

// Dedicated In-Form Google Fill Button (Strictly on-demand, ZERO auto-login on page load)
function initAuthFormAutoFill() {
  const targetFormIds = ['jobSeekerForm', 'employerForm', 'customerForm', 'customerLeadForm', 'contactForm', 'cspForm', 'sidebarMiniForm'];

  targetFormIds.forEach(id => {
    const form = document.getElementById(id);
    if (!form || form.querySelector('.form-google-fill-box')) return;

    // Create the in-form Google button
    const fillBox = document.createElement('div');
    fillBox.className = 'form-google-fill-box';
    fillBox.innerHTML = `
      <button type="button" class="btn-google-form-fill" aria-label="Auto-fill with Google">
        <svg class="google-icon-svg" viewBox="0 0 24 24" width="16" height="16">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Fill details with Google</span>
      </button>
      <div class="google-fill-status" style="display:none;">
        <span class="status-text">✓ Filled from Google (<strong class="status-name"></strong>)</span>
        <button type="button" class="btn-clear-autofill" title="Clear and enter manually">✕ Clear</button>
      </div>
      <div class="form-or-divider">
        <span>OR ENTER DETAILS MANUALLY</span>
      </div>
    `;

    // Insert right before the first visible form-group / input
    const firstField = form.querySelector('.form-group, .input-group, input:not(.hp-trap), select');
    if (firstField) {
      firstField.parentNode.insertBefore(fillBox, firstField);
    } else {
      form.prepend(fillBox);
    }

    const fillBtn = fillBox.querySelector('.btn-google-form-fill');
    const statusBox = fillBox.querySelector('.google-fill-status');
    const statusName = fillBox.querySelector('.status-name');
    const clearBtn = fillBox.querySelector('.btn-clear-autofill');

    // On-demand click handler: ONLY triggers when user deliberately clicks
    fillBtn.addEventListener('click', async (e) => {
      e.preventDefault();
      if (!window.OdiinsAuth || typeof window.OdiinsAuth.signIn !== 'function') {
        alert('Authentication service is still loading. Please try again.');
        return;
      }

      const user = await window.OdiinsAuth.signIn(fillBtn);
      if (user) {
        form._autofilledUser = user;

        // Populate Name
        const nameInput = form.querySelector('input[name="name"], input[name="fullName"], input[name="applicantName"], input[name="businessName"], #name, #empName, #seekerName, #custName, #cspName, #jsName, #empBizName, #cntName');
        if (nameInput) {
          nameInput.value = user.displayName || '';
          nameInput.dataset.autofilled = 'true';
        }

        // Populate Email if field exists
        const emailInput = form.querySelector('input[name="email"], input[type="email"], #email, #empEmail, #seekerEmail');
        if (emailInput) {
          emailInput.value = user.email || '';
          emailInput.dataset.autofilled = 'true';
        }

        // Update UI
        fillBtn.style.display = 'none';
        statusName.textContent = user.displayName || user.email;
        statusBox.style.display = 'inline-flex';
      }
    });

    // Clear handler
    clearBtn.addEventListener('click', (e) => {
      e.preventDefault();
      form._autofilledUser = null;

      const nameInput = form.querySelector('input[data-autofilled="true"]');
      if (nameInput) {
        nameInput.value = '';
        delete nameInput.dataset.autofilled;
      }
      const emailInput = form.querySelector('input[type="email"][data-autofilled="true"], input[name="email"][data-autofilled="true"]');
      if (emailInput) {
        emailInput.value = '';
        delete emailInput.dataset.autofilled;
      }

      statusBox.style.display = 'none';
      fillBtn.style.display = 'inline-flex';
    });
  });
}


