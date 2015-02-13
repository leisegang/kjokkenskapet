<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package kjokkenskapet
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

       <nav id="site-navigation" class="main-navigation" role="navigation">
           <h1 class="menu-toggle"><?php _e( 'Menu', 'kjokkenskapet' ); ?></h1>
           <a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'kjokkenskapet' ); ?></a>

           <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
       </nav><!-- #site-navigation -->

<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
       <div id="headimg">
           <?php if ( get_header_image() ) : ?>
           <img src="<?php header_image(); ?>" alt="">
           <?php else : ?>
           <h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
           <div class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
           <?php endif; ?>
       </div>


	</header><!-- #masthead -->

	<div id="content" class="site-content">
