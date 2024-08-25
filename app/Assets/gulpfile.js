import gulp from "gulp";
import webpack from "webpack-stream";
import MiniCssExtractPlugin from "mini-css-extract-plugin";

import path from "path";
import url from "url";
import fs from "fs";

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const CWD = process.cwd();
const APP_DIR = fs.readFileSync(`${CWD}/config/app`, 'utf8').trim();


gulp.task("default", () =>
  gulp
    .src(`./${APP_DIR}/JavaScripts/index.js`)
    .pipe(
      webpack({
        // Any configuration options...
        // Path to your entry point. From this file Webpack will begin its work
        entry: `./${APP_DIR}/JavaScripts/index.js`,

        // Path and filename of your result bundle.
        // Webpack will bundle all JavaScript into this file
        output: {
          path: path.resolve(__dirname, "dist"),
          publicPath: "",
          filename: "app.min.js"
        },
        plugins: [
          new MiniCssExtractPlugin(),
        ],
        module: {
          rules: [
            {
              test: /\.js$/,
              exclude: /(node_modules)/,
              use: {
                loader: "babel-loader",
                options: {
                  presets: ["@babel/preset-env"]
                }
              }
            },
            {
              test: /\.ts$/,
              use: "ts-loader"
            },
            {
              test: /\.css$/,
              use: [
                "style-loader",
                {
                  loader: "css-loader",
                  options: {
                    importLoaders: 1,
                    modules: true
                  }
                }
              ]
            }
          ]
        },

        mode: "production"
      })
    )
    .pipe(gulp.dest("dist/"))
);
