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