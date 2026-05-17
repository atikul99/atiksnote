<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package atiksnote
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="site-info">
				<?php echo "© 2026 Atikul. All rights reserved."; ?>
				<span class="sep"> | </span>
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'atiksnote' ) ); ?>">
					<?php
					/* translators: %s: CMS name, i.e. WordPress. */
					printf( esc_html__( 'Developed by %s', 'atiksnote' ), 'Atikul' );
					?>
				</a>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
