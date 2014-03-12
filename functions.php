/* Created by Tibor Kányádi */

<?php
register_sidebar( array(
		'name'          => __( 'Third Widget Area', 'twentythirteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears on pages with Template Area 3.', 'twentythirteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
register_sidebar( array(
		'name'          => __( 'Fourth Widget Area', 'twentythirteen' ),
		'id'            => 'sidebar-4',
		'description'   => __( 'Appears on pages with Template Area 4.', 'twentythirteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );	
register_sidebar( array(
		'name'          => __( 'Five Widget Area', 'twentythirteen' ),
		'id'            => 'sidebar-5',
		'description'   => __( 'Appears on pages with Template Area 5.', 'twentythirteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );	

// Add child theme javascripts
    add_action('init', 'add_javascript');
 
    function add_javascript() {
        // Add JQuery
        wp_enqueue_script('jquery');
 
        // Add the scripts
        $js_url = get_bloginfo('stylesheet_directory') . '/js';
        wp_enqueue_script('jquery-min',"$js_url/jquery.min.js");
        wp_enqueue_script('responsiveslides',"$js_url/responsiveslides.min.js");
    }	
	

/**
  * Filters the_category() to output html 5 valid rel tag
  *
  * @param string $text
  * @return string
  */
function ispireme_fix_category_tag ($ispireme_cat_output) {
$ispireme_cat_output = str_replace(array('rel="category tag"','rel="category"'),'', $ispireme_cat_output);

return $ispireme_cat_output;
}
add_filter( 'the_category', 'ispireme_fix_category_tag' );

// Removes Masonry enqueued by Twenty Thirteen to handle vertical alignment of footer widgets.
function slbd_dequeue_masonry() {
   wp_dequeue_script( 'jquery-masonry' );
}
add_action( 'wp_print_scripts', 'slbd_dequeue_masonry' );

function twentythirteen_child_body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_page_template('page1.php') && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';
	
	if ( is_page_template('page2.php') && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';	
		
	if ( is_page_template('page3.php') && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';		

	if ( ! get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';

	return $classes;
}
add_filter( 'body_class', 'twentythirteen_child_body_class' );
/**
 * Count number of widgets in a sidebar
 * Used to add classes to widget areas so widgets can be displayed one, two, three or four per row
 */
function slbd_count_widgets( $sidebar_id ) {
	// If loading from front page, consult $_wp_sidebars_widgets rather than options
	// to see if wp_convert_widget_settings() has made manipulations in memory.
	global $_wp_sidebars_widgets;
	if ( empty( $_wp_sidebars_widgets ) ) :
		$_wp_sidebars_widgets = get_option( 'sidebars_widgets', array() );
	endif;
	
	$sidebars_widgets_count = $_wp_sidebars_widgets;
	
	if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) :
		$widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
		$widget_classes = 'widget-count-' . count( $sidebars_widgets_count[ $sidebar_id ] );
		if ( $widget_count % 4 == 0 || $widget_count > 6 ) :
			// Four widgets er row if there are exactly four or more than six
			$widget_classes .= ' per-row-4';
		elseif ( $widget_count >= 3 ) :
			// Three widgets per row if there's three or more widgets 
			$widget_classes .= ' per-row-3';
		elseif ( 2 == $widget_count ) :
			// Otherwise show two widgets per row
			$widget_classes .= ' per-row-2';
		endif; 

		return $widget_classes;
	endif;
}
