require('jquery');

require('../extensions/fancybox/jquery.fancybox');
require('../extensions/fancybox/jquery.fancybox.pack');
require('../extensions/fancybox/jquery.fancybox-media');
require('../extensions/owlcarousel/owl.carousel');
require('../extensions/portfolio/portfolio');
require('../extensions/portfolio/isotope.js');

import './google-map.js';
import './menu.js';

$(document).ready(function() {
    console.log('js !');
    const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
    imagesContext.keys().forEach(imagesContext);

    /*$('.sidenav').sidenav();
    $('select').formSelect();*/

    /*
         *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
         */
    $('.fancybox-media')
        .attr('rel', 'media-gallery')
        .fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            prevEffect: 'none',
            nextEffect: 'none',
            arrows: false,
            helpers: {
                media: {},
                buttons: {}
            }
        });

});