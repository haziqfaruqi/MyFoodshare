import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
  server: {
    host: 'localhost',          // Use localhost for local development
    port: 5173,                 // Vite default port
    strictPort: true,           // Ensure it uses this exact port
    hmr: {
      host: 'localhost',        // Use localhost for HMR
    },
  },
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    react()
  ],
  optimizeDeps: {
    exclude: ['lucide-react'],
  },
});
