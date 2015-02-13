<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the #content div and all content after
 */
?>
	</div><!-- #content -->
	</div><!-- wide contenitor-->
	<footer id="colophon" class="site-footer" role="contentinfo">
  		 <div class="widget-footer container">
   			<?php get_sidebar( 'footer' ); ?>
   		</div><!-- .widget-footer -->
		<div class="site-info">
        <?php get_template_part( 'content/social' ); // Social link ?>
        
		<?php esc_attr_e( '&copy;', 'impresa' ); ?> <?php _e( date( 'Y' ) ); ?> <a href="<?php echo esc_url(home_url( '/' )) ?>" target="_blank" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
		<?php bloginfo( 'name' ); ?>
        </a>        
        <span class="sep"> | </span>		 
		<?php printf( __( 'Theme: %1$s by %2$s ', 'impresa' ), 'Big Impresa', '<a href="http://www.iograficathemes.com/" rel="designer">Iografica Themes</a>' ); ?>
        <span class="sep"> | </span>
		<?php printf( __( 'Powered by ', 'impresa' )); ?><a href="http://wordpress.org/" rel="generator">
		<?php printf( __( '%s', 'impresa' ), 'WordPress' ); ?></a>             
        </div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>