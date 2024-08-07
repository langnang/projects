const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .css('resources/css/app.css', 'public/css');


// "@fortawesome/fontawesome-free": "^6.5.1",
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/css', 'public/vendor/@fortawesome/fontawesome-free/6.5.1/css');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/js', 'public/vendor/@fortawesome/fontawesome-free/6.5.1/js');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/metadata', 'public/vendor/@fortawesome/fontawesome-free/6.5.1/metadata');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/svgs', 'public/vendor/@fortawesome/fontawesome-free/6.5.1/svgs');
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/vendor/@fortawesome/fontawesome-free/6.5.1/webfonts');
// "axios": "^0.21.4",
mix.copyDirectory('node_modules/axios/dist', 'public/vendor/axios/0.21.4');
// "bootstrap": "^4.6.2",
mix.copyDirectory('node_modules/bootstrap/dist', 'public/vendor/bootstrap/4.6.2');
// "bootstrap-icons": "^1.11.3",
mix.copyDirectory('node_modules/bootstrap-icons/font', 'public/vendor/bootstrap-icons/1.11.3');
// "docsify-cli": "^4.4.4",
// "highlight.js": "^11.9.0",
// "holderjs": "^2.9.9",
mix.copy('node_modules/holderjs/holder.js', 'public/vendor/holderjs/2.9.9/holder.js');
mix.copy('node_modules/holderjs/holder.min.js', 'public/vendor/holderjs/2.9.9/holder.min.js');
// "jquery": "^3.7.1",
mix.copyDirectory('node_modules/jquery/dist', 'public/vendor/jquery/3.7.1');
// "lodash": "^4.17.21",
mix.copy('node_modules/lodash/lodash.js', 'public/vendor/lodash/4.17.21/lodash.js');
mix.copy('node_modules/lodash/lodash.min.js', 'public/vendor/lodash/4.17.21/lodash.min.js');
// "markdown-it": "^14.1.0",
mix.copyDirectory('node_modules/markdown-it/dist', 'public/vendor/markdown-it/14.1.0');
// "masonry-layout": "^4.2.2",
mix.copyDirectory('node_modules/masonry-layout/dist', 'public/vendor/masonry-layout/4.2.2');
// "mockjs": "^1.1.0",
mix.copyDirectory('node_modules/mockjs/dist', 'public/vendor/mockjs/1.1.0');
// "moment": "^2.30.1",
mix.copy('node_modules/moment/moment.js', 'public/vendor/moment/2.30.1/moment.js');
mix.copyDirectory('node_modules/moment/min', 'public/vendor/moment/2.30.1');
mix.copyDirectory('node_modules/moment/locale', 'public/vendor/moment/2.30.1/locale');
// "normalize.css": "^8.0.1",
mix.copy('node_modules/normalize.css/normalize.css', 'public/vendor/normalize.css/8.0.1/normalize.css');
// "popper.js": "^1.16.1",
mix.copyDirectory('node_modules/popper.js/dist/umd', 'public/vendor/popper.js/1.16.1');
// "vue": "^2.6.12",
mix.copyDirectory('node_modules/vue/dist', 'public/vendor/vue/2.6.12');

