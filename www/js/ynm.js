(function () {
  'use strict';

  if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style');
    msViewportStyle.appendChild(
      document.createTextNode('@-ms-viewport{width:auto!important}')
    );
    document.querySelector('head').appendChild(msViewportStyle);
  }
})();

function clearCookies() {
  // A cookie-k törlése az összes domain-en
  document.cookie.split(";").forEach(function(c) {
    document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date(0).toUTCString() + ";path=/");
  });
}

function logout() {
  // Töröljük a cookie-kat
  clearCookies();

  // Töröljük a sessionStorage és localStorage adatokat
  sessionStorage.clear();
  localStorage.clear();

  // Átirányítjuk a felhasználót a logout oldalra vagy a főoldalra
  window.location.href = "/index";  // Cseréld le a megfelelő logout URL-re
}

document.addEventListener('DOMContentLoaded', function() {
  var currentYear = new Date().getFullYear();
  document.querySelectorAll('#currentYear').forEach(function(el) {
    el.textContent = currentYear;
  });
});
