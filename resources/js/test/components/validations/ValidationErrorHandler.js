/**
 * Manejo de errores de validación en formularios
 */
export class ValidationErrorHandler {
    static show(errors, options = {}) {
        // ✅ VERIFICAR: Solo mostrar si realmente hay errores del servidor
        if (!errors || Object.keys(errors).length === 0) {
            console.log('No hay errores para mostrar');
            return 0;
        }

        // Limpiar errores anteriores
        this.clear();

        // Mostrar nuevos errores solo si vienen del servidor
        let errorCount = 0;
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                errorCount++;
            }
        });

        return errorCount;
    }

    static clear() {
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });

        // ✅ Limpiar también mensajes de texto rojo
        document.querySelectorAll('.text-danger').forEach(el => {
            if (el.textContent.includes('field is required')) {
                el.textContent = '';
                el.style.display = 'none';
            }
        });
        console.log('✅ Validaciones limpiadas con clear de ValidationErrorHandler');
    }
}