<?php
	// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
		die (__('Please do not load this page directly. Thanks!', 'blocks2'));
	}
?>

<?php if ( !empty($post->post_password) && $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) : ?>
	<div class="errorbox">
		<?php _e('Enter your password to view comments.', 'blocks2'); ?>
	</div>
<?php return; endif; ?>

<?php
	$count = comment_count($count);
	if($count > 0) {
	    echo '<div id="comments" style="text-align:center; margin:5px 10px;"><h2 style="font-size:14px;margin-left:20px;">', $count, '&nbsp;responses to:&nbsp;', the_title(), '</h2></div>';
	}
?>
<div id="ajaxcm_container">
<?php if ($comments) : ?>

	<?php
	$comment_pages = '';
	if(get_option('page_comments')) {
    	$comment_pages = paginate_comments_links(array('echo' => 0));
	}
	if( !empty($comment_pages)) {
		echo '<div class="commentnavi">', $comment_pages, '</div>';
	}
	?>	
	<ol id="thecomments" class="commentlist">
		<?php
			
//		    $walker = new Walker_Comment_Wink();
		    $walker = new Walker_Comment();
		    wp_list_comments(array('walker' => $walker, 'callback' => 'custom_comments'));			
		?>
	</ol>
	<?php
	if( !empty($comment_pages)) {
		echo '<div class="commentnavi">', $comment_pages, '</div>';
	}
	?>
<?php elseif (comments_open()) : // If there are no comments yet. ?>
	<div class="messagebox">
		<?php _e('No comments yet.', 'blocks2'); ?>
	</div>

<?php endif; ?>
</div>
<?php if (!comments_open()) : // If comments are closed. ?>
	<div class="messagebox">
		<?php _e('Comments are closed.', 'blocks2'); ?>
	</div>

<?php elseif ( get_option('comment_registration') && !$user_ID ) : // If registration required and not logged in. ?>
	<div class="messagebox">
		<?php
			if (function_exists('wp_login_url')) {
				$login_link = wp_login_url();
			} else {
				$login_link = get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode(get_permalink());
			}
		?>
		<?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', 'blocks2'), $login_link); ?>
	</div>

<?php else : ?>
	<div id="respond">
	<form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">

		<div><?php cancel_comment_reply_link(__('Cancel reply', 'blocks2')) ?></div>

		<!-- comment info -->
		<?php if ( $user_ID ) : ?>
			<?php
				$logout_link = wp_logout_url();
			?>
			<div class="row"><?php echo 'Logged in as'; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><strong><?php echo $user_identity; ?></strong></a>. <a href="<?php echo $logout_link; ?>" title="<?php echo 'Log out of this account'; ?>"><?php echo 'Logout &raquo;'; ?></a></div>

		<?php else : ?>
			<?php if ( $comment_author != "" ) : ?>
				<div class="row">
					<?php printf('Welcome back <strong>%s</strong>.', $comment_author); ?>
					<!--<span id="show_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','');MGJS.setStyleDisplay('show_author_info','none');MGJS.setStyleDisplay('hide_author_info','');"><?php _e('Change &raquo;'); ?></a></span>
					<span id="hide_author_info"><a href="javascript:void(0);" onclick="MGJS.setStyleDisplay('author_info','none');MGJS.setStyleDisplay('show_author_info','');MGJS.setStyleDisplay('hide_author_info','none');"><?php _e('Close &raquo;'); ?></a></span>-->
				</div>
			<?php endif; ?>
			<div id="author_info">
				<div class="row">
					<input type="text" name="author" id="author" class="textfield" value="<?php echo $comment_author; ?>" size="24" tabindex="1" />
					<label for="author" class="small"><?php _e('Name', 'blocks2'); ?> <?php if ($req) _e('(required)', 'blocks2'); ?></label>
				</div>
				<div class="row">
					<input type="text" name="email" id="email" class="textfield" value="<?php echo $comment_author_email; ?>" size="24" tabindex="2" />
					<label for="email" class="small"><?php _e('E-Mail (will not be published)', 'blocks2');?> <?php if ($req) _e('(required)', 'blocks2'); ?></label>
				</div>
				<div class="row">
					<input type="text" name="url" id="url" class="textfield" value="<?php echo $comment_author_url; ?>" size="24" tabindex="3" />
					<label for="url" class="small"><?php _e('Website', 'blocks2'); ?></label>
				</div>
			</div>

			<?php if ( $comment_author != "" ) : ?>
				<!--<script type="text/javascript">MGJS.setStyleDisplay('hide_author_info','none');MGJS.setStyleDisplay('author_info','none');</script>-->
			<?php endif; ?>

		<?php endif; ?>

	<!-- comment input -->
	<table class="row"><tr><td>
	        <h2 style="margin:4px;color:#676;">Subject</h2>
			<input class="textfield" type="text" name="hikari-titled-comments" id="hikari-titled-comments" tabindex="4" value="" />&nbsp;&nbsp;<strong>(this is optional)</strong>
		<br /><h2 style="margin:4px;color:#676;">Comment text</h2><textarea name="comment" id="comment" rows="8" cols="10" tabindex="4" style="width:99%;height:120px;"></textarea>
	</td></tr></table>
	<?php echo "Allowed HTML: ".allowed_tags(); ?>
	<br /><br />
	<!-- comment submit and rss -->
	<div id="submitbox">
		<a class="feed" href="<?php bloginfo('comments_rss2_url'); ?>"><?php _e('Subscribe to comments feed', 'blocks2'); ?></a>
		<input name="submit" type="submit" id="submit" class="button" tabindex="5" value="<?php _e('Submit Comment', 'blocks2'); ?>" />
		<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
		<div class="fixed"></div>
	</div>

	<?php if (function_exists('wp_list_comments')) : ?>
		<div><?php comment_id_fields(); ?></div>
	<?php endif; ?>

	<div><?php do_action('comment_form', $post->ID); ?></div>

	</form>
	</div>
<?php endif; ?>
