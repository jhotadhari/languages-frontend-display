<?php
/*
	grunt.concat_in_order.declare('init');
*/


// load_plugin_textdomain
function lfd_load_textdomain(){
	
	load_plugin_textdomain(
		'lfd-text',
		false,
		dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
	);
}
add_action( 'plugins_loaded', 'lfd_load_textdomain' );






?>