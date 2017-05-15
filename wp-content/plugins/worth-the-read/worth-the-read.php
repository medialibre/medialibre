<?php
/**
 * Plugin Name: Worth The Read
 * Plugin URI: http://www.welldonemarketing.com
 * Description: Adds read length progress bar to single posts and pages, as well as an optional reading time commitment label.
 * Version: 1.2.1
 * Author: Well Done Marketing
 * Author URI: http://www.welldonemarketing.com
 * License: GPL2
 */

# load front-end assets
add_action( 'wp_enqueue_scripts', 'wtr_enqueued_assets' );
function wtr_enqueued_assets() {
	# don't load js and css on homepage unless this is set to display there
	$options = get_option( 'wtr_settings' );
	$types_home = isset($options['wtr_progress_types_home']) ? $options['wtr_progress_types_home'] : '';
	$load_scripts = true;
	if(is_front_page() && empty($types_home)) {
		$load_scripts = false;
	}
	if($load_scripts) {
		wp_enqueue_script( 'wtr-js', plugin_dir_url( __FILE__ ) . 'js/wtr.js', array( 'jquery' ), false, true );
		wp_enqueue_style( 'wtr-css', plugin_dir_url( __FILE__ ) . 'css/wtr.css', false, false, 'all');
	}
}

# load admin assets
add_action( 'admin_enqueue_scripts', 'wtr_enqueued_assets_admin' );
function wtr_enqueued_assets_admin() {
	wp_enqueue_script( 'wtr-admin-js', plugin_dir_url( __FILE__ ) . 'js/wtr-admin.js', array( 'jquery' ), false, true );
	wp_enqueue_style( 'wtr-admin-css', plugin_dir_url( __FILE__ ) . 'css/wtr-admin.css', false, false, 'all');
}

# wrap content in div with php variables in data attributes
add_filter( 'the_content', 'wtr_wrap_content', 10, 2 ); 
function wtr_wrap_content( $content ) { 
	global $post;
	$options = get_option( 'wtr_settings' );
	$types_page = isset($options['wtr_progress_types_page']) ? $options['wtr_progress_types_page'] : '';
	$types_post = isset($options['wtr_progress_types_post']) ? $options['wtr_progress_types_post'] : '';
	$placement = $options['wtr_progress_placement'];
	$placement_offset = empty($options['wtr_progress_placement_offset']) ? 0 : $options['wtr_progress_placement_offset'];
	$width = $options['wtr_progress_width'];
	$fgopacity = $options['wtr_progress_fg_opacity'];
	$mutedopacity = $options['wtr_progress_muted_opacity'];
	$mute = isset($options['wtr_progress_mute']) ? $options['wtr_progress_mute'] : '';
	$transparent = isset($options['wtr_progress_transparent']) ? $options['wtr_progress_transparent'] : '';
	$touch = isset($options['wtr_progress_touch']) ? $options['wtr_progress_touch'] : '';
	$comments = get_comment_count($post->ID) > 0 ? $options['wtr_progress_comments'] : 0;
	$comments_bg = $options['wtr_progress_comments_bg'];
	$fg = $options['wtr_progress_fg'];
	$bg = $options['wtr_progress_bg'];
	$types = array($types_page, $types_post);
	# add cpts to types array
	$cpts = get_post_types(array('public' => true, '_builtin' => false), 'objects'); 
	foreach ($cpts as $cpt) {
		$types[] = isset($options['wtr_progress_types_' . $cpt->name]) ? $options['wtr_progress_types_' . $cpt->name] : '';
	}
	if ( !empty($types) && is_singular($types)) {
		$content = '<div id="wtr-content" 
	    	data-bg="' . $bg . '" 
	    	data-fg="' . $fg . '" 
	    	data-width="' . $width . '" 
	    	data-mute="' . $mute . '" 
	    	data-fgopacity="' . $fgopacity . '" 
	    	data-mutedopacity="' . $mutedopacity . '" 
	    	data-placement="' . $placement . '" 
	    	data-placement-offset="' . $placement_offset . '" 
	    	data-transparent="' . $transparent . '" 
	    	data-touch="' . $touch . '" 
	    	data-comments="' . $comments . '" 
	    	data-commentsbg="' . $comments_bg . '" 
	    	data-location="page"
	    	>' . $content . '</div>';
	}
	return $content;
}

