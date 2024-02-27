<?php
/**
 * First, let's set the maximum content width based on the theme's
 * design and stylesheet.
 * This will limit the width of all uploaded images and embeds.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1296; /* pixels */
}


if ( ! function_exists( 'bootstrap_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various
	 * WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme
	 * hook, which runs before the init hook. The init hook is too late
	 * for some features, such as indicating support post thumbnails.
	 */
	function bootstrap_setup() {

		add_theme_support( 'title-tag' );

        /**
         * Make theme available for translation.
         * Translations can be placed in the /languages/ directory.
         */
		load_theme_textdomain( 'bootstrap', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to <head>.
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for post thumbnails and featured images.
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size('bootstrap-117x96-cropped', 117, 96, true);

		/**
		 * Add support for two custom navigation menus.
		 */
		register_nav_menus( array(		
			'primary'   => __( 'Primary Menu', 'bootstrap' ),
		) );

		/**
		 * Enable support for the following post formats:
		 * aside, gallery, quote, image, and video
		 */
		add_theme_support( 'post-formats', array( 'aside', 'gallery', 'quote', 'image', 'video', 'chat' ) );
	}
endif; // bootstrap_setup

add_action( 'after_setup_theme', 'bootstrap_setup' );

/**
 * Add a sidebar.
 */
function bootstrap_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'bootstrap' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'bootstrap' ),
		'before_widget' => '<div id="%1$s" class="widget mb-3 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="fst-italic">',
		'after_title'   => '</h4>',
	) );

}
add_action( 'widgets_init', 'bootstrap_widgets_init' );


function bootstrap_add_theme_scripts() {
	// Including CSS
	wp_enqueue_style( 'googlefonts', 'https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap' );
	wp_enqueue_style( 'bootstrap', get_theme_file_uri('assets/css/bootstrap.min.css'), [], '5.3.2' );
	wp_enqueue_style( 'custom', get_theme_file_uri('assets/css/custom.css'), [], '1.0.0' );
	wp_enqueue_style( 'blog', get_theme_file_uri('assets/css/blog.css'), [], '1.0.0' );
	wp_enqueue_style( 'style', get_stylesheet_uri() );


	// Including JS
	wp_enqueue_script('color-modes', get_theme_file_uri('assets/js/color-modes.js'), [], '5.3', false);
	wp_enqueue_script('bootstrap', get_theme_file_uri('assets/js/bootstrap.bundle.min.js'), [], '5.3', true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bootstrap_add_theme_scripts' );


function bootstrap_wp_head(){
	?>
	<meta name="theme-color" content="#ff0">
	<?php
}
add_action('wp_head', 'bootstrap_wp_head');

function bootstrap_wp_body_open(){
	get_template_part('template-parts/svg-icons');
}
add_action('wp_body_open', 'bootstrap_wp_body_open');


function bootstrap_fallback_cb(){
	if(!current_user_can('edit_themes')) return;
	?>
	<div class="nav-scroller py-1 mb-3 border-bottom">
    	<nav class="nav nav-underline justify-content-between">
			<a class="nav-item nav-link link-body-emphasis" href="<?php echo esc_url(admin_url('nav-menus.php')) ?>"><?php esc_attr_e('Add Primary Menu', 'bootstrap') ?></a>
    	</nav>
  	</div>	
	<?php
}

function bootstrap_nav_menu_link_attributes($atts, $menu_item, $args){	
	$link_classes = [];
	if(!empty($args->link_class)){
		$link_classes[] =  $args->link_class;
	}

	if(in_array('current-menu-item', $menu_item->classes)){
		$link_classes[] =   'active';
	}

	if(!empty($link_classes)){
		$atts['class'] = implode(' ', $link_classes);
	}
	
	return $atts;
}
add_filter('nav_menu_link_attributes', 'bootstrap_nav_menu_link_attributes', 10, 3);


function bootstrap_posts_link_attributes($atts) {
	return 'class="btn btn-outline-primary rounded-pill"';
}
add_filter('next_posts_link_attributes', 'bootstrap_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'bootstrap_posts_link_attributes');


function bootstrap_pagination_output( $template, $class ) {
	
	$template = '
	<nav class="navigation %1$s" aria-label="%4$s">
    <h2 class="screen-reader-text">%2$s</h2>
    <div class="nav-links d-flex gap-1">%3$s</div>
</nav>';

	return $template;
}
add_filter( 'navigation_markup_template', 'bootstrap_pagination_output', 99, 2 );

function bootstrap_get_sticky_posts_for_home($total = 3){
	
	$sticky = get_option( 'sticky_posts' );
	if( empty($sticky) ) return[];		

	$banner_posts = $grid_posts = [];

	$query_args = [
		'post_type' => 'post',
		'post__in' => $sticky,
		'ignore_sticky_posts' => true,
		'posts_per_page' => -1
	];
	$the_query = new WP_Query( $query_args );
	// The Loop.
	if ( $the_query->have_posts() ) {
		$count = 0;
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			if($count >= $total ) break;

			if($count <= 0 ){
				$banner_posts[] = get_the_ID();
			}else{
				$grid_posts[] = get_the_ID();
			}			
				
			$count++; 
		}	
	}
	wp_reset_postdata();

	$GLOBALS['bootstrap_banner_posts'] = $banner_posts;
	$GLOBALS['bootstrap_grid_posts'] = $grid_posts;
	$GLOBALS['bootstrap_sticky_home_posts'] = array_merge($banner_posts, $grid_posts);
	
}

function bootstrap_exclude_single_posts_home($query) {

	if ( $query->is_home() && $query->is_main_query() ) {
		bootstrap_get_sticky_posts_for_home();
		global $bootstrap_sticky_home_posts;
		if(!empty($bootstrap_sticky_home_posts)){
			$query->set( 'post__not_in', $bootstrap_sticky_home_posts );
		}
		
	}
}
add_action( 'pre_get_posts', 'bootstrap_exclude_single_posts_home' );


function add_image_size_to_media($sizes){
    $custom_sizes = array(
		'bootstrap-117x96-cropped' => '117x96-cropped'
    );
    return array_merge( $sizes, $custom_sizes );
}
add_filter('image_size_names_choose', 'add_image_size_to_media');

include __DIR__ .'/inc/blocks/archives.php';
include __DIR__ .'/inc/blocks/latest-posts.php';