"use strict";

// Preloader area
const preloader = document.getElementById("preloader");
const preloaderFunction = () => {
    preloader.style.display = "none";
};


// Toggle sidebar start
document.querySelectorAll('.toggle-sidebar-btn').forEach(button => {
    button.addEventListener('click', () => {
        document.body.classList.toggle("toggle-sidebar");
    });
});
// Toggle sidebar end



// Tooltip
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// cmn select2 start
$(document).ready(function () {
    $('.cmn-select2').select2();
    flatpickr(".datepicker", {});
});
// cmn select2 end

// cmn-select2-modal
$(".modal-select").select2({
    dropdownParent: $("#formModal"),
});

// cmn-select2 with image start
$(document).ready(function () {
    $('.cmn-select2-image').select2({
        templateResult: formatState,
        templateSelection: formatState
    });
});

// select2 function
function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var baseUrl = "assets/img/mini-flag";
    var $state = $(
        '<span><img src="' + baseUrl + '/' + state.element.value.toLowerCase() + '.svg" class="img-flag" /> ' + state.text + '</span>'
    );
    return $state;
};
// cmn-select2 with image start





$(document).ready(function () {
    // owl carousel dashboard card
    $('.carousel-1').owlCarousel({
        loop: true,
        // autoplay: true,
        margin: -20,
        nav: false,
        dots: false,
        // rtl:true,
        responsive: {
            0: {
                items: 1
            },
            375: {
                items: 2
            },
            576: {
                items: 3
            },
            768: {
                items: 4
            },
            992: {
                items: 3
            }
        }
    });

    // Bootstrap datepicker start
    if ($('.date').length) {
        $('.date').datepicker({
            // options here
            format: 'dd/mm/yyyy',
        });
    }
    // Bootstrap datepicker end
    //Multi step progress section start

    // Jquery UI start
    if ($('#datepicker').length) {
        $("#datepicker").datepicker({
            buttonImageOnly: false,
        });
    }
    // Jquery UI end




    // Piechart
    if ($('#pieChart').length) {
        var options = {
            series: [44, 55, 13, 33],
            chart: {
                type: 'donut',
                height: '300'
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'right',
                offsetY: 0,
                height: 230,
            }
        };

        var chart = new ApexCharts(document.querySelector("#pieChart"), options);
        chart.render();
    }
    // Apexcharts end

    // Circle progress start

    if ($('.circle').length) {
        $('.first.circle').circleProgress({
            value: 0.90,
            size: 70,
            fill: {
                gradient: ["#ae8656"]
            }
        }).on('circle-animation-progress', function (event, progress) {
            $(this).find('span').html(Math.round(90 * progress) + '<i>%</i>');
            $(this).find('span').addClass('percent');
        });

    }
    if ($('.circle').length) {
        $('.second.circle').circleProgress({
            value: 0.60,
            size: 70,
            fill: {
                gradient: ["#ae8656"]
            }
        }).on('circle-animation-progress', function (event, progress) {
            $(this).find('span').html(Math.round(60 * progress) + '<i>%</i>');
            $(this).find('span').addClass('percent');
        });

    }
    if ($('.circle').length) {
        $('.third.circle').circleProgress({
            value: 0.30,
            size: 70,
            fill: {
                gradient: ["#ae8656"]
            }
        }).on('circle-animation-progress', function (event, progress) {
            $(this).find('span').html(Math.round(30 * progress) + '<i>%</i>');
            $(this).find('span').addClass('percent');
        });
    }

    // Circle progress start

    // Line progressbar start
    if ($('#jq1').length) {
        $('#jq1').LineProgressbar({
            percentage: 90,
            fillBackgroundColor: '#fec339',
            height: '5px',
            radius: '5px',
        });
    }
    if ($('#jq2').length) {
        $('#jq2').LineProgressbar({
            percentage: 50,
            fillBackgroundColor: '#fec339',
            height: '5px',
            radius: '5px',
        });
    }
    if ($('#jq3').length) {
        $('#jq3').LineProgressbar({
            percentage: 70,
            fillBackgroundColor: '#fec339',
            height: '5px',
            radius: '5px',
        });
    }
    if ($('#jq4').length) {
        $('#jq4').LineProgressbar({
            percentage: 60,
            fillBackgroundColor: '#fec339',
            height: '5px',
            radius: '5px',
        });
    }

    // Line progressbar end

});


