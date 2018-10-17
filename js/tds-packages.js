jQuery(function($) {

$(document).ready(function() {

    $('.open-popup-link').click(function(e){
        e.preventDefault();
        var gallery_title = $(this).data('slidertitle');
        var gallery_post_id = $(this).data('loadslider');
        // Show loading animation
        var package_card = $(this).closest('.tds-package-item');
        $(package_card).addClass('tds-popup-loading');
        $.ajax(
            {
                url: ajaxfrontendurl.ajax_url,
                type: 'POST',
                data: {
                    action: 'tds_load_slider_images',
                    gallery_title: gallery_title,
                    gallery_post_id: gallery_post_id,
                },
                success: function (response) {
                    showLightbox(response.html, gallery_post_id);
                    $(package_card).removeClass('tds-popup-loading');
                  }
            })
    });

    function showLightbox(html, gallery_id){

        $.magnificPopup.open({
            mainClass: 'mfp-fade tds-gallery',
            items: {
                src: html,
                type: 'inline'
            }
        });
        initializeFlexSlider(gallery_id);
    }
    function initializeFlexSlider(gallery_id) {
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
            sync: '#carousel-' + gallery_id
        });
    }

    $('.open-iframe-link').click(function(e){
        e.preventDefault();
        var iframe_src = $(this).attr('href');

        var iframe_options = {
            type: 'iframe',
            items: {
                src: iframe_src,
            },
            iframe: {
                markup: '<div class="tds-packages-iframe block-review">'+
                        '<div class="mfp-close"></div>'+
                        '<iframe align="center" class="mfp-iframe" width="90%" height="90%" frameborder="0"></iframe>'+
                        '</div>',
            },
            mainClass: 'mfp-custom-iframe',
        }
        $.magnificPopup.open(iframe_options);

        // $('.open-iframe-link').magnificPopup(option);
    });


}); //end $(document).ready(function()


});
