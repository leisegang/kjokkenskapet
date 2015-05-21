<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package kjokkenskapet
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
            <?php get_sidebar( 'footer' ); ?>
		<div class="site-info">
		Matboks Holding AS Orgnr: 992 401 028 &copy; Kjøkkenskapet
			<span class="sep"> | </span>
Design og hosting av <a href="http://dittnett.com/">DittNett.com</a>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
