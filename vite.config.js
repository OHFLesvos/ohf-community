import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";
import vue from '@vitejs/plugin-vue2'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                compilerOptions: {
                    whitespace: 'preserve'
                },
            }
        }),
    ],
    resolve: {
        alias: {
            "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
        },
    },
    server: {
        hmr: {
            host: "localhost",
        },
    },
});
