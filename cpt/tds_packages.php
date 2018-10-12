<?php
/* Custom post type for wpla_licenses */
// namespace DustySun\TDS_Packages\CPT;
/* Custom post type for wpla_licenses */
namespace TDSPackages\CPT;
use \Dusty_Sun\WP_CPT_API\v1_3 as DSWPCPTAPI;

class TDSPackagesCPT extends DSWPCPTAPI\CPTBuilder {

  public function __construct() {

    //Set the custom post type first
    $this->set_custom_post_type('tds_packages');

    parent::__construct();

    // Register the CPT
    add_action( 'init', array( $this, 'register_tds_packages' ) );

    // Register the taxonomies
    add_action( 'init', array( $this, 'tds_packages_register_taxonomies' ));

    
    
    // Custom JS for the CPT in the admin area
    // add_action( 'admin_head', array($this, 'wpla_licenses_admin_scripts') );

   

    // Add the pages to the appropriate spot in the menu
    // add_action('admin_menu', array($this, 'tds_packages_admin_menu'), 100);



    // Register the custom title
    add_filter( 'enter_title_here', array($this, 'tds_packages_change_title_text') );

    // Add the custom columns to the post field view
    add_filter('manage_edit-tds_packages_columns', array($this, 'set_custom_edit_tds_packages_columns'));

    // Fill the custom columns with data from  postmeta
    add_action('manage_tds_packages_posts_custom_column', array($this, 'custom_tds_packages_column'), 10, 2);

    //Allow custom column sorting
    add_filter('manage_edit-tds_packages_sortable_columns', array($this, 'custom_tds_packages_sortable_columns'));
    add_filter('request', array($this, 'tds_package_post_type_orderby'));


    // Add the meta box fields
    // add_action('add_meta_boxes_' . $this->custom_post_type, array($this,'add_update_server_links_metabox'));
    // add_action('add_meta_boxes_' . $this->custom_post_type, array($this,'add_woocommerce_info_metabox'));
  } // end function __construct

  public function register_tds_packages() {

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
  } //end private function register_wpla_licenses


//   public function tds_packages_admin_menu() {
//     add_submenu_page(
//         'edit.php?post_type=tds_packages',
//         __('Settings', 'tds_packages'),
//         __('Settings', 'tds_packages'),
//         'manage_options',
//         'tds_packages_menu',
//         array($this, 'tds_packages_menu_options'));
//   } //end function tds_packages_admin_menu

  // replaces abstract function in library 
  public function define_meta_box_fields($post_id = null) {

    //Add the fields that will go beneath the main post area
    $meta_box_fields[] = array(
        'section_name' => 'tds_packages_details',
        'title' => 'Package Details',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
        array(
            'label' => 'Post Type:',
            'desc' => '',
            'id' => 'tds_package_post_type',
            'type' => 'radio',
            'options' => array(
                'Activity' => 'Activity',
                'Destination' => 'Destination',
                'Location' => 'Location',
                'Tour' => 'Tour',
                'Other' => 'Accommodation',
            ),
        ),
        array(
            'label' => 'Link Type:',
            'desc' => '',
            'id' => 'tds_package_link_type',
            'type' => 'radio',
            'class' => 'toggle_link_type',
            'options' => array(
                'self' => 'Same Window',
                'blank' => 'New Window',
                'lightbox_iframe' => 'Lightbox iframe',
                'lightbox_gallery' => 'Lightbox Gallery'
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
            'label' => 'Link Location:',
            'desc' => '',
            'id' => 'tds_package_page_url',
            'type' => 'text',
            'class' => 'self blank lightbox_iframe toggle_link_type',
            'default' => ''
        ),
        array(
            'label' => 'Link Text:',
            'desc' => '',
            'id' => 'tds_package_page_link_text',
            'type' => 'text',
            'default' => ''
        ),
        array(
          'label' => 'Photos:',
          'desc' => 'Hold shift to select multiple items',
          'id' => 'tds_package_photos',
          'type' => 'gallery',
          'class' => 'lightbox_gallery toggle_link_type',
          'default' => ''
        ),
        array(
            'label' => 'Package Description:',
            'desc' => '',
            'id' => 'tds_package_description',
            'type' => 'texteditor',
            'default' => ''
        ),
      )
    );
    //Add the fields that will go on the side of the main post area

    $meta_box_fields[] = array(
        'section_name' => 'tds_packages_details_side',
        'title' => 'Package Price',
        'context' => 'side',
        'priority' => 'low',
        'fields' => array(
        array(
            'label' => 'Price:',
            'desc' => '',
            'id' => 'tds_package_price',
            'type' => 'text',
            'default' => ''
        ),
        )
    );
    
    return $meta_box_fields;
  } // end function define_meta_box_fields


