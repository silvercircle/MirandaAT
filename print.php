<?php
#
# Generic Print Template
# ----------------------
#
# This file will be used if no print.php file is present in your theme's folder
#
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><title><?php
if ( $title = trim(wp_title('&#8211;', false)) ) {
	if ( strpos($title, '&#8211;') === 0 )
		$title = trim(substr($title, strlen('&#8211;')));
	echo $title;
} else {
	bloginfo('description');
}
?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
<meta name="robots" content="noindex,nofollow" />
<style type="text/css">
body {
	padding: 30px;
}
</style>
</head>
<body>
<?php

# show posts
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		
		if ( is_single() )
			the_date(null, '<p>', '</p>' . "\n");
		
		echo '<a href="'.get_permalink() . '"><h1>';
		the_title();
		echo '</h1></a>' . "\n";
		
		the_content();
		
		if ( is_single() )
			echo '<p>'
				. sprintf(__('Filed under %s', 'sem-bookmark-me'), get_the_category_list(', '))
				. '</p>' . "\n";
	}
}
?>
</body>
</html>