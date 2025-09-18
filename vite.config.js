import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/custom.scss', 
                'resources/js/app.js',
                'resources/js/display.js',
                'resources/js/adminUsers.js'
            ],
            refresh: true,
        }),
        tailwindcss()
    ],
    resolve: {
        alias: {
            '@' : path.resolve(__dirname, 'resources')
        }
    }
});