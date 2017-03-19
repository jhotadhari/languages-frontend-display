<?php
/*
	grunt.concat_in_order.declare('init');
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// load_plugin_textdomain
function lfd_load_textdomain(){
	
	$loaded = load_plugin_textdomain(
		'lfd-text', 
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);

}
add_action( 'init', 'lfd_load_textdomain' );


?>