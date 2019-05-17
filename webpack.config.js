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
    //.addEntry('extensions')
   /* .addPlugin( new CopyWebpackPlugin([
        { from: './assets/theme-base/extensions/fancybox', to: 'build/extensions/fancybox' },
        { from: './assets/theme-base/extensions/owlcarousel', to: 'build/extensions/owlcarousel' },
        { from: './assets/theme-base/extensions/portfolio', to: 'build/extensions/portfolio' },

    ])) */

   /* .addEntry('js/jquery', './node_modules/jquery/dist/jquery.min.js')
    .addEntry('js/materialize', './node_modules/materialize-js/index.js')
     */
   // .addEntry('js/jquery', './node_modules/jquery/dist/jquery.min.js')



    .addEntry('js/bootstrap/bootstrap', './assets/theme-base/bootstrap/js/bootstrap.js')
    .addEntry('js/bootstrap/ie10-viewport-bug-workaround', './assets/theme-base/bootstrap/js/ie10-viewport-bug-workaround.js')
    .addEntry('js/bootstrap/ie-emulation-modes-warning', './assets/theme-base/bootstrap/js/ie-emulation-modes-warning.js')
  //  .addEntry('js/extensions/fancybox/jquery.fancybox', './assets/theme-base/extensions/fancybox/jquery.fancybox.js')
  //  .addEntry('js/extensions/fancybox/jquery.fancybox.pack', './assets/theme-base/extensions/fancybox/jquery.fancybox.pack.js')
  //  .addEntry('js/extensions/fancybox/jquery.fancybox-media', './assets/theme-base/extensions/fancybox/jquery.fancybox-media.js')
  //  .addEntry('js/extensions/owlcarousel/owl.carousel', './assets/theme-base/extensions/owlcarousel/owl.carousel.js')
  //  .addEntry('js/extensions/portfolio/isotope', './assets/theme-base/extensions/portfolio/isotope.min.js')
  //  .addEntry('js/extensions/portfolio/portfolio', './assets/theme-base/extensions/portfolio/portfolio.js')

    //.addEntry('js/google-map', './assets/theme-base/js/google-map.js')

   // .addEntry('js/google-map', './assets/theme-base/js/google-map.js')
   // .addEntry('js/menu', './assets/theme-base/js/menu.js')
    .addEntry('js/app', './assets/theme-base/js/app.js')
    //.addEntry('extensions/'

  //  .addStyleEntry('css/extensions/owlcarousel/owl.carousel.min', './assets/theme-base/extensions/owlcarousel/assets/owl.carousel.min.css')
  //  .addStyleEntry('css/extensions/owlcarousel/owl.theme.default.min', './assets/theme-base/extensions/owlcarousel/assets/owl.theme.default.min.css')
    .addStyleEntry('css/extensions/fancybox/jquery.fancybox', './assets/theme-base/extensions/fancybox/jquery.fancybox.css')

    /*.addEntry('js/analytics', './assets/js/analytics.js')*/
    .addStyleEntry('css/bootstrap/bootstrap', './assets/theme-base/bootstrap/css/bootstrap.css')
    .addStyleEntry('css/bootstrap/bootstrap-theme', './assets/theme-base/bootstrap/css/bootstrap-theme.css')
    .addStyleEntry('css/bootstrap/carousel', './assets/theme-base/bootstrap/css/carousel.css')
    .addStyleEntry('css/bootstrap/ie10-viewport-bug-workaround', './assets/theme-base/bootstrap/css/ie10-viewport-bug-workaround.css')
    .addStyleEntry('css/normalize', './assets/theme-base/css/normalize.css')
    .addStyleEntry('css/style', './assets/theme-base/css/style.css')
    .addStyleEntry('css/app', './assets/theme-base/scss/app.scss')

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
