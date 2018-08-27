<?php
/*
Template Name: Full Slider
*/

get_header();
?>

		<main id="main" class="page-fullslider">
			<div class="main-wrap">
      <?php print get_template_layout('FrontPageLayout'); ?>

			</div>
		</main><!-- #main -->

        <div id="gallery-label" class="gallery-label">
  <div id="gallery-label--wrap">
    <div class="gallery-label--content">
      <a id="prev" class="no-barba"> <i class="fas fa-chevron-left"></i> </a>
      <h1 id="gallery-label--title"></h1>
      <a id="next" class="no-barba"> <i class="fas fa-chevron-right"></i> </a>
      </div>
      <h5 id="gallery-label--desc"></h5>
      <span>Buy Print</span>
    </div>
  </div>


<?php

get_footer();
