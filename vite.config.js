import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";

export default defineConfig({
    plugins: [
        symfonyPlugin(),
    ],
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            input: {
                app: "./assets/app.js",
                carrousel: "./assets/scripts/carrousel.js"
            },
            external: [
                '@symfony/stimulus-bridge/controllers.json'
            ]
        }
    },
    resolve: {
        alias: {
            '@': '/assets',
            '~bootstrap': 'bootstrap',
            '~bootstrap-icons': 'bootstrap-icons',
        }
    },
    server: {
        watch: {
            usePolling: true
        }
    },
});
