<?php
/**
 * Outputs the single post content. Displayed by single.php.
 * 
 * @package kjokkenskapet
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
<?php
    if( $wp_query->current_post == 0 && !is_paged() && is_front_page() ) { // Custom template for the first post on the front page
        if (has_post_thumbnail()) {
            echo '<div class="front-index-thumbnail clear">';
            echo '<div class="image-shifter">';
            echo '<a href="' . get_permalink() . '" title="' . __('Read ', 'kjokkenskapet') . get_the_title() . '" rel="bookmark">';
            kjokkenskapet_the_responsive_thumbnail( get_the_ID() );
            echo '</a>';
            echo '</div>';
            echo '</div>';
        }
        echo '<div class="index-box';
        if (has_post_thumbnail()) { echo ' has-thumbnail'; };
        echo '">';
    } else {
        echo '<div class="index-box">';
        if (has_post_thumbnail()) {
            echo '<div class="small-index-thumbnail clear">';
            echo '<a href="' . get_permalink() . '" title="' . __('Read ', 'kjokkenskapet') . get_the_title() . '" rel="bookmark">';
            echo the_post_thumbnail('index-thumb');
            echo '</a>';
            echo '</div>';
        }

    }
    ?>
      
	<header class="entry-header clear">
            
            <?php
                /* translators: used between list items, there is a space after the comma */
                $category_list = get_the_category_list( __( ', ', 'kjokkenskapet' ) );

                if ( kjokkenskapet_categorized_blog() ) {
                    echo '<div class="category-list">' . $category_list . '</div>';
                }
            ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
                    <?php kjokkenskapet_posted_on(); ?>
                    <?php 
                    if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) { 
                        echo '<span class="comments-link">';
                        comments_popup_link( __( 'Leave a comment', 'kjokkenskapet' ), __( '1 Comment', 'kjokkenskapet' ), __( '% Comments', 'kjokkenskapet' ) );
                        echo '</span>';
                    }
                    ?>
                    <?php edit_post_link( __( 'Edit', 'kjokkenskapet' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-meta -->
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

	<footer class="entry-footer">
		<?php
			echo get_the_tag_list( '<ul><li><i class="fa fa-tag"></i>', '</li><li><i class="fa fa-tag"></i>', '</li></ul>' );
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
