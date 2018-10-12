Dusty Sun WP CPT API
================

A class to make it easier to create custom post types in WordPress

THIS FILE NEEDS TO BE UPDATED WITH ALL OPTIONS. 

Features
--------
* **Feature 1.**    

  Description

Getting Started
---------------

### Adding the class to your theme

Require the file:
```
require( dirname( __FILE__ ) . '/lib/ds_wp_cpt_api/ds_wp_cpt_api.php');
```

## Notes

Notes

### Instantiation

Example:
```
$my_api_settings = array(
  'json_file' => plugin_dir_path( __FILE__ ) . '/plugin-options.json',
  'register_settings' => true
);

$my_settings_page = new My_DustySun_WP_CPT_API($my_api_settings);
```

Field Options

Readonly - set to true or false 

Class

You can have groups toggled on and off. 

For the radio button, add 'class' => 'toggle_blankname'.

For the inputs, add 'class' => 'specificradiovalue toggle_blankname'. If the radio button value is selected that matches the one shown for the input, it will be shown.


### Changelog
#### 1.3.5 - 2018-10-11 
* Added gallery image type.
* Fixed errors in the radio and checkbox types.

#### 1.3.4 - 2018-08-30
* Updated the read only options for most fields.