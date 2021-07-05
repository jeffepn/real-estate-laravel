const mix = require("laravel-mix");
const path = require("path");
const CompressionPlugin = require("compression-webpack-plugin");
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
    plugins: [new CompressionPlugin()],
  })
  .js("resources/js/app.js", "dist/js/realestatelaravel.js")
  .sass("resources/scss/app.scss", "dist/css/realestatelaravel.css")
  .vue();
