<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<title>
<?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;
	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'northern' ), max( $paged, $page ) );

?>
</title>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );
	wp_head();
?>

</head>

<body <?php body_class(); ?>>

<header id="header">    
<h1><a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
<?php bloginfo('name'); ?></a></h1>
<em><?php bloginfo('description'); ?></em>

<?php
// Check to see if the header image has been removed
$header_image = get_header_image();
if ( ! empty( $header_image ) ) :
?>
<img src="<?php header_image(); ?>" id="headerpic" width="<?php echo HEADER_IMAGE_WIDTH; ?>px" height="<?php echo HEADER_IMAGE_HEIGHT; ?>px" alt="" />
<?php endif; // end check for removed header image ?>
</header>

<input type="checkbox" id="isexpanded" />
<label for="isexpanded" id="expand-btn" title="Navigation">Menu</label>
<nav class="expandable">
<?php wp_nav_menu( array( 'container' => 'false', ) ); ?>
</nav>

<section id="wrapper">
 






<section id="content">