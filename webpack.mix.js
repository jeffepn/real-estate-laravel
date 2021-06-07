const  mix = require('laravel-mix');


mix.js("src/resources/js/app.js", "src/dist/js")
.sass("src/resources/scss/app.scss", "src/dist/css");
