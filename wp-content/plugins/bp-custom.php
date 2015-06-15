<?php
define ( 'BP_FORUMS_SLUG', 'local news' );
function my_bp_groups_forum_first_tab() {
global $bp;

$bp->bp_options_nav[‘groups’][‘home’][‘position’] = ’50′;
}
add_action(‘wp’, ‘my_bp_groups_forum_first_tab’);
?>