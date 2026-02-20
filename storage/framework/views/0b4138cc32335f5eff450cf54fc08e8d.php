<?php
    $icons = [
        'MTN' => ['color' => 'bg-yellow-400', 'text' => 'text-black'],
        'Telecel' => ['color' => 'bg-red-600', 'text' => 'text-white'],
        'AT' => ['color' => 'bg-blue-600', 'text' => 'text-white'],
        'Wifi' => ['icon' => 'wifi'],
        'Signal' => ['icon' => 'signal'],
        'Data' => ['icon' => 'data']
    ];
    
    // Strict limit for performance
    $bubbleCount = 12; 
?>

<!-- Container: z-0 to sit above body background, but pointer-events-none to let clicks through -->
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0" id="bubble-container">
    <?php for($i = 0; $i < $bubbleCount; $i++): ?>
        <?php
            $type = array_rand($icons);
            $config = $icons[$type];
            // JS will handle positioning, but we set initial random styles for the inner visual
            $size = rand(40, 90); 
            $opacity = rand(30, 50) / 100;
        ?>
        
        <!-- Bubble Element: Positioned entirely by JS -->
        <div 
            class="bubble-entity absolute will-change-transform top-0 left-0"
            data-size="<?php echo e($size); ?>"
            style="
                width: <?php echo e($size); ?>px; 
                height: <?php echo e($size); ?>px; 
                opacity: <?php echo e($opacity); ?>;
                transform: translate3d(-100px, -100px, 0); /* Init off-screen */
            "
        >
            <!-- Inner Visuals: Purely decorative, no layout impact -->
            <div class="w-full h-full rounded-full backdrop-blur-[0px] border-[1px] border-black/5 dark:border-white/10 flex items-center justify-center shadow-[inset_0_-10px_30px_rgba(0,0,0,0.1),_inset_0_10px_30px_rgba(255,255,255,0.2),_0_20px_40px_rgba(0,0,0,0.05)] dark:shadow-[inset_0_-10px_30px_rgba(255,255,255,0.4),_inset_0_10px_30px_rgba(255,255,255,0.5),_0_20px_40px_rgba(255,255,255,0.2)] overflow-hidden relative group">
                
                <!-- Rainbow/Iridescent Overlay -->
                <!-- Light mode: Stronger color burn. Dark mode: Soft overlay -->
                <div class="absolute inset-0 bg-gradient-to-br from-pink-500/20 via-purple-500/20 to-blue-500/20 dark:from-pink-500/10 dark:via-purple-500/10 dark:to-blue-500/10 mix-blend-color-burn dark:mix-blend-overlay pointer-events-none rounded-full"></div>
                
                <!-- Glossy highlight -->
                <div class="absolute top-[8%] left-[12%] w-[35%] h-[20%] bg-white/60 dark:bg-white/40 blur-[2px] rounded-[100%] rotate-[-25deg]"></div>
                
                <!-- Package/Cargo Container -->
                <div class="w-1/3 h-1/3 bg-white/30 dark:bg-white/10 backdrop-blur-sm rounded-full border border-white/40 dark:border-white/30 shadow-xl flex items-center justify-center relative z-10 p-1 opacity-60 dark:opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent pointer-events-none rounded-full"></div>
                    
                    <?php if(isset($config['text'])): ?>
                        <div class="w-full h-full rounded-full <?php echo e($config['color']); ?> flex items-center justify-center shadow-inner opacity-60">
                            <span class="font-black text-[6px] uppercase <?php echo e($config['text']); ?>"><?php echo e($type); ?></span>
                        </div>
                    <?php elseif($config['icon'] === 'wifi'): ?>
                        <svg class="w-2/3 h-2/3 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                        </svg>
                    <?php elseif($config['icon'] === 'signal'): ?>
                        <svg class="w-2/3 h-2/3 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    <?php else: ?>
                        <svg class="w-2/3 h-2/3 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 21a10.003 10.003 0 008.384-4.562l.054.091m-9.936-2.043a6.733 6.733 0 01-.01-1.406m6.111 0a6.733 6.733 0 01-.01 1.406M12 11a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endfor; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('bubble-container');
        const elements = document.querySelectorAll('.bubble-entity');
        
        // Configuration
        let width = window.innerWidth;
        let height = window.innerHeight;
        let mouseX = -1000;
        let mouseY = -1000;
        
        // Physics Objects
        const bubbles = Array.from(elements).map(el => {
            const size = parseFloat(el.getAttribute('data-size'));
            return {
                el: el,
                x: Math.random() * width,
                y: height + Math.random() * 500, // Start below screen scattered
                size: size,
                speed: 0.5 + Math.random() * 1.5, // Pixels per frame rise
                swayAmplitude: 20 + Math.random() * 30,
                swayFrequency: 0.002 + Math.random() * 0.003,
                swayOffset: Math.random() * Math.PI * 2,
                vx: 0, // Interaction velocity X
                vy: 0  // Interaction velocity Y
            };
        });

        // Resize Handler
        window.addEventListener('resize', () => {
            width = window.innerWidth;
            height = window.innerHeight;
        });

        // Mouse Tracker
        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        // Main Animation Loop
        function animate() {
            for (let i = 0; i < bubbles.length; i++) {
                const b = bubbles[i];
                
                // 1. Vertical Rise
                b.y -= b.speed;
                
                // Reset to bottom if gone off top
                if (b.y < -b.size) {
                    b.y = height + b.size;
                    b.x = Math.random() * width;
                    // Reset velocities
                    b.vx = 0;
                    b.vy = 0;
                }

                // 2. Horizontal Sway (Sine Wave)
                // We calculate a sway offset based on time (using frame count or just incrementing)
                // Using Date.now() for smooth time-based animation
                const time = Date.now();
                const sway = Math.sin(time * b.swayFrequency + b.swayOffset) * b.swayAmplitude;

                // 3. Mouse Interaction (Scatter)
                // Calculate distance to mouse
                // Current center position
                const centerX = b.x + sway + b.size / 2;
                const centerY = b.y + b.size / 2;
                
                const dx = mouseX - centerX;
                const dy = mouseY - centerY;
                const distSq = dx * dx + dy * dy;
                const radius = 200; // Interaction radius
                const radiusSq = radius * radius;

                if (distSq < radiusSq) {
                    const dist = Math.sqrt(distSq);
                    const force = (radius - dist) / radius; // 0 to 1
                    
                    // Push away
                    const angle = Math.atan2(dy, dx);
                    const pushForce = 2 * force; // Strength multiplier
                    
                    b.vx -= Math.cos(angle) * pushForce;
                    b.vy -= Math.sin(angle) * pushForce;
                }

                // Friction to slow down scatter velocity
                b.vx *= 0.95;
                b.vy *= 0.95;

                // 4. Final Position
                // We add sway and interaction velocity to the base X/Y
                const finalX = b.x + sway + b.vx;
                const finalY = b.y + b.vy;

                // UPDATE DOM
                b.el.style.transform = `translate3d(${finalX}px, ${finalY}px, 0)`;
            }

            requestAnimationFrame(animate);
        }

        // kick off
        animate();
    });
</script><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views/components/floating-bubbles.blade.php ENDPATH**/ ?>