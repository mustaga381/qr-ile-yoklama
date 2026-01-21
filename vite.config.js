import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    server: {
        host: "0.0.0.0", // Dışarıdan gelen isteklere izin ver
        port: 5173, // Portun sabitlendiğinden emin ol
        hmr: {
            host: "192.168.1.2", // Tarayıcının bağlanmaya çalışacağı IP
        },
    },
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
