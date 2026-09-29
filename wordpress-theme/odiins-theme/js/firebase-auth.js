/**
 * Odiins Global Services - Firebase Authentication & Google Sign-In Engine
 * Dedicated for in-form optional auto-fill (Zero auto-login on page load)
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

  let authInstance = null;
  let currentUser = null;

  // Clear legacy persistent session from localStorage so no auto-login occurs
  try {
    localStorage.removeItem('odiins_auth_user');
    sessionStorage.removeItem('odiins_auth_user');
  } catch (e) {}

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
      showAuthNotice('Firebase domain setup syncing. Please try again or test in Incognito window.', 'info', 7000);
      return;
    }
    if (error.code === 'auth/network-request-failed') {
      showAuthNotice('Network connection issue. Please check your internet connection.', 'error', 4500);
      return;
    }
    if (error.code === 'auth/operation-not-allowed') {
      showAuthNotice('Google Sign-in is currently disabled in Firebase Console.', 'error', 6000);
      return;
    }

    const msg = error.message || 'Unable to complete sign-in. Please try again.';
    showAuthNotice(msg, 'error', 5000);
  }

  // Initialize Firebase App & Auth with ZERO persistence (No auto-login)
  function initAuth() {
    if (typeof firebase === 'undefined') {
      setTimeout(initAuth, 200);
      return;
    }

    try {
      if (!firebase.apps.length) {
        firebase.initializeApp(FIREBASE_CONFIG);
      }
      authInstance = firebase.auth();

      // Enforce NONE persistence so credentials are never saved across page refreshes/visits
      if (authInstance.setPersistence && firebase.auth.Auth && firebase.auth.Auth.Persistence) {
        authInstance.setPersistence(firebase.auth.Auth.Persistence.NONE).catch(() => {});
      }

      // Check if returning from a mobile redirect sign-in
      if (authInstance.getRedirectResult) {
        authInstance.getRedirectResult()
          .then((result) => {
            if (result && result.user) {
              const u = extractUserData(result.user);
              currentUser = u;
              syncUserToFirestore(u);
              notifyAuthSubscribers(u);
              showAuthNotice(`Details retrieved from Google for ${u.displayName}!`, 'success');
            }
          })
          .catch((err) => {
            if (err && err.code) handleAuthError(err);
          });
      }
    } catch (err) {
      console.error('Firebase Auth initialization error:', err);
    }
  }

  function extractUserData(user) {
    return {
      uid: user.uid,
      displayName: user.displayName || 'Valued User',
      email: user.email || '',
      photoURL: user.photoURL || '',
      provider: 'google.com',
      lastLogin: new Date().toISOString()
    };
  }

  // Notify forms that user details were retrieved via Google
  function notifyAuthSubscribers(user) {
    window.dispatchEvent(new CustomEvent('odiins:auth-state-changed', {
      detail: { user: user }
    }));
  }

  // On-demand Google Sign In triggered strictly from in-form button
  async function signInWithGoogle(triggerBtn) {
    if (!authInstance) {
      showAuthNotice('Authentication service is initializing. Please try again in a moment.', 'info');
      return null;
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
        <span>Connecting to Google...</span>
      `;
    }

    const provider = new firebase.auth.GoogleAuthProvider();
    provider.addScope('email');
    provider.addScope('profile');
    provider.setCustomParameters({ prompt: 'select_account' });

    try {
      const result = await authInstance.signInWithPopup(provider);
      if (result && result.user) {
        const u = extractUserData(result.user);
        currentUser = u;
        syncUserToFirestore(u);
        notifyAuthSubscribers(u);
        showAuthNotice(`Details retrieved for ${u.displayName}!`, 'success');
        return u;
      }
      return null;
    } catch (error) {
      if (error.code === 'auth/popup-blocked' || error.code === 'auth/cancelled-popup-request') {
        try {
          showAuthNotice('Opening Google Sign-in...', 'info', 2000);
          await authInstance.signInWithRedirect(provider);
          return null;
        } catch (redirectErr) {
          handleAuthError(redirectErr);
        }
      } else {
        handleAuthError(error);
      }
      return null;
    } finally {
      if (triggerBtn && originalHtml) {
        triggerBtn.disabled = false;
        triggerBtn.classList.remove('loading');
        triggerBtn.innerHTML = originalHtml;
      }
    }
  }

  // Clear / Sign Out
  async function signOut() {
    currentUser = null;
    try {
      localStorage.removeItem('odiins_auth_user');
      sessionStorage.removeItem('odiins_auth_user');
      if (authInstance) {
        await authInstance.signOut();
      }
    } catch (e) {}
    notifyAuthSubscribers(null);
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
    } catch (err) {}
  }

  // Public API exposed on window.OdiinsAuth
  window.OdiinsAuth = {
    getUser: function () {
      return currentUser;
    },
    signIn: function(triggerBtn) {
      return signInWithGoogle(triggerBtn);
    },
    signOut: signOut,
    isLoggedIn: function () {
      return !!currentUser;
    }
  };

  // Run on DOM load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAuth);
  } else {
    initAuth();
  }
})();
