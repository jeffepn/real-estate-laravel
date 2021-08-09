const mix = require("laravel-mix");
const path = require("path");
const CompressionPlugin = require("compression-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
if (mix.inProduction()) {
  root = "dist";
  jsOutputDir = "dist/js";
  cssOutputDir = "dist/css";
} else {
  root = "assets";
  jsOutputDir = "assets/js";
  cssOutputDir = "assets/css";
}
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
    plugins: [new CompressionPlugin()], //new CleanWebpackPlugin()],
    output: {
      filename: "[name].js",
      chunkFilename: `${jsOutputDir}/js/[name].[contenthash].js`,
      //chunkFilename: "js/[name].[contenthash].js",
      //path: path.resolve(__dirname, root),
      //publicPath: "assets",
    },
  })
  .js("resources/js/app.js", `${jsOutputDir}/js/realestatelaravel.js`)
  .sass("resources/scss/app.scss", `${cssOutputDir}/css/realestatelaravel.css`)
  .vue();
