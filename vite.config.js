import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js'
        }
    },
    // For Laravel Sail on Windows Subsystem for Linux 2 (WSL2).
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
