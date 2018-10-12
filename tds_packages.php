<?php
/*
Plugin Name: Build Custom Packages for Tours and Activities
Plugin URI: http://talleyservices.com
Description: Allows adding custom packages that can be shown on pages using a simple shortcode
Version: 1.4
Author: Talley Services
Author URI: http://talleyservices.com
License: GPLv2
*/
// namespace Cindy;
//Include the admin panel page
require_once( dirname( __FILE__ ) . '/tds_packages_admin.php');
//Include the CPT functions
require_once( dirname( __FILE__ ) . '/lib/dustysun-wp-cpt-api/ds_wp_cpt_api.php');

require_once( dirname( __FILE__ ) . '/cpt/tds_packages.php');
use \TDSPackages\CPT;
class TDS_Packages {

  private $tds_packages_cpt;

  // private $meta_box_field_array = array();
  public function __construct() {

    // activate the CPT
    $this->tds_packages_cpt = new CPT\TDSPackagesCPT();


    //Fill the meta box fields in the object
    // $this->meta_box_fields = $this->tds_packages_add_fields();

    // Register the JS for the admin screen
    add_action( 'admin_enqueue_scripts', array($this, 'register_admin_tds_packages_scripts'));

    // Register style sheet.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_tds_packages_styles_scripts' ) );

    // Allow file uploads
    // add_action('post_edit_form_tag', array($this, 'update_edit_form'));

    // Register the CPT
    // add_action( 'init', array( $this, 'register_tds_packages' ) );

    // Register the taxonomies
    // add_action( 'init', array( $this, 'tds_packages_register_taxonomies' ));

    // Register the custom title
    // add_filter( 'enter_title_here', array($this, 'tds_packages_change_title_text') );

    // Add the meta box fields
    // add_action('add_meta_boxes', array($this, 'tds_add_main_meta_box'));

    // Add the custom columns to the post field view
    // add_filter('manage_edit-tds_packages_columns', array($this, 'set_custom_edit_tds_packages_columns'));

    // Fill the custom columns with data from  postmeta
    // add_action('manage_tds_packages_posts_custom_column', array($this, 'custom_tds_packages_column'), 10, 2);

    //Allow custom column sorting
    // add_filter('manage_edit-tds_packages_sortable_columns', array($this, 'custom_tds_packages_sortable_columns'));
    // add_filter('request', array($this, 'tds_package_post_type_orderby'));

    //Save the CPT data
    // add_action('save_post',  array($this,'tds_save_data'));

    register_activation_hook( __FILE__, array($this, 'tds_default_settings' ));

  }


  //Enqueue the styles
  public function register_tds_packages_styles_scripts() {

    wp_register_style('tds-packages', plugins_url('css/tds-packages.css', __FILE__));
    wp_enqueue_style('tds-packages');

    wp_register_style('tds-featherlight', plugins_url('css/featherlight.css', __FILE__));
    wp_enqueue_style('tds-featherlight');


    wp_register_style('tds-magnific-popup', plugins_url('lib/magnific-popup/magnific-popup.css', __FILE__));
    wp_enqueue_style('tds-magnific-popup');


    wp_register_style('tds-bxslider', plugins_url('lib/bxslider/jquery.bxslider.min.css', __FILE__));
    wp_enqueue_style('tds-bxslider');


    // wp_register_style('tds-pretty-photo', plugins_url('lib/pretty-photo/css/prettyPhoto.css', __FILE__));
    // wp_enqueue_style('tds-pretty-photo');


    wp_register_script('tds-packages', plugins_url('js/tds-packages.js', __FILE__));
    wp_enqueue_script('tds-packages');

    wp_register_script('tds-featherlight', plugins_url('js/featherlight.js', __FILE__));
    wp_enqueue_script('tds-featherlight');


    wp_register_script('tds-magnific-popup', plugins_url('lib/magnific-popup/jquery.magnific-popup.min.js', __FILE__));
    wp_enqueue_script('tds-magnific-popup');


    // wp_register_script('tds-pretty-photo', plugins_url('lib/pretty-photo/jsjquery.prettyPhoto.js', __FILE__));
    // wp_enqueue_script('tds-pretty-photo');


    // wp_register_script('tds-jssor', plugins_url('lib/jssor/jssor.slider.min.js', __FILE__));
    // wp_enqueue_script('tds-jssor');

    wp_register_script('tds-bxslider', plugins_url('lib/bxslider/jquery.bxslider.min.js', __FILE__));
    wp_enqueue_script('tds-bxslider');

  } //end public function register_tds_packages_styles

  //Enqueue the scripts for the post editor
  public function register_admin_tds_packages_scripts() {
    wp_register_script('tds-packages-admin-js', plugins_url('js/tds-packages-admin.js', __FILE__));
    wp_enqueue_script('tds-packages-admin-js');
  } //end public function register_tds_packages_styles

  //Allow the CPT form to have file uploads
  public function update_edit_form() {
      echo ' enctype="multipart/form-data"';
  } // end update_edit_form


