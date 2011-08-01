<?php 
global $skip_header, $skip_nav;
$skip_header = 0;
$skip_nav = 0;
get_header(); ?>

<div style="border-bottom:3px solid #ccc;padding:5px;" id="post-<?php the_ID(); ?>">
	<h1 style="float:left;">
		<?php
			if (is_search()) {
				_e('Search Results', 'blocks2');
			} else {
				_e('Archives', 'blocks2');
			}
		?>
	</h1>
	<div class="meta">
		<div class="info" style="float:right;">
<?php
// If this is a search
if (is_search()) {
	printf( __('Keyword: &#8216;%1$s&#8217;', 'blocks2'), wp_specialchars($s, 1) );
// If this is a category archive
} elseif (is_category()) {
	printf( __('Archive for the &#8216;%1$s&#8217; Category', 'blocks2'), single_cat_title('', false) );
// If this is a tag archive
} elseif (is_tag()) {
	printf( __('Posts Tagged &#8216;%1$s&#8217;', 'blocks2'), single_tag_title('', false) );
// If this is a daily archive
} elseif (is_day()) {
	printf( __('Archive for %1$s', 'blocks2'), get_the_time(__('F jS, Y', 'blocks2')) );
// If this is a monthly archive
} elseif (is_month()) {
	printf( __('Archive for %1$s', 'blocks2'), get_the_time(__('F, Y', 'blocks2')) );
// If this is a yearly archive
} elseif (is_year()) {
	printf( __('Archive for %1$s', 'blocks2'), get_the_time(__('Y', 'blocks2')) );
// If this is an author archive
} elseif (is_author()) {
	printf( __('Archive by %1$s', 'blocks2'), get_the_author() );
// If this is a paged archive
} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
	_e('Blog Archives', 'blocks2');
}
?>
		</div>
		<div class="fixed"></div>
	</div>
	</div>
	<br />
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
		<div class="post">
    		<div class="datewidget">
	            <span class="month"><?php the_time('M'); ?></span>
		    <span class="day"><?php the_time('jS'); ?></span>
		</div>		
		<h2 class="title indent"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<div class="meta indent">
			<div class="info">
				<?php the_time('Y') ?>
				<?php if ($options['author']) : ?>
					 | <?php echo 'By '; the_author_posts_link(); ?>
				<?php endif; ?>
				<?php if ($options['categories']) : ?>
					 | <?php echo 'In '; the_category(', '); ?>
				<?php endif; ?>
			</div>
			<div class="comments">
				<?php
					comments_popup_link('No comments', '1 comment','% comments');
					edit_post_link('Edit', ' | ', '');
					echo '&nbsp;|&nbsp;', get_post_meta($post->ID, 'views', true), ' views';
				?>
			</div>
			<div style="clear:right;"></div>
		</div>

			<div class="content">
			    <?php the_content('<b>Read more...</b>');
			    socialbar($post, true);
			    ?>
			</div>
		</div>
		<hr class="post_separator" />
		<?php endwhile; ?>

	<?php else : ?>
		<div class="errorbox">
			<?php _e('Sorry, no posts matched your criteria.', 'blocks2'); ?>
		</div>

	<?php endif; ?>

<?php comments_template(); ?>

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
