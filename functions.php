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
function async_like_and_tweet($l, $fb = true, $tw = true, $layout="button_count")
{
    global $fbxml, $twitter_widgets, $social_privacy, $plusone;
    
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
            document.write(\'<fb:like style="min-width:300px;min-height:21px;" width="300" href="',$l,'" layout="',$layout,'" send="true" show_faces="false" action="recommend" font="verdana"></fb:like>\');
	    }
    	})();';
    }
    if($tw) {
	$twitter_widgets = 1;
	$plusone++;
	echo '
		document.write(\'<div style="float:right;max-width:65px;overflow:hidden;"><div style="max-width:65px;" class="g-plusone" data-href="',$l,'" data-size="medium" data-count="true"></div></div>\');
   	    document.write(\'<a href="http://twitter.com/share" style="border:none;" class="twitter-share-button" data-count="horizontal" data-url="',$l,'" data-via="Alex_Vie"></a>\');
	';
    }	
    echo '//]]>
       </script>';
	getILikeThis('get');
}

function socialbar(&$post, $is_index = false)
{
	global $social_privacy;
	
	if($social_privacy) {
		socialbar_passive($post, $is_index);
		return;
	}
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
	if($is_index)
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

function socialbar_passive(&$post, $is_index = false)
{
	global $social_privacy, $plusone;
	
	if(get_post_meta($post->ID, 'no_share_links', true)) {
		the_tags('<div class="tags">Tags: ', ', ', '</div>');
		return;
	}
	
	if($is_index) {
		$buttons = get_post_meta($post->ID, 'share_on_index', true);
		$bmbar_class = ($buttons === '1') ? 'bmbar' : '';
	}
	else {
		$buttons = true;
   		//$bmbar_class = $social_privacy ? '' : 'bmbar';
   		$bmbar_class = 'bmbar';
	}
	echo '<div style="margin-top:10px;">';
	if(!$buttons)
		getILikeThis('get');
	the_tags('<div class="tags">Tags: ', ', ', '</div>');
	echo '<div style="clear:both;"></div></div>';

	echo '<div class="',$bmbar_class,'">';
	if(!$is_index || $buttons) {
		$url = get_permalink($post->ID);
		$title = urlencode($post->post_title);
		getILikeThis('get');
		$plusone++;
		
		//$fb = "<span class=\"share_button share_fb\" onclick=\"share_popup(\'http://www.facebook.com/sharer.php?u=".$url."\', 500,400);\">Share</span>";
		//$tw = "<span class=\"share_button share_tw\" onclick=\"share_popup(\'http://twitter.com/share?url=".$url."&amp;text=".$title."\', 550,300);\">Tweet</span>";
		echo '<div style="float:left;"><a role="button" rel="nofollow" class="share_button share_fb" href="http://www.facebook.com/sharer.php?u=',$url,'">Share</a>
			<a role="button" rel="nofollow" class="share_button share_tw" href="http://twitter.com/share?url=',$url,'&amp;text=',$title,'">Tweet</a>
			<a role="button" rel="nofollow" class="share_button share_digg" href="http://digg.com/submit?phase=2&amp;title=',$title,'&amp;url=',$url,'">Digg</a>
			<a role="button" rel="nofollow" class="share_button share_buzz" href="http://www.google.com/buzz/post?url=',$url,'">Buzz</a></div>&nbsp;&nbsp;
			<script type="text/javascript">
			//<![CDATA[
            	document.write(\'<g:plusone href="',$url,'" size="medium"></g:plusone>\');
    		//]]>
       		</script>';
			
		/*	
		echo '<script type="text/javascript">
			//<![CDATA[
			document.write(\'',$fb,'&nbsp;&nbsp;',$tw,'\');
			//]]>			
			</script>
			<noscript>
			<a class="share_button share_fb" href="http://www.facebook.com/sharer.php?u=',$url,'">Share</a>
			<a class="share_button share_tw" href="http://twitter.com/share?url=',$url,'">Tweet</a>
			</noscript>';
			*/
	}
	echo '<div class="fixed"></div></div>';	
}

function social_media_widget()
{
	global $fbxml, $twitter_widgets, $plusone, $social_privacy;
	
	if($social_privacy)
		return;
		
	$l = get_bloginfo('wpurl');
    echo '<div class="widget"><h3>Social media</h3></div><div style="font-size:10px !important;line-height:100% !important;font-family:Verdana,helvetica,sans-serif;">

	<script type="text/javascript">
	//<![CDATA[
	';
    if(1) {
       $fbxml = 1;
       echo '(function() {
 
	   if(1 || !jQuery.browser.mozilla) {
            document.write(\'<fb:like style="min-width:60px;min-height:21px;" width="60" href="',$l,'" layout="button_count" send="false" show_faces="false" action="like" font="verdana"></fb:like>\');
	    }
    	})();';
    }
    if(1) {
	$twitter_widgets = 1;
	$plusone++;
	echo '
		document.write(\'<div style="float:right;max-width:65px;overflow:hidden;"><div style="max-width:65px;" class="g-plusone" data-href="',$l,'" data-size="medium" data-count="true"></div></div>\');
   	    document.write(\'<a href="http://twitter.com/share" style="border:none;" class="twitter-share-button" data-count="horizontal" data-url="',$l,'" data-via="Alex_Vie"></a>\');
	';
    }	
    echo '//]]>
       </script></div>';
}

/** l10n */
function theme_init(){
	register_nav_menu('primary', __('Navigation'));
	add_post_type_support( 'page', 'excerpt' );
	wp_register_sidebar_widget( 'social', 'Social Media', 'social_media_widget');
	//add_theme_support('bbpress');
	//add_theme_support( 'post-thumbnails' );
	//set_post_thumbnail_size( 115, 115 );
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
	global $commentcount, $post;
	if(!$commentcount) {
		$commentcount = 0;
	}
	$cid = $comment->comment_ID;
?>
	<li id="comment-<?php echo $cid ?>" class="comment<?php if($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback') {echo ' pingcomment';} else if($comment->comment_author_email == get_the_author_meta('user_email', $post->post_author)) {echo ' admincomment';} else {echo ' regularcomment';} ?>">
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

				<?php comment_author() ?>,

				<?php if(get_comment_author_url()) : ?>
					</a>
				<?php else : ?>
					</span>
				<?php endif; ?>
			<?php printf('%1$s at %2$s', get_comment_time('M jS, Y'), get_comment_time('H:i') ); ?>
			</div>
			<div class="count">
				<?php if($comment->comment_type != 'pingback' && $comment->comment_type != 'trackback') : ?>
					<?php if (!get_option('thread_comments')) : ?>
						<a href="javascript:void(0);" onclick="c_reply('commentauthor-<?php echo $cid ?>', 'comment-<?php echo $cid ?>', 'comment');"><?php echo('Reply'); ?></a> | 
					<?php else : ?>
						<?php comment_reply_link(array('depth' => $depth, 'max_depth'=> $args['max_depth'], 'reply_text' => 'Reply', 'after' => ' | '));?>
					<?php endif; ?>
					<a href="javascript:void(0);" onclick="c_quote('commentauthor-<?php echo $cid ?>', 'comment-<?php echo $cid ?>', 'commentbody-<?php echo $cid ?>', 'comment');"><?php echo 'Quote'; ?></a> | 
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

				<div style="float:right;margin-left:10px;"><?php //ckrating_display_karma(); ?></div>
				<div id="commentbody-<?php echo $cid ?>">
					<?php comment_text() ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="fixed"></div>
<?php 
}

//require_once('forum-functions.php');
?>
