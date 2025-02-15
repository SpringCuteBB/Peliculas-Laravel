import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/dom.js",
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: "public/build",
        emptyOutDir: true,
        rollupOptions: {
            input: {
                app: "resources/js/app.js",
                dom: "resources/js/dom.js",
                css: "resources/css/app.css",
            },
        },
    },
});
