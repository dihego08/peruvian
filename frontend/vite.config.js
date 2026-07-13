import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    react(),
    tailwindcss(),
  ],
  // Si despliega en subcarpeta: VITE_BASE_PATH=/peruvian/frontend/dist/ npm run build
  base: process.env.VITE_BASE_PATH || '/',
  preview: {
    // Mismo comportamiento que .htaccess al probar el build localmente
    historyApiFallback: true,
  },
})
