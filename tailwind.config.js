/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./vendor/livewire/flux-pro/stubs/**/*.blade.php",
    "./vendor/livewire/flux/stubs/**/*.blade.php",
  ],
  theme: {
    extend: {
        // colors: {
        //     zinc: {
        //         ...require('tailwindcss/colors').indigo,
        //     },
        // },
    },
  },
  plugins: [],
}
