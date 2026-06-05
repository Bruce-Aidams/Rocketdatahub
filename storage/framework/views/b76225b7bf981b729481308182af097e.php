<?php ($hideNav = true); ?>


<?php $__env->startSection('title', 'Recover Password'); ?>

<?php $__env->startSection('content'); ?>

<div class="auth-page" x-data="{
        loading: false,
        submit() { this.loading = true; this.$refs.forgotForm.submit(); }
    }">

    <div class="auth-bg">
        <div class="auth-bg-orb auth-bg-orb-1"></div>
        <div class="auth-bg-orb auth-bg-orb-2"></div>
    </div>

    <div class="auth-card">

        
        <div class="auth-left">
            <a href="<?php echo e(url('/')); ?>" class="auth-logo-link">
                <img src="<?php echo e(asset('logo.png')); ?>" alt="Logo" class="auth-logo-img">
                <span class="auth-logo-name"><?php echo e(config('app.name')); ?></span>
            </a>
            <div class="auth-brand-copy">
                <h1 class="auth-heading">Forgot Your<br>Password? 🔒</h1>
                <p class="auth-subtext">
                    Enter your email and we'll send you a reset link.<br>
                    Remember it?
                    <a href="<?php echo e(route('login')); ?>" class="auth-link-blue">Sign in here!</a>
                </p>
            </div>
        </div>

        
        <div class="auth-character">
            <img src="<?php echo e(asset('images/auth-character.png')); ?>"
                 alt="Floating character"
                 class="auth-char-img"
                 onerror="this.style.display='none'">
        </div>

        
        <div class="auth-right">

            <?php if(session('status')): ?>
                <div class="auth-alert auth-alert-success"><?php echo e(session('status')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="auth-alert auth-alert-error"><?php echo e($errors->first()); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.email')); ?>" x-ref="forgotForm" @submit.prevent="submit" class="auth-form">
                <?php echo csrf_field(); ?>

                
                <div class="auth-field">
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>"
                           required autofocus placeholder="Enter Email Address"
                           class="auth-input">
                    <span class="auth-input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                </div>

                
                <button type="submit" :disabled="loading" class="auth-btn-primary">
                    <template x-if="!loading"><span>Send Reset Link</span></template>
                    <template x-if="loading">
                        <span class="auth-loading">
                            Sending
                            <span class="auth-dots"><span>.</span><span>.</span><span>.</span></span>
                        </span>
                    </template>
                </button>

                
                <div style="text-align:center; margin-top:.5rem;">
                    <a href="<?php echo e(route('login')); ?>" class="auth-back-link">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        Back to Sign In
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>

<?php echo $__env->make('auth._auth_styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
.auth-back-link {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .82rem;
    color: #6366f1;
    font-weight: 600;
    text-decoration: none;
}
.auth-back-link:hover { text-decoration: underline; }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bruce\OneDrive\Desktop\Projects\RocketDataHub\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>