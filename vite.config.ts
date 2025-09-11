import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
  server: {
    host: '0.0.0.0',           // Allow connections from other devices
    port: 5173,                 // Vite default port
    strictPort: true,           // Ensure it uses this exact port
    hmr: {
      host: '192.168.0.109',    // <-- Your computer's local IP address
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
