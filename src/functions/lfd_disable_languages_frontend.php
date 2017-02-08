<?php
/*
	grunt.concat_in_order.declare('lfd_disable_languages_frontend');
	grunt.concat_in_order.require('init');
*/

function lfd_disable_languages_frontend () {
	
	global $wp;
	global $q_config;	
	
	$languages_frontend = lfd_get_option('languages_frontend');
	
	if (! $languages_frontend ) return;
	
	$languages_disable = array_diff ( $q_config['enabled_languages'], $languages_frontend );
	
	$current_lang = qtranxf_getLanguage();

	if (
		is_admin()													// we need to be on frontend
		|| in_array( $q_config['default_language'], $languages_disable ) 	// default language can't be disabled
		|| $q_config['detect_browser_language'] == 1				// detect_browser_language has to be false
		|| $q_config['hide_default_language'] == 1					// hide_default_language has to be false
		) return;

	// disable_lang in global q_config
	foreach ( $languages_disable as $lang_k => $lang_v ){
		$q_config['enabled_languages'] = array_diff($q_config['enabled_languages'], array($lang_v));
		lfd_recursive_unset($q_config, $lang_v);
	}
		
	// redirect
	if ( in_array( $current_lang, $languages_disable ) ){			
		$current_url_default_lang = qtranxf_convertURL( home_url(add_query_arg(array(),$wp->request)), $q_config['default_language'] );
		wp_redirect( $current_url_default_lang, 301 );
		exit();	
	}

}
add_action( 'init', 'lfd_disable_languages_frontend', 1 );

?>