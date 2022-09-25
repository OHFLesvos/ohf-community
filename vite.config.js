import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";
const { createVuePlugin } = require("vite-plugin-vue2");

export default defineConfig({
    plugins: [
        laravel(["resources/js/app.js"]),
        createVuePlugin()
    ],
    resolve: {
        alias: {
            "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
            ziggy: path.resolve(__dirname, "vendor/tightenco/ziggy/dist"),
        },
    },
    server: {
        hmr: {
            host: "localhost",
        },
    },
});
