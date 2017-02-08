<?php
/*
	grunt.concat_in_order.declare('Lfd_defaults');
	grunt.concat_in_order.require('init');
*/


class Lfd_defaults {


	public $defaults = array();

	public function add_default( $arr ){
		$defaults = $this->defaults;
		$this->defaults = array_merge( $defaults , $arr);
	}
	
	public function get_default( $key ){
		if ( array_key_exists($key, $this->defaults) ){
			return $this->defaults[$key];

		}
			return null;
	}


}



function lfd_init_defaults(){
	global $lfd_defaults;
	
	$lfd_defaults = new Lfd_defaults();
	
	// $defaults = array(
	// 	// silence ...
	// );
	
	// $lfd_defaults->add_default( $defaults );	
}
add_action( 'admin_init', 'lfd_init_defaults', 1 );
add_action( 'init', 'lfd_init_defaults', 1 );



?>