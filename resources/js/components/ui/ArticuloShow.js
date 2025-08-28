class ArticuloShow {
    constructor() {
        this.init();
    }

    init() {
        this.setupProgressBar();
        this.setupAnimations();
        this.setupSmoothScroll();
        this.setupTracking();
    }

    // Barra de progreso profesional
    setupProgressBar() {
        window.addEventListener('scroll', () => {
            const article = document.querySelector('article');
            const progressBar = document.getElementById('progressBar');

            if (article && progressBar) {
                const articleTop = article.offsetTop;
                const articleHeight = article.scrollHeight;
                const windowHeight = window.innerHeight;
                const scrollTop = window.scrollY;

                const progress = Math.min(100, Math.max(0,
                    ((scrollTop - articleTop + windowHeight) / articleHeight) * 100
                ));

                progressBar.style.width = progress + '%';
            }
        });
    }

    // Animaciones profesionales
    setupAnimations() {
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -30px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observar elementos cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.fade-in-professional, .slide-in-professional').forEach(element => {
                observer.observe(element);
            });
        });
    }

    // Smooth scroll
    setupSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // Sistema de tracking
    setupTracking() {
        // Tracking de descargas
        const trackDownload = () => {
            console.log('Descarga registrada:', new Date().toISOString());
            // Aquí puedes agregar tu lógica de analytics
        };

        const trackShare = (platform) => {
            console.log('Compartido en:', platform, new Date().toISOString());
            // Aquí puedes agregar tu lógica de analytics
        };

        // Asignar eventos cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', () => {
            // Tracking de descargas
            document.querySelectorAll('[download]').forEach(btn => {
                btn.addEventListener('click', trackDownload);
            });

            // Tracking de compartir
            document.querySelectorAll('.share-executive').forEach(btn => {
                btn.addEventListener('click', function () {
                    const platform = this.querySelector('i').className.includes('twitter') ? 'Twitter' :
                        this.querySelector('i').className.includes('facebook') ? 'Facebook' :
                            this.querySelector('i').className.includes('linkedin') ? 'LinkedIn' : 'Email';
                    trackShare(platform);
                });
            });
        });
    }
}

// Inicializar cuando el documento esté listo
document.addEventListener('DOMContentLoaded', () => {
    new ArticuloShow();
});