# display on the home page 
add_action( 'wp_footer', 'wtr_wrap_home', 10, 2 ); 
function wtr_wrap_home() {
	global $post;
	$options = get_option( 'wtr_settings' );
	$types_home = isset($options['wtr_progress_types_home']) ? $options['wtr_progress_types_home'] : '';
	$placement = $options['wtr_progress_placement'];
	$placement_offset = empty($options['wtr_progress_placement_offset']) ? 0 : $options['wtr_progress_placement_offset'];
	$width = $options['wtr_progress_width'];
	$fgopacity = $options['wtr_progress_fg_opacity'];
	$mutedopacity = $options['wtr_progress_muted_opacity'];
	$mute = isset($options['wtr_progress_mute']) ? $options['wtr_progress_mute'] : '';
	$transparent = isset($options['wtr_progress_transparent']) ? $options['wtr_progress_transparent'] : '';
	$touch = isset($options['wtr_progress_touch']) ? $options['wtr_progress_touch'] : '';
	$comments = get_comment_count($post->ID) > 0 ? $options['wtr_progress_comments'] : 0;
	$comments_bg = $options['wtr_progress_comments_bg'];
	$fg = $options['wtr_progress_fg'];
	$bg = $options['wtr_progress_bg'];
	# only do this if the home page is not showing a static page
	# because this would fall under the "page" post type instead
	if(is_front_page() && is_home() && !empty($types_home)) {
		echo '<div id="wtr-content" 
		    	data-bg="' . $bg . '" 
		    	data-fg="' . $fg . '" 
		    	data-width="' . $width . '" 
		    	data-mute="' . $mute . '" 
		    	data-fgopacity="' . $fgopacity . '" 
		    	data-mutedopacity="' . $mutedopacity . '" 
		    	data-placement="' . $placement . '" 
		    	data-placement-offset="' . $placement_offset . '" 
		    	data-transparent="' . $transparent . '" 
		    	data-touch="' . $touch . '" 
		    	data-comments="' . $comments . '" 
		    	data-commentsbg="' . $comments_bg . '" 
		    	data-location="home" 
		    	></div>';
	}
}

# wrap comments in div so we can get ahold of a total comment section height
# one of these two actions will usually run, but never at the same time
add_action( 'comment_form_after', 'wtr_wrap_comments' );
add_action( 'comment_form_closed', 'wtr_wrap_comments' );
function wtr_wrap_comments() {
	global $post;
	if(get_comment_count($post->ID) > 0) echo '<div id="wtr-comments-end"></div>';
}
# if the theme doesn't use either of those actions, try another one
if(!has_action( 'wtr_wrap_comments' )) add_action( 'wp_footer', 'wtr_wrap_comments_footer' );
function wtr_wrap_comments_footer() {
	global $post;
	# don't add this on homepage unless this is set to display there
	$options = get_option( 'wtr_settings' );
	$types_home = isset($options['wtr_progress_types_home']) ? $options['wtr_progress_types_home'] : '';
	$show_div = true;
	if(is_front_page() && empty($types_home)) {
		$show_div = false;
	}
	if(get_comment_count($post->ID) > 0 && $show_div) echo '<div id="wtr-comments-end" class="at-footer"></div>';
}

