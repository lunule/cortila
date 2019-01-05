<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Gutenbergtheme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-chocolat-title="<?php the_title(); ?>">
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
	
		<div class="entry-thumbnail">			

			<?php
			$thumbnail_ID 		= get_post_thumbnail_id( $post->ID );
			$thumbnail_Obj 		= wp_get_attachment_image_src( $thumbnail_ID, 'full', false );
			$thumbnail_width 	= $thumbnail_Obj[1];

			if ( (int) $thumbnail_width < 2000 ) $thumbnail_size = 'full';
			if ( (int) $thumbnail_width >= 2000 ) $thumbnail_size = 'single-post-thumbnail';			 
			the_post_thumbnail( $thumbnail_size );
			?>

		</div>

	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
