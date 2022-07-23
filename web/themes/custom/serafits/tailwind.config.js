/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["**/*.twig", "../../../modules/**/*.twig"],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        violet: "#5964E0",
        lightviolet: "#939BF4",
        verydarkblue: "#19202D",
        midnight: "#121721",
        white: "#FFFFFF",
        lightgray: "#F4F6F8",
        gray: "#9DAEC2",
        darkgray: "#6E8098",
      },
    },
  },
  plugins: [],
}