# time commitment placement
add_action('loop_start','wtr_conditional_title');
function wtr_conditional_title($query){
	global $wp_query;
	if($query === $wp_query) {
		add_filter( 'the_title', 'wtr_filter_title', 10, 2);
	} else {
		remove_filter( 'the_title', 'wtr_filter_title', 10, 2);
	}
}
function wtr_filter_title( $title ) {
	$options = get_option( 'wtr_settings' );
	$types_page = isset($options['wtr_time_types_page']) ? $options['wtr_time_types_page'] : '';
	$types_post = isset($options['wtr_time_types_post']) ? $options['wtr_time_types_post'] : '';
	$placement = $options['wtr_time_placement'];
	if(($types_post && is_single()) || ($types_page && is_page())) {
	    if($placement=='before-title') {
	    	$title = wtr_time_commitment() . $title;
	    }elseif($placement=='after-title') {
	    	$title = $title . wtr_time_commitment();
	    }
	}
    return $title;
}
add_filter( 'the_content', 'wtr_filter_content', 10, 2);
function wtr_filter_content( $content ) {
	$options = get_option( 'wtr_settings' );
	$types_page = isset($options['wtr_time_types_page']) ? $options['wtr_time_types_page'] : '';
	$types_post = isset($options['wtr_time_types_post']) ? $options['wtr_time_types_post'] : '';
	$placement = $options['wtr_time_placement'];
	if(($types_post && is_single()) || ($types_page && is_page())) {
	    if($placement=='before-content') {
	    	$content = wtr_time_commitment() . $content;
	    }
	}
    return $content;
}

function wtr_time_commitment() {
	$out = '';
	global $post;
	$word_count = str_word_count(strip_tags(get_post_field( 'post_content', $post->ID )), 0, 'αβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩ');
	$time_length = round($word_count / 200);
	$options = get_option( 'wtr_settings' );
	$time_format = empty($options['wtr_time_format']) ? '# min read' : $options['wtr_time_format'];
    $time_label = str_replace('#', '<i class="fa fa-clock-o" aria-hidden="true" style="display:inline-block;margin-right:0.2em;"></i> <span class="wtr-time-number">' . $time_length . '</span>', $time_format);
    $time_size = $options['wtr_time_style_size'];
    $time_color = $options['wtr_time_style_color'];
    $placement = $options['wtr_time_placement'];
    if($time_size || $time_color) {
    	$style = ' style="font-size:' . $time_size . 'px;color:' . $time_color . ';"';
    } else {
    	$style = '';
    }
    $cssblock = isset($options['wtr_time_style_block']) ? $options['wtr_time_style_block'] : '';
    $cssblock = $cssblock ? ' block' : '';
	$out .= '<span class="wtr-time-wrap' . $cssblock . ' ' . $placement . '"' . $style . '>' . $time_label . '</span>';
	return $out;
}

# add settings link on plugin page
function wtr_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=worth_the_read">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wtr_plugin_settings_link' );


# begin plugin settings page
add_action( 'admin_menu', 'wtr_add_admin_menu' );
add_action( 'admin_init', 'wtr_settings_init' );
add_action( 'admin_enqueue_scripts', 'wtr_add_color_picker' );


function wtr_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'wtr-js-admin', plugin_dir_url( __FILE__ ) . 'js/wtr-admin.js', array( 'wp-color-picker' ), false, true );
    }
}


function wtr_add_admin_menu(  ) { 

	add_options_page( 'Worth The Read', 'Worth The Read', 'manage_options', 'worth_the_read', 'wtr_options_page' );

}


