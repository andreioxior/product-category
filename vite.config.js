import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    'admin': ['./resources/js/admin.js'],
                    'search': ['./resources/js/search.js'],
                    'vendor': ['axios', 'alpinejs'],
                }
            }
        },
        chunkSizeWarningLimit: 1000,
        cssCodeSplit: true,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    optimizeDeps: {
        include: ['axios', 'alpinejs'],
    },
});
