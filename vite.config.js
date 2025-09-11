import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";

export default defineConfig({
    plugins: [
        symfonyPlugin(),
    ],
    build: {
        rollupOptions: {
            input: {
                app: "./assets/app.js"
            },
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `
                    @import "C:/Users/Utilisateur/Documents/Simplon.co/simplonco_exe/END_PROJECT/lingostep/lingostep-back/assets/styles/_variables.scss";
                `
            }
        }
    }
});
