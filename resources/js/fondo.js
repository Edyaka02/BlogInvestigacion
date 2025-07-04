export function initializeAnimatedBackground() {
    const background = document.getElementById('animated-background');

    // Verifica si el contenedor existe
    if (!background) return;

    // Función para crear partículas
    function createParticle() {
        const particle = document.createElement('div');
        particle.classList.add('particle');

        // Posición inicial aleatoria (horizontalmente)
        particle.style.left = `${Math.random() * 100}vw`;
        particle.style.top = `-10px`; // Comienza justo fuera de la parte superior de la pantalla

        // Tamaño aleatorio
        const size = Math.random() * 10 + 5; // Entre 5px y 15px
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;

        // Duración aleatoria para la animación
        particle.style.animationDuration = `${Math.random() * 5 + 5}s`; // Entre 5s y 10s

        // Agrega la partícula al contenedor
        background.appendChild(particle);

        // Elimina la partícula después de que termine la animación
        setTimeout(() => {
            particle.remove();
        }, 10000); // 10s (duración máxima de la animación)
    }

    // Genera partículas continuamente
    setInterval(createParticle, 200); // Crea una partícula cada 200ms
}