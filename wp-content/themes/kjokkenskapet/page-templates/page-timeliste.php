<?php
/**
 * Template Name: Timeliste
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

<div class="timeliste">  
    <div id="side">
        <article>
            <div class="column-box-inside">  
            <header class="entry-header-box">
                <h2 class="entry-title"></h2>
            </header><!-- .entry-header -->
            <div class="entry-content-box">
            
            
            <?php include($_SERVER['DOCUMENT_ROOT'].'/ansatt/views/default/index.php');?>
            
            
            
            </div><!-- .entry-content -->
            </div>
        </article><!-- #post-## -->
	</div><!-- #side -->
 </div>


<?php get_footer(); ?>
