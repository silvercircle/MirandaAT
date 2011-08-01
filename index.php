<?php 
	global $skip_header, $skip_nav; $skip_header = 0; $skip_nav = 0;
	$options = get_option('blocks2_options'); 
	get_header(); 
?>
<?php if (have_posts()) : ?>
	<?php while (have_posts()) : 
			the_post(); 
			//update_post_caches($posts); 
	?>
<div class="post" id="post-<?php the_ID(); ?>">
	<div class="datewidget">
		<span class="month"><?php the_time('M'); ?></span>
		<span class="day"><?php the_time('jS'); ?></span>
	</div>
	<h2 class="title indent"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	<div class="meta indent">
		<div class="info">
			<?php the_time(/* 'M jS, Y */ 'Y, H:i e');
			    echo ' | By '; the_author_posts_link();
			    echo ' | In '; the_category(', '); 
			?>
		</div>
		<div class="comments">
			<?php
				comments_popup_link('No comments', '1 comment','% comments');
				edit_post_link('Edit', ' | ', '');
				echo '&nbsp;|&nbsp;', get_post_meta($post->ID, 'views', true), ' views ';
			?>
		</div>
		<div style="clear:right;"></div>
	</div>

	<div class="content">
	    <?php 
	    the_content('Read more...');
	    socialbar($post, true);
	    ?>
	</div>
</div>
<hr class="post_separator" />
<?php endwhile; ?>

<?php else : ?>
	<div class="errorbox">
		<?php echo 'Sorry, no posts matched your criteria.'; ?>
	</div>
<?php endif; ?>

<div id="pagenavi">
	<?php if(function_exists('wp_pagenavi')) : ?>
		<?php wp_pagenavi() ?>
	<?php else : ?>
		<span class="alignleft floatleft"><?php previous_posts_link(__('&laquo; Newer Entries', 'blocks2')); ?></span>
		<span class="alignright floatright"><?php next_posts_link(__('Older Entries &raquo;', 'blocks2')); ?></span>
	<?php endif; ?>
	<div class="fixed"></div>
</div>

<?php get_footer(); ?>
