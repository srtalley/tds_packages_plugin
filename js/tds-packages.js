jQuery(function($) {


$(document).ready(function() {

    // $('.gallery').each(function() { // the containers for all your galleries
    //     $(this).magnificPopup({
    //         delegate: 'a', // the selector for gallery item
    //         type: 'image',
    //         gallery: {
    //           enabled:true
    //         }
    //     });
    // });
    

    $('.bxslider').bxSlider({
        'easing': 'ease-in-out',
        // 'pagerType': 'full'
        'pagerCustom': '.bx-pager'
    });


}); //end $(document).ready(function()


});
