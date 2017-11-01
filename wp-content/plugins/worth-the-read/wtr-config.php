<?php

if ( ! class_exists( 'Redux' ) ) {
    return;
}

// This is your option name where all the Redux data is stored.
$opt_name = "wtr_settings";

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    // TYPICAL -> Change these values as you need/desire
    'opt_name'             => $opt_name,
    // This is where your data is stored in the database and also becomes your global variable name.
    'display_name'         => __( 'Worth The Read', 'wtr' ),
    // Name that appears at the top of your panel
    'display_version'      => '1.3.3',
    // Version that appears at the top of your panel
    'menu_type'            => 'menu',
    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu'       => true,
    // Show the sections below the admin menu item or not
    'menu_title'           => __( 'Worth The Read', 'wtr' ),
    'page_title'           => __( 'Worth The Read', 'wtr' ),
    // You will need to generate a Google API key to use this feature.
    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_api_key'       => '',
    // Set it you want google fonts to update weekly. A google_api_key value is required.
    'google_update_weekly' => false,
    // Must be defined to add google fonts to the typography module
    'async_typography'     => true,
    // Use a asynchronous font on the front end or font string
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => false,
    // Show the panel pages on the admin bar
    'admin_bar_icon'       => 'dashicons-portfolio',
    // Choose an icon for the admin bar menu
    'admin_bar_priority'   => 50,
    // Choose an priority for the admin bar menu
    'global_variable'      => '',
    // Set a different name for your global variable other than the opt_name
    'dev_mode'             => false,
    // Show the time the page took to load, etc
    'update_notice'        => true,
    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer'           => true,
    // Enable basic customizer support
    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

    // OPTIONAL -> Give you extra features
    'page_priority'        => null,
    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent'          => 'themes.php',
    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions'     => 'manage_options',
    // Permissions needed to access the options panel.
    'menu_icon'            => 'dashicons-text',
    // Specify a custom URL to an icon
    'last_tab'             => '',
    // Force your panel to always open to a specific tab (by id)
    'page_icon'            => 'icon-themes',
    // Icon displayed in the admin panel next to your menu_title
    'page_slug'            => 'wtr_options',
    // Page slug used to denote the panel
    'save_defaults'        => true,
    // On load save the defaults to DB before user clicks save or not
    'default_show'         => false,
    // If true, shows the default value next to each field that is not the default value.
    'default_mark'         => '',
    // What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export'   => true,
    // Shows the Import/Export panel when not used as a field.

    // CAREFUL -> These options are for advanced use only
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag'           => true,
    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database'             => '',
    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

    'use_cdn'              => true,
    // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

    //'compiler'             => true,

    // HINTS
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'light',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'fade',
                'duration' => '200',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'fade',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    )
);

// ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
/*
$args['admin_bar_links'][] = array(
    'id'    => 'redux-docs',
    'href'  => 'http://docs.reduxframework.com/',
    'title' => __( 'Documentation', 'wtr' ),
);

$args['admin_bar_links'][] = array(
    //'id'    => 'redux-support',
    'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
    'title' => __( 'Support', 'wtr' ),
);

$args['admin_bar_links'][] = array(
    'id'    => 'redux-extensions',
    'href'  => 'reduxframework.com/extensions',
    'title' => __( 'Extensions', 'wtr' ),
);
*/

// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
/*
$args['share_icons'][] = array(
    'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
    'title' => 'Visit us on GitHub',
    'icon'  => 'el el-github'
    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
);
*/

// Panel Intro text -> before the form
/*
if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
    if ( ! empty( $args['global_variable'] ) ) {
        $v = $args['global_variable'];
    } else {
        $v = str_replace( '-', '_', $args['opt_name'] );
    }
    $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'wtr' ), $v );
} else {
    $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'wtr' );
}

// Add content after the form.
$args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'wtr' );
*/

Redux::setArgs( $opt_name, $args );

/*
 * ---> END ARGUMENTS
 */

/*
 * ---> START HELP TABS
 */

