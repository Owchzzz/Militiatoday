<div class="home-page single-group" role="main">
	<?php if(bp_is_item_admin()) { ?>
		<div class="notice info">
			<p>Welcome to your group home page!<br />
			Click <a href="<?php bp_group_admin_permalink() ?>">Admin</a> above to set the content for this page.</p>
		</div>
	<?php } ?>
	<?php bp_group_description(); ?>
</div>