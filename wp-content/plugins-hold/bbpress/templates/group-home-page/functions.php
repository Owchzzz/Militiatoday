<?php
/*
 * Created on Nov 28, 2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

function add_activity_tab() {
	global $bp;
	
	if(bp_is_group()) {
		bp_core_new_subnav_item( 
			array( 
				'name' => 'Activity', 
				'slug' => 'activity', 
				'parent_slug' => $bp->groups->current_group->slug, 
				'parent_url' => bp_get_group_permalink( $bp->groups->current_group ), 
				'position' => 11, 
				'item_css_id' => 'nav-activity',
				'screen_function' => create_function('',"bp_core_load_template( apply_filters( 'groups_template_group_home', 'groups/single/home' ) );"),
				'user_has_access' => 1
			) 
		);
		
		if ( bp_is_current_action( 'activity' ) ) {
			add_action( 'bp_template_content_header', create_function( '', 'echo "' . esc_attr( 'Activity' ) . '";' ) );
			add_action( 'bp_template_title', create_function( '', 'echo "' . esc_attr( 'Activity' ) . '";' ) );
		}
	}
}

add_action( 'bp_actions', 'add_activity_tab', 8 );

?>