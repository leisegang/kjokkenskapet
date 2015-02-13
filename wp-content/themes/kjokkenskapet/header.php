<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Kjøkkenskapet
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'kjokkenskapet' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="container site-branding">
        
        <?php if ( get_header_image() ) : ?>
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" class="header-image">
		<?php endif; // End header image check. ?>
        
       <div class="five columns branding">
		
       		<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="site-logo" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"> 		
            <img src="<?php echo of_get_option('logo_uploader'); ?>" alt="<?php echo esc_attr( bloginfo( 'name' )); ?>"></a></h1>
      </div> <!-- .five columns branding--> 
      <div class="eleven columns navmenu">
        <nav id="site-navigation" class="main-navigation" role="navigation">
			<h1 class="menu-toggle"><?php _e( 'Menu', 'kjokkenskapet' ); ?></h1>
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'kjokkenskapet' ); ?></a>
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- #site-navigation -->
      </div> <!-- .eleven columns navmenu-->
      </div><!-- .container site-branding-->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
