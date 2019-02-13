<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Gutenbergtheme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		
		<div class="wrap--entry-title">

			<?php
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			?>

			<div class="entry-meta"><?php cortila_posted_on(); ?></div><!-- .entry-meta -->

		</div>
		
		<?php
		if ( class_exists('acf') ) :

			$display_opts 		= get_field('display-opts');
			$loop_featimg_ID 	= get_field('loop-featimg');

			if ( has_post_thumbnail() && 
				 ( 
					( NULL == $display_opts ) || 
					( 'default' == $display_opts ) 
				 ) 
			   ) : ?>

				<div class="entry-thumbnail">
					<?php the_post_thumbnail( 'blog-post-thumbnail' ); ?>
				</div>

			<?php elseif ( ( '' !== $loop_featimg_ID ) && ( 'alternative' == $display_opts ) ) :

				$mimetype 	= get_post_mime_type( $loop_featimg_ID );
				$imgsize 	= ( 'image/gif' == $mimetype ) ? 'full' : 'blog-post-thumbnail';

				// set the default src image size
				$image_src_Arr 	= wp_get_attachment_image_src( $loop_featimg_ID, $imgsize );

				$image_src 		= $image_src_Arr[0];
				$image_width 	= $image_src_Arr[1];
				$image_height 	= $image_src_Arr[2];
				?>

				<div class="entry-thumbnail">
					<?php echo "<img src='{$image_src}' width='{$image_width}' height='{$image_height}' alt='' />"; ?> 
				</div>

			<?php endif;

		else :

			if ( has_post_thumbnail() ) : ?>

				<div class="entry-thumbnail">
					<?php the_post_thumbnail( 'blog-post-thumbnail' ); ?>
				</div>

			<?php endif;

		endif; ?>
		
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		if ( !has_excerpt() ) :

			/* wp_trim_words(
				get_the_content( sprintf(
					wp_kses(
						// translators: %s: Name of current post. Only visible to screen readers
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'cortila' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				) ),
				40,
				'...'
			);*/

			the_content();

		else :

			the_excerpt();

		endif;

		?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
