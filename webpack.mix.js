const mix = require("laravel-mix");
const path = require("path");
const webpack = require("webpack");
const CompressionPlugin = require("compression-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer")
  .BundleAnalyzerPlugin;

const root = mix.inProduction() ? "dist" : "assets";

mix
  .webpackConfig({
    optimization: {
      concatenateModules: true,
    },
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
      new BundleAnalyzerPlugin(),
      new webpack.ContextReplacementPlugin(
        /moment[\\\/]locale$/,
        /^\.\/(en|pt-br)$/,
      ),
    ],
    output: {
      filename: "[name].js",
      chunkFilename: "js/[name].[contenthash].js",
      path: path.resolve(__dirname, root),
    },
  })
  .js("resources/js/app.js", "js/realestatelaravel.js")
  .sass("resources/scss/app.scss", "css/realestatelaravel.css")
  .copyDirectory(root, "public/realestatelaravel")
  .vue();
