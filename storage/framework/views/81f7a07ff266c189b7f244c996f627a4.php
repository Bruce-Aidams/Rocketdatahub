<style>
/* ─────────── Reset for full-screen auth ─────────── */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

/* ─────────── Auth page shell — 100% × 100vh ─────────── */
.auth-page {
    position: relative;
    width: 100%;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
    box-sizing: border-box;
    padding: 1.5rem;
}

/* Gradient background — fixed to fill screen */
.auth-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #fdf8f4 0%, #f0f0ff 40%, #eaf0ff 70%, #f8f4ff 100%);
    z-index: 0;
}
.auth-bg-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: .5;
}
.auth-bg-orb-1 {
    width: 520px; height: 520px;
    top: -120px; left: -120px;
    background: radial-gradient(circle, #fde68a 0%, #fbbf24 50%, transparent 70%);
}
.auth-bg-orb-2 {
    width: 460px; height: 460px;
    bottom: -100px; right: -80px;
    background: radial-gradient(circle, #c4b5fd 0%, #818cf8 50%, transparent 70%);
}

/* ─────────── Card ─────────── */
.auth-card {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 1080px;
    /* Fit exactly within viewport height */
    max-height: calc(100vh - 3rem);
    background: rgba(255,255,255,0.72);
    backdrop-filter: blur(24px) saturate(1.5);
    border-radius: 28px;
    box-shadow: 0 8px 40px rgba(100,100,180,.14), 0 1.5px 0 rgba(255,255,255,.8) inset;
    border: 1px solid rgba(255,255,255,0.6);
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    align-items: center;
    overflow: hidden; /* keep rounded corners clean */
}

/* ─────────── Left ─────────── */
.auth-left {
    padding: 2.5rem 2.25rem;
    display: flex;
    flex-direction: column;
    gap: 1.75rem;
    height: 100%;
    justify-content: center;
    box-sizing: border-box;
}
.auth-logo-link {
    display: flex;
    align-items: center;
    gap: .75rem;
    text-decoration: none;
}
.auth-logo-img {
    width: 44px; height: 44px;
    object-fit: contain;
    filter: drop-shadow(0 2px 8px rgba(79,140,255,.3));
}
.auth-logo-name {
    font-weight: 800;
    font-size: 1rem;
    color: #1e293b;
    letter-spacing: -.5px;
}
.auth-heading {
    font-size: 1.85rem;
    font-weight: 800;
    color: #111827;
    line-height: 1.2;
    letter-spacing: -.5px;
    margin-bottom: .75rem;
}
.auth-subtext {
    font-size: .875rem;
    color: #6b7280;
    line-height: 1.7;
}
.auth-link-blue {
    color: #4f6ef7;
    font-weight: 700;
    text-decoration: none;
}
.auth-link-blue:hover { text-decoration: underline; }

/* ─────────── Center character ─────────── */
.auth-character {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem 0;
    height: 100%;
    align-self: stretch;
}
.auth-char-img {
    width: min(260px, 90%);
    height: auto;
    object-fit: contain;
    animation: auth-float 3.5s ease-in-out infinite;
    filter: drop-shadow(0 16px 32px rgba(0,0,0,.12));
}
@keyframes auth-float {
    0%, 100% { transform: translateY(0px) rotate(-2deg); }
    50%       { transform: translateY(-14px) rotate(2deg); }
}

/* ─────────── Right form — scrollable if needed ─────────── */
.auth-right {
    padding: 2.5rem 2.25rem;
    display: flex;
    flex-direction: column;
    height: 100%;
    justify-content: center;
    overflow-y: auto;   /* only the form column scrolls if needed */
    box-sizing: border-box;
}
.auth-form {
    display: flex;
    flex-direction: column;
    gap: .8rem;
}

/* Alerts */
.auth-alert {
    padding: .7rem 1rem;
    border-radius: 10px;
    font-size: .78rem;
    font-weight: 600;
    margin-bottom: .4rem;
}
.auth-alert-error  { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
.auth-alert-success{ background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

/* Inputs */
.auth-field { position: relative; }
.auth-input {
    width: 100%;
    height: 46px;
    padding: 0 2.8rem 0 1.1rem;
    background: #f0f4ff;
    border: 1.5px solid transparent;
    border-radius: 12px;
    font-size: .875rem;
    color: #1e293b;
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
    font-family: inherit;
    box-sizing: border-box;
}
.auth-input::placeholder { color: #94a3b8; }
.auth-input:focus {
    background: #fff;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,.12);
}
.auth-input-icon {
    position: absolute;
    right: .9rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    display: flex;
    align-items: center;
    pointer-events: none;
}
.auth-eye-btn {
    background: none;
    border: none;
    cursor: pointer;
    pointer-events: all;
    padding: 0;
    color: #94a3b8;
}
.auth-eye-btn:hover { color: #6366f1; }

/* Recover link */
.auth-recover { text-align: right; margin-top: -.3rem; }
.auth-recover-link { font-size: .78rem; color: #94a3b8; text-decoration: none; }
.auth-recover-link:hover { color: #6366f1; }

/* Primary button */
.auth-btn-primary {
    width: 100%;
    height: 46px;
    background: #4f6ef7;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: .93rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 4px 18px rgba(79,110,247,.38);
    font-family: inherit;
    margin-top: .2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.auth-btn-primary:hover { background: #3b55e5; box-shadow: 0 6px 24px rgba(79,110,247,.5); }
.auth-btn-primary:active { transform: scale(.98); }
.auth-btn-primary:disabled { opacity: .7; cursor: not-allowed; }

/* Loading dots */
.auth-loading { display: flex; align-items: center; gap: .25rem; }
.auth-dots span { animation: auth-dot-blink .9s infinite; opacity: 0; }
.auth-dots span:nth-child(2) { animation-delay: .15s; }
.auth-dots span:nth-child(3) { animation-delay: .3s; }
@keyframes auth-dot-blink { 0%,80%,100%{opacity:0} 40%{opacity:1} }

/* Divider */
.auth-divider {
    display: flex;
    align-items: center;
    gap: .75rem;
    margin: .2rem 0;
}
.auth-divider-line { flex: 1; height: 1px; background: #e2e8f0; }
.auth-divider-text { font-size: .72rem; color: #94a3b8; white-space: nowrap; font-weight: 500; }

/* Social */
.auth-social-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .85rem;
}
.auth-social-btn {
    width: 50px; height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    transition: box-shadow .2s, transform .15s, border-color .2s;
    text-decoration: none;
    color: #374151;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}
.auth-social-btn:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,.1);
    transform: translateY(-2px);
    border-color: #c7d2fe;
}
.auth-social-apple { color: #1e293b; }

/* ─────────── Responsive — tablet ─────────── */
@media (max-width: 900px) {
    html, body { overflow: auto; }          /* allow scroll on mobile */
    .auth-page {
        height: auto;
        min-height: 100vh;
        align-items: flex-start;
        padding: 1.5rem 1rem;
    }
    .auth-card {
        grid-template-columns: 1fr;
        max-width: 440px;
        max-height: none;
        overflow: visible;
    }
    .auth-character { order: -1; height: auto; padding: 1.5rem 1rem .5rem; }
    .auth-char-img { width: 150px; }
    .auth-left {
        height: auto;
        padding: 1.25rem 2rem;
        align-items: center;
        text-align: center;
        gap: .75rem;
    }
    .auth-right { height: auto; padding: 1rem 2rem 2.5rem; overflow-y: visible; }
    .auth-heading { font-size: 1.5rem; }
}
@media (max-width: 480px) {
    .auth-page { padding: 1rem .75rem; }
    .auth-card { border-radius: 20px; }
    .auth-left  { padding: 1rem 1.5rem; }
    .auth-right { padding: .75rem 1.5rem 2rem; }
    .auth-heading { font-size: 1.35rem; }
    .auth-character { display: none; }   /* hide animated image on small mobile */
}
</style>
<?php /**PATH C:\Users\bruce\OneDrive\Desktop\Projects\RocketDataHub\resources\views/auth/_auth_styles.blade.php ENDPATH**/ ?>