module.exports = {
  content: ["./public/**/*.html", "./public/**/*.php", "./src/**/*.php"],
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [require("@tailwindcss/aspect-ratio")],
};
