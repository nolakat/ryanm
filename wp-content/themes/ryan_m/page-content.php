<?php
/*
Template Name: Content Layout
*/

$title = get_field('title');
$subtitle = get_field('subtitle');
$content = get_field('content');
$datepicker = get_field ('datepicker');
$bgimg = get_field('bgimg');
$igthing = get_field('igthing');
?>

<?php get_header(); ?>
<main id="main">
  <div class="main-wrap">
<div class="test-wrap">
   <div class="col">
       <h1 class="title"><?php echo $title ?></h1>
       <h5><?php echo $subtitle ?></h5>
       <div class="content-box">
            <?php echo $content ?>
    </div>
    </div>
<div class="col bgimg"  <?php if( $showbg ): ?> style="background-image: url('<?php echo $bgimg['url']; ?>');" <?php endif; ?>>
    <?php echo $igthing ?> 
    </div>

</main>


<div id="gallery-label" class="gallery-label test">
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