// === EJECUCIÓN INMEDIATA (como tu script inline) ===
console.log('📄 ShowPageManager.js cargando...');

// Barra de progreso profesional
window.addEventListener('scroll', function() {
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

// Animaciones profesionales
const observerOptions = {
    threshold: 0.15,
    rootMargin: '0px 0px -30px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, observerOptions);

// Smooth scroll setup
function setupSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Analíticas simuladas
function trackDownload() {
    console.log('📥 Descarga registrada:', new Date().toISOString());
    
    // Aquí puedes agregar lógica personalizada desde la configuración
    if (window.showPageManagerConfig && window.showPageManagerConfig.onDownload) {
        window.showPageManagerConfig.onDownload('unknown');
    }
}

function trackShare(platform) {
    console.log('📤 Compartido en:', platform, new Date().toISOString());
    
    // Aquí puedes agregar lógica personalizada desde la configuración
    if (window.showPageManagerConfig && window.showPageManagerConfig.onShare) {
        window.showPageManagerConfig.onShare(platform);
    }
}

// Setup de tracking
function setupTracking() {
    // Tracking de descargas
    document.querySelectorAll('[download]').forEach(btn => {
        btn.addEventListener('click', trackDownload);
    });

    // Tracking de compartir
    document.querySelectorAll('.share-executive').forEach(btn => {
        btn.addEventListener('click', function() {
            const platform = this.querySelector('i').className.includes('twitter') ?
                'Twitter' :
                this.querySelector('i').className.includes('facebook') ? 'Facebook' :
                this.querySelector('i').className.includes('linkedin') ? 'LinkedIn' :
                'Email';
            trackShare(platform);
        });
    });
}

// Inicialización principal
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 ShowPageManager ejecutándose...');
    
    // Configurar animaciones
    document.querySelectorAll('.fade-in-professional, .slide-in-professional').forEach(element => {
        observer.observe(element);
    });
    
    // Configurar smooth scroll
    setupSmoothScroll();
    
    // Configurar tracking
    setupTracking();
    
    console.log('✅ ShowPageManager completamente inicializado');
});

// === CLASE OPCIONAL PARA USO AVANZADO ===
class ShowPageManager {
    constructor(options = {}) {
        this.options = {
            debug: options.debug || false,
            enableProgressBar: options.enableProgressBar !== false,
            enableAnimations: options.enableAnimations !== false,
            enableTracking: options.enableTracking !== false,
            onDownload: options.onDownload || trackDownload,
            onShare: options.onShare || trackShare,
            ...options
        };
        
        if (this.options.debug) {
            console.log('🎯 ShowPageManager clase inicializada con opciones:', this.options);
        }
    }
    
    // Métodos públicos
    updateProgress() {
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
            return progress;
        }
        return 0;
    }
    
    triggerAnimations() {
        document.querySelectorAll('.fade-in-professional, .slide-in-professional').forEach(el => {
            el.classList.add('visible');
        });
    }
}

// Hacer disponible globalmente
window.ShowPageManager = ShowPageManager;

console.log('📄 ShowPageManager.js completamente cargado');