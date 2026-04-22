/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',

    // --- BARIS INI WAJIB ADA AGAR MARY UI MUNCUL ---
    './vendor/robsontenorio/mary/src/View/Components/**/*.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    // --- BARIS INI WAJIB ADA UNTUK STYLE DAISYUI ---
    require('daisyui'),
    require('@tailwindcss/typography'),
  ],
};
