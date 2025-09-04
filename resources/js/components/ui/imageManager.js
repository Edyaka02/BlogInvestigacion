/**
 * Resuelve la URL de una imagen con fallback
 * @param {string|null} imagePath - Ruta de la imagen desde la base de datos
 * @param {string} defaultImage - Imagen por defecto
 * @returns {string} URL completa de la imagen
 */
export function resolveImageUrl(imagePath, defaultImage = '/storage/images/default-article.jpg') {
    // Si no hay imagen, usar default
    if (!imagePath) {
        return defaultImage;
    }

    // Si ya es URL completa (http/https)
    if (imagePath.startsWith('http')) {
        return imagePath;
    }

    // Si ya tiene barra inicial
    if (imagePath.startsWith('/')) {
        return imagePath;
    }

    // Si es ruta relativa, agregar storage/
    return `/storage/${imagePath}`;
}