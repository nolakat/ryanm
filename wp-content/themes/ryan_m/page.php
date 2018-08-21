<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ryan_m
 */

get_header();
?>

		<main id="main">
			<div class="main-wrap">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );


		endwhile; // End of the loop.
		?>

		
			</div>
		</main><!-- #main -->

		<div id="gallery-label" class="gallery-label">
  <div id="gallery-label--wrap">
    <div class="gallery-label--content">
      <a id="prev" class="no-barba"> << </a>
      <h1 id="gallery-label--title"></h1>
      <a id="next" class="no-barba"> >> </a>
      </div>
      <h5 id="gallery-label--desc"></h5>
      <span>Buy Print</span>
    </div>
  </div>

<?php

get_footer();
