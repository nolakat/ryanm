<?php
   $maintitle = get_sub_field('maintitle');
   $images = get_field('new_gallery');
  $size = 'full'; // (thumbnail, medium, large, full or custom size)
?>

<h1 class="maintitle"> <?php echo $maintitle ?></h1>

<?php if( have_rows('gallery_field') ): ?>

	<ul class="slides">

	<?php while( have_rows('gallery_field') ): the_row(); 

		// vars
		$image = get_sub_field('image');

		?>

		<li class="slide">

			<?php if( $link ): ?>
				<a href="<?php echo $link; ?>">
			<?php endif; ?>

				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" />

			<?php if( $link ): ?>
				</a>
			<?php endif; ?>

		    <?php echo $content; ?>

		</li>

	<?php endwhile; ?>

	</ul>

<?php endif; ?>
