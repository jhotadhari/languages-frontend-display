<?php
/*
	grunt.concat_in_order.declare('lfd_get_languages');
	grunt.concat_in_order.require('init');
*/


// find taxs and exclude some
function lfd_get_languages(){
	global $q_config;
	
	$langs = $q_config['enabled_languages'];
	
	$langs_arr = array();
	foreach ( $langs as $lang ){
		$langs_arr[$lang] = $q_config['language_name'][$lang];
	}
	
	return $langs_arr;
}

?>