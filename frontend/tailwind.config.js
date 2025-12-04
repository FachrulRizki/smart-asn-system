/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}", // Tentukan Tailwind harus mencari di semua file di folder src
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
