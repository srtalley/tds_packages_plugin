jQuery(function($) {

console.log('area you edvent');
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
    // $('.open-popup-link').magnificPopup({
    //     type:'inline',
    //     midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
    //   });

    // $('.bxslider').bxSlider({
    //     'easing': 'ease-in-out',
    //     // 'pagerType': 'full'
    //     'pagerCustom': '.bx-pager'
    // });
    console.log(ajaxfrontendurl.ajax_url);
    $('.open-popup-link').click(function(e){
        e.preventDefault();
        var gallery_post_id = $(this).data('loadslider');
        var flexslider_wrapper = $('#flexslider-wrapper');
        $.ajax(
            {
                url: ajaxfrontendurl.ajax_url,
                type: 'POST',
                data: {
                    action: 'tds_load_slider_images',
                    gallery_post_id: gallery_post_id,
                },
                success: function (response) {
                    // $('#colophon').prepend(response.html);
                    console.log(response.status);
                    showFeatherlight(response.html, flexslider_wrapper, gallery_post_id);
                    
                    // initializeFlexSlider(gallery_post_id);
                    // var current_time = new Date($.now());
                    // wp_settings_api_response_data += '<p><strong>' + current_time + '</strong></p>';
        
                    // wp_settings_api_response_data += '<h4>Result: ' + response.messages + '</h4>';
                    // //final
                    // $(wp_settings_api_response).prepend(wp_settings_api_response_data);
                  }
            })
    });
    // $( document ).on('click', '.open-popup-link', {
    //     function() {

    //     }
    // };
    function showFeatherlight(html, element, gallery_id){
        console.log('show f');
        // element.html(html);
        // , {
        //     // namespace:'yourmom',
        //     resetCss: true,
        //     closeSpeed: 5000,
            
        // }
        $.featherlight(html);
        initializeFlexSlider(gallery_id);
    }
    function initializeFlexSlider(gallery_id) {
        console.log('CALLED' + gallery_id);
        // The slider being synced must be initialized first

        $('#carousel-' + gallery_id).flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: true,
            slideshow: false,
            itemWidth: 100,
            itemMargin: 5,
            asNavFor: '#gallery-' + gallery_id
        });
        
        $('#gallery-' + gallery_id).flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: true,
            slideshow: false,
            sync: '#carousel' + gallery_id
        });
    }


}); //end $(document).ready(function()


});
