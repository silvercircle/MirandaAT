<!DOCTYPE html>
<?php
    global $sidebar_style, $display_sidebar, $fbxml, $twitter_widgets, $social_privacy, $is_IE, $skip_header, $skip_nav;
    $fbxml = 0;
    $twitter_widgets = 0;
    if($display_sidebar == '0') {
        $main_td_style = 'width:auto;';
    }
    else {
        $main_td_style = 'width:100%;';
    }
    if (is_home()) {
        $home_menu = 'current_page_item';
    } else {
        $home_menu = 'page_item';
    }
    $feed = get_bloginfo('rss2_url');
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<?php 
		$css_dir = get_bloginfo('stylesheet_directory');
	    global $template_url, $blog_url;
	    $template_url = get_bloginfo('template_url');
	    $blog_url = get_bloginfo('wpurl');
	    if(is_singular() && $post->post_excerpt != '') {
			echo '<meta name="description" content="',the_excerpt_rss(),'" />
			';
	    }
	?>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title><?php bloginfo('name'); wp_title(); ?></title>
	<link rel="alternate" type="application/rss+xml" title="<?php echo 'RSS 2.0 - all posts'; ?>" href="<?php echo $feed; ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo 'RSS 2.0 - all comments'; ?>" href="<?php bloginfo('comments_rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<!--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/flick/jquery-ui.css" type="text/css" media="all" />-->
	<link title="normal" rel="stylesheet" media="screen" type="text/css" href="<?php echo $css_dir; ?>/style.css" />

	<?php
	    $social_privacy = $_COOKIE['wp_privacy'] ? 1 : 0;
	    if($is_IE) {
			echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>';
	    }
	    else {
			echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>';	    
	    }
	?>
	
	<?php if(is_singular()) {
		wp_enqueue_script('comment-reply'); 
		$_style = get_post_meta($post->ID, 'cstyle', true);
		if($_style != '') {
			echo '<style type="text/css">
			'.$_style.'
			</style>';
		}
	}
	?>
	<?php wp_head(); ?>
 	<script type="text/javascript" src="<?php echo $template_url; ?>/js/support.js?ver=1.0.1"></script>
	<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js?ver=1.8.12"></script>-->
	
	<script type="text/javascript">
   	// <![CDATA[
   	var _gaq = _gaq || [];
   	_gaq.push(['_setAccount', 'UA-15512457-1']);
	_gaq.push(['_setDomainName', 'blog.miranda.or.at']);
   	_gaq.push(['_trackPageview']);
	
	var ga = document.createElement('script');
	var sa = document.getElementsByTagName('script')[0];
	ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	sa.parentNode.insertBefore(ga, sa);
	// ]]>
	</script>
</head>

<?php flush(); ?>

<body>
<div id="wrap">
<script type="text/javascript">
// <![CDATA[

var wp_privacy = parseInt(readCookie('wp_privacy'));
var menu_active = false;
var template_url = '<?php echo $template_url; ?>';
var blogUrl ='<?php echo $blog_url; ?>';
var $d;

var textSizeUnit = 'pt';
var textSizeStep = 1;
var textSizeMax = 16;
var textSizeMin = 8;
var textSizeDefault = 10;

var cookie = readCookie("wp_textsizestyle");
var textsize = cookie ? parseInt(cookie) : textSizeDefault;

var cookie_toggle = readCookie("wp_textstyle");
var textstyle = cookie_toggle ? cookie_toggle : 'serif';

if(textsize < textSizeMin || textsize > textSizeMax) {
    textsize = textSizeDefault;
}
setInitialFontSize(textsize, textstyle);
// ]]>
</script>
<!-- header START -->
<?php if(!$skip_header) : ?>
<header>
<div id="header">
	<div id="title">
		<!--
		<div style="display:none;"><h1 class="header"><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div id="tagline"><?php bloginfo('description'); ?></div></div>-->
		<a href="<?php bloginfo('url'); ?>/"><img style="margin-left:30px;" src="<?php echo $template_url;?>/images/bloglogo.png" alt="logo" title="logo" /></a>
	</div>


	<!-- WordPress searchbox -->
	<div class="searchbox widget">
		<form action="<?php bloginfo('home'); ?>/" method="get">
			<div class="content">
				<input type="text" class="textfield searchfield" name="s" size="24" value="<?php echo wp_specialchars($s, 1); ?>" />
				<input type="submit" class="searchbutton" value=" " />
			</div>
		</form>
	</div>

	<div class="fixed"></div>
</div>
</header>
<?php endif; ?>

<!-- header END -->

