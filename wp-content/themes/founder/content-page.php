<div <?php post_class(); ?>>
	<?php do_action( 'page_before' ); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
		</div>
		<?php ct_founder_featured_image(); ?>
		<div class="post-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . __( 'Pages:', 'founder' ),
				'after'  => '</p>',
			) ); ?>
		</div>
		<?php do_action( 'page_after' ); ?>
	</article>
	<?php comments_template(); ?>
</div>