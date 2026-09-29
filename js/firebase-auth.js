(function () {
  'use strict';
  // Purge any legacy auth cache
  try {
    localStorage.removeItem('odiins_auth_user');
    sessionStorage.removeItem('odiins_auth_user');
  } catch (e) {}
})();
