<?php

if(!class_exists('CountyFunc_loader')) {
	
	
	class CountyFunc_loader {
		
		
		/*
		*Current Loader Version
		*/
		protected $version;
		protected $table_name;
		
		public function __construct($version) {
			$this->version = $version;
			
			global $wpdb;
			$this->table_name = $wpdb->prefix . 'RACounty';
		}
		
		public function install_racounty_func() {
			
			$this->create_ratable_func($this->table_name);
	
	
			add_option('RACounty_dbversion',$this->version);
	
		} //install_racounty_func		


		public function update_racounty_func() {
			global $wpdb;
			$installed_ver = get_option('RACounty_dbversion');


			if($installed_ver !== $this->version) { // Perform updated TABLE Struct
				$this->table_name;
				$this->create_ratable_func($this->table_name);
				
				update_option('RACounty_dbversion',$this->version);
			}
			
		} // update_racounty_func


		public function create_ratable_func($table_name) {
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
				`established` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				`county_name` text NOT NULL,
				`state` text NOT NULL,
				`desc` text NULL,
				UNIQUE KEY id (id)
				) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		} // create_ratable_func
		
	}
		
}