<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>
<style type="text/css">
.fa-table { border: 2px solid #cebb90 !important; border-collapse: collapse !important;
            margin-left: auto; margin-right: auto;}
.fa-table td,th { border: 1px solid #cebb90 !important;
                  padding: 5px; vertical-align: top; }
.fa-table th { border: 2px solid #cebb90 !important; background: #d2dbeb !important; }
#fa-month-grid { width: 100%; }
.fa-hdr1, .fa-hdr3 { text-align: left; padding: 6px; }
.fa-hdr2 { font-size: .8em; }
.fa-count { font-size: .9em; }
.fa-count, .fa-total, .fa-day, .fa-cmt { text-align: right; }
#fa-all-posts { width:100%; margin-top: 1.5em; margin-left: auto;
                                   margin-right: auto; }
.fa-day { font-weight: bold; }
</style>
<?php if (have_posts()) : the_post(); ?>
<div class="post" id="post-<?php the_ID(); ?>">
	<br />
	<h1 style="text-align:center;">Archives</h2><br />
	<div id="archives" class="content">
		<?php
			//af_ela_super_archive();
			if (function_exists('month_grid_archive')) {
				month_grid_archive();
				all_posts_archive();
			} else {
				echo '<ul>';
				wp_get_archives('type=monthly&show_post_count=1');
				echo '</ul>';
			}
		?>
	</div>
</div>

<?php else : ?>
	<div class="errorbox">
		<?php _e('Sorry, no posts matched your criteria.', 'blocks2'); ?>
	</div>
<?php endif; ?>

<?php
	if (function_exists('wp_list_comments')) {
		comments_template('', true);
	} else {
		comments_template();
	}
?>

<?php get_footer(); ?>
