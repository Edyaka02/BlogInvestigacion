import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/navbar.css',
                'resources/css/footer.css',
                'resources/css/cards_public.css', 
                'resources/sass/app.scss', 
                'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
