<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Gutenbergtheme
 */
?>

</div><!-- #page -->

<footer id="colophon" class="site-footer">

	<div class="wrap--footer-navigation">

		<nav class="footer-navigation">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-2',
				'menu_id'        => 'footer-menu',
			) );
			?>
		</nav><!-- #site-navigation -->

	</div>

</footer><!-- #colophon -->

<?php wp_footer(); ?>

</body>
</html>
