<?php
/*
 * Template Name: Barebone
 * template to show a page without additional information
 * just the article (+ comments, if allowed).
 *
 * respects certain post meta variables.
 *
 * March 2010, by silvercircle .at. gmail .dot. com | http://blog.miranda.or.at/
 * This template is based on the blocks2 theme
 */
    global $sidebar_style, $display_sidebar;
    if(isset($_GET['action']) && $_GET['action'] == 'print') {
		include dirname(__FILE__) . '/print.php';
		die;
    }
    if(get_post_meta($post->ID, 'no_sidebar', true) == 1) {
		$sidebar_style = "display:none;";
		$display_sidebar = "0";
    } else {
		$sidebar_style = "display:table-cell;";
		$display_sidebar = "1";
    }
    get_header();
?>
    <?php if (have_posts()) : the_post(); ?>

    <div class="post" style="background:transparent;border:none;" id="post-<?php the_ID(); ?>">
    <div class="content">
    	<?php the_content();
	    if (function_exists('TA_content_jump')) {
		echo '<div style="clear:both;text-align:center;display:block;margin-top:10px;">'; TA_content_jump(1); echo '</div>';
	    }
	    $field = get_post_meta($post->ID, 'no_share_links', true);
		if($field != '1') {
		    template_share_link();
		}
	    ?>

	    <div class="fixed"></div>

	    <?php if(get_post_meta($post->ID, 'ratings', true) === '1') {
			the_ratings();
	    } ?>
	    </div>
	    <div style="float:right;margin:3px;">
	        <a title="Printable version" href="<?php echo get_permalink() . '?action=print'; ?>">Printable version</a>
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
