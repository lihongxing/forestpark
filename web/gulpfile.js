var gulp   = require('gulp');
var jshint = require('gulp-jshint');//语法检查
var concat = require('gulp-concat');//合并文件
var uglify = require('gulp-uglify');//压缩代码
var rename = require('gulp-rename');//重命名
var del = require('del');
// 语法检查
gulp.task('jshint', function () {
    return gulp.src('wechat/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});
// 合并文件之后压缩代码
gulp.task('minify', function (){
    return gulp.src('wechat/*.js')
        .pipe(concat('wechat.js'))
        .pipe(gulp.dest('wechat/dist'))
        .pipe(uglify())
        .pipe(rename('wechat.min.js'))
        .pipe(gulp.dest('wechat/dist'));
});
// 监视文件的变化
gulp.task('watch', function () {
    gulp.watch('wechat/*.js', ['jshint', 'minify']);
});
// 注册缺省任务
gulp.task('default', ['jshint', 'minify', 'watch']);
// gulp.task('default', ['jshint', 'minify']);
