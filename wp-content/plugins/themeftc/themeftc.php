<?php 
/**
 * Plugin Name: ThemeFTC
 * Plugin URI: http://themeftc.com
 * Description: Add shortcodes and custom post types for ThemeFTC's theme
 * Version: 1.1.0
 * Author: ThemeFTC Team
 * Author URI: http://themeftc.com
 */
class Themeftc_Plugin{

	function __construct(){
		$this->include_files();
	}
	
	function include_files(){
		$file_names = array('register', 'shortcodes', 'banner', 'brands_slider', 'footer', 'product_deals', 'single_image', 'testimonial');
		foreach( $file_names as $file_name ){
			$file = plugin_dir_path( __FILE__ ) . '/includes/' . $file_name . '.php';
			if( file_exists($file) ){
				require_once($file);
			}
		}
	}
	
}
new Themeftc_Plugin();

?>