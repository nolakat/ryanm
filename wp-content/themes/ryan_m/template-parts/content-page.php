<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ryan_m
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">

	</header><!-- .entry-header -->
dsfdsfds

	<div class="entry-content">
		 <?php
		the_content();
		?> 

<?php next_image_link(); ?>


	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
