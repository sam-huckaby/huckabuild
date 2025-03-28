/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.php",
    "./resources/views/**/*.twig",
    "./resources/views/**/*.latte",
    "./resources/js/**/*.js",
    "./resources/css/**/*.css"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
  safelist: [
    // Add any dynamic classes here that Tailwind might not detect
  ]
} 