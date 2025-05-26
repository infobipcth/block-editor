import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  build: {
    rollupOptions: {
      input: path.resolve(__dirname, 'src/styles/styles-out.scss'),
      output: {
        assetFileNames: 'styles.css'
      }
    },
    outDir: 'dist',
    emptyOutDir: false
  },
  css: {
    preprocessorOptions: {
      scss: {}
    }
  }
});
