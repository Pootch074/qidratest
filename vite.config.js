import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from '@tailwindcss/vite'
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/custom.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        viteStaticCopy({
            targets: [
                {
                    src: 'resources/assets/icons/*',
                    dest: 'assets/icons'
                },
                {
                    src: 'resources/assets/fonts/*',
                    dest: 'assets/fonts'
                },
                {
                    src: 'resources/images/*',
                    dest: 'assets/images'
                }
            ]
        }),
    ],
    resolve: {
        alias: {
            '@' : path.resolve(__dirname, 'resources')
        }
    }
});
