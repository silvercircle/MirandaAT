	<!-- main END -->
	<?php get_sidebar(); ?>
	<!-- footer START -->
	<footer>
	<div id="footer">
	<script type="text/javascript">
   	// <![CDATA[
		jQuery('div#footer').hide();
	// ]]>
	</script>
		<div id="about">
			<?php echo 'Powered by <a href="http://www.wordpress.org/">WordPress</a> | Design by Miranda@at'; ?>
			 | <?php echo 'Valid <a href="http://validator.w3.org/check?uri=referer">HTML 5</a> and <a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS 3</a>'; ?>
			 | <?php echo get_num_queries(); ?> queries,&nbsp;<?php echo 1000*timer_stop(0,3); ?> ms CPU
			 <br />
			 Using <a href="http://www.jquery.org">jQuery</a> ColorBox by <a href="http://colorpowered.com/colorbox/">Color Powered</a> and Syntax Highlighter by <a href="http://alexgorbatchev.com/SyntaxHighlighter/">A. Gorbatchev</a>
		</div>
		<ul id="admin">
			<?php wp_register(); ?>
			<li><a href="/contact">Contact</a></li>
			<li><?php wp_loginout(); ?></li>
			<li id="gotop"><a href="#" onclick="MGJS.goTop();return false;"><?php echo('TOP'); ?></a></li>
		</ul><br />
		<div class="fixed"></div>
	</div>
	</footer>
	<!-- footer END -->
</div>
<!-- content END -->
</div><!-- wrap -->
<div id="fb-root"></div>

<?php
	global $fbxml, $twitter_widgets, $template_url, $social_privacy, $plusone;
	wp_footer();
  	echo '<script type="text/javascript">
	// <![CDATA[

    var anchor = document.getElementsByTagName(\'SCRIPT\')[0];
	var t2 = document.createElement(\'SCRIPT\');
	t2.type = "text/javascript";
	t2.async = true;
	t2.src = "',$template_url,'/js/support-footer-min.js?ver=1.1.0";
	anchor.parentNode.insertBefore(t2, anchor);
    ';
	if(1/*$social_privacy != 1*/) {
		if($fbxml) {
			echo '
			window.fbAsyncInit = function() {
   				FB.init({appId: \'109862169045977\', status: true, cookie: true, xfbml: true});
  			};
  			(function() {
    			var e = document.createElement(\'script\'); e.async = true;
    			e.src = \'http://connect.facebook.net/en_US/all.js\';
				document.getElementById(\'fb-root\').appendChild(e);
  			}());
			';
		}
		if($twitter_widgets) {
			echo '
			var t1 = document.createElement(\'SCRIPT\');

			t1.src = \'http://platform.twitter.com/widgets.js\'; 
			t1.type = "text/javascript"; 
			t1.async = true;
			anchor.parentNode.insertBefore(t1, anchor);
			';
		}
	}
	if($plusone) {
		echo '
			var t3 = document.createElement(\'SCRIPT\');

			t3.src = \'http://apis.google.com/js/plusone.js\'; 
			t3.type = "text/javascript"; 
			t3.async = true;
			anchor.parentNode.insertBefore(t3, anchor);
			';
	}
    echo '
	// ]]>
	</script>';	    
?>
</body>
</html>
