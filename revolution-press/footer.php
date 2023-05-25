<?php
// Do not allow direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The template for displaying the footer
 * Contains the closing of the #content div and all content after.
 */
?>
	</div><!-- #content -->
		<?php
		if ((is_front_page() or is_home()) and get_theme_mod('revolution_press_recent_post_home')) {
		    echo esc_html(revolution_press_recent_post_slider());	
		}
		?>		
	<footer id="colophon" class="site-footer" itemscope="itemscope" itemtype="http://schema.org/WPFooter">
			<div class="footer-center">
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<div class="footer-widgets">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<div class="footer-widgets">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<div class="footer-widgets">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
					<div class="footer-widgets">
						<?php dynamic_sidebar( 'footer-4' ); ?>
					</div>
				<?php endif; ?>
			</div>		
		<div class="site-info">
		<?php
		if ( get_theme_mod( 'revolution_press_copyright' ) ) {
			echo wp_kses_post( get_theme_mod( 'revolution_press_copyright' ) );
		}
		else { ?>
			<a class="powered" href="<?php echo esc_url( __( 'https://wordpress.org/', 'revolution-press' ) ); ?>">
				<?php
				esc_html_e( 'Powered by WordPress', 'revolution-press' );
				?>
			</a>
			<p>
				<?php esc_html_e( 'All rights reserved', 'revolution-press' ); ?>  &copy; <?php bloginfo( 'name' ); ?>			
				<a title="SEOS THEMES - Revolution Press" href="<?php echo esc_url( 'https://seosthemes.com/', 'revolution-press' ); ?>" target="_blank"><?php esc_html_e( 'Revolution Press Theme by SEOS THEMES', 'revolution-press' ); ?></a>
			</p>
		<?php } ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
	<?php if( get_theme_mod( 'activate_back_to_top', true ) ) { ?>
	<a href="#" class="cd-top text-replace js-cd-top"><span class="dashicons dashicons-arrow-up-alt2"></span></a>
	<?php } ?>
<?php wp_footer(); ?>
</body>
</html>