<?php
/**
 * ryan_m functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ryan_m
 */




//We support splitting functions.php into separate files, so do so whenever possible.
$theme_path = get_template_directory();

$dir = opendir($theme_path . "/components");
while ($dir !== FALSE && FALSE !== ($entry = readdir($dir))) {
    if (is_dir($theme_path . "/components/" . $entry)) {
        foreach (glob($theme_path . "/components/" . $entry . "/*.decl.php") as $file) {
            require_once "$file";
        }
    }
}

$dir2 = opendir($theme_path . "/layouts");
while ($dir2 !== FALSE && FALSE !== ($entry = readdir($dir2))) {
    if (is_dir($theme_path . "/layouts/" . $entry)) {
        foreach (glob($theme_path . "/layouts/" . $entry . "/*.decl.php") as $file) {
            require_once "$file";
        }
    }
}

class SkeletonWarrior_Renderer {
    private $args;
    private $file;

    public function __get($name) {
        return $this->args[$name];
    }

    public function __construct($file, $args = array()) {
        $this->file = $file;
        $this->args = $args;
    }

    public function __isset($name){
        return isset( $this->args[$name] );
    }

    public function render() {
        //Pollute current scope with arguments.
        foreach ($this->args as $__k => $__v) {
            $$__k = $__v;
        }

        if( locate_template($this->file) ){
            include( locate_template($this->file) );//Theme Check free. Child themes support.
        }
    }
}

function get_template_component($component, $slug = null, $name = null, $args = array()) {
    if (!isset($slug)) {
        $slug = $component;
    }

    do_action( "get_template_part_{$component}/{$slug}", $slug, $name );

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name )
        $templates[] = "components/{$component}/{$slug}-{$name}.php";

    $templates[] = "components/{$component}/{$slug}.php";

    $template_renderer = new SkeletonWarrior_Renderer($templates, $args);
    $template_renderer->render();
}

function get_template_layout($layout, $slug = null, $name = null, $args = array()) {
    if (!isset($slug)) {
        $slug = $layout;
    }

    do_action( "get_template_part_{$layout}/{$slug}", $slug, $name );

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name )
        $templates[] = "layouts/{$layout}/{$slug}-{$name}.php";

    $templates[] = "layouts/{$layout}/{$slug}.php";

    $template_renderer = new SkeletonWarrior_Renderer($templates, $args);
    $template_renderer->render();
}


 // retrieves the attachment ID from the file URL
 function pippin_get_image_id($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return $attachment[0]; 
}


/* Allow SVG uploads.
 */
function skeletonwarrior_check_filetype_and_ext($data, $file, $filename, $mimes) {
    global $wp_version;
    if( $wp_version == '4.7' || ( (float) $wp_version < 4.7 ) ) {
        return $data;
    }

    $filetype = wp_check_filetype( $filename, $mimes );

    return array(
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    );
}
add_filter('wp_check_filetype_and_ext', 'skeletonwarrior_check_filetype_and_ext', 10, 4 );

/* Add SVG as a mime type.
 */
function skeletonwarrior_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'skeletonwarrior_mime_types' );

/* Ensure SVGs don't break the admin environment.
 */
function skeletonwarrior_fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'skeletonwarrior_fix_svg' );

/* Generates SRCSET attribute from an image structure.
 */
function skeletonwarrior_srcset($image) {
    $srcset = $image["url"] . " " . $image["width"] . "w";

    foreach ($image["sizes"] as $k => $v) {
        if (endsWith($k, "-width") || endsWith($k, "-height")) continue;

        $srcset .= ", " . $v . " " . $image["sizes"][$k . "-width"] . "w";
    }

    return $srcset;
}

/* Create a new WP_Query and set it as the default loop.
 */
function enter_post_archive($post_type, $addl_keys) {
    global $wp_query;
    global $wp_query_stack;

    if (!isset($wp_query_stack)) {
        $wp_query_stack = array();
    }

    array_push($wp_query_stack, $wp_query);

    if (!isset($addl_keys)) {
        $addl_keys = array();
    }

    $addl_keys["post_type"] = $post_type;

    $wp_query = new WP_Query($addl_keys);
};

/* Enter a single post object.
 */
function enter_post($new_post) {
    global $post;

    $post = $new_post;
};

/* Reset the state of the main loop after a call to enter_post_archive.
 *
 * You MUST call this function after you are finished with the secondary loop,
 * or things in WordPress will be broke.
 */
function exit_loop() {
    global $wp_query;
    global $wp_query_stack;

    $wp_query = array_pop($wp_query_stack);
    wp_reset_postdata();
}

/* Customizable login page.
 */
function skeletonwarrior_custom_login_image() {
    echo "<style>
    body.login #login h1 a {
    background: url('".get_bloginfo('template_url')."/assets/img/login_logo.svg') 8px 0 no-repeat transparent;
    background-position: center center;
    background-size:50%;
    height:150px;
    width:320px; }

    </style>";
}
add_action("login_head", "skeletonwarrior_custom_login_image");

/* Custom post type for widgets.
 */
function skeletonwarrior_widget_post_type() {
    register_post_type('skw_widget', array(
        'labels' => array(
            'name' => __("Widget Settings"),
            'singular_name' => __("Widget Setting"),
        ),
        'description' => __("Widget settings to be used by certain widget types.", 'skeleton_warrior'),
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'has_archive' => false,
        'menu_position' => 100,
        'menu_icon' => 'dashicons-schedule',
        'supports' => array('title', 'revisions'),
    ));
}
add_action("init", "skeletonwarrior_widget_post_type");