  public function register_tds_packages() {
    // debug_to_console('this');
    $labels = array(
      'name' => __( 'Package Profiles', 'tds_packages_plugin' ),
  		'singular_name' => __( 'Package', 'tds_packages_plugin' ),
  		'add_new_item' => __( 'Add New Package', 'tds_packages_plugin' ),
  		'edit_item' => __( 'Edit Package', 'tds_packages_plugin' ),
  		'new_item' => __( 'New Package', 'tds_packages_plugin' ),
  		'not_found' => __( 'No Packages found', 'tds_packages_plugin' ),
  		'all_items' => __( 'All Packages', 'tds_packages_plugin' )
    );
    $args   = array(
      'labels' => $labels,
      'public' => false,
      'publicly_queriable' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'show_in_nav_menus' => false,
      'has_archive' => false,
      'rewrite' => false,
      'map_meta_cap' => true,
      'menu_icon' => 'dashicons-index-card',
      'supports' => array( 'title', 'thumbnail', 'author' ),
      'exclude_from_search' => true,
    );

    register_post_type( 'tds_packages', $args );
  } //end private function register_tds_packages

  public function tds_packages_add_fields() {

    //Add the fields that will go beneath the main post area
    $meta_box_fields['tds_packages'][] = array(
        'section_name' => 'tds_packages_details',
        'title' => 'Package Details',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
          array(
              'name' => 'Post Type:',
              'desc' => '',
              'id' => 'tds_package_post_type',
              'type' => 'radio',
              'options' => array(
                  'tds_package_activity' => array(
                    'label' => 'Activity',
                    'value' => 'Activity',
                  ),
                  'tds_package_destination' => array(
                    'label' => 'Destination',
                    'value' => 'Destination',
                  ),
                  'tds_package_location' => array(
                    'label' => 'Location',
                    'value' => 'Location',
                  ),
                  'tds_package_tour' => array(
                    'label' => 'Tour',
                    'value' => 'Tour',
                  ),
                  'tds_package_other' => array(
                    'label' => 'Accommodation',
                    'value' => 'Other',
                  ),
                ),
          ),
          array(
              'name' => 'Link Type:',
              'desc' => '',
              'id' => 'tds_package_link_type',
              'type' => 'radio',
              'options' => array(
                  'tds_package_same_window' => array(
                    'label' => 'Same Window',
                    'value' => 'self',
                  ),
                  'tds_package_new_window' => array(
                    'label' => 'New Window',
                    'value' => 'blank',
                  ),

                  'tds_package_lightbox' => array(
                    'label' => 'Lightbox',
                    'value' => 'lightbox_iframe',
                  ),
                ),
          ),
          // array(
          //     'name' => 'PDF Upload:',
          //     'desc' => '',
          //     'id' => 'tds_package_pdf_itinerary',
          //     'type' => 'pdfattachment',
          //     'default' => ''
          // ),
          array(
              'name' => 'Link Location:',
              'desc' => '',
              'id' => 'tds_package_page_url',
              'type' => 'text',
              'default' => ''
          ),

          array(
              'name' => 'Link Text:',
              'desc' => '',
              'id' => 'tds_package_page_link_text',
              'type' => 'text',
              'default' => ''
          ),
          array(
              'name' => 'Package Description:',
              'desc' => '',
              'id' => 'tds_package_description',
              'type' => 'texteditor',
              'default' => ''
          ),


        )
    );
    //Add the fields that will go on the side of the main post area

