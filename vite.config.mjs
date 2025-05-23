import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  build: {
    outDir: 'dist',
    lib: {
      // Path to your library entry point. Change as appropriate!
      entry: path.resolve(__dirname, 'src/index.ts'),
      name: 'BlockEditor',
      fileName: (format) => `index.js`
    },
    rollupOptions: {
      // Externalize deps you don't want bundled
      external: [
        'react', 'react-dom',
        '@wordpress/api-fetch', '@wordpress/base-styles', '@wordpress/block-editor',
        '@wordpress/block-library', '@wordpress/blocks', '@wordpress/components',
        '@wordpress/data', '@wordpress/element', '@wordpress/format-library',
        '@wordpress/hooks', '@wordpress/keyboard-shortcuts', '@wordpress/server-side-render',
        'axios', 'uuid'
      ]
    }
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@import "${path.resolve(__dirname, 'src/styles/_global-variables.scss')}";`
      }
    }
  }
});
