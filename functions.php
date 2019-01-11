<?php
/**
 * cortila functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cortila
 */

if ( ! function_exists( 'cortila_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function cortila_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on cortila, use a find and replace
		 * to change 'cortila' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'cortila', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'cortila' ),
			'menu-2' => esc_html__( 'Colophon', 'cortila' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( '_s_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Editor Styles
		add_editor_style( 'style.css' );				

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );
	}
endif;
add_action( 'after_setup_theme', 'cortila_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cortila_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'cortila_content_width', 1040 );
}
add_action( 'after_setup_theme', 'cortila_content_width', 0 );

/**
 * Register Google Fonts
 */
function cortila_fonts_url() {
	$fonts_url = '';

	/*
	 *Translators: If there are characters in your language that are not
	 * supported by Noto Serif, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$notoserif = esc_html_x( 'on', 'Noto Serif font: on or off', 'cortila' );

	if ( 'off' !== $notoserif ) {
		$font_families = array();
		$font_families[] = 'Noto Serif:400,400italic,700,700italic';
		$font_families[] = 'Syncopate:400,700';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;

}

/**
 * Enqueue scripts and styles.
 */
function cortila_enqueue_scripts() {

	$is_single_type = ( is_single() || is_singular() || is_page() );

	/* Styles
	-------------------------------------------------------------------------------------------- */

	if ( $is_single_type ) :
		wp_enqueue_style( 'mediaelement' );
		wp_enqueue_style( 'wp-mediaelement' );
	endif;

	wp_enqueue_style( 
		'cortila-chocolat', 
		get_template_directory_uri() . '/assets/css/vendor/chocolat.css', 
		array(), 
		filemtime( get_template_directory_uri() . '/assets/css/vendor/chocolat.css' ) 
	);
	
	wp_enqueue_style( 
		'cortila-style', 
		get_stylesheet_uri(), 
		array( 'cortila-chocolat' ), 
		filemtime( get_stylesheet_uri() ) 
	);

	wp_enqueue_style( 'cortila-fonts', cortila_fonts_url() );

	/* Scripts
	-------------------------------------------------------------------------------------------- */

	if ( $is_single_type ) :
		wp_enqueue_script( 'mediaelement' );
		wp_enqueue_script( 'wp-mediaelement' );
	endif;

	wp_enqueue_script( 'cortila-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_register_script( 
		'cortila-chocolat', 
		get_template_directory_uri() . '/assets/js/vendor/jquery.chocolat.min.js', 
		array( 'jquery' ), 
		filemtime( get_template_directory() . '/assets/js/vendor/jquery.chocolat.min.js' ), 
		true 
	);

	wp_register_script( 
		'cortila-global', 
		get_template_directory_uri() . '/assets/js/global.js', 
		array( 'jquery', 'cortila-chocolat' ), 
		filemtime( get_template_directory() . '/assets/js/global.js' ), 
		true 
	);

	$localize_r = array(
		'siteUrl' => get_site_url(),
	);
	wp_localize_script( 'cortila-global', 'cortila', $localize_r );

	wp_enqueue_script( 'cortila-global' );	

}
add_action( 'wp_enqueue_scripts', 'cortila_enqueue_scripts' );

/**
 * Enqueue scripts and styles.
 */
function cortila_enqueue_admin_scripts() {

	wp_enqueue_style( 
		'cortila-admin-style', 
		get_template_directory_uri() . '/assets/css/main-admin.css', 
		array(), 
		filemtime( get_template_directory() . '/assets/css/main-admin.css' ) 
	);

}
add_action( 'admin_enqueue_scripts', 'cortila_enqueue_admin_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Require Mobile Detect
 */
require get_template_directory() . '/inc/Mobile_Detect.php';

/**
 * Require Shortcodes
 */
require get_template_directory() . '/inc/shortcodes.php';

/* ------------------------------------------------------------------------------------------------
# Mobile Detect
------------------------------------------------------------------------------------------------ */

$detect = new Mobile_Detect;

// Any mobile device (phones or tablets).
// if ( $detect->isMobile() ) {}

// Any tablet device.
// if( $detect->isTablet() ){}

// Exclude tablets.
// if( $detect->isMobile() && !$detect->isTablet() ){}

/* ------------------------------------------------------------------------------------------------
# Images
------------------------------------------------------------------------------------------------ */

/* Deactivate WP's default image optimization behaviour */
add_filter( 'jpeg_quality', function( $arg ) { return 100; } );

/* Custom image sizes */
add_image_size( 'blog-post-thumbnail', 960, 640, true );
add_image_size( 'single-post-thumbnail', 2000, 9999, false );
add_image_size( 'marginal', 480, 9999, false );

// Register custom image sizes for backend use
add_filter( 'image_size_names_choose', 'cortila_imgsize_names' );
function cortila_imgsize_names( $sizes ) {

    return array_merge( $sizes, array(
        'blog-post-thumbnail' 	=> __( 'Post thumbnail on post listing pages' ),
        'single-post-thumbnail' => __( 'Post thumbnail on single post pages' ),
        'marginal' 				=> __( 'Marginal image' ),                
    ) );

}

/* ------------------------------------------------------------------------------------------------
# Dev helpers
------------------------------------------------------------------------------------------------ */

/**
 * Custom body classes
 */

add_filter( 'body_class', 'cortila_custom_body_classes' );
function cortila_custom_body_classes( $classes ) {
	
	global $post;
	$detect = new Mobile_Detect;
	
	$is_single = ( is_page() || is_single() || is_singular() );

	if ( $detect->isiOS() )
		$classes[] = 'is-ios';	

	if ( $is_single ) :

		$classes[] = 'single-type';

		if ( has_post_thumbnail( $post->ID ) )
			$classes[] = 'has-post-thumbnail';

		if ( class_exists('acf') ):

			$featimg_align = get_field('featimg-align');

			switch ( $featimg_align ) :

			    case 'fullwidth': 	$classes[] = 'featimg-fullwidth'; 	break;
			    case 'center': 		$classes[] = 'featimg-center'; 		break;
			    case 'right': 		$classes[] = 'featimg-right'; 		break;
			  	default: 			$classes[] = 'featimg-left';

			endswitch;

		endif;	

	endif;

	return $classes;

}

/**
 * Custom post classes
 */

add_filter( 'post_class', 'cortila_custom_post_classes' );
function cortila_custom_post_classes( $classes ) {
	
	global $post;
	$is_single = ( is_page() || is_single() || is_singular() );

	if ( $is_single ) :

		$classes[] = 'chocolat-parent';

	endif;
	
	return $classes;

}

/**
 * Reorder metaboxes
 */

add_filter( 'get_user_option_meta-box-order_post', 'metabox_order' );
function metabox_order( $order ) {
    return array(
        'side' => join( 
            ",", 
            array(
            	'submitdiv',
            	'categorydiv',
            	'tagsdiv-post_tag',
                'postimagediv',
                'acf-group_5c05198f95723',
                'acf-group_5c0cfa426a73a',
            )
        ),
    );
}



/* ------------------------------------------------------------------------------------------------
# Plugins
------------------------------------------------------------------------------------------------ */