    //Change the "Enter Title Here" text
    public function tds_packages_change_title_text( $title ){
        $addPostScreeen = get_current_screen();
 
      if  ( 'tds_packages' == $addPostScreeen->post_type ) {
             $title = 'Enter Package Name';
        }
 
        return $title;
   } //end function tds_packages_change_title_text( $title )



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
  } //end function tds_packages_post_type_orderby


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

  } //end function tds_packages_register_taxonomies



  /**
   * Add a bit of style
   */
  public function wpla_licenses_admin_scripts() {
    // only load these scripts on the appropriate CPT edit screen
    $screen = get_current_screen();
    if( is_object($screen) && 'wpla_licenses' == $screen->post_type ) {
    ?>
    <style>
      body.post-type-wpla_licenses #post-body-content {
        margin-bottom: 0;
      }
    </style>
  	<script>
  		jQuery( document ).ready( function( $ ) {

        var license_validity_measurement = $('#wpla_license_validity\\[measurement\\]');
        var license_validity_term = $('#wpla_license_validity\\[term\\]');

        // set the number box to grayed out if forever is selected
        if(license_validity_measurement.length) {
          
          enable_disable_software_license_validity_term($(license_validity_measurement).val().toLowerCase());

          // change it if the menu is changed
          $(license_validity_measurement).change(function(){
            enable_disable_software_license_validity_term(this.value);
          });

          function enable_disable_software_license_validity_term(select_value) {
            if(select_value == 'forever'){
              //disable the input box
              $(license_validity_term).prop("disabled", true);
              $(license_validity_term).val('');
              $(license_validity_term).attr('placeholder', '');
            } else {
              $(license_validity_term).prop("disabled", false);
              //check if there is no value
              if($(license_validity_term).val() < 1) {
                $(license_validity_term).val('1');
                $(license_validity_term).css({'border': '1px solid red'});
              }
            }
          } //end function enable_disable_software_license_validity_term
        } // end if length
  		});
	   </script>
     <style>
        th.manage-column.column-wpla_license_num_of_installations,
        td.wpla_license_num_of_installations.column-wpla_license_num_of_installations {
          width: 30px;
        }
        th.manage-column.column-wpla_license_type,
        td.wpla_license_type.column-wpla_license_type {
          width: 105px;
        }
        th.manage-column.column-wpla_license_order_number,
        td.wpla_license_order_number.column-wpla_license_order_number {
          width: 70px;
        }
        td.wpla_license_num_of_installations.column-wpla_license_num_of_installations,
        td.wpla_license_order_number.column-wpla_license_order_number {
          text-align: center;
        }
        td.date.column-date,
        td.wpla_license_type.column-wpla_license_type {
          font-size: 12px;
        }
        @media (max-width: 1200px) {
          th.manage-column.column-wpla_license_order_number,
          td.wpla_license_order_number.column-wpla_license_order_number {
            display:none;
          }
          th.manage-column.column-wpla_product_name,
          td.wpla_product_name.column-wpla_product_name {
            display:none;
          }
        }
        @media (max-width: 1400px) { 
          th.manage-column.column-wpla_license_manual_updatepkg,
          td.wpla_license_manual_updatepkg.column-wpla_license_manual_updatepkg {
            display:none;
          }
          th.manage-column.column-wpla_license_website_url,
          td.wpla_license_website_url.column-wpla_license_website_url {
            display:none;
          }
        }
     </style>
     <?php 
    } // end if
  } // end function wpla_licenses_admin_scripts()

} // end class TDSPackagesCPT
