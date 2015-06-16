<?php
/*
Plugin Name: County Fuctionality
Plugin URI: 
Description: County functionality a specific plugin created for militiatoday
Author: RichardAbear
Version: 0.0.1
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
		protected $version = '0.0.1';
		
		
		protected $loader;
		
		//Functionality
		
		public function __construct() {gi
			
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
		
		
		
	}// END OF CLASS
} //END OF IF


if( class_exists('County_Functionality')) {
	$countyfunc = new County_Functionality();
	register_activation_hook(__FILE__,array(&$countyfunc,'setupPlugin'));
}