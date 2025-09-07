/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./resources/views/**/*.blade.php",
    "./app/Http/Controllers/**/*.php",
    "./storage/framework/views/*.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}