<style>
/* ─────────── Premium Auth Pages Layout ─────────── */
body {
    background: #f8f9fc !important;
}

/* Base auth page container */
.auth-viewport {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 1rem;
    box-sizing: border-box;
    position: relative;
    z-index: 10;
}

/* Card layout sizing & shadow */
.auth-mobile-card {
    width: 100%;
    max-width: 440px;
    background: #ffffff;
    border-radius: 2.5rem;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.04);
    position: relative;
    overflow: hidden;
    padding: 2.5rem 2rem;
    margin: auto 0;
}

/* Gradient strip at top of card */
.auth-card-gradient-bar {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, var(--color-primary) 0%, #8592ff 50%, #b8c5e8 100%);
}

/* Avatar circle ring matching design */
.auth-avatar-wrapper {
    width: 5.5rem;
    height: 5.5rem;
    border-radius: 50%;
    background: rgba(105, 108, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0.5rem auto 1.5rem;
    box-shadow: 0 0 0 8px rgba(105, 108, 255, 0.03);
}

.auth-avatar-inner {
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    background: rgba(105, 108, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-primary);
    overflow: hidden;
}

/* Dynamic active state tab button */
.auth-tab-pill {
    flex: 1;
    padding: 0.75rem 1rem;
    text-align: center;
    border-radius: 0.85rem;
    font-weight: 700;
    font-size: 0.875rem;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom placeholder / layout items */
.auth-input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.auth-input-label {
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    color: #94a3b8;
    padding-left: 0.25rem;
}

.auth-input-wrapper {
    position: relative;
}

.auth-input-field {
    width: 100%;
    height: 3.5rem;
    padding-left: 3.25rem;
    padding-right: 1.25rem;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 1.25rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #0f172a;
    outline: none;
    transition: all 0.2s ease;
    box-sizing: border-box;
}

.auth-input-field:focus {
    background: #ffffff;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 4px rgba(105, 108, 255, 0.08);
}

.auth-input-icon-left {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-primary);
    opacity: 0.85;
    pointer-events: none;
}

.auth-input-icon-right {
    position: absolute;
    right: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
}

/* Custom premium primary button */
.auth-submit-btn {
    width: 100%;
    height: 3.5rem;
    border-radius: 1.25rem;
    background: var(--color-primary);
    color: #ffffff;
    font-weight: 800;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border: none;
    cursor: pointer;
    box-shadow: 0 10px 20px -5px rgba(105, 108, 255, 0.3);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.auth-submit-btn:hover {
    opacity: 0.92;
    transform: translateY(-1px);
    box-shadow: 0 12px 24px -5px rgba(105, 108, 255, 0.4);
}

.auth-submit-btn:active {
    transform: scale(0.98);
}

.auth-submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Bottom Nav bar container */
.auth-bottom-nav {
    width: 100%;
    max-width: 440px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    border-top: 1px solid #f1f5f9;
    border-radius: 1.5rem 1.5rem 0 0;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 0.75rem 1rem;
    box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.02);
    margin-top: 2rem;
    box-sizing: border-box;
}

.auth-bottom-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    color: #94a3b8;
    text-decoration: none;
    font-size: 0.65rem;
    font-weight: 700;
    transition: all 0.2s ease;
}

.auth-bottom-nav-item:hover {
    color: #64748b;
}

.auth-bottom-nav-item.active {
    color: var(--color-primary);
}

/* Floating Middle Book action button */
.auth-bottom-nav-book-btn {
    width: 3.25rem;
    height: 3.25rem;
    border-radius: 50%;
    background: var(--color-primary);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 16px rgba(105, 108, 255, 0.35);
    transform: translateY(-0.75rem);
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.auth-bottom-nav-book-btn:hover {
    transform: translateY(-0.85rem) scale(1.05);
    box-shadow: 0 8px 20px rgba(105, 108, 255, 0.45);
}

/* Dark mode basic overrides */
.dark .auth-mobile-card {
    background: #0f172a;
    border-color: #1e293b;
}
.dark .auth-bottom-nav {
    background: rgba(15, 23, 42, 0.85);
    border-color: #1e293b;
}
.dark .auth-input-field {
    background: #1e293b;
    border-color: #334155;
    color: #ffffff;
}
.dark .auth-input-field:focus {
    background: #0f172a;
}
</style>
<?php /**PATH C:\Users\bruce\OneDrive\Desktop\Projects\RocketDataHub\resources\views/auth/_auth_styles.blade.php ENDPATH**/ ?>