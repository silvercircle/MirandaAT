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
    global $sidebar_style, $display_sidebar, $post;
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
		echo '<div class="content_jump">'; TA_content_jump(1); echo '</div>';
   		socialbar($post, false);
   		$views = get_post_meta($post->ID, 'views', true);
   		$views++;
	    update_post_meta($post->ID, 'views', $views);
	    ?>
        <div style="float:right;font-size:11px;"><?php edit_post_link(__('Edit, ', 'blocks2'), '', ''); echo $views;?> Views, <a title="Printable version" href="<?php echo get_permalink() . '?action=print'; ?>">Printable version</a></div>
	    </div>
    </div>

    <?php else : ?>
	<div class="errorbox">
	    <?php _e('Sorry, no posts matched your criteria.', 'blocks2'); ?>
	</div>
    <?php 
    	endif;
   		comments_template('', true);
   		get_footer(); 
    ?>
