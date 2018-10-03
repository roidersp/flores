<?php 
/**
 * Plugin Name: FTC Importer
 * Plugin URI: http://themeftc.com
 * Description: Import demo content ThemeFTC's theme
 * Version: 1.0.0
 * Author: ThemeFTC
 * Author URI: http://themeftc.com
 */

if( !class_exists('FTC_Importer') ){
	class FTC_Importer{

		function __construct(){
			/* Register js, css */
			add_action('admin_enqueue_scripts', array($this, 'ftc_register_scripts'));
			
			/* Register Menu Page */
			add_action('admin_menu', array($this, 'ftc_menu_page'));
			
			/* Register ajax action */			
			add_action( 'wp_ajax_ftc_import_revslider', array($this, 'import_revslider') );
			add_action( 'wp_ajax_ftc_import_theme_options', array($this, 'import_theme_options') );
			add_action( 'wp_ajax_ftc_mmm_options_backup', array($this, 'mmm_options_backup') );
			add_action( 'wp_ajax_ftc_import_widget', array($this, 'import_widget') );
			add_action( 'wp_ajax_ftc_import_config', array($this, 'import_config') );
			add_action( 'wp_ajax_ftc_import_content', array($this, 'import_content') );
		}
		
		function ftc_register_scripts(){
			wp_enqueue_style( 'ftc-import-style', plugins_url( '/assets/style.css', __FILE__ ) );
			wp_register_script( 'ftc-import-script', plugins_url( '/assets/script.js', __FILE__ ), array( 'jquery' ), false, true );
		}
		
		function ftc_menu_page(){
			add_menu_page( 'Import Demo Data', 'FTC Importer', 'switch_themes', 'ftc_importer', array($this, 'ftc_import_demo_content'), '', 40);
		}
		
		function ftc_import_demo_content(){
			wp_enqueue_script( 'ftc-import-script' );
			?>

			<div class="ftc-importer-wrapper">
				<div class="note">
					<div class="note_import">
						<div class="logo"><img src="<?php echo plugins_url( 'assets/logo.png', __FILE__ ); ?>"></div>
						<div class="heading">
							<h2>ThemeFTC - Import Demo Content</h2>
							<p style="font-size: 15px;padding-left: 0; font-style: italic;">Thank you for purchasing our premium eCommerce theme.</p>
						</div>

						<h4>Please read before importing:</h4>
						<p>This importer will help you build your site look like our demo.</p>
						<p>Please installed and activated <strong> ThemeFTC, WooCommerce, Visual Composer, Mega Main Menu and Revolution Slider plugins.</strong></p>
						<p>If you need support please contact our support team: <a href="https://themeftc.ticksy.com">https://themeftc.ticksy.com</a></p>
						
					</div>
					<div class="screen">
						<?php $theme_obj = wp_get_theme(); ?>
						<img src="<?php echo esc_url($theme_obj->get_screenshot()) ?>">
					</div>

				</div>
				<h3>Full Import:</h3>
				<div class="options">
					<div class="button-wrapper">
						<button id="ftc-import-button-full">Full Import</button>
						<i class="fa fa-spinner fa-spin importing-button hidden"></i>
					</div>	
					<h3>Select the options for custom import:</h3>				
					<div class="option">
						<label for="ftc_import_theme_options">
							<input type="checkbox" name="ftc_import_theme_options" id="ftc_import_theme_options" value="1" />
							Theme Options
						</label>
					</div>
					<div class="option">
						<label for="ftc_mmm_options_backup">
							<input type="checkbox" name="ftc_mmm_options_backup" id="ftc_mmm_options_backup" value="1" />
							Mega Main Menu
						</label>
					</div>
					<div class="option">
						<label for="ftc_import_widget">
							<input type="checkbox" name="ftc_import_widget" id="ftc_import_widget" value="1" />
							Widgets
						</label>
					</div>
					<div class="option">
						<label for="ftc_import_revslider">
							<input type="checkbox" name="ftc_import_revslider" id="ftc_import_revslider" value="1" />
							Revolution Slider
						</label>
					</div>
					<div class="option">
						<label for="ftc_import_demo_content">
							<input type="checkbox" name="ftc_import_demo_content" id="ftc_import_demo_content" value="1" />
							Demo Content
						</label>
					</div>
				</div>
				<div class="button-wrapper">
					<button id="ftc-import-button" disabled>Import</button>
					<i class="fa fa-spinner fa-spin importing-button hidden"></i>
				</div>
				<div class="import-result hidden">
					<div class="progress">
						<div class="progress-bar progress-bar-striped active" role="progressbar"
						aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
						0% Complete
					</div>
				</div>
				<div class="messages">
					
				</div>
			</div>
		</div>
		<?php
	}

	/* Include Importer Classes */
	function include_importer_classes(){
		if ( ! class_exists( 'WP_Importer' ) ) {
			include ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		}

		if ( ! class_exists('WP_Import') ) {
			include_once dirname(__FILE__) . '/includes/wordpress-importer.php';
		}
	}

	/* Dont Resize image while importing */
	function no_resize_image( $sizes ){
		return array();
	}

	/* Import XML */
	function import_content(){
		set_time_limit(0);
		if ( !defined('WP_LOAD_IMPORTERS') ){ 
			define('WP_LOAD_IMPORTERS', true); 
		}

		add_filter('intermediate_image_sizes_advanced', array($this, 'no_resize_image'));

		$file_path = dirname(__FILE__) . '/data/demo_content.xml';

		if( file_exists($file_path) ){
			$this->include_importer_classes();

			$importer = new WP_Import();
			$importer->fetch_attachments = true;
			ob_start();
			$importer->import($file_path);
			ob_end_clean();

			echo 'Successful Import Demo Content';
		}

		wp_die();
	}

	function import_config(){
		$this->woocommerce_settings();
		$this->menu_locations();
		$this->update_options();
		echo 'Config successfully';
		wp_die();
	}

	/* Import Theme Options */
	function import_theme_options(){
		$theme_options_path = dirname(__FILE__) . '/data/theme_options.txt';
		if( !file_exists($theme_options_path) && !class_exists( 'ReduxFramework' ) ){
			wp_die();
		}
		$theme_options_url = untrailingslashit( plugin_dir_url(__FILE__) ) . '/data/theme_options.txt';
		$theme_options_content = wp_remote_get( $theme_options_url );
		$redux_options_data = json_decode( $theme_options_content['body'], true );
		$redux_framework = \ReduxFrameworkInstances::get_instance( 'carna_opt' );
		if ( isset( $redux_framework->args['opt_name'] ) ){ 
			$redux_framework->set_options( $redux_options_data );
		}

		echo 'Successful Import Theme Options';
		wp_die();
	}

	/* Import Mega Main Menu */
	function mmm_options_backup() {
		global $mega_main_menu;
		$mega_main_menu_options_path = dirname(__FILE__) . '/data/mega_main_menu.txt';
		if( !file_exists($mega_main_menu_options_path) ){
			wp_die();
		}
		$mega_main_menu_options_url = untrailingslashit( plugin_dir_url(__FILE__) ) . '/data/mega_main_menu.txt';
		$backup_file_content = wp_remote_get( $mega_main_menu_options_url );
		$options_backup = json_decode( $backup_file_content['body'], true );
		if ( isset( $options_backup['last_modified'] ) ) {
			$options_backup['last_modified'] = time() + 30;
			update_option($mega_main_menu->constant[ 'MM_OPTIONS_NAME' ], $options_backup );
		}
		
		echo 'Successful Import Mega Main Menu';
		wp_die();
	}

	/* Import Sidebar Content */
	function import_widget(){
		/* clear widget*/
		$sidebars = wp_get_sidebars_widgets();
		$inactive = isset($sidebars['wp_inactive_widgets']) && is_array( $sidebars['wp_inactive_widgets'] ) ? $sidebars['wp_inactive_widgets'] : array();

		unset($sidebars['wp_inactive_widgets']);

		foreach ( $sidebars as $sidebar => $widgets ) {
			if( is_array( $widgets ) ){
				$inactive = array_merge($inactive, $widgets);
			}

			$sidebars[$sidebar] = array();
		}

		$sidebars['wp_inactive_widgets'] = $inactive;
		wp_set_sidebars_widgets( $sidebars );
		/* end clear */

		$file_path = dirname(__FILE__) . '/data/widget_data.json';
		if( !file_exists($file_path) ){
			wp_die();
		}
		$file_url = untrailingslashit( plugin_dir_url(__FILE__) ) . '/data/widget_data.json';
		$widget_json = wp_remote_get( $file_url );
		$widget_data = $widget_json['body'];
		$widget_data = json_decode( $widget_data, true);
		unset($widget_data[0]['wp_inactive_widgets']);

		$sidebar_data = $widget_data[0];
		$widget_data = $widget_data[1];

		foreach( $widget_data as $widget_data_title => $widget_data_value ){
			$widgets[ $widget_data_title ] = '';
			foreach( $widget_data_value as $widget_data_key => $widget_data_array ) {
				if( is_int( $widget_data_key ) ) {
					$widgets[$widget_data_title][$widget_data_key] = 'on';
				}
			}
		}
		unset($widgets[""]);

		foreach( $sidebar_data as $title => $sidebar ){
			$count = count( $sidebar );
			for ( $i = 0; $i < $count; $i++ ) {
				$widget = array( );
				$widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
				$widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
				if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
					unset( $sidebar_data[$title][$i] );
				}
			}
			$sidebar_data[$title] = array_values( $sidebar_data[$title] );
		}

		foreach( $widgets as $widget_title => $widget_value ){
			foreach( $widget_value as $widget_key => $widget_value ){
				$widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
			}
		}

		$sidebar_data = array( array_filter( $sidebar_data ), $widgets );

		/* Parse data */
		global $wp_registered_sidebars;

		/* Add custom sidebars to registered sidebars variable */
		$custom_sidebars = get_option('ftc_custom_sidebars');
		if( is_array($custom_sidebars) && !empty($custom_sidebars) ){
			foreach( $custom_sidebars as $name ){
				$custom_sidebar = array(
					'name' 			=> ''.$name.''
					,'id' 			=> sanitize_title($name)
					,'description' 	=> ''
					,'class'		=> 'ftc-custom-sidebar'
				);
				if( !isset($wp_registered_sidebars[$custom_sidebar['id']]) ){
					$wp_registered_sidebars[$custom_sidebar['id']] = $custom_sidebar;
				}
			}
		}

		$sidebars_data = $sidebar_data[0];
		$widget_data = $sidebar_data[1];

		$current_sidebars = get_option( 'sidebars_widgets' );

		$new_widgets = array();

		foreach( $sidebars_data as $import_sidebar => $import_widgets ){
			foreach( $import_widgets as $import_widget ){
				if( array_key_exists( $import_sidebar, $current_sidebars ) ){
					$title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
					$index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );

					$current_widget_data = get_option( 'widget_' . $title );

					$new_widget_name = self::get_new_widget_name( $title, $index );
					$new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

					if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
						while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
							$new_index++;
						}
					}
					$current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
					if ( array_key_exists( $title, $new_widgets ) ) {
						$new_widgets[$title][$new_index] = $widget_data[$title][$index];
						$multiwidget = $new_widgets[$title]['_multiwidget'];
						unset( $new_widgets[$title]['_multiwidget'] );
						$new_widgets[$title]['_multiwidget'] = $multiwidget;
					} else {
						$current_widget_data[$new_index] = $widget_data[$title][$index];
						$current_multiwidget = isset($current_widget_data['_multiwidget']) ? $current_widget_data['_multiwidget'] : false;
						$new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
						$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
						unset( $current_widget_data['_multiwidget'] );
						$current_widget_data['_multiwidget'] = $multiwidget;
						$new_widgets[$title] = $current_widget_data;
					}

				}
			}
		}

		if( isset( $new_widgets ) && isset( $current_sidebars ) ){
			update_option( 'sidebars_widgets', $current_sidebars );

			foreach( $new_widgets as $title => $content ){
				update_option( 'widget_' . $title, $content );
			}
			return true;
		}

		echo 'Successful Import Widgets';
		wp_die();
	}

	function get_new_widget_name( $widget_name, $widget_index ){
		$current_sidebars = get_option( 'sidebars_widgets' );
		$all_widget_array = array( );
		foreach ( $current_sidebars as $sidebar => $widgets ) {
			if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
				foreach ( $widgets as $widget ) {
					$all_widget_array[] = $widget;
				}
			}
		}
		while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
			$widget_index++;
		}
		$new_widget_name = $widget_name . '-' . $widget_index;
		return $new_widget_name;
	}

	/* Import Revolution Slider */
	function import_revslider(){
		if( class_exists('RevSliderSlider')) {
			$slider_directory  = dirname(__FILE__) . '/data/revslider/';
			$slider_files = array();
			$slider = new RevSlider();
			foreach( glob( $slider_directory . '*.zip' ) as $filename ) {
				$filename = basename($filename);
				$slider_files[] = $slider_directory . $filename;
			}
			foreach($slider_files as $index => $filepath){				
				ob_start();
				$response = $slider->importSliderFromPost(true, true,$filepath);
				ob_clean();
				ob_end_clean();
			}
		}
	}

	/* WooCommerce Settings */
	function woocommerce_settings(){
		$woopages = array(
			'woocommerce_shop_page_id' 			=> 'Shop'
			,'woocommerce_cart_page_id' 		=> 'Shopping cart'
			,'woocommerce_checkout_page_id' 	=> 'checkout'
			,'woocommerce_myaccount_page_id' 	=> 'My Account'
		);
		foreach( $woopages as $woo_page_name => $woo_page_title ) {
			$woopage = get_page_by_title( $woo_page_title );
			if( isset( $woopage->ID ) && $woopage->ID ) {
				update_option($woo_page_name, $woopage->ID);
			}
		}
		$catalog = array(
			'width' 	=> '270',
			'height'	=> '270',
			'crop'		=> 1 
		);

		$single = array(
			'width' 	=> '393',
			'height'	=> '393',
			'crop'		=> 1 
		);

		$thumbnail = array(
			'width' 	=> '101',
			'height'	=> '101',
			'crop'		=> 1 
		);

		update_option( 'shop_catalog_image_size', $catalog );
		update_option( 'shop_single_image_size', $single ); 	
		update_option( 'shop_thumbnail_image_size', $thumbnail ); 
		
		if( class_exists('YITH_Woocompare') ){
			update_option('yith_woocompare_compare_button_in_products_list', 'yes');
		}

		if( class_exists('WC_Admin_Notices') ){
			WC_Admin_Notices::remove_notice('install');
		}
		delete_transient( '_wc_activation_redirect' );

		flush_rewrite_rules();
	}

	/* Menu Locations */
	function menu_locations(){
		$locations = get_theme_mod( 'nav_menu_locations' );
		$menus = wp_get_nav_menus();

		if( $menus ) {
			foreach($menus as $menu) {
				if( $menu->name == 'Main menu' ) {
					$locations['primary'] = $menu->term_id;
				}
				
			}
		}
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/* Update Options */
	function update_options(){
		$homepage = get_page_by_title( 'Home' );
		if( isset( $homepage ) && $homepage->ID ){
			update_option('show_on_front', 'page');
			update_option('page_on_front', $homepage->ID);
		}
	}
}
new FTC_Importer();
}
?>