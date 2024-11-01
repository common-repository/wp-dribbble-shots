<?php $shots = get_the_shots();

foreach ($shots as $shot):
	$date = date( 'M d, Y', strtotime( $shot->created_at ) ); 
?>
<div class="wp-dribbble-shot">
	<h4><a href="<?php echo $shot->url; ?>" target="_blank"><?php echo $shot->title; ?></a></h4>
	<p class="wp-dribbble-date"><?php echo $date; ?></p>
	<a class="wp-dribbble-image-link" href="<?php echo $shot->image_url; ?>" title="<?php echo $shot->title; ?>">
		<img src="<?php echo $shot->image_url; ?>" alt="<?php echo $shot->title; ?>"/>
	</a>
	<ul class="wp-dribbble-toolbar">
		<li class="views"><span><?php echo $shot->views_count; ?></span></li>
		<li class="comments"><a target="_blank" href="<?php echo esc_url($shot->url.'#comments'); ?>" title="View comments on this screenshot"><span><?php echo $shot->comments_count; ?></span></a></li>
		<li class="likes"><a target="_blank" href="<?php echo esc_url($shot->url.'/fans'); ?>" title="See fans of this screenshot"><span><?php echo $shot->likes_count; ?></span></a></li>
	</ul>
</div>
<?php endforeach; ?>