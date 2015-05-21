<?php
/**
 * kjokkenskapet functions and definitions
 *
 * @package kjokkenskapet
 */

/**
 * For child theme authors: To disable the styles and layouts from kjokkenskapet properly, 
 * add the following code to your child theme functions.php file:
 *
 * <?php
 * add_action( 'wp_enqueue_scripts', 'dequeue_parent_theme_styles', 11 );
 * function dequeue_parent_theme_styles() {
 *     wp_dequeue_style( 'kjokkenskapet-parent-style' );
 *     wp_dequeue_style( 'kjokkenskapet-layout' );
 * }
 *
 */

if ( ! function_exists( 'kjokkenskapet_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kjokkenskapet_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on kjokkenskapet, use a find and replace
	 * to change 'kjokkenskapet' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'kjokkenskapet', get_template_directory() . '/languages' );
        
        /**
        * Set the content width based on the theme's design and stylesheet.
        */
        if ( ! isset( $content_width ) ) {
               $content_width = 700; /* pixels */
        }
        
        // This theme styles the visual editor to resemble the theme style.
        $font_url = '//fonts.googleapis.com/css?family=Lato:300,400,400italic,700,900,900italic|PT+Serif:400,700,400italic,700italic';
        add_editor_style( array( 'inc/editor-style.css', str_replace( ',', '%2C', $font_url ) ) );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
        // Featured image sizes for resesponsive display
        add_image_size('large-thumb', 1060, 650, true);
        add_image_size('medium-thumb', 800, 490);
        add_image_size('small-thumb', 400, 245);
        // Featured image size for small image in archives
        add_image_size('index-thumb', 780, 250, true);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'kjokkenskapet' ),
                'social' => __( 'Social Menu', 'kjokkenskapet'),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'kjokkenskapet_custom_background_args', array(
		'default-color' => '313131',
		'default-image' => get_template_directory_uri() . '/images/pattern.svg',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
                'caption',
	) );
}
endif; // kjokkenskapet_setup
add_action( 'after_setup_theme', 'kjokkenskapet_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function kjokkenskapet_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'kjokkenskapet' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
        
        register_sidebar( array(
		'name'          => __( 'Footer Widgets', 'kjokkenskapet' ),
                'description'   => __( 'Footer widgets area appears, not surprisingly, in the footer of the site.', 'kjokkenskapet' ),
		'id'            => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'kjokkenskapet_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kjokkenskapet_scripts() {
        
        // Get the current layout setting (sidebar left or right)
        $kjokkenskapet_layout = get_option( 'layout_setting' );

        // Load parent theme stylesheet even when child theme is active
        if ( is_child_theme() ) {
                wp_enqueue_style( 'kjokkenskapet-parent-style', trailingslashit( get_template_directory_uri() ) . 'style.css' );
        } else {
                wp_enqueue_style( 'kjokkenskapet-style', get_stylesheet_uri() );
        }

        if (is_page_template('page-templates/page-nosidebar.php') || ! is_active_sidebar( 'sidebar-1' )) {
            wp_enqueue_style( 'kjokkenskapet-layout' , get_template_directory_uri() . '/layouts/no-sidebar.css' );
        } elseif ( $kjokkenskapet_layout == 'left-sidebar' ) {
            wp_enqueue_style( 'kjokkenskapet-layout' , get_template_directory_uri() . '/layouts/sidebar-content.css' );
        } else {
            wp_enqueue_style( 'kjokkenskapet-layout' , get_template_directory_uri() . '/layouts/content-sidebar.css' );

        }

        // Load child theme stylesheet
        if ( is_child_theme() ) {
                wp_enqueue_style( 'kjokkenskapet-style', get_stylesheet_uri() );
        }

        // Lato http://www.google.com/fonts/specimen/Lato + PT Serif http://www.google.com/fonts/specimen/PT+Serif
        wp_enqueue_style( 'kjokkenskapet-google-fonts', '//fonts.googleapis.com/css?family=Lato:100,300,400,400italic,700,900,900italic|PT+Serif:400,700,400italic,700italic' );
        
        // FontAwesome
        wp_enqueue_style('kjokkenskapet_fontawesome', get_template_directory_uri() . '/fonts/font-awesome/css/font-awesome.min.css');

	wp_enqueue_script( 'kjokkenskapet-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
        
        wp_enqueue_script( 'kjokkenskapet-search', get_template_directory_uri() . '/js/hide-search.js', array(), '20120206', true );
        
        wp_enqueue_script( 'kjokkenskapet-superfish', get_template_directory_uri() . '/js/superfish.min.js', array('jquery'), '20140328', true );
        
        wp_enqueue_script( 'kjokkenskapet-superfish-settings', get_template_directory_uri() . '/js/superfish-settings.js', array('jquery'), '20140328', true );
        
        wp_enqueue_script( 'kjokkenskapet-masonry', get_template_directory_uri() . '/js/masonry-settings.js', array('masonry'), '20140401', true );
        
        wp_enqueue_script( 'kjokkenskapet-enquire', get_template_directory_uri() . '/js/enquire.min.js', false, '20140429', true );
        
        wp_enqueue_script( 'kjokkenskapet-picturefill', get_template_directory_uri() . '/js/picturefill.min.js', false, '20140512', false );
        
        if (is_single() || is_author() ) {
            	wp_enqueue_script( 'kjokkenskapet-hide', get_template_directory_uri() . '/js/hide.js', array('jquery'), '20140310', true );
        }

	wp_enqueue_script( 'kjokkenskapet-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kjokkenskapet_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
