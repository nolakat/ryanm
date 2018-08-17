<?php 

$images = get_sub_field('newgallery');
$size = 'full'; // (thumbnail, medium, large, full or custom size)

if( $images ): ?>
        <div id="gallery-overlay" class="hidden"></div>


<div id ="gallery-container">
<?php foreach( $images as $image ): ?>
<!-- <div class="image-wrap" style="background-image: url('<?php echo $image['url']; ?>');"> -->
<div class="image-wrap">

<?php echo wp_get_attachment_image( $image['ID'], $size, "", array( "id" => $image['ID'], "title" => $image['title'], "desc" => $image['description'] ) ); ?>
        </div>
        
        <?php endforeach; ?>        
</div>
<?php endif; ?>

<script>


</script>





