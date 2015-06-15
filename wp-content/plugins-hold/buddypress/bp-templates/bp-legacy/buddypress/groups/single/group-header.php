<?php

do_action( 'bp_before_group_header' );

?>

<div id="item-actions">

	<h3><b>County Links:</b></h3> <br>
	<a href="http://militiatoday.com/groups/butler-county-pennsylvania/forum/">Local News</a><br>
	<a href="http://militiatodaytv.com/group/butlercounty">Butler Channels</a><br>
	<a href="http://militiatoday.com/obits/">Obituaries</a><br>
	<a href="http://militiatoday.com/classifieds/">Classifieds</a><br>
	<a href="http://militiatoday.com/classifieds/">Local Businesses</a><br>
	
	

  
 
</div><!-- #item-actions -->

<div id="item-header-avatar">
	<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">

		<?php bp_group_avatar(); ?>

	</a>
</div><!-- #item-header-avatar -->

<div id="item-header-content">
<?php do_action( 'bp_before_group_header_meta' ); ?>
<?php bp_group_description(); ?>
	<div id="item-meta">
		<div id="item-buttons">

			<?php do_action( 'bp_group_header_actions' ); ?>

		</div><!-- #item-buttons -->

		<?php do_action( 'bp_group_header_meta' ); ?>

	</div>
</div><!-- #item-header-content -->

<?php
do_action( 'bp_after_group_header' );
do_action( 'template_notices' );
?>