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
		$langs_arr[$lang] = $q_config['language_name'][$lang] . ( $q_config['default_language'] == $lang ? ' ' . __('(default language)','lfd-text') : '' );
	}

	
	return $langs_arr;
}

?>
<?php
/*
	grunt.concat_in_order.declare('lfd_options_page');
	grunt.concat_in_order.require('init');
*/
/**
 * CMB2 Plugin Options
 * @version 0.1.0
 */
class Lfd_admin_options {

	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'lfd_options';

	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'lfd_option_metabox';

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var Lfd_admin_options
	 */
	protected static $instance = null;

	/**
	 * Returns the running object
	 *
	 * @return Lfd_admin_options
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	protected function __construct() {
		// Set our title
		$this->title = __( 'Languages Frontend Display', 'lfd-text' );
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );

		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
		add_action( 'cmb2_after_options-page_form_' . $this->metabox_id, array( $this, 'enqueue_style'), 10, 2 );
		
	}


	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_submenu_page( 'options-general.php', $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	public function add_options_page_metabox() {
		global $lfd_defaults;
		$defaults = $lfd_defaults->get_default($this->key);
		
		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );
		
		$cmb->add_field( array(
			'desc' => 
				'<span class="font-initial font-red">'
				. __('detect_browser_language has to be false.','lfd-text')
				. '</span> '
				. '<span class="font-initial">'
				. __('This plugin won\'t do anything, until the qTranslate-X option "Detect Browser Language" is set to false','lfd-text')
				. '</span> ',
			'type' => 'title',
			'id'   => 'if_browser_language',
			'show_on_cb'   => 'lfd_options_page_show_on_cb_if_browser_language',
		) );
		
		$cmb->add_field( array(
			'desc' => 
				'<span class="font-initial font-red">'
				. __('hide_default_language has to be false.','lfd-text')
				. '</span> '                                                    
				. '<span class="font-initial">'
				. __('This plugin won\'t do anything, until the qTranslate-X option "URL Modification Mode -> Hide URL language information for default language" is set to false','lfd-text')
				. '</span> ',
			'type' => 'title',
			'id'   => 'if_hide_default_language',
			'show_on_cb'   => 'lfd_options_page_show_on_cb_if_hide_default_language',
		) );
		
		$cmb->add_field( array(
			'desc' => 
				'<span class="font-initial font-red">'
				. __('default language is disabled.','lfd-text')
				. '</span> '
				. '<span class="font-initial">'
				. __('This plugin won\'t do anything, until you enable the default language again.','lfd-text')
				. '</span> ',
			'type' => 'title',
			'id'   => 'if_default_language_disabled',
			'show_on_cb'   => 'lfd_options_page_show_on_cb_if_default_language_disabled',
		) );

		$cmb->add_field( array(
			'desc' => 
				'<span class="font-initial font-red">'
				. __('all languages are disabled.','lfd-text')
				. '</span> '
				. '<span class="font-initial">'
				. __('come on, don\'t do that!','lfd-text')
				. '</span> ',
			'type' => 'title',
			'id'   => 'if_all_languages_disabled',
			'show_on_cb'   => 'lfd_options_page_show_on_cb_if_all_languages_disabled',
		) );
		
		$cmb->add_field( array(
			'name'    => __( 'Enabled Languages on Frontend', 'lfd-text' ),
			'id'      => 'languages_frontend',
			'type'    => 'multicheck',
			'default' => $defaults['languages_frontend'],
			'options_cb' => 'lfd_get_languages',
		) );

	}
	
	public function enqueue_style( $post_id, $cmb ) {
		wp_enqueue_style( 'lfd_options_page', plugin_dir_url( __FILE__ ) . 'css/lfd_options_page.css', false );
	}

	/**
	 * Register settings notices for display
	 *
	 * @since  0.1.0
	 * @param  int   $object_id Option key
	 * @param  array $updated   Array of updated fields
	 * @return void
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}

		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'lfd-text' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}

		throw new Exception( 'Invalid property: ' . $field );
	}

}

/**
 * Helper function to get/return the Lfd_admin_options object
 * @since  0.1.0
 * @return Lfd_admin_options object
 */
function lfd_admin() {
	return Lfd_admin_options::get_instance();
}


function lfd_options_page_add_defaults(){
	global $lfd_defaults;
	global $q_config;
	
	$lfd_defaults->add_default( array(
		lfd_admin()->key => array(
			'languages_frontend' => $q_config['enabled_languages']
			),
	));
}
add_action( 'admin_init', 'lfd_options_page_add_defaults', 2 );
add_action( 'init', 'lfd_options_page_add_defaults', 2 );
	

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function lfd_get_option( $key = '', $default = null ) {
	global $lfd_defaults;
	
	if ( $default == null ){
		$default = $lfd_defaults->get_default( lfd_admin()->key )[$key];
	}
	
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( lfd_admin()->key, $key, $default );
	}

	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( lfd_admin()->key, $key, $default );

	
	if ( gettype($opts) != 'array' ) return false;
	
	$val = $default;

	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}

// Get it started
lfd_admin();



// show_on_cb
function lfd_options_page_show_on_cb_if_browser_language(){
	global $q_config;
	return $q_config['detect_browser_language'] == 1;
}
function lfd_options_page_show_on_cb_if_hide_default_language(){
	global $q_config;
	return $q_config['hide_default_language'] == 1;
}
function lfd_options_page_show_on_cb_if_default_language_disabled(){
	global $q_config;
	$languages_frontend = lfd_get_option('languages_frontend');
	if (! $languages_frontend ) return false;
	return in_array( $q_config['default_language'], $languages_frontend ) ? false : true;
}
function lfd_options_page_show_on_cb_if_all_languages_disabled(){
	$languages_frontend = lfd_get_option('languages_frontend');
	if (! $languages_frontend || gettype($languages_frontend) != 'array') return false;
	return count($languages_frontend) == 0;
}
?>
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