/*
$tabs = array(
    array(
        'id'      => 'redux-help-tab-1',
        'title'   => __( 'Theme Information 1', 'wtr' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'wtr' )
    ),
    array(
        'id'      => 'redux-help-tab-2',
        'title'   => __( 'Theme Information 2', 'wtr' ),
        'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'wtr' )
    )
);
Redux::setHelpTab( $opt_name, $tabs );

// Set the help sidebar
$content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'wtr' );
Redux::setHelpSidebar( $opt_name, $content );
*/

/*
 * <--- END HELP TABS
 */


/*
 *
 * ---> START SECTIONS
 *
 */

/*

    As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


 */

// -> START Reading Progress
Redux::setSection( $opt_name, array(
    'title' => __( 'Reading Progress', 'wtr' ),
    'id'    => 'progress',
    'desc'  => __( 'Displays a reading progress bar indicator showing the user how far scrolled through the current post they are.', 'wtr' ),
    'icon'  => 'el el-bookmark-empty'
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Functionality', 'wtr' ),
    'id'         => 'progress-functionality',
    'subsection' => true,
    'desc'		 => __( 'How the progress bar works.', 'wtr' ),
    'fields'     => array(
    	array(
            'id'       => 'progress-display',
            'type'     => 'button_set',
            'title'    => __( 'Display On', 'wtr' ),
            'multi'    => true,
            'options'  => array(
                'post' => 'Posts',
                'page' => 'Pages',
                'home' => 'Home Page'
            ),
            'default'  => array('posts')
        ),
        array(
            'id'       => 'progress-cpts',
            'type'     => 'button_set',
            'multi'    => true,
            'title'    => __( 'Custom Post Types', 'wtr' ),
            'subtitle' => __( 'You can show the progress bar on custom post types, too', 'wtr' ),
            'desc' => __( 'These are added by your theme and/or plugins', 'wtr' ),
            'data'     => 'post_types',
            'args' 	   => array(
            				'public' => true, 
            				'_builtin' => false
            			),
        ),
        array(
            'id'       => 'progress-comments',
            'type'     => 'switch',
            'title'    => __( 'Include Comments', 'wtr' ),
            'subtitle' => __( 'The post comments should be included in the progress bar length', 'wtr' ),
            'default'  => false,
        ),
        array(
            'id'       => 'progress-placement',
            'type'     => 'image_select',
            'title'    => __( 'Placement', 'wtr' ),
            //Must provide key => value(array:title|img) pairs for radio options
            'options'  => array(
                'top' => array(
                    'alt' => 'Top',
                    'img' => ReduxFramework::$_url . 'assets/img/top.png'
                ),
                'bottom' => array(
                    'alt' => 'Bottom',
                    'img' => ReduxFramework::$_url . 'assets/img/bottom.png'
                ),
                'left' => array(
                    'alt' => 'Left',
                    'img' => ReduxFramework::$_url . 'assets/img/left.png'
                ),
                'right' => array(
                    'alt' => 'Right',
                    'img' => ReduxFramework::$_url . 'assets/img/right.png'
                )
            ),
            'default'  => 'top'
        ),
        array(
            'id'            => 'progress-offset',
            'type'          => 'slider',
            'title'          => __( 'Offset', 'wtr' ),
            'subtitle'       => __( 'The progress bar can be offset from the Placement edge specified above', 'wtr' ),
            'desc'           => __( 'This is handy for fixed headers and menus that you don\'t want covered up', 'wtr' ),
            'default'       => 0,
            'min'           => 0,
            'step'          => 1,
            'max'           => 500,
            'display_value' => 'text'
        ),
        array(
            'id'       => 'progress-fixed-opacity',
            'type'     => 'switch',
            'title'    => __( 'Fixed Opacity', 'wtr' ),
            'subtitle' => __( 'Always use the Muted Opacity - opacity will not change on scroll', 'wtr' ),
            'default'  => false,
        ),
        array(
            'id'       => 'progress-touch',
            'type'     => 'switch',
            'title'    => __( 'Touch Devices', 'wtr' ),
            'subtitle' => __( 'Display on touch screen devices like phones and tablets', 'wtr' ),
            'default'  => false,
        ),
        array(
            'id'       => 'progress-placement-touch',
            'type'     => 'image_select',
            'title'    => __( 'Touch Placement', 'wtr' ),
            'subtitle'       => __( 'You can have different placement for touch devices.', 'wtr' ),
            //Must provide key => value(array:title|img) pairs for radio options
            'options'  => array(
                'top' => array(
                    'alt' => 'Top',
                    'img' => ReduxFramework::$_url . 'assets/img/top.png'
                ),
                'bottom' => array(
                    'alt' => 'Bottom',
                    'img' => ReduxFramework::$_url . 'assets/img/bottom.png'
                ),
                'left' => array(
                    'alt' => 'Left',
                    'img' => ReduxFramework::$_url . 'assets/img/left.png'
                ),
                'right' => array(
                    'alt' => 'Right',
                    'img' => ReduxFramework::$_url . 'assets/img/right.png'
                )
            ),
            'default'  => 'top',
            'required' => array('progress-touch', 'equals', '1' )
        ),
        array(
            'id'            => 'progress-offset-touch',
            'type'          => 'slider',
            'title'          => __( 'Touch Offset', 'wtr' ),
            'subtitle'       => __( 'You can have a different offset for touch devices.', 'wtr' ),
            'default'       => 0,
            'min'           => 0,
            'step'          => 1,
            'max'           => 500,
            'display_value' => 'text',
            'required' => array('progress-touch', 'equals', '1' )
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Style', 'wtr' ),
    'id'         => 'progress-style',
    'subsection' => true,
    'desc'		 => __( 'How the progress bar looks.', 'wtr' ),
    'fields'     => array(
        array(
            'id'            => 'progress-thickness',
            'type'          => 'slider',
            'title'          => __( 'Thickness', 'wtr' ),
            'default'       => 5,
            'min'           => 1,
            'step'          => 1,
            'max'           => 500,
            'display_value' => 'text'
        ),
        array(
            'id'       => 'progress-foreground',
            'type'     => 'color',
            //'output'   => array( '.site-title' ),
            'title'    => __( 'Foreground', 'wtr' ),
            'subtitle' => __( 'The part that moves on scroll', 'wtr' ),
            'default'  => '#f44813',
        ),
        array(
            'id'            => 'progress-foreground-opacity',
            'type'          => 'slider',
            'title'          => __( 'Foreground Opacity', 'wtr' ),
            'default'       => 0.5,
            'min'           => 0,
            'step'          => 0.01,
            'max'           => 1,
            'resolution'    => 0.01,
            'display_value' => 'label'
        ),
        array(
            'id'       => 'progress-background',
            'type'     => 'color',
            //'output'   => array( '.site-title' ),
            'title'    => __( 'Background', 'wtr' ),
            'subtitle' => __( 'Stationary. Does not apply when Transparent Background is on', 'wtr' ),
            'default'  => '#FFFFFF',
        ),
        array(
            'id'       => 'progress-comments-background',
            'type'     => 'color',
            //'output'   => array( '.site-title' ),
            'title'    => __( 'Comments Background', 'wtr' ),
            'subtitle' => __( 'Only applies if Include Comments is on.', 'wtr' ),
            'default'  => '#ffcece',
        ),
        array(
            'id'       => 'progress-transparent-background',
            'type'     => 'switch',
            'title'    => __( 'Transparent Background', 'wtr' ),
            'subtitle' => __( 'Only the foreground (scrolling bar) will appear', 'wtr' ),
            'default'  => false,
        ),
        array(
            'id'            => 'progress-muted-opacity',
            'type'          => 'slider',
            'title'         => __( 'Muted Opacity', 'wtr' ),
            'subtitle'		=> __( 'Bar opacity while idle (not scrolling)', 'wtr' ),
            'hint'     		=> array(
                    			'title'   => 'Tip',
                    			'content' => '.50 seems to work pretty well here'
                    		),
            'default'       => 0.5,
            'min'           => 0,
            'step'          => 0.01,
            'max'           => 1,
            'resolution'    => 0.01,
            'display_value' => 'label'
        ),
        array(
            'id'       => 'progress-muted-foreground',
            'type'     => 'color',
            'title'    => __( 'Muted Foreground', 'wtr' ),
            'subtitle' => __( "Foreground color whilte idle (not scrolling)", 'wtr' ),
            'default'  => '#f44813',
        ),
    )
) );


// -> START Time Commitment
Redux::setSection( $opt_name, array(
    'title' => __( 'Time Commitment', 'wtr' ),
    'id'    => 'time',
    'desc'  => __( 'A text label at the beginning of the post/page informing the user how long it will take them to read it, assuming a 200wpm pace.', 'wtr' ),
    'icon'  => 'el el-time'
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Functionality', 'wtr' ),
    'id'         => 'time-functionality',
    'subsection' => true,
    'desc'		 => __( 'How the time commitment label works.', 'wtr' ),
    'fields'     => array(
        array(
            'id'       => 'time-display',
            'type'     => 'button_set',
            'title'    => __( 'Display On', 'wtr' ),
            'multi'    => true,
            'options'  => array(
                'post' => 'Posts',
                'page' => 'Pages'
            ),
            'default'  => array('post')
        ),
        array(
            'id'       => 'time-cpts',
            'type'     => 'button_set',
            'multi'    => true,
            'title'    => __( 'Custom Post Types', 'wtr' ),
            'subtitle' => __( 'You can show the time commitment label on custom post types, too', 'wtr' ),
            'desc' => __( 'These are added by your theme and/or plugins', 'wtr' ),
            'data'     => 'post_types',
            'args'     => array(
                            'public' => true, 
                            '_builtin' => false
                        ),
        ),
        array(
            'id'       => 'time-placement',
            'type'     => 'radio',
            'title'    => __( 'Placement', 'wtr' ),
            'subtitle' => __( 'Only used where specified to display via the options above. If there is nothing selected for Display On or Custom Post Types, the only way to display the time commitment label is by using the shortcode.', 'wtr' ),
            'desc' => __( 'Or you can use this shortcode: <b style="color:#05c134;">[wtr-time]</b>', 'wtr'),
            'options'  => array(
                'before-title' => 'Before Title',
                'after-title' => 'After Title',
                'before-content' => 'Before Content'
            ),
            'default'  => 'after-title'
        ),
        array(
            'id'       => 'time-format',
            'type'     => 'text',
            'title'    => __( 'Format', 'wtr' ),
            'subtitle' => __( 'Use # as a placeholder for the number', 'wtr' ),
            'desc'     => __( 'Example: "# min read" becomes "12 min read"', 'wtr' ),
            'default'  => '# min read',
        ),
        array(
            'id'       => 'time-block-level',
            'type'     => 'switch',
            'title'    => __( 'Block-Level', 'wtr' ),
            'subtitle' => __( 'Do not float the label next to anything (make it its own line)', 'wtr' ),
            'default'  => false,
        )
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Style', 'wtr' ),
    'id'         => 'time-style',
    'subsection' => true,
    'desc'		 => __( 'How the time commitment label looks.', 'wtr' ),
    'fields'     => array(
        array(
            'id'       => 'time-typography',
            'type'     => 'typography',
            'title'    => __( 'Font', 'wtr' ),
            'subtitle' => __( 'Leave unselected to use theme defaults', 'wtr' ),
            'google'   => true,
            'output'   => array('.wtr-time-wrap'),
            'default'  => array(
                'color'       => '#CCCCCC',
                'font-size'   => '16px',
            ),
        ),
        array(
            'id'       => 'time-css',
            'type'     => 'ace_editor',
            'title'    => __( 'Custom CSS', 'wtr' ),
            'mode'     => 'css',
            'theme'    => 'monokai',
            'default'  => "
.wtr-time-wrap{ 
	/* wraps the entire label */
	margin: 0 10px;

}
.wtr-time-number{ 
	/* applies only to the number */
	
}"
        ),
    )
) );

/*
 * <--- END SECTIONS
 */


?>