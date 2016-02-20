<?php
	
/**
 * Load theme compatibility functions
 */
function addtoany_load_theme_compat() {
	add_action( 'loop_start', 'addtoany_excerpt_remove' );
}

add_action( 'after_setup_theme', 'addtoany_load_theme_compat', -1 );

/**
 * Remove from excerpts where buttons could be redundant or awkward
 */
function addtoany_excerpt_remove() {
	// If Twenty Sixteen theme
	if ( 'twentysixteen' == get_stylesheet() || 'twentysixteen' == get_template() ) {
		// If blog index, single, or archive page, where excerpts are used as "intros"
		if ( is_single() || is_archive() || is_home() ) {
			remove_filter( 'the_excerpt', 'A2A_SHARE_SAVE_add_to_content', 98 );
		}	
	}
}