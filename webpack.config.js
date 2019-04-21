var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin');
//const MiniCssExtractPlugin = require('mini-css-extract-plugin');
Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
   /* .addEntry('js/jquery', './node_modules/jquery/dist/jquery.min.js')
    .addEntry('js/materialize', './node_modules/materialize-js/index.js')
     */
    .addEntry('js/app', './assets/js/app.js')
    /*.addEntry('js/analytics', './assets/js/analytics.js')*/
    .addStyleEntry('css/app', './assets/scss/app.scss')
    /*.addStyleEntry('css/admin', './assets/scss/admin.scss')*/

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    //.enableSingleRuntimeChunk()
    .disableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    /* .addPlugin(new CopyWebpackPlugin([
        // Copy the skins from tinymce to the build/skins directory
        { from: 'node_modules/tinymce/skins', to: 'js/skins' },
    ]))

    .addPlugin(new CopyWebpackPlugin([
        // Copy the skins from tinymce to the build/skins directory
        { from: 'node_modules/tinymce/themes', to: 'js/themes' },
    ]))
    */

    .autoProvideVariables({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
    })

    .configureFilenames({
        images: '[path][name].[hash:8].[ext]',
    })
    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();
