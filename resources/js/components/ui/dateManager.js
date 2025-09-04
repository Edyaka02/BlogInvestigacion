/**
 * UTILIDAD: Formatear fecha como en Blade
 */
export function formatearFecha(fechaString) {
    if (!fechaString) return 'Fecha no disponible';

    try {
        const fecha = new Date(fechaString);

        // ✅ DÍAS: En español
        const diasSemana = [
            'domingo', 'lunes', 'martes', 'miércoles',
            'jueves', 'viernes', 'sábado'
        ];

        // ✅ MESES: En español
        const meses = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        ];

        const diaSemana = diasSemana[fecha.getDay()];
        const dia = fecha.getDate();
        const mes = meses[fecha.getMonth()];
        const año = fecha.getFullYear();

        // ✅ FORMATO: "lunes, 15 de marzo de 2024"
        return `${diaSemana}, ${dia} de ${mes} de ${año}`;

    } catch (error) {
        console.error('Error al formatear fecha:', error);
        return fechaString; // Devolver original si hay error
    }
}