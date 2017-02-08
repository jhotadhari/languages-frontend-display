<?php
/*
	grunt.concat_in_order.declare('lfd_recursive_unset');
	grunt.concat_in_order.require('init');
*/

function lfd_recursive_unset(&$array, $unwanted_key) {
	unset($array[$unwanted_key]);
	foreach ($array as &$value) {
		if (is_array($value)) {
			lfd_recursive_unset($value, $unwanted_key);
		}
	}
}

?>