    $meta_box_fields['tds_packages'][] = array(
        'section_name' => 'tds_packages_details_side',
        'title' => 'Package Price',
        'context' => 'side',
        'priority' => 'low',
        'fields' => array(
          array(
              'name' => 'Price:',
              'desc' => '',
              'id' => 'tds_package_price',
              'type' => 'text',
              'default' => ''
          ),
        )
    );
   return $meta_box_fields;
  }

  //Add custom columns and unset columns to post list view
  public function set_custom_edit_tds_packages_columns($columns)
  {
    $columns['cb'] = '<input type="checkbox" />';
    $columns['title'] = __('Package Name');
    $columns['tds_package_post_type'] = __('Package Type');
    unset($columns['author']);
    return $columns;
  } //end function set_custom_edit_tds_packages_columns

  //Add the custom postmeta info to the column
  public function custom_tds_packages_column($column, $post_id)
  {
    switch ($column) {
      case 'tds_package_post_type':
      $tds_package_type_column = get_post_meta( $post_id , 'tds_package_post_type' , true );
      if ($tds_package_type_column == 'Other'): $tds_package_type_column = 'Accommodation'; endif;
      echo $tds_package_type_column;
      break;
    } //end switch
  } //end function custom_tds_packages_column


  //set up the sortable columns
  public function custom_tds_packages_sortable_columns($columns)
  {
    $columns['tds_package_post_type'] = 'tds_package_post_type';
    return $columns;
  } //end function set_custom_edit_tds_packages_columns

  //allow sorting of the custom columns
  public function tds_package_post_type_orderby($vars)
  {
    if ( isset( $vars['orderby'] ) && 'tds_package_post_type' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'tds_package_post_type',
            'orderby' => 'meta_value'
        ) );
    }

    return $vars;
  } //end function tds_package_post_type_orderby

  public function tds_packages_register_taxonomies(){

    $destination_labels =  array(
        'name'              => __( 'Destinations', 'tds_packages_plugin'),
        'singular_name'     => __( 'Destination', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Destinations', 'tds_packages_plugin' ),
        'all_items'         => __( 'Destinations', 'tds_packages_plugin' ),
        'parent_item'       => __( 'Parent Destination', 'tds_packages_plugin' ),
        'parent_item_colon' => __( 'Parent Destination:', 'tds_packages_plugin' ),
        'edit_item'         => __( 'Edit Destination', 'tds_packages_plugin' ),
        'update_item'       => __( 'Update Destination', 'tds_packages_plugin' ),
        'add_new_item'      => __( 'Add New Destination', 'tds_packages_plugin' ),
        'new_item_name'     => __( 'New Destination', 'tds_packages_plugin' ),
        'menu_name'         => __( 'Destinations', 'tds_packages_plugin' ),
    );
    $destination_args = array(
        'hierarchical'      => true,
        'labels'            => $destination_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array("slug" => "tds_destinations"),
    );
    register_taxonomy( 'tds-packages-destination', array( 'tds_packages' ), $destination_args );


    $duration_labels =  array(
        'name'              => __( 'Durations', 'tds_packages_plugin'),
        'singular_name'     => __( 'Duration', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Durations', 'tds_packages_plugin' ),
        'all_items'         => __( 'Durations', 'tds_packages_plugin' ),
        'parent_item'       => __( 'Parent Duration', 'tds_packages_plugin' ),
        'parent_item_colon' => __( 'Parent Duration:', 'tds_packages_plugin' ),
        'edit_item'         => __( 'Edit Duration', 'tds_packages_plugin' ),
        'update_item'       => __( 'Update Duration', 'tds_packages_plugin' ),
        'add_new_item'      => __( 'Add New Duration', 'tds_packages_plugin' ),
        'new_item_name'     => __( 'New Duration', 'tds_packages_plugin' ),
        'menu_name'         => __( 'Durations', 'tds_packages_plugin' ),
    );
    $duration_args = array(
        'hierarchical'      => true,
        'labels'            => $duration_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array("slug" => "tds_durations"),
    );
    register_taxonomy( 'tds-packages-duration', array( 'tds_packages' ), $duration_args );


    $location_labels =  array(
        'name'              => __( 'Locations', 'tds_packages_plugin'),
        'singular_name'     => __( 'Location', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Locations', 'tds_packages_plugin' ),
        'all_items'         => __( 'Locations', 'tds_packages_plugin' ),
        'parent_item'       => __( 'Parent Location', 'tds_packages_plugin' ),
        'parent_item_colon' => __( 'Parent Location:', 'tds_packages_plugin' ),
        'edit_item'         => __( 'Edit Location', 'tds_packages_plugin' ),
        'update_item'       => __( 'Update Location', 'tds_packages_plugin' ),
        'add_new_item'      => __( 'Add New Location', 'tds_packages_plugin' ),
        'new_item_name'     => __( 'New Location', 'tds_packages_plugin' ),
        'menu_name'         => __( 'Locations', 'tds_packages_plugin' ),
    );
    $location_args = array(
        'hierarchical'      => true,
        'labels'            => $location_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array("slug" => "tds_locations"),
    );
    register_taxonomy( 'tds-packages-location', array( 'tds_packages' ), $location_args );

    $type_labels =  array(
        'name'              => __( 'Types', 'tds_packages_plugin'),
        'singular_name'     => __( 'Type', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Types', 'tds_packages_plugin' ),
        'all_items'         => __( 'Types', 'tds_packages_plugin' ),
        'parent_item'       => __( 'Parent Type', 'tds_packages_plugin' ),
        'parent_item_colon' => __( 'Parent Type:', 'tds_packages_plugin' ),
        'edit_item'         => __( 'Edit Type', 'tds_packages_plugin' ),
        'update_item'       => __( 'Update Type', 'tds_packages_plugin' ),
        'add_new_item'      => __( 'Add New Type', 'tds_packages_plugin' ),
        'new_item_name'     => __( 'New Type', 'tds_packages_plugin' ),
        'menu_name'         => __( 'Types', 'tds_packages_plugin' ),
    );
    $type_args = array(
        'hierarchical'      => true,
        'labels'            => $type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array("slug" => "tds_types"),
    );
    register_taxonomy( 'tds-packages-type', array( 'tds_packages' ), $type_args );

  }
  private function debug_to_console($data) {
      if(is_array($data) || is_object($data))
  	{
  		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
  	} else {
      echo("<script>console.log('PHP: ".addslashes($data)."');</script>");
  	}
  }
  private function print_r2($val){
          echo '<pre>';
          print_r($val);
          echo  '</pre>';
  }

  //Change the "Enter Title Here" text
  public function tds_packages_change_title_text( $title ){
       $addPostScreeen = get_current_screen();

     if  ( 'tds_packages' == $addPostScreeen->post_type ) {
            $title = 'Enter Package Name';
       }

       return $title;
  } //end function tds_packages_change_title_text( $title )

  public function tds_default_settings() {
    if(!get_option('tds_packages_activity_color_option')): add_option( 'tds_packages_activity_color_option', '#2d75b6'); endif;
    if(!get_option('tds_packages_destination_color_option')): add_option( 'tds_packages_destination_color_option', '#2d75b6'); endif;
    if(!get_option('tds_packages_location_color_option')): add_option( 'tds_packages_location_color_option', '#2d75b6'); endif;
    if(!get_option('tds_packages_other_color_option')): add_option( 'tds_packages_other_color_option', '#2d75b6'); endif;
    if(!get_option('tds_packages_tour_color_option')): add_option( 'tds_packages_tour_color_option', '#2d75b6'); endif;

  } //end function tds_default_settings()

  //Generic sections to add meta boxes to post types
  public function tds_add_main_meta_box($post_type) {
      $meta_box_fields_to_add = $this->meta_box_fields;
      foreach($meta_box_fields_to_add as $post_type => $meta_box_value) {
        foreach($meta_box_value as $value){
          // debug_to_console($value['fields']);
          add_meta_box($value['section_name'], $value['title'], array( $this, 'tds_standard_format_box'), $post_type, $value['context'], $value['priority'], $value['fields']);
        }
      }
  } //end function tds_add_main_meta_box($meta_box)

  //Format meta boxes
  public function tds_standard_format_box($post, $callback_fields) {

    // Use nonce for verification
    wp_nonce_field(basename(__FILE__), 'tds_meta_box_nonce');

    echo '<table class="form-table">';

    foreach ($callback_fields['args'] as $field) {

        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        $standardFieldLabel = '<tr>'.
                '<th style="width:20%"><label for="'. $field['id'] .'">'. $field['name']. '</label></th>'.
                '<td>';
        $expandedFieldLabel = '<tr>'.
                '<th style="width:40%"><label for="'. $field['id'] .'">'. $field['name']. '</label></th>'.
                '<td>';
        $topFieldLabel = '<tr>'.
                '<th COLSPAN=2 style="width:20%; padding-bottom:0px;"><label for="'.
                $callback_fields['id'] .'">'. $field['name']. '</label></th></tr>'.
                '<tr><td COLSPAN=2>';
        switch ($field['type']) {
            case 'text':
                echo $standardFieldLabel;
                echo ' <input type="text" name="'. $field['id']. '" id="'. $field['id'] .'" value="'. ($meta ? $meta : $field['default']) . '" size="30" style="width:100%" />'. '<br />'. $field['desc'];
                break;
          case 'text_small':
            echo $standardFieldLabel;
            echo ' <input type="text" name="'. $field['id']. '" id="'. $callback_fields['id'] .'" value="'. ($meta ? $meta : $field['default']) . '" size="30" style="width:100%" />'. '<br />'. $field['desc'].'</p>';
          break;
            case 'textarea':
                echo $standardFieldLabel;
                echo '<textarea name="'. $field['id']. '" id="'. $field['id']. '" cols="60" rows="4" style="width:97%">'. ($meta ? $meta : $field['default']) . '</textarea>'. '<br />'. $field['desc'];
                break;
            case 'select':
                echo $expandedFieldLabel;
                echo '<select name="'. $field['id'] . '" id="'. $field['id'] . '">';
                foreach ($field['options'] as $option) {
                    echo '<option '. ( $meta == $option ? ' selected="selected"' : '' ) . '>'. $option . '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                echo $standardFieldLabel;
                //Set a counter for how many items there are
                //If this is the first item, we'll check it in case there
                //are no items actually checked
                $radioCounter = 1;
                foreach ($field['options'] as $radioKey => $option) {
                  echo '<input type="radio" value="'.$option['value'].'" name="'.$field['id'].'" id="'.$radioKey.'"',$meta == $option['value'] || $radioCounter == 1 ? ' checked="checked"' : '',' />
                  <label for="'.$radioKey.'">'.$option['label'].'</label> &nbsp;&nbsp;';
                  //increase the radioCounter
                  $radioCounter++;
                }
                break;
            case 'checkbox':
                echo $standardFieldLabel;
                foreach ($field['options'] as $checkKey => $option) {
                  echo '<input type="checkbox" value="'.$option['value'].'" name="'.$field['id'].'[]" id="'.$checkKey.'"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' />
                  <label for="'.$checkKey.'">'.$option['label'].'</label> &nbsp;&nbsp;';
                }
                break;
            case 'texteditor':
                echo $topFieldLabel;
                wp_editor( $meta, $field['id'], array(
                  'wpautop'       => true,
                  'media_buttons' => false,
                  'textarea_name' => $field['id'],
                  'textarea_rows' => 10,
                  'teeny'         => true
                ));
                break;
            case 'pdfattachment':
                echo $standardFieldLabel;
                if(!empty($meta['url'])):
                  $path = parse_url($meta['url'], PHP_URL_PATH);
                  $pathFragments = explode('/', $meta['url']);
                  $end = end($pathFragments);
                  echo '<a href="'. $meta['url'] .'" target="_blank">' . $end . '</a>';
                endif;
                echo ' <input type="file" name="'. $field['id']. '" id="'. $field['id'] .'" size="30" style="width:100%" />'. '<br />'. $field['desc'];
              break;
        }
        echo     '</td>'.'</tr>';
    } //end   foreach ($meta_box[$post->post_type]['fields'] as $field) {

    echo '</table>';

  }//end  function tds_standard_format_box($post, $callback_fields)

  public function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
  public function tds_save_data($post_id) {

    global $post;
    $meta_box_fields = $this->meta_box_fields;

    //Verify nonce
    if (!isset($_POST['tds_meta_box_nonce']) || !wp_verify_nonce($_POST['tds_meta_box_nonce'], basename(__FILE__))) {
        return;
    } else {
      //Check autosave
      if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
          return $post_id;
      }

      //Check permissions
      if (!current_user_can('edit_page', $post_id)) {
          return $post_id;
      }


      // if ('page' == $_POST['post_type']) {
      //
      // } else {
      //     return $post_id;
      // }
      // $this->write_log($_FILES);

      foreach($meta_box_fields as $post_type => $meta_box_sections) {

        foreach($meta_box_sections as $meta_box_values){

          foreach($meta_box_values['fields'] as $field){





            //check if this is a file upload
            if($field['type'] == 'pdfattachment') {
              //Check if the $_FILES array is filled
              if(!$_FILES[$field['id']]['error'] == 4) {

              $supported_types = array('application/pdf');
              $arr_file_type = wp_check_filetype(basename($_FILES[$field['id']]['name']));
              $uploaded_type = $arr_file_type['type'];
               $upload = wp_upload_bits($_FILES[$field['id']]['name'], null, file_get_contents($_FILES[$field['id']]['tmp_name']));
              if(in_array($uploaded_type, $supported_types)) {
                  $upload = wp_upload_bits($_FILES[$field['id']]['name'], null, file_get_contents($_FILES[$field['id']]['tmp_name']));
                  if(isset($upload['error']) && $upload['error'] != 0) {
                      wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                  } else {
                      update_post_meta($post_id, $field['id'], $upload);
                  }
              }
              else {
                  wp_die("The file type that you've uploaded is not a PDF.");
              }
              } //new
            } //end if($field['type'] == 'pdfattachment')

            else {
              //Do a regular update
              $old = get_post_meta($post_id, $field['id'], true);
              $new = $_POST[$field['id']];
              if ($new && $new != $old) {
                  update_post_meta($post_id, $field['id'], $new);
              } elseif ('' == $new && $old) {
                  delete_post_meta($post_id, $field['id'], $old);
              }
            }//end if($_FILES)



          } //end foreach($meta_box_value as $field)
        } //end foreach($meta_box_sections as $meta_box_values)
      } // end foreach($meta_box_fields as $post_type => $meta_box_sections)
    } //end if (!isset($_POST['tds_meta_box_nonce'])
  }


} //end class TDS_Packages

//http://stackoverflow.com/questions/2843356/can-i-pass-arguments-to-my-function-through-add-action

class TDS_Packages_Shortcode {
////////////////////////////////////////////////////
// PACKAGES - TOURS AND ACTIVITIES SHORTCODE
////////////////////////////////////////////////////



  public function __construct() {

    add_shortcode( 'show-packages', array($this, 'add_tds_packages_shortcode') );
  }
  public function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }


  public function add_tds_packages_shortcode($tdsAttributes =[], $tdsContent = null, $tdsTag = '') {


    //make the array keys and attributes lowercase
    $tdsAttributes = array_change_key_case((array)$tdsAttributes, CASE_LOWER);
    //override any default attributes with the user defined parameters
    $tdsCustomAttributes = shortcode_atts([
      'class'          => 'all',
      'destination'    => 'all',
      'duration'       => 'all',
      'location'       => 'all',
      'type'           => 'all',
      'style'          => 'side',
      'desc'           => 'yes',
      'cols'           => 'two',
    ], $tdsAttributes, $tdsTag);

    $tds_meta_query_combined = array();
    $tds_taxonomy_query_combined = array();

    // $tds_class_query = '';
    //build our taxonomy query, depending on shortcodes
    if($tdsCustomAttributes['class'] != 'all') {
      //wee have to serialize the item to match
      $tds_meta_query_combined = array(
        'key' => 'tds_package_post_type',
        'value' => $tdsCustomAttributes['class'],
        'compare' => 'LIKE'
      );
    } //end if($tdsCustomAttributes['class'] != 'all')

    if($tdsCustomAttributes['destination'] != 'all') {
      //we have to "explode" the string into an array if there are multiple items
      $tds_taxonomy_query_combined[] = array(
        'taxonomy' => 'tds-packages-destination',
        'field' => 'slug',
        'terms' => explode(',', $tdsCustomAttributes['destination']),
      );
    } //end if($tdsCustomAttributes['destination'] != 'all')

    if($tdsCustomAttributes['duration'] != 'all') {
      //we have to "explode" the string into an array if there are multiple items
      $tds_taxonomy_query_combined[] = array(
        'taxonomy' => 'tds-packages-duration',
        'field' => 'slug',
        'terms' => explode(',', $tdsCustomAttributes['duration']),
      );
    } //end if($tdsCustomAttributes['duration'] != 'all')

    if($tdsCustomAttributes['location'] != 'all') {
      //we have to "explode" the string into an array if there are multiple items
      $tds_taxonomy_query_combined[] = array(
        'taxonomy' => 'tds-packages-location',
        'field' => 'slug',
        'terms' => explode(',', $tdsCustomAttributes['location']),
      );
    } //end if($tdsCustomAttributes['location'] != 'all')

    if($tdsCustomAttributes['type'] != 'all') {
      //we have to "explode" the string into an array if there are multiple items
      $tds_taxonomy_query_combined[] = array(
        'taxonomy' => 'tds-packages-type',
        'field' => 'slug',
        'terms' => explode(',', $tdsCustomAttributes['type']),
      );
    } //end if($tdsCustomAttributes['type'] != 'all')

    global $paged;


    $tds_packages_args = array(
      'post_type' => 'tds_packages',
      'post_status' => 'publish',
      'orderby' => 'title',
      'order' => 'ASC',
      'posts_per_page' => -1,
      // 'paged' => $paged,
      'fields' => 'ids',
      'meta_query' => array( $tds_meta_query_combined ),
      'tax_query' => $tds_taxonomy_query_combined
    );

    $tds_packages_query = new WP_Query( $tds_packages_args );

    if( $tds_packages_query->have_posts() ){
      while( $tds_packages_query->have_posts() ){
        $tds_packages_query->the_post();

        $tds_packages_post_id = get_the_ID();
        $tds_packages_query_title = get_the_title();


        //GET THE DURATIONS
        $tds_duration_names = array();
        $tds_packages_duration = get_the_terms($tds_packages_post_id, 'tds-packages-duration');
        if($tds_packages_duration):foreach($tds_packages_duration as $tds_duration):
          $tds_duration_names[] = $tds_duration->name;
        endforeach; endif;
        $tds_durations = implode('', $tds_duration_names);

        //Package price
        $tds_package_price = get_post_meta($tds_packages_post_id, 'tds_package_price', true);


        $tds_packages_query_items[] = array(
          'id'      => $tds_packages_post_id,
          'title'   => $tds_packages_query_title,
          'duration' => $tds_durations,
          'price'   => $tds_package_price
        );
      } // end while( $tds_packages_query->have_posts() )
    } // end if( $tds_packages_query->have_posts()

    //handle post titles that start with numbers. Order 10 after 9 instead of after 1
    usort($tds_packages_query_items, function($a, $b) {
        return strnatcasecmp($a['title'], $b['title']);
    });

    //See if this is a tour and sort differently
    //Removed 8/31/2017
    // if ($tdsCustomAttributes['class'] == strtolower('tour')){
    //   //get the list of items to sort
    //   foreach ($tds_packages_query_items as $tds_key => $tds_row) {
    //       $tds_sort_duration[$tds_key]  = $tds_row['duration'];
    //       $tds_sort_price[$tds_key] = $tds_row['price'];
    //   }
    //
    //   array_multisort($tds_sort_duration, SORT_NUMERIC, $tds_sort_price, SORT_NUMERIC, $tds_packages_query_items);
    // } //end if ($tdsCustomAttributes['class'] == strtolower('tour'))



    if( $tds_packages_query_items ) {
      $tdsHTML = '';
      //make the enclosing div
      $tdsHTML .= '<div class="tds-packages">';
      foreach($tds_packages_query_items as $tds_packages_query_item) {

        $tds_post_id = $tds_packages_query_item['id'];

        //Package title
        $tds_package_title = $tds_packages_query_item['title'];

        //Package price
        $tds_package_price = $tds_packages_query_item['price'];

        if($tds_package_price){
          $tds_package_price = number_format($tds_package_price);
        } else {
          $tds_package_price = 0;
        }

        //Package image
        $tds_package_thumbnail = get_the_post_thumbnail_url($tds_post_id, 'full');

        //Package description
        $tds_package_description = get_post_meta($tds_post_id, 'tds_package_description', true);

        // //Check if we will link to a lightbox or new window
        $tds_link_data_lightbox = '';
        $tds_package_link_type =  get_post_meta($tds_post_id, 'tds_package_link_type', true);
        $tds_package_url_target = '';

        $build_gallery_div = false;
        $tds_package_url_target = '_self';
        //Get the saved link
        $tds_package_url = get_post_meta($tds_post_id, 'tds_package_page_url', true);
        if($tds_package_link_type == 'blank') {
          // $tds_package_pdf_itinerary = get_post_meta($tds_post_id, 'tds_package_pdf_itinerary', true);
          // $tds_package_url = $tds_package_pdf_itinerary['url'];
          $tds_package_url_target = '_blank';
        } elseif($tds_package_link_type == 'lightbox_iframe') {
          $tds_link_data_lightbox = 'data-featherlight="iframe"';
        } elseif($tds_package_link_type == 'lightbox_gallery') {
          $tds_package_url = '#gallery-' . $tds_post_id;
          $build_gallery_div = true;
        } else {
          //check if it's a PDF
          if (substr($tds_package_url,-3)=="pdf") {
            $tds_package_url_target = '_blank';
          } else {
            $tds_package_url_target = '_self';
          }
        }//end if($tds_package_link_type[0] == 'pdf')



        //Get the link text, if any
        $tds_package_link_text = get_post_meta($tds_post_id, 'tds_package_page_link_text', true);


        //GET THE DESTINATIONS
        $tds_destination_names = array();
        $tds_packages_destination = get_the_terms($tds_post_id, 'tds-packages-destination');
        if($tds_packages_destination):foreach($tds_packages_destination as $tds_destination):
          $tds_destination_names[] = $tds_destination->name;
        endforeach; endif;
        $tds_destinations = implode(', ', $tds_destination_names);


        //GET THE DURATIONS
        $tds_duration_names = array();
        $tds_packages_duration = get_the_terms($tds_post_id, 'tds-packages-duration');
        if($tds_packages_duration):foreach($tds_packages_duration as $tds_duration):
          $tds_duration_names[] = $tds_duration->name;
        endforeach; endif;
        $tds_durations = implode(', ', $tds_duration_names);


        //GET THE TYPES
        $tds_type_names = array();
        $tds_packages_type = get_the_terms($tds_post_id, 'tds-packages-type');
        if($tds_packages_type):foreach($tds_packages_type as $tds_type):
          $tds_type_names[] = $tds_type->name;
        endforeach; endif;
        $tds_types = implode(', ', $tds_type_names);


        //GET THE LOCATIONS
        $tds_location_names = array();
        $tds_packages_location = get_the_terms($tds_post_id, 'tds-packages-location');
        if($tds_packages_location):foreach($tds_packages_location as $tds_location):
          $tds_location_names[] = $tds_location->name;
        endforeach; endif;
        $tds_locations = implode(', ', $tds_location_names);


        //GET THE TYPE OF ITEM - TOUR, ACTIVITY or LOCATION
        //Check what kind of post this is
        $tds_package_post_type =  get_post_meta($tds_post_id, 'tds_package_post_type', true);

         //Set the header color
        $tds_package_color;
        //BUILD THE HEADER LINE DEPENDING UPON IF THE LAYOUT
        if($tdsCustomAttributes['style'] == 'top') {
          $tds_header_line = $tds_package_title;
          $tds_sub_header_line = '';
          $tds_click_to_view_text = $tds_package_title;
        } else if ($tdsCustomAttributes['style'] == 'side') {
          $tds_header_line = $tds_package_title;
          $tds_sub_header_line = '';
          $tds_click_to_view_text = 'View ' . $tds_package_title;
        }


        // COLORS
         //  IS A TOUR OR ACTIVITY
        $tds_meta = '';
        if($tds_package_post_type == 'Destination') {
          $tds_package_color = get_option('tds_packages_destination_color_option');

        } elseif($tds_package_post_type == 'Activity') {
          $tds_package_color = get_option('tds_packages_activity_color_option');
          $tds_click_to_view_text = 'Read More About ' . $tds_package_title;
        } elseif($tds_package_post_type == 'Tour') {
          $tds_package_color = get_option('tds_packages_tour_color_option');
          $tds_click_to_view_text = 'View Detailed Itinerary';
          $tds_meta = '<span class="tds-meta-name">' . $tds_destinations . ': ' . $tds_durations .' from </span> <span class="tds-meta-price-combined"> <strong>&nbsp;$' . $tds_package_price . 'pp</strong></span>';
          $tds_meta = '<span class="tds-meta-name">' . $tds_destinations . ': ' . $tds_durations .' from $' . $tds_package_price . 'pp</span>';
          // $tds_meta = '<span class="tds-meta-name">' . $tds_destinations . ': ' . $tds_durations .' from </span> <span class="tds-meta-price-combined"><span class="fa-stack fa-lg"><i class="fa fa-circle-o fa-stack-2x"></i><i class="fa fa-stack-1x fa-usd" aria-hidden="true"></i></span>' . '<span class="tds-meta-price">' . $tds_package_price . '</span></span>';

        } elseif($tds_package_post_type == 'Location') {
          $tds_package_color = get_option('tds_packages_location_color_option');
          $tds_click_to_view_text = 'Read More About ' . $tds_locations;
        } elseif($tds_package_post_type == 'Other') {
          $tds_package_color = get_option('tds_packages_other_color_option');
          $tds_meta = '<span class="tds-meta-name">Location: ' . $tds_locations . '</span>';
        }

        if(!empty($tds_package_link_text) || $tds_package_link_text != '') {
          $tds_click_to_view_text =  $tds_package_link_text ;
        }

         //Add wrappers to the link and the arrow
        $tds_click_to_view_text = '<span class="tds-ctv-link">' . $tds_click_to_view_text . '</span><span class="tds-link-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle-o fa-stack-2x"></i><i class="fa fa-stack-1x fa-arrow-right" aria-hidden="true"></i></span></span>';

          $tdsHTML .= '<div class="tds-package-item display-' . $tdsCustomAttributes['style'] . ' columns-' . $tdsCustomAttributes['cols'] .' desc-' . $tdsCustomAttributes['desc'] . '">';

          $tdsHTML .= '<div class="tds-item-img">';

          $tdsHTML .= '<a class="clickable-link" href="' . $tds_package_url . '" target="' . $tds_package_url_target . '"' . $tds_link_data_lightbox . '></a>';

            $tdsHTML .= '<div class="tds-item-img-bg" style="background-image: url(' . $tds_package_thumbnail. ')">';

              $tdsHTML .= get_the_post_thumbnail($tds_post_id, 'large');

              $tdsHTML .= '</div> <!-- end tds-item-img-bg-->';
            $tdsHTML .= '<div class="tds-click-overlay">';
              $tdsHTML .= '<a href="' . $tds_package_url . '" target="' . $tds_package_url_target . '"' . $tds_link_data_lightbox . '>' . $tds_click_to_view_text . '</a>';
            $tdsHTML .= '</div><!-- end tds-click-overlay-->';

          $tdsHTML .= '</div> <!-- end tds-item-img-->';

        if($tdsCustomAttributes['desc'] == 'yes') {
          $tdsHTML .= '<div class="tds-item-desc">';
            $tdsHTML .= '<div class="tds-combined-title" style="background-color: ' . $tds_package_color . ';"><h3>';
            $tdsHTML .= '<a href="' . $tds_package_url . '" target="' . $tds_package_url_target . '"' . $tds_link_data_lightbox . '>' .         $tds_header_line . '</a></h3></div>';
              $tdsHTML .= '<div class="tds-description"><span>';
              $tdsHTML .= $tds_package_description . '</span>';
              // $tdsHTML .= '</p></div>';
              // $tdsHTML .= '<div class="tds-meta"><p>';
              $tdsHTML .= '<div class="tds-meta">' . $tds_meta;
              $tdsHTML .= '</div></div>';

          $tdsHTML .= '</div> <!-- end tds-item-desc-->';
        }
        
        if($build_gallery_div) {
          $tdsHTMLGalleryPager = '<div class="bx-pager">';

          
          $tdsHTML .= '<div id="gallery-' . $tds_post_id .'" class="gallery" style="display: block;">';

          // get the gallery photos
          $gallery_photo_ids = get_post_meta($tds_post_id, 'tds_package_photos', true);
          $tdsHTML .= '<ul class="bxslider">';

          $gallery_photo_counter = 0; 

          foreach($gallery_photo_ids as $gallery_photo_id) {
            // retrieve the URL of the thumb 
            // retrieve the URL of the full size
            $gallery_image_full = wp_get_attachment_image($gallery_photo_id, 'full');

            // retrieve the URL of the full size
            $gallery_image_thumb_url = wp_get_attachment_image_src($gallery_photo_id, 'thumb')[0];
            // $tdsHTML .= '<a href="' . $gallery_image_url . '">' . $gallery_image_thumb . '</a>';

            $tdsHTML .= '<li><p>Photo number ' . $gallery_photo_counter . '</p>' . $gallery_image_full . '</li>';

            $tdsHTMLGalleryPager .= '<a data-slide-index="' . $gallery_photo_counter . '" href="#" style="display: inline-block; max-width:100px;">' . $gallery_image_full . '</a>';

            ++$gallery_photo_counter;
          } // end foreach 

          $tdsHTML .= '</ul>';

          $tdsHTMLGalleryPager .= '</div>';

          $tdsHTML .= $tdsHTMLGalleryPager;

          $tdsHTML .= '</div>';
        }

        //Close outer item HTML
        $tdsHTML .= '</div> <!-- end tds-package-item -->';

      } // end foreach($tds_packages_query_items as $tds_packages_query_item)

      //close the outer div
      $tdsHTML .= '</div> <!-- end tds-packages -->';

    }//end if( !empty($tds_packages_query_items)


    return $tdsHTML;
  } //end public function add_tds_packages_shortcode


  public function wl ( $log )  {
    if ( true === WP_DEBUG ) {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }
  } // end public function wl 
} //end class
  $tds_packages_shortcode = new TDS_Packages_Shortcode();
  $tds_packages = new TDS_Packages();
