function switchCSS(styleName)
{
    jQuery('link[@rel*=style][title]').each(function(i) {
        this.disabled = true;
        if (this.getAttribute('title') == styleName) this.disabled = false;
    });
    createCookie('wp_stylesheet', styleName, 365);
}

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else var expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

/*
 * supporting javascript for the i-like-this plugin
 */
function likeThis(postId) {
	if (postId != '') {
		jQuery('#iLikeThis-'+postId+' .ilk_counter').text('...');
		
		jQuery.post(blogUrl + "/wp-content/plugins/i-like-this/like.php",
			{ id: postId },
			function(data){
				jQuery('#iLikeThis-'+postId+' .ilk_button').attr('class', 'ilk_button liked');
				jQuery('#iLikeThis-'+postId+' .ilk_button').html('<span class="liked">You liked this (' + data +')</span>');
			});
	}
}

var sans_family = '"Trebuchet MS",Verdana,helvetica,sans-serif';
var serif_family = 'Constantia,Georgia,serif';

function setInitialFontSize(newSize, newStyle) {
    if(jQuery.browser.msie) {
        document.styleSheets[0].addRule(".content", "font-size:" + parseInt(newSize) + textSizeUnit + " !important;");
        document.styleSheets[0].addRule("#dsq-content", "font-size:" + parseInt(newSize) + textSizeUnit + " !important;");
        if(newStyle == 'serif') {
            document.styleSheets[0].addRule('.content', 'font-family:' + serif_family + ' !important;');
            document.styleSheets[0].addRule('#dsq-content', 'font-family:' + serif_family + ' !important;');
        }
        else {
            document.styleSheets[0].addRule('.content', 'font-family:' + sans_family + ' !important;');
            document.styleSheets[0].addRule('#dsq-content', 'font-family:' + sans_family + ' !important;');
        }
    }
    else {
        document.styleSheets[0].insertRule(".content {font-size:" + parseInt(newSize) + textSizeUnit + " !important;}", 0);
        document.styleSheets[0].insertRule("#dsq-content {font-size:" + parseInt(newSize) + textSizeUnit + " !important;}", 0);
        if(newStyle == 'serif') {
            document.styleSheets[0].insertRule('.content {font-family:' + serif_family + ' !important;}', 0);
            document.styleSheets[0].insertRule('#dsq-content {font-family:' + serif_family + ' !important;}', 0);
        }
        else {
            document.styleSheets[0].insertRule('.content {font-family:' + sans_family + ' !important;}', 0);
            document.styleSheets[0].insertRule('#dsq-content {font-family:' + sans_family + ' !important;}', 0);
        }
    }
    return false;
}

function setTextStyle(newSize) {
    if(textstyle == 'serif') {
        jQuery('.content').css('cssText', 'font-family:' + serif_family + ' !important;font-size: ' + parseInt(newSize) + textSizeUnit + ' !important;');
        jQuery('#dsq-content').css('cssText', 'font-family:' + serif_family + ' !important;font-size: ' + parseInt(newSize) + textSizeUnit + ' !important;');
    }
    else {
        jQuery('.content').css('cssText', 'font-family:' + sans_family + ' !important; font-size: ' + parseInt(newSize) + textSizeUnit + ' !important;');
        jQuery('#dsq-content').css('cssText', 'font-family:' + sans_family + ' !important; font-size: ' + parseInt(newSize) + textSizeUnit + ' !important;');
    }
    jQuery('#fontsize').text(newSize + textSizeUnit);
    createCookie('wp_textstyle', textstyle, 500);
    createCookie('wp_textsizestyle', newSize, 500);
    return false;
}