/* ACF JSON */
function skeletonwarrior_json_load_point($paths) {
    $theme_path = get_template_directory();
    
    if (is_dir($theme_path . "/assets/acf-fieldgroups")) {
        $paths[] = $theme_path . "/assets/acf-fieldgroups";
    }
    
    $dir = opendir($theme_path . "/components");
    while ($dir !== FALSE && FALSE !== ($entry = readdir($dir))) {
        if (is_dir($theme_path . "/components/" . $entry . "/acf-fieldgroups")) {
            $paths[] = $theme_path . "/components/" . $entry . "/acf-fieldgroups";
        }
    }
    
    $dir2 = opendir($theme_path . "/layouts");
    while ($dir2 !== FALSE && FALSE !== ($entry = readdir($dir2))) {
        if (is_dir($theme_path . "/layouts/" . $entry . "/acf-fieldgroups")) {
            $paths[] = $theme_path . "/layouts/" . $entry . "/acf-fieldgroups";
        }
    }
    
    return $paths;
}
add_filter('acf/settings/load_json', 'skeletonwarrior_json_load_point');

//add inline validation to comments on blog
function comment_validation_init() {
    if(is_singular() && comments_open() ) { ?>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script type="text/javascript" src="http://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#commentform').validate({
                    onfocusout: function(element) {
                        this.element(element);
                    },
                    rules: {
                        author: {
                            required: true,
                            minlength: 2,
                            normalizer: function(value) { return $.trim(value); }
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        comment: {
                            required: true,
                            minlength: 20,
                            normalizer: function(value) { return $.trim(value); }
                        }
                    },
                    messages: {
                        author: "Please enter in your name.",
                        email: "Please enter a valid email address.",
                        comment: "Message box can't be empty!"
                    },
                    errorElement: "div",
                    errorPlacement: function(error, element) {
                        element.before(error);
                    }
                });
            });
        </script>
    <?php }
}
add_action('wp_footer', 'comment_validation_init');

//add google fonts
function wpb_add_google_fonts() {
 
    wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500', false ); 
    }
     
    add_action( 'wp_enqueue_scripts', 'wpb_add_google_fonts' );

//stylize acf
function my_acf_admin_head() {
    ?>
    <style type="text/css">

.acf-field input[type="text"], .acf-field input[type="password"], .acf-field input[type="number"], .acf-field input[type="search"], .acf-field input[type="email"], .acf-field input[type="url"], .acf-field textarea, .acf-field select {
    width: 100%;
    padding: 15px 10px;
    resize: none;
    margin: 0;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    font-size: 16px;
    border: 1px solid black;
    line-height: 1.4;
    max-width: 600px;
}

.acf-flexible-content .layout .acf-fc-layout-handle {
        /*background-color: #00B8E4;*/
        background-color: #202428;
        color: #eee;
    }

    .acf-repeater.-row > table > tbody > tr > td,
    .acf-repeater.-block > table > tbody > tr > td {
        border-top: 2px solid #202428;
    }

    .acf-repeater .acf-row-handle {
        vertical-align: top !important;
        padding-top: 16px;
    }

    .acf-repeater .acf-row-handle span {
        font-size: 20px;
        font-weight: bold;
        color: #202428;
    }

    .imageUpload img {
        width: 75px;
    }

    .acf-gallery .acf-gallery-attachment .margin{
        background: #ccc;
    }

    .acf-repeater .acf-row-handle .acf-icon.-minus {
        top: 30px;
    }

    .acf-field select {
    padding: 2px;
    max-width: 120px;
    border: 1px solid black;
}

.acf-field.acf-field-group {
    background: #dadada;
}

.acf-field .acf-label {
    vertical-align: top;
    margin: 0 0 10px;
    font-size: 1.1em;
}

    </style>
    <?php
}

add_action('acf/input/admin_head', 'my_acf_admin_head');



 add_action(‘after_setup_theme’, function(){ 
//add custom image sizes

// Make sure featured images are enabled
add_theme_support( 'post-thumbnails' );

// Add featured image sizes
add_image_size( 'featured-large', 640, 294, true ); // width, height, crop
add_image_size( 'featured-small', 320, 147, true );

// Add other useful image sizes for use through Add Media modal
add_image_size( 'medium-width', 480 );
add_image_size( 'medium-height', 9999, 480 );
add_image_size( 'medium-something', 480, 480 );

// Register the three useful image sizes for use in Add Media modal
add_filter( 'image_size_names_choose', 'wpshout_custom_sizes' );
function wpshout_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'medium-width' => __( 'Medium Width' ),
        'medium-height' => __( 'Medium Height' ),
        'medium-something' => __( 'Medium Something' ),
    ) );
}


 });






if ( ! function_exists( 'ryan_m_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function ryan_m_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on ryan_m, use a find and replace
		 * to change 'ryan_m' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'ryan_m', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'ryan_m' ),
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
		add_theme_support( 'custom-background', apply_filters( 'ryan_m_custom_background_args', array(
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
	}
endif;
add_action( 'after_setup_theme', 'ryan_m_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ryan_m_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'ryan_m_content_width', 640 );
}
add_action( 'after_setup_theme', 'ryan_m_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ryan_m_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'ryan_m' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'ryan_m' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'ryan_m_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function ryan_m_scripts() {
	wp_enqueue_style( 'ryan_m-style', get_stylesheet_uri() );
    wp_enqueue_style( 'ryan_m-custom-style', get_template_directory_uri() . '/assets/main.css' );
    wp_enqueue_style('fancybox-css', get_template_directory_uri() . '/assets/js/jquery.fancybox.min.css');

	wp_enqueue_script( 'ryan_m-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'ryan_m-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script( 'ryan_m-custom-scripts', get_template_directory_uri() . '/build/script.js' );
    wp_enqueue_script('fancybox', get_template_directory_uri() . '/assets/js/jquery.fancybox.min.js');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ryan_m_scripts' );

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



