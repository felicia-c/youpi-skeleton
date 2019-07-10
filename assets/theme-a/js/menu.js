//jQuery to collapse the navbar on scroll
$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $('.bg').addClass('parallax-scrolled');
        $(".navbar-fixed-top").addClass("top-nav-collapse");

    } else {
        $('.bg').removeClass('parallax-scrolled');
        $(".navbar-fixed-top").removeClass("top-nav-collapse");

    }
});
