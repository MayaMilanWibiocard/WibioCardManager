import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/vanilla.js',
                'resources/js/webcard.js',
                'resources/js/cardmanager.js',
                'resources/js/cardpersonalizer.js',
            ],
            refresh: true
        }),
    ],
});
