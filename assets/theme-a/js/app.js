//require('jquery');
import '../../../node_modules/jquery/dist/jquery.js';
require('slick-carousel/slick/slick');
import '../../../node_modules/quill/core.js';
import '../../../node_modules/quill/quill.js';
//var isotope = require('../../theme-base/extensions/portfolio/isotope');
import '../../../node_modules/isotope-layout/dist/isotope.pkgd.js';
require('../../theme-base/extensions/fancybox/jquery.fancybox');
require('../../theme-base/extensions/fancybox/jquery.fancybox.pack');
require('../../theme-base/extensions/fancybox/jquery.fancybox-media');
require('../../theme-base/extensions/owlcarousel/owl.carousel');
import './menu.js';


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
/*
    var options = {
        debug: 'info',
        modules: {
            toolbar: '#toolbar'
        },
        placeholder: 'Compose an epic...',
        readOnly: true,
        theme: 'snow'
    };
    var editor = new Quill('#editor', options);
*/
    $('.index-carousel').slick({
        lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        nav: true,
        mobileFirst: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ],
    });
    $(window).on('resize', function() {
       // if ($('.slider:not(.no-display)').length) {
            $('.index-carousel').slick('init');
        //}
    });

    var portfolio = $('.grid-item');

    if (portfolio.length) {
        // init Isotope

        var $grid = $('.grid').isotope({
            // options
            itemSelector: '.grid-item',
            percentPosition: true,
            masonry: {
                columnWidth: '.grid-sizer'
            }
        });
// layout Isotope after each image loads
        $grid.imagesLoaded().progress(function () {
            $grid.isotope('layout');
        });
/*
        var grid = document.querySelector('.grid');
        var iso = new Isotope( grid, {
            // options...
            itemSelector: '.grid-item',
            masonry: {
                columnWidth: 200
            }
        });
*/
    }
/*
        $('body').on('click', '.isotopeFilters2 a', function(e) {
            console.log('isotope2');
            $('.isotopeFilters2').find('.active').removeClass('active');
            $(this).parent().addClass('active');
            var filterValue = $(this).attr('data-filter');
            isotopeContainer2.isotope({ filter: filterValue });
            e.preventDefault();
        });
*/

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