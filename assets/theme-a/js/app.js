require('jquery');

require('../../theme-base/extensions/fancybox/jquery.fancybox');
require('../../theme-base/extensions/fancybox/jquery.fancybox.pack');
require('../../theme-base/extensions/fancybox/jquery.fancybox-media');
require('../../theme-base/extensions/owlcarousel/owl.carousel');
require('../../theme-base/extensions/portfolio/portfolio');
require('../../theme-base/extensions/portfolio/isotope.js');

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