<?php
/*
Template Name: Gallery Layout
*/

$images = get_field('newgallery');
$images_count = count($images);

?>

<?php get_header(); ?>
<main id="main">
  <div class="main-wrap">

<?php print get_template_layout('GalleryLayout'); ?>
<!-- </div>
</div> -->
</div>
</main>
<div id="gallery-label">
  <div id="gallery-label--wrap" class="fadeOut">
<div class="gallery-label--content">
<a id="prev" class="no-barba"> << </a>
<h1 id="gallery-label--title"></h1>
<a id="next" class="no-barba"> >> </a>
</div>
<h5 id="gallery-label--desc"></h5>
<span>Buy Print</span>
</div>
</div>
<? get_footer();
