<?php

//remove_filter('template_redirect', 'redirect_canonical');

//add_filter('wp_loaded','flushRules');

    // Remember to flush_rules() when adding rules
function flushRules(){
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
function template_share_link() {
    echo '<div class="share_div" style="float:right;width:420px;min-width:420px;height:16px;min-height:16px;"><a class="share_link" style="float:right;" href="javascript:void(0);"><span class="share_title" style="display:none;">', the_title(), '</span>';
    echo '<span class="share_url" style="display:none;">', the_permalink(), '</span><img src="',bloginfo('template_url'),'/images/share.gif" style="border:0;display:none;" alt="share" title="share it" class="share_img" /></a><span class="share_anchor"></span></div>';
}

/**
 * create JavaScript to inject asynchronous and XHTML compliant
 * facebook "like" and Twitter "tweet" buttons
 * @params:
 * 	$l:	article permalink to share
 *	$fb:	if true, generate facebook button
 *	$tw:	if true, generate tweet button
 */
function async_like_and_tweet($l, $fb = true, $tw = true, $layout="standard")
{
    global $fbxml, $twitter_widgets, $social_privacy;
    
    if($social_privacy) {
		return;
    }
    echo '
	<script type="text/javascript">
	//<![CDATA[
	';
    if($fb) {
       $fbxml = 1;
       echo '(function() {
 
	   if(1 || !jQuery.browser.mozilla) {
            document.write(\'<fb:like style="min-width:500px;min-height:21px;" width="500" href="',$l,'" layout="',$layout,'" send="true" show_faces="false" action="recommend" font="verdana"></fb:like>\');
	    }
    	})();';
    }
    if($tw) {
	$twitter_widgets = 1;
	echo '(function() {
   	    document.write(\'<a href="http://twitter.com/share" style="border:none;" class="twitter-share-button" data-count="horizontal" data-url="',$l,'" data-via="Alex_Vie"></a>\');
	})();
	';
    }	
    echo '//]]>
       </script>';
}

function socialbar($post, $is_index = false)
{
	global $social_privacy;
	
	if(get_post_meta($post->ID, 'no_share_links', true)) {
		the_tags('<div class="tags">Tags: ', ', ', '</div>');
		return;
	}
	
	if($is_index) {
		$buttons = get_post_meta($post->ID, 'share_on_index', true);
		$bmbar_class = (!$social_privacy && $buttons === '1') ? 'bmbar' : '';
	}
	else {
		$buttons = true;
   		$bmbar_class = $social_privacy ? '' : 'bmbar';
   		//$bmbar_class = 'bmbar';
	}
	echo '<div style="margin-top:10px;">';
	getILikeThis('get');
	the_tags('<div class="tags">Tags: ', ', ', '</div>');
	echo '<div style="clear:both;"></div></div>';

	echo '<div class="',$bmbar_class,'">';
	//template_share_link();
	if(!$is_index || $buttons) {
		async_like_and_tweet(get_permalink($post->ID));
	}
	echo '<div class="fixed"></div></div>';	
}


/** l10n */
function theme_init(){
	register_nav_menu('primary', __('Navigation'));
}

function theme_init2() {
	wp_deregister_script('jquery');
	wp_deregister_script('l10n');
	//wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js', false, '1.5.2');
	//wp_enqueue_script('jquery');	
}

add_action ('init', 'theme_init');
add_action('template_redirect', 'theme_init2');


/** widgets */
if( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
}

/** Comments */
if (function_exists('wp_list_comments')) {
	// comment count
	function comment_count( $commentcount ) {
		global $id;
		$_comments = get_comments('status=approve&post_id=' . $id);
		$comments_by_type = &separate_comments($_comments);
		return count($comments_by_type['comment']);
	}
}

// custom comments
function custom_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $commentcount;
	if(!$commentcount) {
		$commentcount = 0;
	}
	$cid = $comment->comment_ID;
?>
	<li id="comment-<?php echo $cid ?>" class="comment<?php if($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback') {echo ' pingcomment';} else if($comment->comment_author_email == get_the_author_email()) {echo ' admincomment';} else {echo ' regularcomment';} ?>">
	    <div>
			<?php
				if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') {
					// Support avatar for WordPress 2.5 or higher
					if (function_exists('get_avatar') && get_option('show_avatars')) {
						echo '<div class="pic">';
						echo get_avatar($comment, 24);
						echo '</div>';
					}
				}
			?>
		<div class="info<?php if($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback') {echo ' pinginfo';} else if($comment->comment_author_email == get_the_author_email()) {echo ' admininfo';} else {echo ' regularinfo';} ?>">
			<div class="author">
				<?php hkTC_comment_title($comment->comment_ID,'<span class="comment-title">','</span>&nbsp;by&nbsp;'); ?>
				<?php if (get_comment_author_url()) : ?>
					<a class="authorname" id="commentauthor-<?php echo $cid ?>" href="<?php comment_author_url() ?>" rel="external nofollow">
				<?php else : ?>
					<span class="authorname" id="commentauthor-<?php echo $cid ?>">
				<?php endif; ?>

				<?php comment_author() ?>

				<?php if(get_comment_author_url()) : ?>
					</a>
				<?php else : ?>
					</span>
				<?php endif; ?>
				, <?php printf('%1$s at %2$s', get_comment_time('M jS, Y'), get_comment_time('H:i') ); ?>
			</div>
			<div class="count">
				<?php if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') : ?>
					<?php if (!get_option('thread_comments')) : ?>
						<a href="javascript:void(0);" onclick="MGJS_CMT.reply('commentauthor-<?php echo $cid ?>', 'comment-<?php echo $cid ?>', 'comment');"><?php echo('Reply'); ?></a> | 
					<?php else : ?>
						<?php comment_reply_link(array('depth' => $depth, 'max_depth'=> $args['max_depth'], 'reply_text' => 'Reply', 'after' => ' | '));?>
					<?php endif; ?>
					<a href="javascript:void(0);" onclick="MGJS_CMT.quote('commentauthor-<?php echo $cid ?>', 'comment-<?php echo $cid ?>', 'commentbody-<?php echo $cid ?>', 'comment');"><?php echo 'Quote'; ?></a> | 
				<?php endif; ?>
				<?php edit_comment_link('Edit', '', ' | '); ?>
				<a href="#comment-<?php echo $cid ?>"><?php printf('#%1$s', ++$commentcount); ?></a>
				
			</div>
			<br />
			<!-- <div class="fixed"></div> -->
		</div>
		</div>
		<?php if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') : ?>
			<div class="content">
				<?php if ($comment->comment_approved == '0') : ?>
					<p><small>Your comment is awaiting moderation.</small></p>
				<?php endif; ?>

				<div id="commentbody-<?php echo $cid ?>">
					<div style="float:right;margin-left:10px;"><?php //ckrating_display_karma(); ?></div>

					<?php comment_text() ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="fixed"></div>
<?php 
}
?>
