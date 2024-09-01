const gulp = require("gulp");
const sass = require("gulp-dart-sass");
const sassGlob = require("gulp-sass-glob-use-forward");
const postcss = require("gulp-postcss");
const autoprefixer = require("autoprefixer"); //ブラウザによって対応するCSSを追加してくれる
const cssSorter = require("css-declaration-sorter"); //CSSのプロパティを並び替える
const mmq = require("gulp-merge-media-queries"); //CSSのメディアクエリをまとめる
const cleanCss = require("gulp-clean-css");

const browserSync = require("browser-sync");

// js 縮小化
const uglify = require("gulp-uglify");
const rename = require("gulp-rename");

const htmlBeautify = require("gulp-html-beautify");
// エラーが発生しても強制終了させない
const plumber = require("gulp-plumber");
// エラー発生時のアラート出力
const notify = require("gulp-notify");

//画像の圧縮
const imagemin = require("gulp-imagemin");
const imageminMozjpeg = require("imagemin-mozjpeg");
const pngquant = require("imagemin-pngquant");
const del = require("del");
const changed = require("gulp-changed");
const svgSprite = require("gulp-svg-sprite");

//Sassファイルをコンパイルして出力
const compileSass = () => {
  console.log("Processing SCSS files"); // 追加
  return gulp
    .src("./src/assets/sass/**/*.scss")
    .pipe(
      plumber({ errorHandler: notify.onError("Error: <%= error.message %>") })
    )
    .pipe(sassGlob())
    .pipe(sass({ outputStyle: "expanded" }))
    .pipe(postcss([autoprefixer(), cssSorter()]))
    .pipe(mmq())
    .pipe(gulp.dest("./assets/css/"))
    .pipe(
      cleanCss({
        format: "beautify", // 展開形式を保持
      })
    )
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(gulp.dest("./assets/css/"));
};

//SVGスプライトをまとめて生成
gulp.task("svgSprite", () => {
  return (
    gulp
      .src("src/assets/svg/**/*.svg")
      .pipe(
        plumber({ errorHandler: notify.onError("Error: <%= error.message %>") })
      )
      .pipe(
        svgSprite({
          mode: {
            symbol: {
              dest: ".",
              sprite: "sprite.svg",
              // スプライト画像のプレビュー用HTMLが欲しい人はこちらも記述してください。
              // 任意の場所とファイル名を指定してください。
              example: {
                dest: "../css/svg-sprite-preview/sprite.html",
              },
            },
          },
          svg: {
            // XML宣言を削除する
            xmlDeclaration: true,
            // DOCTYPE宣言を削除する
            doctypeDeclaration: false,
          },
          // SVGOの設定
          svgstore: {
            plugins: [
              // viewBox属性を削除する（widthとheight属性がある場合）。
              // 表示が崩れる原因になるので削除しない。
              { removeViewBox: false },
              // <style>を削除する
              { removeStyleElement: true },
              //fill属性を削除する
              { removeAttrs: { attrs: "fill" } },
            ],
          },
        })
      )
      // 生成したsprite.svgをdist/assets/svg/に保存する
      .pipe(gulp.dest("./assets/svg/"))
      // 生成したsprite.svgをsrc/に保存する
      .pipe(browserSync.reload({ stream: true }))
  );
});

//ファイルの変更を監視する
const watch = () => {
  gulp.watch(
    "./src/assets/sass/**/*.scss",
    gulp.series(compileSass, browserReload)
  );
  gulp.watch("./src/assets/js/**/*.js", gulp.series(minJS, browserReload));
  gulp.watch("./src/assets/img/**/*", gulp.series(imageMinify, browserReload));
  gulp.watch("./src/**/*.php", gulp.series(copyPhp, browserReload));
};

//イメージファイルの削除
const cleanImage = () => {
  return del("./assets/img/");
};

const browserInit = () => {
  browserSync.init({
    proxy: 'http://polaris.wp/', // Local by Flywheelのドメイン
    files: [ './**/*.php' ],
    // port: 8888,
    open: true,
    watchOptions: {
      debounceDelay: 1000, //1秒間、タスクの再実行を抑制
    },
  });
};

//ブラウザのリロード
const browserReload = (done) => {
  browserSync.reload();
  done();
};

const minJS = () => {
  return gulp
    .src("./src/assets/js/**/*.js")
    .pipe(gulp.dest("./assets/js/"))
    .pipe(
      plumber({ errorHandler: notify.onError("Error: <%= error.message %>") })
    )
    .pipe(uglify())
    .pipe(
      rename({
        suffix: ".min",
      })
    )
    .pipe(gulp.dest("./assets/js/"));
};

const copyPhp = () => {
  return gulp.src("./src/**/*.php").pipe(gulp.dest("./"));
};

const imageMinify = () => {
  return (
    gulp
      // `{ since: gulp.lastRun(imageMinify) }`を設定することで、
      // 前回実行時からの差分のファイルのみを対象としてタスクを実行することができる。
      .src("./src/assets/img/**/*", { since: gulp.lastRun(imageMinify) })
      .pipe(
        plumber({ errorHandler: notify.onError("Error: <%= error.message %>") })
      )
      .pipe(
        imagemin([
          // GIF用の設定
          imagemin.gifsicle({ optimizationLevel: 3 }),
          // PNG用の設定
          pngquant({ quality: [0.65, 0.8], speed: 1 }),
          // JPEG用の設定
          imageminMozjpeg({
            quality: 65,
          }),
          // SVG用の設定
          imagemin.svgo({
            plugins: [
              {
                removeViewBox: false,
              },
            ],
          }),
        ])
      )
      .pipe(gulp.dest("./assets/img/"))
  );
};

exports.compileSass = compileSass;
exports.watch = watch;
exports.browserInit = browserInit;
exports.dev = gulp.parallel(browserInit, watch); //順番は関係なく実行される
exports.minJS = minJS;
exports.build = gulp.series(
  cleanImage,
  gulp.parallel(minJS, compileSass, imageMinify, copyPhp, 'svgSprite')
);
exports.clean = cleanImage;
