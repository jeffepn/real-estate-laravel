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
    plugins: [
      new CompressionPlugin(),
      new CleanWebpackPlugin({
        cleanOnceBeforeBuildPatterns: [
          path.resolve(__dirname, root),
          path.resolve(__dirname, "public/"),
        ],
      }),
    ],
    output: {
      filename: "[name].js",
      chunkFilename: "js/[name].[contenthash].js",
      path: path.resolve(__dirname, root),
      publicPath: mix.inProduction()
        ? "/realestatelaravel/"
        : "http://0.0.0.0:9099/realestatelaravel/",
    },
  })
  .extract(["vue", "moment", "axios"], "vendor")
  .js("resources/js/app.js", "js/realestatelaravel.js")
  .sass("resources/scss/app.scss", "css/realestatelaravel.css")
  .copyDirectory(root, "public/realestatelaravel")
  .vue();
