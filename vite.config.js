import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: [
//                 'resources/css/app.css',
//                 // 'resources/sass/app.scss',
//                 'resources/js/app.js'],
//             refresh: true,
//         }),
//     ],
//     // server: {
//     //     host: '192.168.1.64',
//     //     port: 8080,
//     // },
// });
export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Archivos compartidos
                'resources/css/shared/shared.css',
                'resources/js/shared/utilities.js',

                // Admin
                'resources/css/admin.css',
                'resources/js/app.js',

                // Public
                'resources/css/public.css',
                'resources/js/app.js',

                // Agrega aquí tu archivo de eventos:
                'resources/js/entities/eventos/index.js',
            ],
            refresh: true,
        }),
    ],
});