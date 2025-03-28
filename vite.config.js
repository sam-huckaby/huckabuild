import { defineConfig } from 'vite'

export default defineConfig({
  root: './',
  publicDir: 'public',
  build: {
    outDir: 'build',
    rollupOptions: {
      input: {
        app: 'resources/js/app.js',
        vendor: 'resources/js/vendor.js',
        styles: 'resources/css/app.css'
      },
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.names[0] && assetInfo.names[0].endsWith('.php')) {
            return '[name].[ext]'
          }
          if (assetInfo.names[0].endsWith('.css')) {
            return 'css/[name].[ext]'
          }
          return 'assets/[name].[ext]'
        }
      },
      external: [
        'public/index.php'
      ]
    }
  },
  css: {
    postcss: './postcss.config.js',
    modules: {
      localsConvention: 'camelCase'
    }
  },
  optimizeDeps: {
    include: ['tailwindcss']
  },
  server: {
    watch: {
      usePolling: true,
      interval: 100
    }
  },
  resolve: {
    conditions: ['module', 'browser', 'development|production']
  },
  plugins: []
}) 