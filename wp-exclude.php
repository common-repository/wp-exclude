<?php
/*
Plugin Name: WP Exclude
Plugin Version: 0.1.0
Plugin URI: http://www.callum-macdonald.com/code/
Description: Allow users to exclude some posts based on cookies or query vars. Useful for allowing visitors to hide sticky posts once they've read them.
Author: Callum Macdonald
Author URI: http://www.calum-macdonald.com/
*/

if (!function_exists('wpex_init')) :
function wpex_init() {
	$request = array_merge($_REQUEST, $_COOKIE);
	if (isset($request['wp_exclude_posts']))
		add_filter('the_posts', 'wpex_get_posts');
	wp_enqueue_script('wp_exclude_posts', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)) . '/wp-exclude.js', null, '0.1');
}
endif;
add_action('init', 'wpex_init');

if (!function_exists('wpex_get_posts')) :
function wpex_get_posts($posts) {
	// If  there is only one post, return it, no point hiding the post on i's own page!
	if (count($posts) <= 1)
		return $posts;
	$request = array_merge($_REQUEST, $_COOKIE);
	if (!isset($request['wp_exclude_posts']) || empty($request['wp_exclude_posts']))
		return $posts;
	$exclude_posts = array_map('intval', explode(',', $request['wp_exclude_posts']));
	// foreach through the array $posts, test with in_array($needle, $haystack), unset if necessary
	foreach ($posts as $key => $post) {
		if (in_array($post->ID, $exclude_posts))
		unset($posts[$key]);
	}
	
	return array_merge($posts);
	
}
endif;

if (!function_exists('wpex_shortcode')) :
function wpex_shortcode($attr, $content = null) {
	global $post;
	// If there is no content, return a whole link
	if (empty($content))
		$content = __('Exclude this post');
	return '<a href="javascript:wp_exclude_post(' . $post->ID . ');">' . $content . '</a>';
}
endif;

add_shortcode('exclude_link', 'wpex_shortcode');

?>