<!-- content START -->
<div id="content" style="border-top:1px solid black;">
	<!-- menubar START -->
	<?php if(!$skip_nav) : ?>
	<nav>	
	<div id="access" role="navigation">
		<?php
			echo '<ul id="topmenu" class="menu"><li class="',$home_menu,'"><a href="/">Home</a></li>',wp_list_pages('title_li=0&sort_column=menu_order'),'</ul>';
			//wp_nav_menu(array('menu_id' => 'topmenu'));
		?>
		<div style="float:right;">
			<a title="Subscribe to this blog..." class="feedlink" href="<?php echo $feed; ?>"><?php echo('<abbr title="Really Simple Syndication">RSS</abbr> feed'); ?></a>
			<!--<a class="greedlink" href="http://forum.miranda.or.at/"><strong>Forum</strong></a>-->
			<a class="greedlink" href="http://wiki.miranda.or.at/"><strong>Wiki</strong></a>
			<a class="greedlink" href="http://forum.miranda.or.at/"><strong>Forum</strong></a>
			<?php if(!is_user_logged_in()) : ?>
				<a id="cboxlogin" href="#" class="greedlink"><strong>Login</strong></a>
<!-- Start Login-Box -->
	<form id="loginbox" style="display:none;" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post">
		<fieldset>
			<legend id="loginbox_title"></legend>
			<p id="loginbox_username">
				<label>Username:<br />
				<input type="text" name="log" id="user_login" class="loginbox_text" value="" /></label>
			</p>
			<p id="loginbox_password">
				<label>Password:<br />
				<input type="password" name="pwd" id="user_pass" class="loginbox_text" value="" /></label>
			</p>
			<p id="loginbox_rememberme">
				<label><input name="rememberme" type="checkbox" id="rememberme" class="loginbox_checkbox" value="forever" />Remember Me</label>
			</p>
			<p id="loginbox_submit"><input type="submit" class="loginbox_button" value="Login &raquo;" /></p>
			<input type="hidden" name="redirect_to" value="<?php if (LB_BACKTOPAGE) echo $_SERVER['REQUEST_URI']; else { bloginfo('wpurl'); echo '/wp-admin'; }?>" />
		</fieldset>
	</form>
<!-- End Login-Box -->
			<?php else : ?>
				<a class="greedlink" href="<?php echo $blog_url;?>/wp-login.php?action=logout"><strong>Logout</strong></a>
				<?php if(current_user_can('manage_options')) : ?>
					<a class="greedlink" href="<?php echo $blog_url;?>/wp-admin"><strong>Admin</strong></a>
				<?php endif ?>
			<?php endif ?>
			<span class="greedlink" id="fontsize">&nbsp;&nbsp;&nbsp;</span>
			<a title="Increase content text size" class="greedlink" id="fontinc" href="#">&nbsp;</a>
			<a title="Decrease content text size" class="greedlink" id="fontdec" href="#">&nbsp;</a>
			<a title="Toggle serif or sans serif font for content areas" class="greedlink" id="fonttoggle" href="#">&nbsp;</a>
			<!-- <a title="Dark style" class="greedlink" id="setdark" href="#">Dark</a>
			<a title="Normal style" class="greedlink" id="setnormal" href="#">Normal</a> -->
			<a id="social-privacy-dialog" class="greedlink" href="#">Privacy</a>
		</div>
		<div id="highslide-privacy-dialog" style="text-align:left;display:none;">
		    <div class="content" style="padding:4px 10px;">
			You may disable social network connections made by this site by checking the option below. Doing so will remove active Like and Tweet buttons on this page and use passive share links instead.
			<br />
			<br />
			Your preference will be saved to a cookie so it can be remembered for future visits. You can re-enable the social content at any time.
			<br /><br /><input id="social_pref" type="checkbox" name="social_pref" value="1">&nbsp;Disable social network connections for future visits.
			<br /><br /><div style="float:right;" class="content"><a id="save_privacy" onclick="return savePrivacy(this);" href="#">Save and close</a></div>
			<a href="<?php bloginfo('url');?>/about/privacy-statement">View the privacy statement</a>
		    </div>
		</div>
		<div class="fixed"></div>
	</div>
	</nav>
	<?php endif; ?>
	<!-- menubar END -->

	<!-- main START -->
	<div class="content_table_wrapper">
	<script type="text/javascript">
   	// <![CDATA[
		jQuery('div.content_table_wrapper').hide();
	// ]]>
	</script>
	<table class="content_table" style="width:100%;table-layout:fixed;border:0;border-spacing:0;padding:0;">
	<tr>
	<td style="height:4px;min-height:4px;padding-left:5px;padding-right:5px;width:100%;vertical-align:top;"></td>
	<td style="padding-right:5px;width:280px;<?php echo $sidebar_style; ?>"><!--<img src="/t.gif" alt="*" style="width:270px;height:0px;border:0;padding:0;margin:0;" />--></td>
	</tr>
	<tr>
	<td class="content_td" style="vertical-align:top;<?php echo $main_td_style;?>">
	<div id="inner_content">
