const mix = require("laravel-mix");
const path = require("path");

mix
  .webpackConfig({
    resolve: {
      alias: {
        "@": path.resolve(__dirname, "resources", "js"),
        "@components": path.resolve(__dirname, "resources", "js", "components"),
        ziggy: path.resolve("vendor/tightenco/ziggy/dist"),
      },
      extensions: [".js", ".jsx", ".ts", ".tsx"],
    },
  })
  .js("resources/js/app.js", "dist/js/realestatelaravel.js")
  .sass("resources/scss/app.scss", "dist/css/realestatelaravel.css");
