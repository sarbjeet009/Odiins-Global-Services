/**
 * Odiins Global Services - Firebase Authentication & Google Sign-In Engine
 * Provides optional 1-click Google Sign-in, user session persistence,
 * user record upserting in Cloud Firestore, and form auto-fill hooks.
 */

(function () {
  'use strict';

  // Standard Firebase Web Client Configuration
  const FIREBASE_CONFIG = {
    apiKey: "AIzaSyB-qqo9Fu6Sf3RP3m-P_G2qc-95qZWznAU",
    authDomain: "odiins-global-services.firebaseapp.com",
    projectId: "odiins-global-services",
    storageBucket: "odiins-global-services.firebasestorage.app",
    messagingSenderId: "567871011253",
    appId: "1:567871011253:web:7e66ebe926adcded53c1fc",
    measurementId: "G-VR0G0V11MN"
  };

  const SESSION_KEY = 'odiins_auth_user';
  let authInstance = null;
  let currentUser = null;

  // Helper: Show notification toast with fallback
  function showAuthNotice(message, type = 'info', duration = 4000) {
    if (typeof window.showToast === 'function') {
      window.showToast(message, type, duration);
    } else {
      console.log(`[Odiins Auth ${type.toUpperCase()}] ${message}`);
    }
  }

  // Helper: Handle human-readable Firebase Auth errors
  function handleAuthError(error) {
    if (!error) return;
    console.warn('Firebase Auth notice:', error.code, error.message);

    if (error.code === 'auth/popup-closed-by-user') {
      // User closed the popup window deliberately; no alert needed
      return;
    }
    if (error.code === 'auth/unauthorized-domain') {
      showAuthNotice('Firebase domain setup syncing. If you just added odiins.in in Firebase Console, please wait 2-3 minutes for Google DNS cache to update, or try in an Incognito window.', 'info', 7000);
      return;
    }
    if (error.code === 'auth/network-request-failed') {
      showAuthNotice('Network connection issue. Please check your internet connection and try again.', 'error', 4500);
      return;
    }
    if (error.code === 'auth/operation-not-allowed') {
      showAuthNotice('Google Sign-in is currently disabled in Firebase Console. Please verify Authentication > Sign-in method.', 'error', 6000);
      return;
    }

    // Generic fallback
    const msg = error.message || 'Unable to complete sign-in. Please try again.';
    showAuthNotice(msg, 'error', 5000);
  }

  // Initialize Firebase App & Auth
  function initAuth() {
    if (typeof firebase === 'undefined') {
      console.warn('Firebase SDK not loaded. Retrying in 200ms...');
      setTimeout(initAuth, 200);
      return;
    }

    try {
      if (!firebase.apps.length) {
        firebase.initializeApp(FIREBASE_CONFIG);
      }
      authInstance = firebase.auth();

      // Listen for live Auth changes
      authInstance.onAuthStateChanged(handleAuthStateChange);

      // Handle redirect sign-in result (mobile browser fallback)
      authInstance.getRedirectResult()
        .then((result) => {
          if (result && result.user) {
            handleAuthStateChange(result.user);
            showAuthNotice(`Welcome back, ${result.user.displayName || 'valued customer'}!`, 'success');
          }
        })
        .catch((err) => {
          if (err && err.code) {
            handleAuthError(err);
          }
        });

      // Render cached user immediately for 0ms visual flicker
      const cached = getCachedUser();
      if (cached) {
        renderAuthUI(cached);
      } else {
        renderAuthUI(null);
      }
    } catch (err) {
      console.error('Firebase Auth initialization error:', err);
    }
  }

  // Get cached user from localStorage
  function getCachedUser() {
    try {
      const data = localStorage.getItem(SESSION_KEY);
      return data ? JSON.parse(data) : null;
    } catch (e) {
      return null;
    }
  }

  // Handle Auth State Changes from Firebase
  function handleAuthStateChange(user) {
    if (user) {
      const userData = {
        uid: user.uid,
        displayName: user.displayName || 'Valued User',
        email: user.email || '',
        photoURL: user.photoURL || '',
        provider: 'google.com',
        lastLogin: new Date().toISOString()
      };
      currentUser = userData;
      localStorage.setItem(SESSION_KEY, JSON.stringify(userData));
      renderAuthUI(userData);
      syncUserToFirestore(userData);
      notifyAuthSubscribers(userData);
    } else {
      currentUser = null;
      localStorage.removeItem(SESSION_KEY);
      renderAuthUI(null);
      notifyAuthSubscribers(null);
    }
  }

  // Notify listeners (such as forms.js) that auth state has changed
  function notifyAuthSubscribers(user) {
    window.dispatchEvent(new CustomEvent('odiins:auth-state-changed', {
      detail: { user: user }
    }));
  }

  // 1-Click Google Sign In (Popup with Redirect Fallback & Visual Indicator)
  async function signInWithGoogle(triggerBtn) {
    if (!authInstance) {
      showAuthNotice('Authentication is initializing. Please try again in a moment.', 'info');
      return;
    }

    let originalHtml = '';
    if (triggerBtn) {
      originalHtml = triggerBtn.innerHTML;
      triggerBtn.disabled = true;
      triggerBtn.classList.add('loading');
      triggerBtn.innerHTML = `
        <svg class="google-icon-svg auth-loading-spinner" viewBox="0 0 24 24" width="16" height="16">
          <circle cx="12" cy="12" r="10" stroke="#4285F4" stroke-width="3" fill="none" stroke-dasharray="31.4" stroke-dashoffset="10"/>
        </svg>
        <span>Connecting...</span>
      `;
    }

    const provider = new firebase.auth.GoogleAuthProvider();
    provider.addScope('email');
    provider.addScope('profile');
    provider.setCustomParameters({ prompt: 'select_account' });

    try {
      const result = await authInstance.signInWithPopup(provider);
      if (result && result.user) {
        showAuthNotice(`Signed in as ${result.user.displayName || result.user.email}!`, 'success');
      }
      return result.user;
    } catch (error) {
      if (error.code === 'auth/popup-blocked' || error.code === 'auth/cancelled-popup-request') {
        try {
          showAuthNotice('Opening Google Sign-in...', 'info', 2000);
          await authInstance.signInWithRedirect(provider);
          return;
        } catch (redirectErr) {
          handleAuthError(redirectErr);
        }
      } else {
        handleAuthError(error);
      }
    } finally {
      if (triggerBtn && originalHtml) {
        triggerBtn.disabled = false;
        triggerBtn.classList.remove('loading');
        triggerBtn.innerHTML = originalHtml;
      }
    }
  }

  // Sign Out
  async function signOut() {
    if (!authInstance) return;
    try {
      await authInstance.signOut();
      localStorage.removeItem(SESSION_KEY);
      currentUser = null;
      renderAuthUI(null);
      notifyAuthSubscribers(null);
      showAuthNotice('You have been signed out.', 'info');
    } catch (e) {
      console.error('Sign out error:', e);
    }
  }

  // Sync / Upsert user profile in Cloud Firestore (REST API)
  async function syncUserToFirestore(user) {
    if (!user || !user.uid) return;
    try {
      const url = `https://firestore.googleapis.com/v1/projects/${FIREBASE_CONFIG.projectId}/databases/(default)/documents/users/${user.uid}?key=${FIREBASE_CONFIG.apiKey}`;
      const payload = {
        fields: {
          uid: { stringValue: user.uid },
          displayName: { stringValue: user.displayName || '' },
          email: { stringValue: user.email || '' },
          photoURL: { stringValue: user.photoURL || '' },
          lastLogin: { timestampValue: new Date().toISOString() },
          provider: { stringValue: 'google.com' }
        }
      };

      await fetch(url, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
    } catch (err) {
      // Non-critical background sync
    }
  }

  // Render Navbar and Mobile Drawer Auth Component
  function renderAuthUI(user) {
    const slots = document.querySelectorAll('.auth-nav-slot');
    if (!slots.length) return;

    slots.forEach(slot => {
      if (user) {
        // Logged-in state
        const firstName = (user.displayName || 'User').split(' ')[0];
        const avatarHtml = user.photoURL
          ? `<img src="${user.photoURL}" alt="${user.displayName}" class="user-nav-avatar" referrerpolicy="no-referrer">`
          : `<span class="user-nav-avatar-initial">${firstName.charAt(0).toUpperCase()}</span>`;

        slot.innerHTML = `
          <div class="user-nav-dropdown-wrapper">
            <button type="button" class="user-nav-chip" aria-label="User Account Menu" aria-expanded="false">
              ${avatarHtml}
              <span class="user-nav-name">Hi, ${firstName}</span>
              <span class="user-nav-arrow">▾</span>
            </button>
            <div class="user-nav-dropdown-menu" style="display:none;">
              <div class="user-dropdown-header">
                <strong>${user.displayName}</strong>
                <span class="user-dropdown-email">${user.email}</span>
                <span class="user-dropdown-badge">✓ Google Verified</span>
              </div>
              <div class="user-dropdown-info">
                <span>⚡ Forms auto-fill your contact details automatically.</span>
              </div>
              <div class="user-dropdown-divider"></div>
              <button type="button" class="user-dropdown-logout-btn">
                <span>🚪 Sign Out</span>
              </button>
            </div>
          </div>
        `;

        // Wire dropdown toggle
        const chipBtn = slot.querySelector('.user-nav-chip');
        const dropdown = slot.querySelector('.user-nav-dropdown-menu');
        const signOutBtn = slot.querySelector('.user-dropdown-logout-btn');

        if (chipBtn && dropdown) {
          chipBtn.onclick = (e) => {
            e.stopPropagation();
            const isOpen = dropdown.style.display === 'block';
            dropdown.style.display = isOpen ? 'none' : 'block';
            chipBtn.setAttribute('aria-expanded', !isOpen);
          };
        }

        if (signOutBtn) {
          signOutBtn.onclick = (e) => {
            e.preventDefault();
            e.stopPropagation();
            signOut();
          };
        }
      } else {
        // Logged-out state: Clean, non-intrusive Google Sign-in button
        slot.innerHTML = `
          <button type="button" class="btn btn-google-nav" aria-label="Sign In with Google" title="Optional: Sign in with your Google account">
            <svg class="google-icon-svg" viewBox="0 0 24 24" width="16" height="16">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Sign In</span>
          </button>
        `;

        const btn = slot.querySelector('.btn-google-nav');
        if (btn) {
          btn.onclick = (e) => {
            e.preventDefault();
            signInWithGoogle(btn);
          };
        }
      }
    });

    // Close any open dropdowns when clicking outside
    document.addEventListener('click', (e) => {
      document.querySelectorAll('.user-nav-dropdown-menu').forEach(menu => {
        if (menu.style.display === 'block' && !menu.contains(e.target)) {
          menu.style.display = 'none';
        }
      });
    });
  }

  // Public API exposed on window.OdiinsAuth
  window.OdiinsAuth = {
    getUser: function () {
      return currentUser || getCachedUser();
    },
    signIn: function(triggerBtn) {
      return signInWithGoogle(triggerBtn);
    },
    signOut: signOut,
    isLoggedIn: function () {
      return !!(currentUser || getCachedUser());
    }
  };

  // Run on DOM load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAuth);
  } else {
    initAuth();
  }
})();
