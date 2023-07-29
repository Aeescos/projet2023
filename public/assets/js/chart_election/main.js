
  


(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Sidebar Toggler
    $('.sidebar-toggler').click(function () {
        $('.sidebar, .content').toggleClass("open");
        return false;
    });


    // Progress Bar
    $('.pg-bar').waypoint(function () {
        $('.progress .progress-bar').each(function () {
            $(this).css("width", $(this).attr("aria-valuenow") + '%');
        });
    }, {offset: '80%'});


    // Calender
    $('#calender').datetimepicker({
        inline: true,
        format: 'L'
    });


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        items: 1,
        dots: true,
        loop: true,
        nav : false
    });


    // Chart Global Color
    Chart.defaults.color = "#6C7293";
    Chart.defaults.borderColor = "#000000";


    // Worldwide Sales Chart
     var ctx1 = $("#worldwide-sales").get(0).getContext("2d");
    var myChart1 = new Chart(ctx1, {
        type: "bar",
        data: {
            labels: ["13h", "14h", "15h", "16h", "17h", "18h", "19h"],
            datasets: [{
                    label: "ALI",
                    data: [15, 30, 55, 65, 60, 80, 95],
                    backgroundColor: "rgba(235, 222, 222, .7)"
                },
                {
                    label: "MOUSSA",
                    data: [8, 35, 40, 60, 70, 55, 75],
                    backgroundColor: "rgba(135, 122, 122, .5)"
                },
                {
                    label: "ZAYNAB",
                    data: [12, 25, 45, 55, 65, 70, 60],
                    backgroundColor: "rgba(225, 22, 22, .3)"
                },      
                {
                    label: "MOUSSA",
                    data: [1, 10, 15, 16, 30, 55, 88],
                    backgroundColor: "rgba(211, 322, 322, .5)"
                },
                {
                    label: "HAFIDHOI",
                    data: [4, 6, 9, 12, 19, 29, 40],
                    backgroundColor: "rgba(435, 522, 522, .5)"
                },
            ]
            },
        options: {
            responsive: true
        }
    });

    // Salse & Revenue Chart
    var ctx2 = $("#salse-revenue").get(0).getContext("2d");
    var myChart2 = new Chart(ctx2, {
        type: "line",
        data: {
            labels: ["13h", "14h", "15h", "16h", "17h", "18h", "19h"],
            datasets: [{
                    label: "Kadere",
                    data: [15, 30, 55, 45, 70, 65, 85],
                    backgroundColor: "rgba(222, 111, 111, .7)",
                    fill: true
                },
                {
                    label: "KASSIM",
                    data: [9, 13, 17, 13, 190, 18, 27],
                    backgroundColor: "rgba(235, 22, 22, .5)",
                    fill: true
                },
                {
                    label: "MOUDJIB",
                    data: [109, 35, 70, 30, 90, 80, 27],
                    backgroundColor: "rgba(111, 322, 322, .5)",
                    fill: true
                }
            ]
            },
        options: {
            responsive: true
        }
    });



    
})(jQuery);

