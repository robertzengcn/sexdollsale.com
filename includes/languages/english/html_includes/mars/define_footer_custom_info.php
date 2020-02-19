<div class="testimonialsWrapper">
<div id="testimonials" class="testimonials">
<?php require('./blog/wp-blog-header.php'); ?>
<?php
$rand_posts = get_posts('numberposts=4&orderby=rand');
foreach( $rand_posts as $post ){
?>
<div><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
<?php } ?>
 
</div>
</div>