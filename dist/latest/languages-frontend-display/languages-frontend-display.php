<?php
/*
Plugin Name: Languages Frontend Display
Plugin URI: http://waterproof-webdesign.info/languages-frontend-display
Description: qTranslate-X extension. Enable/disable languages on frontened
Version: 0.0.2
Author: jhotadhari
Author URI: http://waterproof-webdesign.info/
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: lfd-text
Domain Path: /languages
Tags: qTranslate,qTranslate-x,language,hide,disable,frontend
*/

/*
	grunt.concat_in_order.declare('_plugin_info');
*/

?>
<?php
/*
	grunt.concat_in_order.declare('init');
	grunt.concat_in_order.require('_plugin_info');
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<?php
/*
	grunt.concat_in_order.declare('cmb2_init');
	grunt.concat_in_order.require('init');
*/



//cmb2 init
function lfd_cmb2_init() {
	include_once plugin_dir_path( __FILE__ ) . 'includes/webdevstudios/cmb2/init.php';
}
add_action('admin_init', 'lfd_cmb2_init', 3);
add_action('init', 'lfd_cmb2_init', 3);




//cmb2-qtranslate init
function lfd_cmb2_init_qtranslate() {
		
	wp_register_script('cmb2_qtranslate_main', plugin_dir_url( __FILE__ ) . '/includes/jmarceli/integration-cmb2-qtranslate/dist/scripts/main.js', array('jquery'));
	wp_enqueue_script('cmb2_qtranslate_main');
}
add_action('admin_enqueue_scripts', 'lfd_cmb2_init_qtranslate');
//add_action('wp_enqueue_scripts', 'lfd_cmb2_init_qtranslate');



?>
<?php
/*
	grunt.concat_in_order.declare('load_functions');
	grunt.concat_in_order.require('init');
	grunt.concat_in_order.require('cmb2_init');
*/


function lfd_plugin_activate(){
    if ( ! is_plugin_active( 'qtranslate-x/qtranslate.php' ) ) {
        wp_die( lfd_get_admin_notice() . '<br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }
}
register_activation_hook( __FILE__, 'lfd_plugin_activate' );

function lfd_load_functions(){
	if ( class_exists( 'QTX_Translator' ) ){
		include_once(plugin_dir_path( __FILE__ ) . 'functions.php');
	} else {
		add_action( 'admin_notices', 'lfd_print_admin_notice' );
	}
}
add_action( 'plugins_loaded', 'lfd_load_functions' );

function lfd_print_admin_notice() {
	echo '<strong><span style="color:#f00;">' . lfd_get_admin_notice() . '</span></strong>';
};

function lfd_get_admin_notice() {
	$plugin_title = 'Languages Frontend Display';
	$parent_plugin_title = 'qTranslate-X';
	return sprintf(esc_html__( '"%s" plugin requires "%s" plugin to be installed and activated!', 'wpwq' ), $plugin_title, $parent_plugin_title);
}


?>