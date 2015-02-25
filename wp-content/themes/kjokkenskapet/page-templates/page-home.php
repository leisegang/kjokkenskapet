<?php
/**
 * Template Name: Home Page
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package kjokkenskapet
 */


get_header(); ?>

	<div class="column-box-1">  
		<main id="featured" class="featured-posts" role="main">

             <?php $query = new WP_Query( 'cat=194&posts_per_page=3&orderby=ID&order=ASC' ); ?>
			<?php $countposts = 0; while ( $query->have_posts() ) : $query->the_post(); $countposts++;?>

				<article id="<?php if($countposts == 3) { ?>last<?php } ?>" <?php post_class(); ?>>
                        <?php 
                    if (has_post_thumbnail()) {
                        echo '<div class="single-post-thumbnail clear">';
                        the_post_thumbnail();
                        echo '</div>';
                    }
                    ?>
                    <div class="featured-box">
                    <header class="entry-header">
                        <h2 class="entry-title"><?php the_title(); ?></h2>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->
                    </div>
                </article><!-- #post-## -->
			<?php endwhile; // end of the loop. ?>
		</main><!-- #main -->
    </div>
        

<div class="column-box-2">  
<div id="side">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
             <?php $query = new WP_Query( 'page_id=1806' ); ?>
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php 
                    if (has_post_thumbnail()) {
                        echo '<div class="single-post-thumbnail clear">';
                        the_post_thumbnail();
                        echo '</div>';
                    }
                    ?>
                    <div class="index-box">
                    <header class="entry-header">
                        <h2 class="entry-title"><?php the_title(); ?></h2>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->
                     </div>
                </article><!-- #post-## -->

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- #side -->
</div>

<?php get_sidebar(); ?>
<div class="clear"></div>  

	<div class="column-box-3">  

             <?php $query = new WP_Query( 'page_id=1843' ); ?>
			<?php while ( $query->have_posts() ) : $query->the_post();?>

				<article <?php post_class(); ?>>
                        <?php 
                    if (has_post_thumbnail()) {
                        echo '<div class="single-post-thumbnail clear">';
                        the_post_thumbnail( 'medium' );
                        echo '</div>';
                    }
                    ?>
                    <div class="column-box-inside">  
                    <header class="entry-header-box">
                        <h2 class="entry-title"><?php the_title(); ?></h2>
                    </header><!-- .entry-header -->
<br>
                    <div class="entry-content-box">
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->
                    </div>
                </article><!-- #post-## -->
			<?php endwhile; // end of the loop. ?>
    </div>


<?php get_footer(); ?>
