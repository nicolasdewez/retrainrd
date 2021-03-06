var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');

Encore
    // directory where all compiled assets will be stored
    .setOutputPath('public/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/build')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    .addEntry('script-app', './assets/js/app.js') // will output as public/build/script-app.js

    .addStyleEntry('style-app', './assets/css/app.scss') // will output as web/build/style-app.css
    .addStyleEntry('style-errors', './assets/css/errors.scss')

    // allow sass/scss files to be processed
    .enableSassLoader()

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

// create hashed filenames (e.g. app.abc123.css)
// .enableVersioning()
;

var config = Encore.getWebpackConfig();

config.plugins = config.plugins.concat([
    new CopyWebpackPlugin([
        { from: './assets/img/no-result.png', to: 'images' }
    ])
]);

// export the final configuration
module.exports = config;