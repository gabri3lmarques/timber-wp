const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const rename = require('gulp-rename');
const purgecss = require('@fullhuman/postcss-purgecss');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');


// Caminhos
const paths = {
    scss: {
        src: ['./assets/scss/**/*.scss'],
        dest: './dist/css/'
    },
    js: {
        src: 'assets/js/**/*.js', 
        dest: 'dist/js',
        filename: 'main.min.js'
    },
    templates: './views/**/*.twig',
    php: './**/*.php',
    temp: './assets/scss/temp/'
};

// Função para compilar SCSS em um arquivo temporário
function compileScss() {
  return gulp.src(paths.scss.src)
    .pipe(sass().on('error', sass.logError)) // Compila SCSS para CSS
    .pipe(gulp.dest(paths.temp)); // Salva o CSS compilado em um diretório temporário
}

// reune todos as classes para safelist
classesArray = ['full-width','half-width','cta-yellow', 'cta-purple', 'cta-green', 'reduced-price', 'yellow', 'purple', 'green', 'card-yellow', 'card-green', 'card-purple'];

// Função para aplicar PurgeCSS e minificar o CSS
async function purifyAndMinify() {
  return gulp.src(`${paths.temp}*.css`)
    .pipe(postcss([
      autoprefixer(), // Adiciona prefixos automáticos
      purgecss({
        content: [
          paths.templates,
          paths.php
        ],
        safelist: classesArray,
      })
    ]))
    .pipe(cleanCSS()) // Minifica o CSS
    .pipe(rename('main.min.css')) // Renomeia para 'main.min.css'
    .pipe(gulp.dest(paths.scss.dest)); // Salva no destino
}

// Função para limpar o diretório temporário
async function cleanTemp() {
  const del = await import('del');
  return del.deleteAsync([paths.temp]);
}

// Tarefa para minificar JavaScript
function scripts() {
    return gulp.src(paths.js.src) // Pega todos os arquivos .js do diretório especificado
        .pipe(concat(paths.js.filename)) // Concatena todos os arquivos em um único arquivo
        .pipe(uglify()) // Minifica o arquivo concatenado
        .pipe(gulp.dest(paths.js.dest)); // Salva o arquivo minificado no diretório de destino
}

// Função para assistir mudanças nos arquivos SCSS, Twig e PHP
function watch() {
  gulp.watch(paths.scss.src, gulp.series(compileScss, purifyAndMinify, cleanTemp));
  gulp.watch(paths.templates, gulp.series(compileScss, purifyAndMinify, cleanTemp));
  gulp.watch(paths.php, gulp.series(compileScss, purifyAndMinify, cleanTemp));
}

// Tarefas
gulp.task('compileScss', compileScss);
gulp.task('purifyAndMinify', purifyAndMinify);
gulp.task('cleanTemp', cleanTemp);
gulp.task('scripts', scripts);
gulp.task('watch', watch);
gulp.task('build', gulp.series('compileScss', 'purifyAndMinify', 'cleanTemp'));

