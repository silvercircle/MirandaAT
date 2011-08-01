/*
Author: mg12
Update: 2008/05/05
Author URI: http://www.neoease.com/
*/

ajcom = {

	startTag : '<span style="color:#fff;background-color:#060;">',
	endTag : '</span>',
	term : null,

	init : function() {
		var I = this.I, s, cmntform, key, comments, f, p1, p2;
	
		if ( f = I('postcomments') ) f.style.display = 'block';
		if ( s = I('ajcom_srchform') ) s.style.display = 'block';
		if ( p1 = I('pagenav1') ) p1.style.display = 'block';
		if ( p2 = I('pagenav2') ) p2.style.display = 'block';

		if ( comments = I('ajcomcomments') ) this.scroll = comments.offsetTop;
	
		if ( cmntform = I('commentform') ) {
			key = document.createElement('input');
			key.setAttribute('type', 'hidden');
			key.name = 'ajax_post';
			key.value = '1';
			cmntform.appendChild(key);
			cmntform.onsubmit = function(){if (ajcom.fieldErr())ajcom.commentPost();return false;};
		}
	},

	commentPost : function(){
		var str = '', err;
		var form = this.I('commentform');
		var link = form.action;
		var elems = form.elements || form.templateElements;
	    var cancel_button = document.getElementById('cancel-comment-reply-link');
	    
		for (i=0; i<elems.length; i++) {
			if (elems[i].tagName == "INPUT") {
				if (elems[i].type == "text" || elems[i].type == "hidden") {
					str += elems[i].name + "=" + encodeURIComponent(elems[i].value) + "&";
				}
				if (elems[i].type == "checkbox") {
					if (elems[i].checked) str += elems[i].name + "=" + encodeURIComponent(elems[i].value) + "&";
					else str += elems[i].name + "=&";
				}
				if (elems[i].type == "radio") {
					if (elems[i].checked) str += elems[i].name + "=" + encodeURIComponent(elems[i].value) + "&";
				}
			}
			if (elems[i].tagName == "SELECT") {
				var sel = elems[i];
				str += sel.name + "=" + encodeURIComponent(sel.options[sel.selectedIndex].value) + "&";
			}
			if (elems[i].tagName == "TEXTAREA") {
				str += elems[i].name + "=" + encodeURIComponent(elems[i].value) + "&";
			}
		}
		str += ('apage=' + cpage);
	
		this.term = null;
		this.err_clear();
		this.lockForm(1);
		cancel_button.click();
		this.get(link, str, 'ajcomcomments');
	},

	formError : function(err) {
		var ele, out, r;
	
		if ( ! ( r = this.I('respond') ) ) return;
		if ( ! err || typeof(err) != 'string' ) err = 'Server error...';
		var p1 = err.indexOf('<p>');
		var p2 = err.lastIndexOf('</p>');
	
		if ( p1 != -1 && p2 != -1 ) out = err.substring( p1, ( p2 + 4 ) );
		else out = err;
	
		if ( out.length > 250 ) out = 'Server error...';
	
		ele = document.createElement('div');
		ele.setAttribute('id', 'ajcom-error');
		ele.innerHTML = out;
		r.parentNode.insertBefore( ele, r );
	
		this.lockForm();
	},

	err_clear : function() {
		var err;
		if ( err = this.I('ajcom-error') ) err.parentNode.removeChild(err);
	},

	lockComments : function(L) {
		var com = this.I('ajcomcomments');
		var links = com.getElementsByTagName('A');
		var op;
	
		if ( L ) {
			if ( this.commentsLock ) return;
			this.commentsLock = true;
			op = 30;
			for( i=0;i<links.length;i++ ) links[i].disabled = true;
		} else {
			if ( ! this.commentsLock ) return;
			this.commentsLock = null;
			op = 100;
			for( i=0;i<links.length;i++ ) links[i].disabled = null;
		}
	
		com.style.filter = 'alpha(opacity='+op+')';
		com.style.opacity = (op / 100);
	//	window.scrollTo( 0, this.scroll );
		this.err_clear();
	},

	lockForm : function(L) {
		var form = this.I('commentform');
		var op, dis;
	
		if ( L ) {
			if ( this.formLock ) return;
			this.formLock = true;
			op = 30;
			dis = 'disabled';
		} else {
			if ( ! this.formLock ) return;
			this.formLock = null;
			op = 100;
			dis = '';
		}
	
		form.style.filter = 'alpha(opacity='+op+')';
		form.style.opacity = (op / 100);
	
		var elems = form.elements || form.templateElements;
		for( i = 0; i < elems.length; i++ ) {
			if ( elems[i].tagName != "FIELDSET" ) elems[i].disabled = dis;
		}
	},

	getComments : function(arg,ele) {
		if ( ele && ele.disabled ) return;
		var _r = arg.split('=');
		
		cpage = _r[1];
		var args = 'postid='+postID+'&ajax_getcomments=1&'+arg;
		this.get(blogUrl+'/wp-load.php', args, 'ajcomcomments');
		this.lockComments(1);
	},

	timeOut : function(s) {
		if ( s ) this.timer = window.setTimeout( function(){ajcom.timeOutErr();},15000);
		else window.clearTimeout( this.timer );
	},

	timeOutErr : function() {
		if ( this.req ) ajcom.req.abort();
		alert('Error: Connection has timed out.');
		this.lockComments();
		this.lockForm();
	},

	get : function(link, data, ele){
		var req;
		try { req = new XMLHttpRequest(); }
		catch(e) {
			try {req = new ActiveXObject('Msxml2.XMLHTTP'); }
			catch(e) { req = new ActiveXObject('Microsoft.XMLHTTP'); }
		}

		if ( req ) {
			this.req = req;
			this.timeOut(1);
			req.onreadystatechange = function() { ajcom.push(ele) };
			req.open('POST', link, true);
			req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			req.send(data);
		}
	},

	push : function(ele) {
		var com, srch, err;
	
		try {
			if ( this.req.readyState == 4 ) {
				this.timeOut();
				if ( this.req.status == 200 ) {

					if ( this.term ) 
						this.I(ele).innerHTML = this.doHighlight( this.req.responseText, this.term );
					else 
						this.I(ele).innerHTML = this.req.responseText;
		
					if ( com = this.I('comment') ) com.value = '';
		
					this.lockForm();
					this.lockComments();
					//window.scrollTo( 0, this.scroll );
		
				} else if ( this.req.status == 500 ) {
		
					this.timeOut();
					err = this.req.responseText || this.req.statusText;
					this.formError( err );
					this.lockComments();
				} // else if ( this.req.statusText != 'Unknown' ) this.formError( this.req.statusText );
			}
		} catch(e) {
	//		alert('Server error.');
		}
	},

	ajSearch : function() {
		var term = this.I('ajcom_search').value;
	
		if ( term.length < 3 ) return false;

		term = term.replace(/</g, '&lt;');
		term = term.replace(/>/g, '&gt;');
		term = encodeURIComponent(term);
		this.term = term;
		this.getComments('apage=1&srch='+term);
	},

	ajReset : function() {
		this.term = null;
		this.getComments('apage=1');
	},

	doHighlight : function(text,term) {

		term = decodeURIComponent(term);
		if ( term.length < 3 ) return text;
		
		term = term.replace(/\x22|\x27/g, "%%%%%%%");
	
		var lcTerm = term.toLowerCase();
		var lcText = text.toLowerCase();
		lcText = lcText.replace(/&#8220;|&#8221;|&#8217;/g, "%%%%%%%");
	
		var newText = "";
		var i = -1;
	
		var patt = /&[a-zA-Z0-9#]{2,6};/;
		var tl = term.length;
		var tenc = term.search(patt);
	
		while( text.length > 0 ) {
			i = lcText.indexOf(lcTerm, i+1);
			if ( i < 0 ) {
				newText += text;
				text = '';
			} else {
				if ( text.lastIndexOf(">", i) >= text.lastIndexOf("<", i) ) {
					if ( text.substr( (i - 4), (tl + 8) ).search(patt) == -1 || tenc != -1 )	{
						newText += text.substring(0, i) + this.startTag + text.substr(i, tl) + this.endTag;
						text = text.substr(i + tl);
						lcText = text.toLowerCase();
						i = -1;
					}
				}
			}
		}
		return newText;
	},

	fieldErr : function() {
		var t = /^[a-z0-9]([a-z0-9_\-\.]+)@([a-z0-9_\-\.]+)(\.[a-z]{2,7})$/i;
		var I = this.I, errcls = 'ajcom-err', err = 0, author = I('author'), email;
	
		if ( ! author ) return true;
	
		if ( author.value == '' ) {
			author.className = errcls;
			author.focus();
			err = 1;
		} else author.className = '';
	
		if ( comment = this.I('comment') ) {
			if ( comment.value == '' ) {
				comment.className = errcls;
				comment.focus();
				err = 1;
			} else comment.className = '';
		}
	
		if ( email = this.I('email') ) {
			if ( email.value == '' || ! t.test( email.value ) ) {
				email.className = errcls;
				email.focus();
				err = 1;
			} else email.className = '';
		}
	
		if ( err ) return false;
		return true;
	},

	I : function(a) {
		return document.getElementById(a);
	}

};

ajcom.init();

function c_quote(authorId, commentId, commentBodyId, commentBox) {
	var author = document.getElementById(authorId).innerHTML;
	var comment = document.getElementById(commentBodyId).innerHTML;

	var insertStr = '<blockquote cite="#' + commentBodyId + '">';
	insertStr += '\n<strong><a href="#' + commentId + '">' + author.replace(/\t|\n|\r\n/g, "") + '</a> :</strong>';
	insertStr += comment.replace(/\t/g, "");
	insertStr += '</blockquote>\n';

	insertQuote(insertStr, commentBox);
}

function insertQuote(insertStr, commentBox) {
	if(document.getElementById(commentBox) && document.getElementById(commentBox).type == 'textarea') {
		field = document.getElementById(commentBox);

	} else {
		alert("The comment box does not exist!");
		return false;
	}

	if(document.selection) {
		field.focus();
		sel = document.selection.createRange();
		sel.text = insertStr;
		field.focus();

	} else if (field.selectionStart || field.selectionStart == '0') {
		var startPos = field.selectionStart;
		var endPos = field.selectionEnd;
		var cursorPos = startPos;
		field.value = field.value.substring(0, startPos)
					+ insertStr
					+ field.value.substring(endPos, field.value.length);
		cursorPos += insertStr.length;
		field.focus();
		field.selectionStart = cursorPos;
		field.selectionEnd = cursorPos;

	} else {
		field.value += insertStr;
		field.focus();
	}
}
