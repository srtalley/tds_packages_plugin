//v1.3.5

jQuery(function($) {

  $(document).ready(function() {
    console.log('what is going on');
    /*
     * Scripts to show or hide sections of the CPT UI based upon
     * selected radio buttons.
     *
     */

    //function to show or hide radio or upload
    function ShowHideradioType() {

     //get the value of the button clicked
     var radioTypeValue = $(this).val();
     var radioClasses = $(this).attr('class');
     // handle situations where there's more than one class
     radioClasses = /(toggle_)\w+/.exec(radioClasses)[0];

     var radioParent = $(this).parentsUntil('tr').parent();
     radioParent.addClass('show-option');

     //select all the types we want to show
     var toggles = $('.ds-wp-cpt-metabox-settings-row.' + radioClasses);
     var togglesToShow = $('.ds-wp-cpt-metabox-settings-row.' + radioClasses + '.' + radioTypeValue);

     toggles.addClass('hide-option');
     togglesToShow.removeClass('hide-option');
   }

    //show or hide the sections based on clicking the radio button
    $('input[class^="toggle_"][type="radio"],input[class^=" toggle_"][type="radio"]').on('click', ShowHideradioType );

    //Show or hide on page load
    $('input[class^="toggle_"][type="radio"]:checked,input[class^=" toggle_"][type="radio"]:checked').trigger( 'click' );

    /*
     * Scripts to show the datepicker
     */
    //Load the datepicker on cpt date picker objects
    $('.ds-cpt-datepicker').datepicker();

    /*
     * Scripts to allow removal of items that were part of an array.
     *
     */
     $('.ds-cpt-removable-array-value').before().on('click', function() {
       $(this).remove();
     });

    /*
     * Scripts to allow removal of uploaded items
     *
     */
    $('.ds-wp-cpt-uploader-removable').before().on('click', function() {
      $(this).removeClass('has-media');
      $(this).find($('.ds-wp-cpt-uploader-value')).val('');
      $(this).find($('.ds-wp-cpt-file-name')).html('');
      $(this).find($('.ds-wp-cpt-uploader-image-container')).html('');
    });
    $('.ds-wp-cpt-image-uploader-removable').before().on('click', function() {
      $(this).removeClass('has-image');
      $(this).find($('.ds-wp-cpt-image-uploader-value')).val('');
      $(this).find($('.ds-wp-cpt-image-uploader-image-container')).html('');
    });
    $('.ds-wp-cpt-image-gallery-uploader-removable').before().on('click', function() {
      console.log(this);
      $(this).remove();
      // $(this).find($('.ds-wp-cpt-image-uploader-image-container')).html('');
    });
    $('.ds-wp-cpt-image-uploader-image-gallery-container').sortable({tolerance:'pointer'});
    /*
     * Handle the link to the post in post title select
     */
     $('select.ds-wp-cpt-post-title-select').change(function(){
      link_name = $(this).attr('id') + '_link';
      $('#' + link_name).attr('href', '/wp-admin/post.php?post=' + $(this).val() + '&action=edit');
     });
  }); //end $(document).ready(funcion()


});
