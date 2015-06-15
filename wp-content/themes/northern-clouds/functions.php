<?php

if ( ! isset( $content_width ) )
	$content_width = 584;
add_action( 'after_setup_theme', 'northern_setup' );

function northern_setup() {
	load_theme_textdomain( 'northern', get_template_directory() . '/languages' );
	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

register_nav_menu( 'primary', __( 'Primary Menu', 'northern' ) );

add_editor_style();
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'custom-background', array( 'default-color' => '444' ) );

require( get_template_directory() . '/inc/theme-options.php' );
};

function northern_custom_header_setup() {
$args = array(
	'random-default'         => true,
	'default-text-color'     => 'D3D3D3',
	'width'                  => 950,
	'height'                 => 282,
	'flex-height'            => true,
	'wp-head-callback'       => 'northern_header_style',
	'admin-head-callback'    => 'northern_admin_header_style',
	'admin-preview-callback' => 'northern_admin_header_image',
);

$args = apply_filters( 'northern_custom_header_args', $args );
add_theme_support( 'custom-header', $args );
}

register_default_headers( array(
    'clouds' => array(
        'url' => '%s/images/header.jpg',
        'thumbnail_url' => '%s/images/header-thumb.jpg',
        'description' => __( 'clouds' ,'northern' )
    ),
    'clouds2' => array(
        'url' => '%s/images/header2.jpg',
        'thumbnail_url' => '%s/images/header2-thumb.jpg',
        'description' => __( 'clouds2' ,'northern'  )
    ),
    'clouds3' => array(
        'url' => '%s/images/header3.jpg',
        'thumbnail_url' => '%s/images/header3-thumb.jpg',
        'description' => __( 'clouds3' ,'northern'  )
    )
));

add_action( 'after_setup_theme', 'northern_custom_header_setup' );

function northern_header_style() {
if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
?>
<style type="text/css">	
#headerpic
{
    background: url('<?php header_image(); ?>');
    width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
    height: <?php echo HEADER_IMAGE_HEIGHT; ?>px
}

<?php
// Has the text been hidden?
if ( 'blank' == get_header_textcolor() ) :
?>

header#header h1 ,
header#header em {
	display: none;
}

<?php
// If the user has set a custom color for the text use that
else :
?>

header#header h1 a,
header#header em {
	color: #<?php echo get_header_textcolor(); ?> !important;
}
<?php endif; ?>
</style>
<?php
}

function northern_admin_header_style() {
	wp_enqueue_style( 'northern-fonts', 'http://fonts.googleapis.com/css?family=Ubuntu' );
?>
<style type="text/css">
#headerpic
{
    background: #<?php echo get_background_color() ?>; width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
    padding: 20px;
}

#headerpic h1
{
    width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
    font-family: Ubuntu;
    margin: 0;
    padding: 0;
}

#headerpic h1 a:first-letter
{
    font-size: 50px;
    color: #EB6E44;
    font-style: italic;
    margin-right: 5px;
}

#headerpic h1 a
{
    font-family: Ubuntu;
    font-size: 35px;
    text-decoration: none;
    display: block;
    line-height: 45px;
}

#headerpic em
{
    font-size: 12px;
    display: block;
    margin-left: 4px;
}

<?php
// Has the text been hidden?
if ( 'blank' == get_header_textcolor() ) :
?>

#headerpic h1 ,
#headerpic em {
	display: none;
}

<?php
// If the user has set a custom color for the text use that
else :
?>

#headerpic h1 a,
#headerpic em {
	color: #<?php echo get_header_textcolor(); ?> !important;
}
<?php endif; ?>




div.northern-admin-header-image
{
    height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
    width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
    background: url(<?php echo get_header_image(); ?>) 0 0 no-repeat;
    border-top : 3px solid #FAFAFA;
    border-bottom : 1px solid #FAFAFA;
    -moz-border-radius: 8px 8px 0 0;
    -webkit-border-radius:  8px 8px 0 0;
    border-radius: 8px 8px 0 0;
    }
</style>
<?php
}

function northern_admin_header_image() { ?>
<div id="headerpic">
<h1><a id="name" href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a></h1>
<em><?php bloginfo( 'description' ); ?></em>


<?php
if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
	$style = ' style="display:none;"';
else
	$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
?>
<?php $header_image = get_header_image();
if ( ! empty( $header_image ) ) : ?>

<div class="northern-admin-header-image">
<?php endif; ?>
</div>
</div>
<?php }

function northern_widgets_init() {
    register_sidebar(array(
    'name'=> __( 'Sidebar Blog', 'northern' ),
    'id' => 'sidebar-blog',
    'description' => 'Sidebar Blog',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ));

    register_sidebar(array(
    'name'=> __( 'Sidebar Page', 'northern' ),
    'id' => 'sidebar-page',
    'description' => 'Sidebar Page',
    'before_widget' => '',
    'after_widget' => '',
    'before_title' => '<h3>',
    'after_title' => '</h3>',
    ));
}
add_action( 'widgets_init', 'northern_widgets_init' );

function northern_pagenavi() {
    global $wp_query, $wp_rewrite;
    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    $pagination = array(
    'base' => @add_query_arg('paged','%#%'),
    'format' => '',
    'total' => $wp_query->max_num_pages,
    'current' => $current,
    'type' =>'plain',
    'show_all' => false,
    'mid_size' => 2,
);
if ( $wp_rewrite->using_permalinks() )
    $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
    if ( !empty( $wp_query->query_vars['s'] ) )
    $pagination['add_args'] = array( 's' => get_query_var( 's' ) );
    echo paginate_links( $pagination );
}

function northern_the_breadcrumb() {
    echo(_e("You are here:", 'northern'));
	echo ' <a href="';
	echo home_url();
	echo '">';
	bloginfo('name');
	echo "</a> ";
	if (is_category() || is_single()) {
		echo "&raquo; ";
        the_category(', ');
		if (is_single()) {
			echo " &raquo; ";
			the_title();
		}
	} elseif (is_page()) {
		echo "&raquo; ";
   		echo the_title();
	}
};

function northern_theme_styles()
{
   wp_register_style( 'screen-style', get_template_directory_uri() . '/style.css');
   wp_register_style('ubuntu-font', 'http://fonts.googleapis.com/css?family=Ubuntu');
   wp_enqueue_style( 'screen-style' );
   wp_enqueue_style('ubuntu-font');
}
add_action('wp_print_styles', 'northern_theme_styles');

function northern_enqueue_scripts() {
	if (!is_admin()) {
	  wp_register_script('superfish', get_template_directory_uri() . '/js/superfish.js');
      wp_register_script('northern-custom', get_template_directory_uri() . '/js/northern-custom.js');
      wp_register_script( 'html5-shim', 'http://html5shim.googlecode.com/svn/trunk/html5.js', array( 'jquery' ), '1', false );
      wp_enqueue_script('jquery');
	  wp_enqueue_script('superfish');
      wp_enqueue_script('northern-custom');
      wp_enqueue_script( 'html5-shim' );
	}
}
add_action('init', 'northern_enqueue_scripts');