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
<div id="side">
		<main id="main" class="site-main" role="main">
             <?php $query = new WP_Query( 'cat=194&posts_per_page=3' ); ?>
			<?php $countposts = 0; while ( $query->have_posts() ) : $query->the_post(); $countposts++;?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php if($countposts == 3  ) { echo 'class="last"'; } ?>>
                        <?php 
                    if (has_post_thumbnail()) {
                        echo '<div class="single-post-thumbnail clear">';
                        echo '<div class="image-shifter">';
                        kjokkenskapet_the_responsive_thumbnail( get_the_ID() );
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                    <div class="index-box">
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_content(); ?>
                        <?php
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . __( 'Pages:', 'kjokkenskapet' ),
                                'after'  => '</div>',
                            ) );
                        ?>
                    </div><!-- .entry-content -->
                     </div>
                </article><!-- #post-## -->

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
</div><!-- #side -->
</div>


<div class="column-box-1">  
<div id="side">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
             <?php $query = new WP_Query( 'page_id=1806' ); ?>
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php 
                    if (has_post_thumbnail()) {
                        echo '<div class="single-post-thumbnail clear">';
                        echo '<div class="image-shifter">';
                        kjokkenskapet_the_responsive_thumbnail( get_the_ID() );
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                    <div class="index-box">
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_content(); ?>
                        <?php
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . __( 'Pages:', 'kjokkenskapet' ),
                                'after'  => '</div>',
                            ) );
                        ?>
                    </div><!-- .entry-content -->
                     </div>
                </article><!-- #post-## -->

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- #side -->
</div>

<?php get_sidebar(); ?>

<div class="column-box-2">  
    <div class="column-box-inside">             
        <?php
        $post_id = 1813;
        $queried_post = get_post($post_id);
        $content = $queried_post->post_title;
        $content = $queried_post->post_content;
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        echo $content;
        ?>
    </div>
</div>
    
<div class="column-box-3">  
    <div class="column-box-inside">             
        <?php
        $post_id = 1806;
        $queried_post = get_post($post_id);
        $content = $queried_post->post_title;
        $content = $queried_post->post_content;
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        echo $content;
        ?>
    </div>
</div>

<?php get_footer(); ?>
