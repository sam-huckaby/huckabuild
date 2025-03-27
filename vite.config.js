import { defineConfig } from 'vite'

export default defineConfig({
  build: {
    outDir: 'public',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        app: 'resources/js/app.js',
        styles: 'resources/css/app.css'
      },
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith('.css')) {
            return 'css/[name].[ext]'
          }
          return 'assets/[name].[ext]'
        }
      }
    }
  },
  css: {
    postcss: './postcss.config.js'
  }
}) 