function wtr_settings_init(  ) { 

	register_setting( 'wtr', 'wtr_settings' );

	#progress indicator

	add_settings_section(
		'wtr_progress', 
		__( 'Progress Indicator', 'wtr' ), 
		'wtr_progress_callback', 
		'wtr'
	);

	add_settings_field( 
		'wtr_progress_types', 
		__( 'Display On', 'wtr' ), 
		'wtr_progress_types_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_comments', 
		__( 'Include Comments', 'wtr' ), 
		'wtr_progress_comments_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_placement', 
		__( 'Placement', 'wtr' ), 
		'wtr_progress_placement_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_width', 
		__( 'Thickness', 'wtr' ), 
		'wtr_progress_width_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_fg', 
		__( 'Foreground', 'wtr' ), 
		'wtr_progress_fg_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_fg_opacity', 
		__( 'Foreground Opacity', 'wtr' ), 
		'wtr_progress_fg_opacity_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_bg', 
		__( 'Background', 'wtr' ), 
		'wtr_progress_bg_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_comments_bg', 
		__( 'Comments Background', 'wtr' ), 
		'wtr_progress_comments_bg_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_transparent', 
		__( 'Transparent Background', 'wtr' ), 
		'wtr_progress_transparent_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_muted_opacity', 
		__( 'Muted Opacity', 'wtr' ), 
		'wtr_progress_muted_opacity_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_mute', 
		__( 'Fixed Opacity', 'wtr' ), 
		'wtr_progress_mute_render', 
		'wtr', 
		'wtr_progress' 
	);

	add_settings_field( 
		'wtr_progress_touch', 
		__( 'Touch Devices', 'wtr' ), 
		'wtr_progress_touch_render', 
		'wtr', 
		'wtr_progress' 
	);

	/*add_settings_field( 
		'wtr_progress_controller', 
		__( 'Controller', 'wtr' ), 
		'wtr_progress_controller_render', 
		'wtr', 
		'wtr_progress' 
	);*/


	#time commitment
	add_settings_section(
		'wtr_time', 
		__( 'Time Commitment', 'wtr' ), 
		'wtr_time_callback', 
		'wtr'
	);

	add_settings_field( 
		'wtr_time_types', 
		__( 'Display On', 'wtr' ), 
		'wtr_time_types_render', 
		'wtr', 
		'wtr_time' 
	);

	add_settings_field( 
		'wtr_time_placement', 
		__( 'Placement', 'wtr' ), 
		'wtr_time_placement_render', 
		'wtr', 
		'wtr_time' 
	);

	add_settings_field( 
		'wtr_time_format', 
		__( 'Format', 'wtr' ), 
		'wtr_time_format_render', 
		'wtr', 
		'wtr_time' 
	);

	add_settings_field( 
		'wtr_time_style_block', 
		__( 'Block-Level', 'wtr' ), 
		'wtr_time_style_block_render', 
		'wtr', 
		'wtr_time' 
	);

	add_settings_field( 
		'wtr_time_style_size', 
		__( 'Font Size', 'wtr' ), 
		'wtr_time_style_size_render', 
		'wtr', 
		'wtr_time' 
	);

	add_settings_field( 
		'wtr_time_style_color', 
		__( 'Font Color', 'wtr' ), 
		'wtr_time_style_color_render', 
		'wtr', 
		'wtr_time' 
	);

}


# progress indicator

function wtr_progress_callback(  ) { 

	echo __( 'Displays a reading progress bar indicator showing the user how far scrolled through the current post they are.', 'wtr' );

}

