require('jquery');

//var isotope = require('../../theme-base/extensions/portfolio/isotope');

require('../../theme-base/extensions/fancybox/jquery.fancybox');
require('../../theme-base/extensions/fancybox/jquery.fancybox.pack');
require('../../theme-base/extensions/fancybox/jquery.fancybox-media');
require('../../theme-base/extensions/owlcarousel/owl.carousel');
import './menu.js';
import '../../theme-base/extensions/portfolio/isotope';
//import'../../theme-base/extensions/portfolio/portfolio';


//import './google-map.js';


$(document).ready(function() {


    console.log('js !');
    const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
    imagesContext.keys().forEach(imagesContext);


    var jumboHeight = $('.parallax-block').outerHeight();
    function parallax(){
        var scrolled = $(window).scrollTop();
        $('.bg').css('height', ((jumboHeight-scrolled) + 60) + 'px');
    }
parallax();
    $(window).scroll(function(e){
        //jumboHeight = $('.parallax-block').outerHeight();
        parallax();
    });


    // 3 column layout
    var isotopeContainer2 = $('.isotopeContainer2');
    //if( !isotopeContainer2.length || !jQuery().isotope ) return;
    //$win.load(function(){
        isotopeContainer2.isotope({
            itemSelector: '.isotopeSelector',
            masonry: {
                columnWidth: 200
            }
        });
        $('body').on('click', '.isotopeFilters2 a', function(e) {
            console.log('isotope2');
            $('.isotopeFilters2').find('.active').removeClass('active');
            $(this).parent().addClass('active');
            var filterValue = $(this).attr('data-filter');
            isotopeContainer2.isotope({ filter: filterValue });
            e.preventDefault();
        });
    //});

    /*
        $('body').on('click', 'a[data-filter]', function(e){
            e.preventDefault();
        });
    */
/*
    $('.isotopeContainer2').myTheme.Isotope({
        itemSelector: '.isotopeSelector',
        layoutMode: 'fitRows',
        transitionDuration: '0.6s',
    });*/
// filter items on button click
/*
    var $grid = $('.isotopeContainer2').isotope({
        // options
        itemSelector: '.isotopeSelector',
        layoutMode: 'fitRows',
        transitionDuration: '0.6s',
    });
    $('ul.filter').on( 'click', 'a', function(e) {
        //e.preventDefault();
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({ filter: filterValue, itemSelector: '.isotopeSelector', });
    });
*/
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

    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    })

});