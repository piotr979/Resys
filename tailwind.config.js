/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    fontFamily: {
      'logo': ['Josefin Sans', 'sans'],
      'pgraph': ['Mulish', 'sans'],
    }, 
    extend: {
      colors: {
        'menu-background': '#0F0923',
        'bg-indigo-1000': '#0E1A3E',
      }
    },
  },
  plugins: [],
}