function wtr_progress_types_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$chk = isset($options['wtr_progress_types_post']) ? $options['wtr_progress_types_post'] : '';
	?>
	<input type='checkbox' id='wtr_settings[wtr_progress_types_post]' name='wtr_settings[wtr_progress_types_post]' <?php checked( $chk, 'post' ); ?> value='post'>
	<label for="wtr_settings[wtr_progress_types_post]">
		<?php echo __( 'Posts', 'wtr' ); ?>
	</label>

	&nbsp;&nbsp;&nbsp;&nbsp;

	<?php $chk = isset($options['wtr_progress_types_page']) ? $options['wtr_progress_types_page'] : ''; ?>
	<input type='checkbox' id='wtr_settings[wtr_progress_types_page]' name='wtr_settings[wtr_progress_types_page]' <?php checked( $chk, 'page' ); ?> value='page'>
	<label for="wtr_settings[wtr_progress_types_page]">
		<?php echo __( 'Pages', 'wtr' ); ?>
	</label>

	&nbsp;&nbsp;&nbsp;&nbsp;

	<?php # get all non built in post types
	$cpts = get_post_types(array('public' => true, '_builtin' => false), 'objects'); 
	foreach ($cpts as $cpt) {

		$chk = isset($options['wtr_progress_types_' . $cpt->name]) ? $options['wtr_progress_types_' . $cpt->name] : ''; 
		?>
		<input type='checkbox' id='wtr_settings[wtr_progress_types_<?php echo $cpt->name; ?>]' name='wtr_settings[wtr_progress_types_<?php echo $cpt->name; ?>]' <?php checked( $chk, $cpt->name ); ?> value='<?php echo $cpt->name; ?>'>
		<label for="wtr_settings[wtr_progress_types_<?php echo $cpt->name; ?>]">
			<?php echo __( $cpt->label, 'wtr' ); ?>
		</label>

		&nbsp;&nbsp;&nbsp;&nbsp;

	<?php } ?>

	<?php $chk = isset($options['wtr_progress_types_home']) ? $options['wtr_progress_types_home'] : ''; ?>
	<input type='checkbox' id='wtr_settings[wtr_progress_types_home]' name='wtr_settings[wtr_progress_types_home]' <?php checked( $chk, 'home' ); ?> value='home'>
	<label for="wtr_settings[wtr_progress_types_home]">
		<?php echo __( 'Home Page', 'wtr' ); ?>
	</label>

	&nbsp;&nbsp;&nbsp;&nbsp;

	<em><?php echo __( 'Unselect all to completely disable.', 'wtr' ); ?></em>

	<?php

}

function wtr_progress_placement_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_progress_placement']) ? $options['wtr_progress_placement'] : 'top';
	$offset = isset($options['wtr_progress_placement_offset']) ? $options['wtr_progress_placement_offset'] : '';
	?>
	<select name='wtr_settings[wtr_progress_placement]'>
		<option value='top' <?php selected( $val, 'top' ); ?>><?php echo __( 'Top', 'wtr' ); ?></option>
		<option value='right' <?php selected( $val, 'right' ); ?>><?php echo __( 'Right', 'wtr' ); ?></option>
		<option value='bottom' <?php selected( $val, 'bottom' ); ?>><?php echo __( 'Bottom', 'wtr' ); ?></option>
		<option value='left' <?php selected( $val, 'left' ); ?>><?php echo __( 'Left', 'wtr' ); ?></option>
	</select>

	<em><?php echo __( 'offset by', 'wtr' ); ?></em>

	<input type="number" name='wtr_settings[wtr_progress_placement_offset]' value="<?php echo $offset; ?>" min='0' max='1000' step='1' />

	<em><?php echo __( 'pixels', 'wtr' ); ?></em>
	
	<?php

}

function wtr_progress_width_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_progress_width']) ? $options['wtr_progress_width'] : '5';
	?>
	<input type="number" name='wtr_settings[wtr_progress_width]' value="<?php echo $val; ?>" min='1' max='10000' />
	<em><?php echo __( 'In pixels', 'wtr' ); ?></em>
	<?php

}

function wtr_progress_fg_opacity_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_progress_fg_opacity']) ? $options['wtr_progress_fg_opacity'] : '.5';
	?>
	<input type="number" name='wtr_settings[wtr_progress_fg_opacity]' value="<?php echo $val; ?>" min='0' max='1' step='.1' />
	<em><?php echo __( 'Higher number = less transparent', 'wtr' ); ?></em>
	<?php

}

function wtr_progress_fg_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_progress_fg']) ? $options['wtr_progress_fg'] : '';
	?>
	<input type="text" name='wtr_settings[wtr_progress_fg]' value="<?php echo $val; ?>" class="color-field" data-default-color="#ef490f" />
	<em><?php echo __( 'The part that moves on scroll.', 'wtr' ); ?></em>
	<?php

}

