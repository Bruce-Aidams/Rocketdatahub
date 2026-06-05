
<div class="site-bg" aria-hidden="true">
    <div class="site-bg-orb site-bg-orb-1"></div>
    <div class="site-bg-orb site-bg-orb-2"></div>
</div>

<style>
.site-bg {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, #fdf8f4 0%, #f0f0ff 40%, #eaf0ff 70%, #f8f4ff 100%);
    z-index: -10;
    pointer-events: none;
}
.site-bg-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    pointer-events: none;
}
.site-bg-orb-1 {
    width: 640px; height: 640px;
    top: -180px; left: -180px;
    background: radial-gradient(circle, #fde68a 0%, #fbbf24 50%, transparent 70%);
    opacity: .38;
}
.site-bg-orb-2 {
    width: 560px; height: 560px;
    bottom: -140px; right: -140px;
    background: radial-gradient(circle, #c4b5fd 0%, #818cf8 50%, transparent 70%);
    opacity: .38;
}
</style>
<?php /**PATH C:\Users\bruce\OneDrive\Desktop\Projects\RocketDataHub\resources\views/partials/_site_bg.blade.php ENDPATH**/ ?>