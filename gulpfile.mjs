import pkg from 'gulp';
const { src, dest, watch, parallel, series } = pkg;

// css
import * as dartSass from "sass";
import gulpSass from "gulp-sass";
const sass = gulpSass(dartSass); // Crea una función sass que use el plugin gulp-sass con el motor dartSass
import plumber from "gulp-plumber";
import autoprefixer from "autoprefixer";
import cssnano from "cssnano";
import postcss from "gulp-postcss";
import sourcemaps from "gulp-sourcemaps";

// javascript
import terser from "gulp-terser-js";
import concat from "gulp-concat"
import rename from "gulp-rename";

// imagenes
import newer from 'gulp-newer';
import imagemin, { mozjpeg, optipng } from 'gulp-imagemin';
import webp from "gulp-webp";
import avif from "gulp-avif";

const paths = {
    scss: "src/scss/**/*.scss",
    js: "src/js/**/*.js",
    images: "src/img/**/*.{jpg,png}",
}

function css() {
    return src(paths.scss) // Identificar el archivo SASS
        .pipe(plumber({ errorHandler: handleError }))  // Manejo de errores con función personalizada
        .pipe(sourcemaps.init())
        .pipe(sass()) // Compilar SASS a CSS
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(sourcemaps.write("."))
        .pipe(dest("./public/build/css")); // Almacenar en la carpeta build
}

function javascript() {
    return src(paths.js)
        .pipe(sourcemaps.init())
        .pipe(concat("bundle.js"))
        .pipe(terser())
        .pipe(sourcemaps.write("."))
        .pipe(rename({ suffix: ".min" }))
        .pipe(dest("./public/build/js"));
}

function images() {
    return src(paths.images)
        .pipe(newer("./public/build/img")) // Solo pasa las nuevas o modificadas
        .pipe(imagemin([
            mozjpeg({ quality: 65, progressive: true }),
	        optipng({ optimizationLevel: 3 }),
        ]))
        .pipe(dest("./public/build/img"));
}

function webpVersion() {
    return src(paths.images)
        .pipe(newer("./public/build/img"))
        .pipe(webp({ quality: 50 }))
        .pipe(dest("./public/build/img"));
}

function avifVersion() {
    return src(paths.images)
        .pipe(newer("./public/build/img"))
        .pipe(avif({ quality: 50 }))
        .pipe(dest("./public/build/img"));
}

function handleError(error) {
    console.error('Error en Gulp:', error.toString());
    this.emit('end');  // Permite que Gulp continúe
}

function dev() {
    watch(paths.scss, css); // Escucha por los cambios
    watch(paths.js, javascript); // Escucha por los cambios
}

const processImages = parallel(images, webpVersion, avifVersion);
const devTasks = series(css, javascript, dev);

export { css };
export { javascript as js };
export { images };
export { webpVersion };
export { avifVersion };
export { processImages };
export { devTasks as dev };