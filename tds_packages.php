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
//Include the shortcode class
require_once( dirname( __FILE__ ) . '/tds_packages_shortcode.php');
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
    // ajax for dismissing the nginx error

    
    register_activation_hook( __FILE__, array($this, 'tds_default_settings' ));

  }


  //Enqueue the styles
  public function register_tds_packages_styles_scripts() {

    wp_register_style('tds-packages', plugins_url('css/tds-packages.css', __FILE__));
    wp_enqueue_style('tds-packages');

    wp_register_style('tds-featherlight', plugins_url('lib/featherlight/featherlight.min.css', __FILE__));
    wp_enqueue_style('tds-featherlight');


    wp_register_style('tds-magnific-popup', plugins_url('lib/magnific-popup/magnific-popup.css', __FILE__));
    wp_enqueue_style('tds-magnific-popup');


    wp_register_style('tds-flexslider', plugins_url('lib/flexslider/flexslider.css', __FILE__));
    wp_enqueue_style('tds-flexslider');


    // wp_register_style('tds-pretty-photo', plugins_url('lib/pretty-photo/css/prettyPhoto.css', __FILE__));
    // wp_enqueue_style('tds-pretty-photo');


    wp_register_script('tds-packages', plugins_url('js/tds-packages.js', __FILE__), array('jquery') );
  
    wp_localize_script( 'tds-packages', 'ajaxfrontendurl',
    array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

    wp_enqueue_script('tds-packages');

    wp_register_script('tds-featherlight', plugins_url('lib/featherlight/featherlight.min.js', __FILE__));
    wp_enqueue_script('tds-featherlight');


    wp_register_script('tds-magnific-popup', plugins_url('lib/magnific-popup/jquery.magnific-popup.min.js', __FILE__));
    wp_enqueue_script('tds-magnific-popup');


    // wp_register_script('tds-pretty-photo', plugins_url('lib/pretty-photo/jsjquery.prettyPhoto.js', __FILE__));
    // wp_enqueue_script('tds-pretty-photo');


    // wp_register_script('tds-jssor', plugins_url('lib/jssor/jssor.slider.min.js', __FILE__));
    // wp_enqueue_script('tds-jssor');

    wp_register_script('tds-flexslider', plugins_url('lib/flexslider/jquery.flexslider-min.js', __FILE__));
    wp_enqueue_script('tds-flexslider');

  } //end public function register_tds_packages_styles

  //Enqueue the scripts for the post editor
  public function register_admin_tds_packages_scripts() {
    wp_register_script('tds-packages-admin-js', plugins_url('js/tds-packages-admin.js', __FILE__));
    wp_enqueue_script('tds-packages-admin-js');
  } //end public function register_tds_packages_styles



  public function tds_default_settings() {
    if(!get_option('tds_packages_activity_color_option')): add_option( 'tds_packages_activity_color_option', '#2d75b6'); endif;
    if(!get_option('tds_packages_destination_color_option')): add_option( 'tds_packages_destination_color_option', '#2d75b6'); endif;
    if(!get_option('tds_packages_location_color_option')): add_option( 'tds_packages_location_color_option', '#2d75b6'); endif;
    if(!get_option('tds_packages_other_color_option')): add_option( 'tds_packages_other_color_option', '#2d75b6'); endif;
    if(!get_option('tds_packages_tour_color_option')): add_option( 'tds_packages_tour_color_option', '#2d75b6'); endif;

  } //end function tds_default_settings()

  public function write_log ( $log )  {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
  

} //end class TDS_Packages
$tds_packages = new TDS_Packages();
