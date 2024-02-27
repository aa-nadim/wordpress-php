<?php
/**
 * First, let's set the maximum content width based on the theme's
 * design and stylesheet.
 * This will limit the width of all uploaded images and embeds.
 */


if ( ! function_exists( 'practice_BS_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various
	 * WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme
	 * hook, which runs before the init hook. The init hook is too late
	 * for some features, such as indicating support post thumbnails.
	 */
	function practice_BS_setup() {

		add_theme_support( 'title-tag' );
        /**
         * Make theme available for translation.
         * Translations can be placed in the /languages/ directory.
         */
		load_theme_textdomain( 'practice_BS', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to <head>.
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for post thumbnails and featured images.
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Add support for two custom navigation menus.
		 */
		register_nav_menus( array(		
			'primary'   => __( 'Primary Menu', 'practice_BS' ),
		) );

		/**
		 * Enable support for the following post formats:
		 * aside, gallery, quote, image, and video
		 */
		add_theme_support( 'post-formats', array( 'aside', 'gallery', 'quote', 'image', 'video', 'chat' ) );
	}
endif; // practice_BS_setup

add_action( 'after_setup_theme', 'practice_BS_setup' );

/**
 * Add a sidebar.
 */
function practice_BS_theme_slug_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'textdomain' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Widgets in this area will be shown on all posts and pages.', 'textdomain' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'practice_BS_theme_slug_widgets_init' );


function practice_BS_add_theme_scripts() {
// Including CSS
wp_enqueue_style( 'bootstrap', get_theme_file_uri('assets/css/bootstrap.min.css'), [], '5.3.2' );
wp_enqueue_style( 'custom', get_theme_file_uri('assets/css/custom.css'), [], '1.0.0' );
wp_enqueue_style( 'style', get_stylesheet_uri() );


// Including JS
wp_enqueue_script('color-modes', get_theme_file_uri('assets/js/color-modes.js'), [], '5.3', false);
wp_enqueue_script('bootstrap', get_theme_file_uri('assets/js/bootstrap.bundle.min.js'), ['masonry'], '5.3', true);
//important
// wp_enqueue_script('masonry-pkgd', get_theme_file_uri('assets/js/masonry.pkgd.min.js'), [], '4.2.2', true);
//wp_enqueue_script('masonry');

if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	wp_enqueue_script( 'comment-reply' );
}
}
add_action( 'wp_enqueue_scripts', 'practice_BS_add_theme_scripts' );


function practice_BS_wp_head(){
	?>
	<meta name="theme-color" content="#ff0">
	<?php
}
add_action('wp_head', 'practice_BS_wp_head');