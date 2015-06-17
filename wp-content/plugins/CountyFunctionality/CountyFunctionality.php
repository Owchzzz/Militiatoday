<?php
/*
Plugin Name: County Fuctionality
Plugin URI: 
Description: County functionality a specific plugin created for militiatoday
Author: RichardAbear
Version: 0.0.2
Author URI: 
*/


// Exit if accessed directly
define( 'RAPLUGINPATH', plugin_dir_path( __FILE__ ) ); 
if ( !defined( 'ABSPATH' ) ) exit;




if(! class_exists('County_Functionality')) {
	
	class County_Functionality {
		/**
		 * Tag identifier used by file includes and selector attributes.
		 * @var string
		 */
		protected $tag = 'county_functionality';

		/**
		 * User friendly name used to identify the plugin.
		 * @var string
		 */
		protected $name = 'County_functionality';

		/**
		 * Current version of the plugin.
		 * @var string
		 */
		protected $version = '0.0.2';
		
		
		protected $loader;
		
		//Functionality
		
		public function __construct() {
			
			//Views
			add_action('admin_menu',array($this,'load_views'));
			
			
			//Dependencies and Updates
			$this->load_dependencies();
			add_action('plugins_loaded',array(&$this->loader,'update_racounty_func'));
			
			
		}
		
		
		private function load_dependencies() {
			require_once (RAPLUGINPATH.'CountyFuncLoader.php');
			$this->loader = new CountyFunc_loader($this->version);
		}
		
		
		public function setupPlugin() {
			$this->loader->install_racounty_func();
		}
		
		
		public function checkforUpdates() {
			$this->loader->update_racounty_func();
		}
		
		
		public function load_views() {
			
			add_menu_page('County Functionality','County System','manage_options','county_func_admin', array($this,'load_county_admin'), 'dashicons-location-alt');
			add_submenu_page('county_func_admin','Add new County','Add new County','manage_options','county_func_admin_add_county',array($this,'load_county_admin_add_county'));
		}
		
		
		
		// Views
		public function load_county_admin() {
			if(isset($_GET['action']) && $_GET['action'] == 'del') {
				global $wpdb;
				$tablename = $wpdb->prefix.'RACounty';
				$result=$wpdb->delete($tablename,array('id'=>mysql_real_escape_string($_GET['id'])));
				echo '<meta http-equiv="refresh" content="0; url=/wp-admin/admin.php?page=county_func_admin&result='.$result.'">';
			}
			require_once(plugin_dir_path(__FILE__).'/admin/county_admin.php');
		}
		
		public function load_county_admin_add_county() {
			
			if(isset($_GET['submit'])) {
				global $wpdb;
				$tablename = $wpdb->prefix.'RACounty';
				$data = array(
				'county_name' => mysql_real_escape_string($_POST['county_name']),
				'state' => mysql_real_escape_string($_POST['state']),
				'desc' => mysql_real_escape_string($_POST['desc']),
				'established' => current_time('mysql', 1));
				
				$result = $wpdb->insert($tablename, $data);
				wp_redirect(get_site_url());
				echo '<meta http-equiv="refresh" content="0; url=/wp-admin/admin.php?page=county_func_admin&result='.$result.'">';
			}
			else {
				require_once(plugin_dir_path(__FILE__).'/admin/county_admin_add_county.php');
			}
			
		}
		
		
	}// END OF CLASS
} //END OF IF


if( class_exists('County_Functionality')) {
	$countyfunc = new County_Functionality();
	register_activation_hook(__FILE__,array(&$countyfunc,'setupPlugin'));
}

//Global Functionality
require_once(plugin_dir_path(__FILE__).'modal.php');