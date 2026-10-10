$(document).ready(function () {
    // owl carousel
    $(".testimonials").owlCarousel({
        loop: true,
        margin: 15,
        rtl: false,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 1,
            },
            992: {
                items: 1,
            },
        },
    });

    // owl carousel
    $(".property-agents").owlCarousel({
        loop: true,
        margin: 15,
        rtl: false,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 1,
            },
        },
    });

    $("#shareBlock").socialSharingPlugin({
        urlShare: window.location.href,
        description: $("meta[name=description]").attr("content"),
        title: $("title").text(),
    });

});


if (document.querySelector("#mainCarousel") != null) {
    // Initialise Carousel
    const mainCarousel = new Carousel(document.querySelector("#mainCarousel"), {
        Dots: false,
    });

// Thumbnails
    const thumbCarousel = new Carousel(document.querySelector("#thumbCarousel"), {
        Sync: {
            target: mainCarousel,
            friction: 0,
        },
        Dots: false,
        Navigation: false,
        center: true,
        slidesPerPage: 1,
        infinite: true,
    });
}


