<canvas id="network-canvas"
    class="absolute inset-0 w-full h-full -z-10 opacity-30 dark:opacity-20 pointer-events-none"></canvas>

<script>
    document.addEventListener('alpine:init', () => {
        const canvas = document.getElementById('network-canvas');
        const ctx = canvas.getContext('2d');
        let width, height;
        let particles = [];

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        }

        window.addEventListener('resize', resize);
        resize();

        class Particle {
            constructor() {
                this.x = Math.random() * width;
                this.y = Math.random() * height;
                this.vx = (Math.random() - 0.5) * 0.5;
                this.vy = (Math.random() - 0.5) * 0.5;
                this.size = Math.random() * 2 + 1;
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                if (this.x < 0 || this.x > width) this.vx *= -1;
                if (this.y < 0 || this.y > height) this.vy *= -1;
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.5)' : 'rgba(99, 102, 241, 0.5)'; // Indigo-500 equivalent
                ctx.fill();
            }
        }

        function initParticles() {
            particles = [];
            const particleCount = Math.min(window.innerWidth / 10, 100); // Responsive count
            for (let i = 0; i < particleCount; i++) {
                particles.push(new Particle());
            }
        }

        initParticles();
        window.addEventListener('resize', initParticles);

        function animate() {
            ctx.clearRect(0, 0, width, height);

            // Draw connections
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < 150) {
                        ctx.beginPath();
                        ctx.strokeStyle = document.documentElement.classList.contains('dark') ?
                            `rgba(255, 255, 255, ${0.1 - distance / 1500})` :
                            `rgba(99, 102, 241, ${0.15 - distance / 1000})`;
                        ctx.lineWidth = 1;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }

            // Update and draw particles
            particles.forEach(p => {
                p.update();
                p.draw();
            });

            requestAnimationFrame(animate);
        }

        animate();
    });
</script><?php /**PATH C:\Users\Bruce\Desktop\Projects\cloudtech\resources\views\components\hero-network-animation.blade.php ENDPATH**/ ?>