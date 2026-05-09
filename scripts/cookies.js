/**
 * COLAB — Sistema de gestión de cookies
 * v1.0 | Cumple AEPD/GDPR
 *
 * Para activar Google Analytics cuando lo tengas:
 * 1. Sustituye 'G-XXXXXXXXXX' por tu ID real.
 * 2. Descomenta los bloques marcados con [GA4].
 */

const COOKIE_NAME = 'colab_cookie_consent';
const COOKIE_DAYS = 365;
const GA_ID       = 'G-XXXXXXXXXX'; // [GA4] Cambia esto por tu ID

// ── Utilidades ────────────────────────────────
function setCookie(name, value, days) {
  const date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = `${name}=${encodeURIComponent(JSON.stringify(value))};expires=${date.toUTCString()};path=/;SameSite=Lax`;
}

function getCookie(name) {
  const cookies = document.cookie.split(';');
  for (let c of cookies) {
    const [key, val] = c.trim().split('=');
    if (key === name) {
      try { return JSON.parse(decodeURIComponent(val)); }
      catch { return null; }
    }
  }
  return null;
}

// ── Google Analytics (desactivado hasta tener ID) ──
function loadGoogleAnalytics() {
  // [GA4] Descomenta este bloque cuando tengas tu ID:
  /*
  if (document.querySelector(`script[src*="${GA_ID}"]`)) return;
  window.dataLayer = window.dataLayer || [];
  function gtag() { dataLayer.push(arguments); }
  gtag('consent', 'update', { analytics_storage: 'granted' });
  const script = document.createElement('script');
  script.async = true;
  script.src = `https://www.googletagmanager.com/gtag/js?id=${GA_ID}`;
  document.head.appendChild(script);
  script.onload = () => {
    gtag('js', new Date());
    gtag('config', GA_ID, { anonymize_ip: true });
  };
  */
}

// [GA4] Bloqueo por defecto — descomenta cuando actives GA4:
/*
window.dataLayer = window.dataLayer || [];
function gtag() { dataLayer.push(arguments); }
gtag('consent', 'default', { analytics_storage: 'denied', wait_for_update: 500 });
*/

// ── Aplicar preferencias ───────────────────────
function applyConsent(prefs) {
  if (prefs.analiticas) loadGoogleAnalytics();
}

// ── Guardar y cerrar ───────────────────────────
function saveConsent(prefs) {
  setCookie(COOKIE_NAME, prefs, COOKIE_DAYS);
  applyConsent(prefs);
  hideBanner();
  hideModal();
}

// ── Acciones de los botones ────────────────────
function acceptAll()  { saveConsent({ tecnicas: true, analiticas: true }); }
function rejectAll()  { saveConsent({ tecnicas: true, analiticas: false }); }
function saveCustom() {
  const analiticas = document.getElementById('toggle-analiticas').checked;
  saveConsent({ tecnicas: true, analiticas });
}

// ── Banner ─────────────────────────────────────
function showBanner() {
  const el = document.getElementById('cookie-banner');
  if (el) { el.removeAttribute('hidden'); el.setAttribute('aria-hidden', 'false'); }
}
function hideBanner() {
  const el = document.getElementById('cookie-banner');
  if (el) { el.setAttribute('hidden', ''); el.setAttribute('aria-hidden', 'true'); }
}

// ── Modal ──────────────────────────────────────
function showModal() {
  const modal   = document.getElementById('cookie-modal');
  const overlay = document.getElementById('cookie-overlay');
  const prefs   = getCookie(COOKIE_NAME);
  const toggle  = document.getElementById('toggle-analiticas');
  if (toggle && prefs) toggle.checked = prefs.analiticas || false;
  if (modal)   { modal.removeAttribute('hidden');   modal.setAttribute('aria-hidden', 'false'); }
  if (overlay) { overlay.removeAttribute('hidden'); overlay.setAttribute('aria-hidden', 'false'); }
  document.body.style.overflow = 'hidden';
}
function hideModal() {
  const modal   = document.getElementById('cookie-modal');
  const overlay = document.getElementById('cookie-overlay');
  if (modal)   { modal.setAttribute('hidden', '');   modal.setAttribute('aria-hidden', 'true'); }
  if (overlay) { overlay.setAttribute('hidden', ''); overlay.setAttribute('aria-hidden', 'true'); }
  document.body.style.overflow = '';
}

// ── Abrir desde footer ─────────────────────────
function openCookieSettings() {
  showModal();
  hideBanner();
}

// ── Init ───────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const prefs = getCookie(COOKIE_NAME);
  if (!prefs) {
    showBanner();
  } else {
    applyConsent(prefs);
  }

  const overlay = document.getElementById('cookie-overlay');
  if (overlay) overlay.addEventListener('click', hideModal);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') hideModal();
  });
});