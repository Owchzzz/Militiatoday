<?php

if( ! class_exists('Countyfunction_modal')) {
	
	class Countyfunction_modal {
		
		protected $tablename="";
		
		public function __construct() {
			global $wpdb;
			$this->tablename = $wpdb->prefix.'RACounty';
		}
		
		public function count_all_counties() {
			global $wpdb;
			$wpdb->get_results( 'SELECT COUNT(*) FROM '.$this->tablename);
			return $wpdb->num_rows;
		}
		
		
		
		public function get_table() {
			global $wpdb;
			$raw_Array =  $wpdb->get_results( 'SELECT * FROM '.$this->tablename, ARRAY_A);
			return $raw_Array;
		}
	}
	
	

}


//global public functions
function display_success() {
	echo '<div class="updated below-h2">
		<p>
			Action Successfull.
		</p>
	</div>';
}

function display_failure() {
	echo '<div class="failed below-h2">
		<p>
			Action Failed.
		<p>
	</div>';
}
	