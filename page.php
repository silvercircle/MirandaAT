<?php     
    global $sidebar_style, $display_sidebar, $skip_header, $skip_nav;
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
	$template = get_post_meta($post->ID, 'custom_template', true);
	if($template != '') {
		include dirname(__FILE__) . '/' . $template . '.php';
		die;
    }
    $skip_header = get_post_meta($post->ID, 'no_header', true) ? true : false;
    $skip_nav = get_post_meta($post->ID, 'no_nav', true) ? true : false;
    get_header(); 
    if (have_posts()) : the_post(); ?>
    
    <div class="post" id="post-<?php the_ID(); ?>">
    <div class="single_print" style="float:right;margin:3px;width:16px;height:16px;">
    <a title="Printable version" rel="nofollow" href="<?php echo get_permalink() . '?action=print'; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
    </div>
    <h2 class="title"><?php the_title(); ?></h2>
    <div class="meta">
	<div class="info"><?php _e('Update: ', 'blocks2'); ?><?php the_modified_time(__('M jS, Y', 'blocks2')); ?></div>
	<div class="comments"><?php edit_post_link(__('Edit', 'blocks2'), '', ''); 
	    $views = get_post_meta($post->ID, 'views', true);
	    $views++;
	    update_post_meta($post->ID, 'views', $views);
	    echo '&nbsp;|&nbsp;',$views,'&nbsp;views'; ?>
	</div>
	<div class="fixed"></div>
    </div>
    <div class="content">
    	<?php the_content();
		echo '<div style="clear:both;text-align:center;display:block;margin-top:10px;">'; TA_content_jump(1); echo '</div><br />';
		socialbar($post, false);
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
