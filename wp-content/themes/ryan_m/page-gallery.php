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
</div>
</main>

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
<? get_footer();
