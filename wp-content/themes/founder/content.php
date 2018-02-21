<div <?php post_class(); ?>>
	<?php do_action( 'post_before' ); ?>
	<article>
		<div class='post-header'>
			<h2 class='post-title'><?php the_title(); ?></h2>
		</div>
		<?php ct_founder_featured_image(); ?>
        <div class="post-after">
			<?php do_action( 'post_after' ); ?>
            <?php
                $custom_fields = get_post_custom();
                $source_title = $custom_fields['source_title'];
                $source_url = $custom_fields['source_url'];
				if ($source_url[0]) {
                    echo "<div class='post-source'>";
              		echo "<span><i class='fa fa-user'></i> </span> <a href='" . $source_url[0] . "' target='_blank'>" . $source_title[0] . "</a>";
                    if ($source_url[1]) {
                        echo " &bull; <a href='" . $source_url[1] . "' target='_blank'>" . $source_title[1] . "</a>";
                    }
                    echo "</div>";
              	}
            ?>
			<?php get_template_part( 'content/post-categories' ); ?>
            <?php
            	$reading_time = $custom_fields['reading_time'];
            	if ($reading_time) {
                    echo "<div class='post-reading'>";
                    echo "<span><i class='fa fa-clock-o'></i> </span> ≃" . $reading_time[0] . "' λεπτά";
                    echo "</div>";
                }
            ?>
			<?php get_template_part( 'content/post-tags' ); ?>
            <?php get_template_part( 'content/post-meta' ); ?>
			<?php 
				//get_template_part( 'content/post-nav' );
			?>
		</div>
		<div class="post-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . __( 'Pages:', 'founder' ),
				'after'  => '</p>',
			) ); ?>
		</div>
	</article>
	<?php comments_template(); ?>
</div>