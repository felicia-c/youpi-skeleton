//const $ = require('jquery');

require('materialize-css');
//require('materialize-js');

global.$ = global.jQuery = $;
window.$ = window.jQuery = $;


$(document).ready(function() {
    console.log('js !');
    const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
    imagesContext.keys().forEach(imagesContext);


    $('.sidenav').sidenav();
    $('select').formSelect();



});