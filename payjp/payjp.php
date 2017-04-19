<?php
/*
Plugin Name: WordPress Pay.jp plugin
Description: Creates Pay.jp widget, thanks page
Version:     1.0.0
Author:      Takuya Fujimura
*/
defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

class payjp{
  public function __construct(){
  
    register_activation_hook(__FILE__, array($this,'plugin_activate')); //activate hook
    register_deactivation_hook(__FILE__, array($this,'plugin_deactivate')); //deactivate hook
  
  }
}

//require 
require_once plugin_dir_path(__FILE__) . 'vendor/payjp-php/init.php';
//include shortcodes
include(plugin_dir_path(__FILE__) . 'inc/payjp_shortcode.php');
//include widgets
include(plugin_dir_path(__FILE__) . 'inc/payjp_widget.php');
