<?php
/**
 * The Template for displaying all single posts.
 *
 * @package kjokkenskapet
 */

get_header(); ?>
<div id="side">

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<div class="index-box">  
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>
                    
                        <?php 
                        if ( get_the_author_meta( 'description' ) ) { 
                            echo '<hr>';
                            echo '<div class="author-index shorter">';
                            get_template_part('inc/author','box');
                            echo '</div>';
                        }
                        ?>

			<?php kjokkenskapet_post_nav(); ?>
    </div>
		<?php endwhile; // end of the loop. ?>
</div>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_sidebar(); ?>
</div><!-- #side -->

<?php get_footer(); ?>
