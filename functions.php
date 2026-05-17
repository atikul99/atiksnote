<?php
/**
 * atiksnote functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package atiksnote
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function atiksnote_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on atiksnote, use a find and replace
		* to change 'atiksnote' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'atiksnote', get_template_directory() . '/languages' );

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
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'atiksnote' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'atiksnote_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'atiksnote_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function atiksnote_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'atiksnote_content_width', 640 );
}
add_action( 'after_setup_theme', 'atiksnote_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function atiksnote_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'atiksnote' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'atiksnote' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'atiksnote_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function atiksnote_scripts() {

	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', [], '1.0.0' );

	wp_enqueue_style( 'highlight', get_template_directory_uri() . '/assets/css/default.min.css', [], '1.0.0' );

	wp_enqueue_style( 'geist-font', 'https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap', [], '1.0.0' );

	wp_enqueue_style( 'atiksnote-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'atiksnote-style', 'rtl', 'replace' );

	wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main-style.css', [], '1.0.0' );

	// JS

	wp_enqueue_script('jquery');

	wp_enqueue_script( 'highlight', get_template_directory_uri() . '/assets/js/highlight.min.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'live-search', get_template_directory_uri() . '/assets/js/live-search.js', array(), _S_VERSION, true );

	wp_localize_script('live-search', 'LiveSearch', [
		'ajaxurl' => admin_url('admin-ajax.php'),
		'nonce'   => wp_create_nonce('live_search_nonce'),
	]);

	wp_enqueue_script( 'atiksnote-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'main', get_template_directory_uri() . '/assets/js/main.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'atiksnote_scripts' );

/**
 * Ajax search
 */

add_action('wp_ajax_nopriv_live_search', 'live_search');
add_action('wp_ajax_live_search', 'live_search');

function live_search() {
    check_ajax_referer('live_search_nonce');

    $keyword = sanitize_text_field($_POST['keyword'] ?? '');
    $q = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 5,
        's'              => $keyword,
    ]);

    if ($q->have_posts()) {
        echo '<ul>';
        while ($q->have_posts()) { 
            $q->the_post();
            $title = get_the_title();

            // Highlight search keyword
            $highlighted = preg_replace(
                '/(' . preg_quote($keyword, '/') . ')/i',
                '<mark>$1</mark>',
                $title
            );

            echo '<li><a href="' . esc_url(get_permalink()) . '">' . $highlighted . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No results found</p>';
    }
    wp_die();
}

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
 * Breadcrumb
 */
require get_template_directory() . '/inc/breadcrumb.php';