function wtr_progress_bg_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_progress_bg']) ? $options['wtr_progress_bg'] : '';
	?>
	<input type="text" name='wtr_settings[wtr_progress_bg]' value="<?php echo $val; ?>" class="color-field" data-default-color="#CCCCCC" />
	<em><?php echo __( 'Stationary. Does not apply when Transparent Background is on.', 'wtr' ); ?></em>
	<?php

}

function wtr_progress_muted_opacity_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_progress_muted_opacity']) ? $options['wtr_progress_muted_opacity'] : '.5';
	?>
	<input type="number" name='wtr_settings[wtr_progress_muted_opacity]' value="<?php echo $val; ?>" min='0' max='1' step='.1' />
	<em><?php echo __( 'Bar opacity while idle (not scrolling). .5 is a good setting.', 'wtr' ); ?></em>
	<?php

}

function wtr_progress_mute_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$chk = isset($options['wtr_progress_mute']) ? $options['wtr_progress_mute'] : '';
	?>
	<input type='checkbox' id='wtr_settings[wtr_progress_mute]' name='wtr_settings[wtr_progress_mute]' <?php checked( $chk, 1 ); ?> value='1'>
	<label for="wtr_settings[wtr_progress_mute]"><em>
		<?php echo __( 'Always use the Muted Opacity - opacity will not change on scroll.', 'wtr' ); ?>
	</em></label>
	<?php 

}

function wtr_progress_transparent_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$chk = isset($options['wtr_progress_transparent']) ? $options['wtr_progress_transparent'] : '';
	?>
	<input type='checkbox' id='wtr_settings[wtr_progress_transparent]' name='wtr_settings[wtr_progress_transparent]' <?php checked( $chk, 1 ); ?> value='1'>
	<label for="wtr_settings[wtr_progress_transparent]"><em>
		<?php echo __( 'Only the foreground (scrolling bar) will appear.', 'wtr' ); ?>
	</em></label>
	<?php 

}

function wtr_progress_comments_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$chk = isset($options['wtr_progress_comments']) ? $options['wtr_progress_comments'] : '';
	?>
	<input type='checkbox' id='wtr_settings[wtr_progress_comments]' name='wtr_settings[wtr_progress_comments]' <?php checked( $chk, 1 ); ?> value='1'>
	<label for="wtr_settings[wtr_progress_comments]"><em>
	<?php echo __( 'The post comments should be included in the progress bar length.', 'wtr' ); ?>
	</em></label>
	<?php

}

function wtr_progress_comments_bg_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_progress_comments_bg']) ? $options['wtr_progress_comments_bg'] : '';
	?>
	<input type="text" name='wtr_settings[wtr_progress_comments_bg]' value="<?php echo $val; ?>" class="color-field" data-default-color="#CCCCCC" />
	<em><?php echo __( 'Only applies if Include Comments is on.', 'wtr' ); ?></em>
	<?php

}

function wtr_progress_touch_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$chk = isset($options['wtr_progress_touch']) ? $options['wtr_progress_touch'] : '';
	?>
	<input type='checkbox' id='wtr_settings[wtr_progress_touch]' name='wtr_settings[wtr_progress_touch]' <?php checked( $chk, 1 ); ?> value='1'>
	<label for="wtr_settings[wtr_progress_touch]"><em>
		<?php echo __( 'Display on touch screen devices like phones and tablets.', 'wtr' ); ?>
	</em></label>
	<?php 

}

/*
function wtr_progress_controller_render(  ) { 

	$options = get_option( 'wtr_settings' );
	?>
	<input type='text' name='wtr_settings[wtr_progress_controller]' value='<?php echo $options['wtr_progress_controller']; ?>'>
	<?php

}
*/

# time commitment

