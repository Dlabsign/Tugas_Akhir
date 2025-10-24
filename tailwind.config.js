/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./views/**/*.php",
    "./views/**/**/*.php",
    "./widgets/**/*.php",
    "./components/**/*.php",
    "./layouts/**/*.php",
    "./assets/**/*.js",
    "./web/**/*.html",
  ],
  safelist: [
    { pattern: /.*/ } // ini artinya semua kelas akan diikutkan
  ],

  theme: {
    extend: {},
  },
  plugins: [],
}

