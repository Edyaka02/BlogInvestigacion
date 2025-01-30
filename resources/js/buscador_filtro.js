export function toggleOptions(event, id) {
    event.stopPropagation();
    const element = document.getElementById(id);
    if (element.style.display === 'none' || element.style.display === '') {
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
}

export function initializeFilters() {

        const anioRadios = document.querySelectorAll('input[name="anio"]');
        const intervaloAniosCheckbox = document.getElementById('intervaloAniosCheckbox');
        const intervaloAnios = document.getElementById('intervaloAnios');

        anioRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'intervalo') {
                    intervaloAnios.style.display = 'block';
                } else {
                    intervaloAnios.style.display = 'none';
                    intervaloAnios.querySelectorAll('input').forEach(input => input.value = '');
                }
            });
        });

        if (intervaloAniosCheckbox.checked) {
            intervaloAnios.style.display = 'block';
        }
}