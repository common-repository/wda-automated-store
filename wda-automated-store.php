<?php

/*
* Plugin Name:          WDA Automated Store
* Description:          Automatically adjust product visibility in WooCommerce based on stock status with customizable settings.
* Plugin URI:           https://webdevadvisor.com/product/wda-automated-store/
* Version:              1.0.0
* Requires at least:    6.2
* Requires PHP:         5.6.20
* License:              GPLv2 or later
* License URI:          https://www.gnu.org/licenses/gpl-2.0.html
* Author:               Web Dev Advisor
* Author URI:           https://webdevadvisor.com/
* Text Domain:          wda-automated-store
* Requires Plugins:     woocommerce
*/



/*-------------------------------------------
*  Exit if accessed directly
*-------------------------------------------*/
defined( 'ABSPATH' ) || exit;



/*-------------------------------------------
*  Plugin Root Path
*-------------------------------------------*/
define( 'WDAAUTOS_ROOT_DIR', plugin_dir_path( __FILE__ ) );



/*-------------------------------------------
*  Plugin Root URL
*-------------------------------------------*/
define( 'WDAAUTOS_ROOT_URL', plugin_dir_url( __FILE__ )) ;   



/*-------------------------------------------
*  Classes
*-------------------------------------------*/
require_once( WDAAUTOS_ROOT_DIR . 'classes/field-templates.php' );
require_once( WDAAUTOS_ROOT_DIR . 'classes/callback.php' );
require_once( WDAAUTOS_ROOT_DIR . 'classes/admin-menu.php' );


/*-------------------------------------------
*  Funtions
*-------------------------------------------*/
require_once( WDAAUTOS_ROOT_DIR . 'functions/activation-actions.php' );



/*-------------------------------------------
*  Modules
*-------------------------------------------*/
require_once( WDAAUTOS_ROOT_DIR . 'modules/class-stock-visibility.php' );


/*-------------------------------------------
*  Plugin Activation Actions
*-------------------------------------------*/
register_activation_hook( WDAAUTOS_ROOT_DIR . 'wda-automated-store.php', 'wdaautos_activation_actions' );