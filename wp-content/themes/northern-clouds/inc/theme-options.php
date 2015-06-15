<?php
/*
northern Theme Options
*/

function northern_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'northern-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-04-28' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'northern_admin_enqueue_scripts' );
 
function northern_theme_options_init() {
	register_setting(
		'northern_options',
		'northern_theme_options',
		'northern_theme_options_validate'
	);

	add_settings_section(
		'general',
		'',
		'__return_false',
		'theme_options'
	);

	add_settings_section(
		'layout',
		'Default layout',
		'northern_settings_field_layout',
		'theme_options',
		'general'
	);
}
add_action( 'admin_init', 'northern_theme_options_init' );

function northern_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_northern_options', 'northern_option_page_capability' );

function northern_theme_options_add_page() {
	$theme_page = add_theme_page(
		'Theme Options',
		'Theme Options',
		'edit_theme_options',
		'theme_options',
		'northern_theme_options_render_page'
	);

	if ( ! $theme_page )
		return;
}
add_action( 'admin_menu', 'northern_theme_options_add_page' );

function northern_layouts() {
	$layout_options = array(
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => 'Left content, right sidebar',
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png'
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => 'Left sidebar, right content',
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png'
		),
		'sidebar-bottom' => array(
			'value' => 'sidebar-bottom',
			'label' => 'Bottom sidebar, full content',
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-bottom_full-content.png'
		)
	);

	return apply_filters( 'northern_layouts', $layout_options );
}

function northern_get_default_theme_options() {
	$default_theme_options = array(
		'theme_layout' => 'sidebar-bottom'
	);

	return apply_filters( 'northern_default_theme_options', $default_theme_options );
}

function northern_get_theme_options() {
	return get_option( 'northern_theme_options', northern_get_default_theme_options() );
}

function northern_settings_field_layout() {
	$options = northern_get_theme_options();
	foreach ( northern_layouts() as $layout ) {
		?>
		
        <div class="theme-layout">        
                
		<label class="description">          
        <p><img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="<?php echo $layout['label']; ?>" /></p>
		<input type="radio" name="northern_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />  
        <strong><?php echo $layout['label']; ?></strong>
		</label>
		</div>      
		<?php
	}
}

function northern_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : wp_get_theme(); ?>
		<h2><?php echo 'Northern-Clouds Theme Options'; ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'northern_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
<?php
}

function northern_theme_options_validate( $input ) {
	$output = $defaults = northern_get_default_theme_options();
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], northern_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];
	return apply_filters( 'northern_theme_options_validate', $output, $input, $defaults );
}

function northern_layout_classes( $existing_classes ) {
	$options = northern_get_theme_options();
	$current_layout = $options['theme_layout'];

	if ( 'content-sidebar' == $current_layout )
		$classes[] = 'right-sidebar';
	elseif ( 'sidebar-content' == $current_layout )
		$classes[] = 'left-sidebar';
		
	elseif ( 'no-sidebar' == $current_layout )
		$classes[] = 'no-sidebar';
		
		
	else
		$classes[] = $current_layout;

	$classes = apply_filters( 'northern_layout_classes', $classes, $current_layout );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'northern_layout_classes' );