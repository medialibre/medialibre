<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
	<?php wp_head(); ?>
</head>

<body id="<?php print get_stylesheet(); ?>" <?php body_class(); ?>>
	<?php do_action( 'body_top' ); ?>
	<a class="skip-content" href="#main"><?php _e( 'Skip to content', 'founder' ); ?> &rarr;</a>
	<div id="overflow-container" class="overflow-container">
		<div id="max-width" class="max-width">
			<?php do_action( 'before_header' ); ?>
			<header class="site-header" id="site-header" role="banner">
				<div id="title-container" class="title-container">
					<?php get_template_part( 'logo' ) ?>
					<?php if ( get_bloginfo( 'description' ) ) {
						echo '<p class="tagline">' . get_bloginfo( 'description' ) . '</p>';
					} ?>
				</div>
				<button id="toggle-navigation" class="toggle-navigation" name="toggle-navigation" aria-expanded="false">
					<span class="screen-reader-text"><?php _e( 'open menu', 'founder' ); ?></span>
					<i class="fa fa-bars" title="<?php _e( 'primary menu icon', 'founder' ); ?>" aria-hidden="true"></i>
				</button>
				<div id="menu-primary-container" class="menu-primary-container">
					<?php get_template_part( 'menu', 'primary' ); ?>
					<?php ct_founder_social_icons_output( 'header' ); ?>
					<?php get_template_part( 'content/search-bar' ); ?>
				</div>
			</header>
			<?php do_action( 'after_header' ); ?>
			<?php get_sidebar( 'primary' ); ?>
			<?php do_action( 'before_main' ); ?>
			<section id="main" class="main" role="main">
				<?php do_action( 'main_top' );
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
				}