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
?>  
    

<?php if (have_posts()) : the_post(); ?>
<div class="post" id="post-<?php the_ID(); ?>">
    <div class="datewidget">
        <span class="month"><?php the_time('M'); ?></span>
        <span class="day"><?php the_time('jS'); ?></span>
    </div>
    <div class="single_print" style="float:right;margin:3px;width:16px;height:16px;">
    <a title="Printable version" rel="nofollow" href="<?php echo get_permalink() . '?action=print'; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
    </div>
	<h2 class="title indent"><?php the_title();?></h2>
	<div class="meta indent">
		<div class="info">
			<?php 
				the_time('Y, H:i e');
		 		echo ' | By ', the_author_posts_link();
				echo ' | In ', the_category(', '); 
			?>
		</div>
		<div class="comments">
			<a href="#respond"><?php _e('Leave a comment', 'blocks2'); ?></a>
			<?php if(pings_open()) : ?>
			 | <a href="<?php trackback_url(); ?>" rel="trackback"><?php _e('Trackback', 'blocks2'); ?></a>
			<?php endif; ?>
			<?php edit_post_link('Edit', ' | ', ''); 
	               $views = get_post_meta($post->ID, 'views', true);
	               $views++;
	               update_post_meta($post->ID, 'views', $views);
	               //echo '&nbsp;|&nbsp;',$views,'&nbsp;views '; 
	        ?>
		</div>
		<div style="clear:right;"></div>
	</div>
	<article>
	<div class="content">
	<?php
		if($post->post_excerpt != '') {
		    echo $post->post_excerpt;
		}
	the_content();
	echo '<div style="text-align:center;clear:both;">'; TA_content_jump(1); echo '</div>';
	socialbar($post, false);
	?>
	</div>
	</article>
</div>

<?php else : ?>
	<div class="errorbox" >
		<?php _e('Sorry, no posts matched your criteria.', 'blocks2'); ?>
		</div><div class="errorbox" >
	</div>
<?php endif; ?>

<!-- related posts -->
<?php
	if(function_exists('wp23_related_posts')) {
		echo '<div id="related_posts">';
		wp23_related_posts();
		echo '</div>';
		echo '<div class="fixed"></div>';
	}
?>

<div id="postnavi" style="margin-top:10px;">
	<?php previous_post_link('<span class="alignleft floatleft">&lt;&lt; %link</span>') ?>
	<?php next_post_link('<span class="alignright floatright">%link &gt;&gt;</span>') ?>
	<div class="fixed"></div>
</div>

<?php
	if (function_exists('wp_list_comments')) {
		comments_template('', true);
	} else {
		comments_template();
	}
?>

<?php get_footer(); ?>
