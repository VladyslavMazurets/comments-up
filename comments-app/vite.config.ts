import vue from "@vitejs/plugin-vue";
import tailwindcss from "tailwindcss";
import { defineConfig } from "vite";

// https://vite.dev/config/
export default defineConfig({
  server: {
    port: 5173,
    cors: true,
    hmr: {
      host: "localhost",
    },
  },
  plugins: [vue()],
  css: {
    postcss: {
      plugins: [
        tailwindcss({
          config: "./tailwind.config.js",
        }),
      ],
    },
  },
});
