var gulp = require('gulp');
var plumber = require('gulp-plumber');
var notify = require("gulp-notify");
var plumberNotifier = require('gulp-plumber-notifier');

var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();



gulp.task('hello', function() {
    console.log('Testing');
  });

  gulp.task('sass', function(){
      return gulp.src('assets/styles/main.scss')
      .pipe(plumber({
        errorHandler: function (err) {
            notify.onError({
                "title": "Icon fonts error.",
                "message": "<%= error.message %>",
            })(err);
            this.emit('end');
        }
    }))
      .pipe(sass())
      .pipe(gulp.dest('assets/'))
      .pipe(browserSync.reload({
          stream: true
      }))
    
  });

  gulp.task('browserSync', function() {
    browserSync.init({
        proxy: "http://localhost/ryan_m/"
    });
  })

  gulp.task('watch', ['browserSync', 'sass'], function(){
      gulp.watch('assets/styles/*.scss', ['sass']);
  })