function wtr_time_callback(  ) { 

	echo __( 'A text label at the beginning of the post/page informing the user how long it will take them to read it, assuming a 200wpm pace.', 'wtr' );

}


function wtr_time_types_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$chk = isset($options['wtr_time_types_post']) ? $options['wtr_time_types_post'] : '';
	?>
	<input type='checkbox' id='wtr_settings[wtr_time_types_post]' name='wtr_settings[wtr_time_types_post]' <?php checked( $chk, 'post' ); ?> value='post'>
	<label for="wtr_settings[wtr_time_types_post]">
		<?php echo __( 'Posts', 'wtr' ); ?>
	</label>

	&nbsp;&nbsp;&nbsp;&nbsp;

	<?php $chk = isset($options['wtr_time_types_page']) ? $options['wtr_time_types_page'] : ''; ?>
	<input type='checkbox' id='wtr_settings[wtr_time_types_page]' name='wtr_settings[wtr_time_types_page]' <?php checked( $chk, 'page' ); ?> value='page'>
	<label for="wtr_settings[wtr_time_types_page]">
		<?php echo __( 'Pages', 'wtr' ); ?>
	</label>

	&nbsp;&nbsp;&nbsp;&nbsp;

	<em><?php echo __( 'Unselect all to completely disable.', 'wtr' ); ?></em>

	<?php 
}

function wtr_time_placement_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$chk = isset($options['wtr_time_placement']) ? $options['wtr_time_placement'] : '';
	?>
	<select name='wtr_settings[wtr_time_placement]'>
		<option value='before-title' <?php selected( $chk, 'before-title' ); ?>>Before Title</option>
		<option value='after-title' <?php selected( $chk, 'after-title' ); ?>>After Title</option>
		<option value='before-content' <?php selected( $chk, 'before-content' ); ?>>Before Content</option>
	</select>
	<?php

}

function wtr_time_format_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_time_format']) ? $options['wtr_time_format'] : '# min read';
	?>
	<input type="text" name='wtr_settings[wtr_time_format]' value="<?php echo $val; ?>" />
	<em><?php echo __( 'Use # as a placeholder for the number. Example: "# min read" becomes "12 min read".', 'wtr' ); ?></em>
	<?php

}

function wtr_time_style_block_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$chk = isset($options['wtr_time_style_block']) ? $options['wtr_time_style_block'] : '';
	?>
	<input type='checkbox' id='wtr_settings[wtr_time_style_block]' name='wtr_settings[wtr_time_style_block]' <?php checked( $chk, 1 ); ?> value='1'>
	<label for="wtr_settings[wtr_time_style_block]"><em>
		<?php echo __( 'Do not float the label next to anything (make it its own line).', 'wtr' ); ?>
	</em></label>
	<?php 

}

function wtr_time_style_size_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_time_style_size']) ? $options['wtr_time_style_size'] : '';
	?>
	<input type="number" name='wtr_settings[wtr_time_style_size]' value="<?php echo $val; ?>" min='0' max='200' step='1' />
	<em><?php echo __( 'In pixels. Leave blank to use theme font size.', 'wtr' ); ?></em>
	<?php

}

function wtr_time_style_color_render(  ) { 

	$options = get_option( 'wtr_settings' );
	$val = isset($options['wtr_time_style_color']) ? $options['wtr_time_style_color'] : '';
	?>
	<input type="text" name='wtr_settings[wtr_time_style_color]' value="<?php echo $val; ?>" class="color-field" data-default-color="#CCCCCC" />
	<em><?php echo __( 'Leave blank to use theme font color.', 'wtr' ); ?></em>
	<?php

}


function wtr_options_page(  ) { 

	?>
	<div class="wrap" id="wtr">

		<h1>Worth The Read</h1>
		<form action='options.php' method='post'>
		
			<?php
			settings_fields( 'wtr' );
			do_settings_sections( 'wtr' );
			submit_button();
			?>
		
		</form>

	</div>
	<?php

}

?>
