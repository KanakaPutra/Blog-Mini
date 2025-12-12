import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    server: {
        hmr: {
            host: 'localhost',
        },
    },

    // Penting untuk kompatibilitas Node 24
    define: {
        'process.env': {}
    },

    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
})
