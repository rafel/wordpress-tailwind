<?php
/*------------------------------------*\
  Add navigation to theme
\*------------------------------------*/
function register_menus() {
  register_nav_menus(array(
   'header-menu'  => esc_html('Main menu', 'wptw'),
 ));
}
add_action('init', 'register_menus');


/*------------------------------------*\
  Theme Support
\*------------------------------------*/
if (function_exists('add_theme_support')) {
  add_theme_support('post-thumbnails');
}


/*------------------------------------*\
  Load script and css
\*------------------------------------*/
function wptw_scripts() {
  if ($GLOBALS['pagenow'] != 'wp-login.php' && ! is_admin()) {
    wp_register_script('wptwscript', get_template_directory_uri() . '/script.js', filemtime(get_template_directory().'/script.js'));
    wp_enqueue_script('wptwscript');
  }
}
add_action('wp_enqueue_scripts', 'wptw_scripts');

function wptw_styles() {
  wp_dequeue_style('wp-block-library');
  wp_register_style('wptwstyle', get_template_directory_uri().'/style.css', filemtime(get_template_directory().'/style.css'));
  wp_enqueue_style('wptwstyle');
}
add_action('wp_enqueue_scripts', 'wptw_styles');


/*------------------------------------*\
  Optimizations
\*------------------------------------*/
// Disable Emojis function
function wp_disable_emojis() {
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  add_filter( 'emoji_svg_url', '__return_false' );
}
add_action('init', 'wp_disable_emojis');        // Disable Emojis

// Disable Dashicons on Front-end
function wpdocs_dequeue_dashicon() {
  if (current_user_can('update_core')) {
    return;
  }
  wp_deregister_style('dashicons');
}
add_action('wp_enqueue_scripts', 'wpdocs_dequeue_dashicon');

// Disable Contact Form 7 JS/CSS
add_filter('wpcf7_load_js', '__return_false');
add_filter('wpcf7_load_css', '__return_false');

// Removes wp-embed.min.js
function my_deregister_scripts(){
  wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'my_deregister_scripts');

// Remove jquery-migrate
function dequeue_jquery_migrate($scripts) {
  if (!is_admin() && !empty($scripts->registered['jquery'])) {
    $scripts->registered['jquery']->deps = array_diff(
      $scripts->registered['jquery']->deps,
      [ 'jquery-migrate' ]
   );
  }
}
add_action('wp_default_scripts', 'dequeue_jquery_migrate');

// Remove jquery
function remove_query() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', false);
    }
}
add_action('init', 'remove_query');

/* Remove comments */ 
add_action('admin_init', function () {
  // Redirect any user trying to access comments page
  global $pagenow;
  
  if ($pagenow === 'edit-comments.php') {
    wp_redirect(admin_url());
    exit;
  }
  // Remove comments metabox from dashboard
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
  // Disable support for comments and trackbacks in post types
  foreach (get_post_types() as $post_type) {
    if (post_type_supports($post_type, 'comments')) {
      remove_post_type_support($post_type, 'comments');
      remove_post_type_support($post_type, 'trackbacks');
    }
  }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
  if (is_admin_bar_showing()) {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
  }
});

// Remove the width and height attributes from inserted images
function remove_width_attribute($html) {
  $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
  return $html;
}

// Remove Admin bar
function remove_admin_bar() {
  return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag) {
  return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions($html) {
  $html = preg_replace('/(width|height)=\"\d*\"\s/', '', $html);
  return $html;
}


/*------------------------------------*\
  Remove actions && add filters
\*------------------------------------*/

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Remove the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Remove the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Remove the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Remove the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'wp_generator'); // Remove the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10); // Remove oEmbed discovery links in the website.
remove_action('wp_head', 'rest_output_link_wp_head', 10); // Remove the REST API link tag into page header.

// Add Filters
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('post_thumbnail_html', 'remove_width_attribute', 10); // Remove width and height dynamic attributes to post images
add_filter('image_send_to_editor', 'remove_width_attribute', 10); // Remove width and height dynamic attributes to post images
