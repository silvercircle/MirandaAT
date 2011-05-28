<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	<link rel="alternate" type="application/rss+xml" title="<?php _e('RSS 2.0 - all posts', 'blocks2'); ?>" href="<?php echo $feed; ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php _e('RSS 2.0 - all comments', 'blocks2'); ?>" href="<?php bloginfo('comments_rss2_url'); ?>" />

	<!-- style START -->
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/404.css" type="text/css" media="screen" />
	<!-- style END -->

	<?php wp_head(); ?>
</head>

<body>

<div id="container">
	<div id="talker">
	</div>
	<h1><?php _e('Not found', 'blocks2'); ?></h1>
	<div id="notice">
		<p style="text-align:center;font-weight:bold;">There are 40 billion pages on the Net,<br />That's a guess,<br />No-one can ever say it's true,<br />But I cannot find the page as requested by you</p>
		<hr />
		<p><?php _e("You can either (a) click on the 'back' button in your browser and try to navigate through our site in a different direction, or (b) click on the following link to go to homepage.", 'blocks2'); ?></p>
		<div class="back">
			<a href="<?php bloginfo('url'); ?>/"><?php _e('Back to homepage &raquo;', 'blocks2'); ?></a>
		</div>
	<small><center>Some depressed web server listening on port 80, waiting all day to serve requests only to see people requesting things it cannot find.</center></small>
	</div>
</div>

</body>
</html>
