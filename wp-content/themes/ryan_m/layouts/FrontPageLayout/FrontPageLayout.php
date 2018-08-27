<?php
$title = get_field('test');


?>
<div id="fullslide-grid">

    <?php if( have_rows('fullsliderfield') ): ?>

	<!-- <ul class="slides"> -->
    <?php echo "<ul class='sb-slider slick-slider'>";

      while( have_rows('fullsliderfield') ): the_row(); 

		// vars
		$image = get_sub_field('image');
        $title = get_sub_field('imagetitle');
        $year = get_sub_field('imageyear');
        $size = get_sub_field('imagesize');

        ?>

        <!-- <li class="full-img"> -->
        <li class="slide" style="background-image: url('<?php echo $image['url']; ?>'); background-size: <?php echo $size ?>;">
        <!-- <img class="no-barba" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" /> -->
        <div class="content-wrap">
        <div class="content">
        <span><a href=<?php echo $title['url'] ?>><?php echo $title['title'] ?> / <?php echo $year ?></a></span>

</div>
</div>
        </li>

	<?php endwhile; ?>

	</ul>

<?php endif; ?>


</div>
<script>
   

</script>

