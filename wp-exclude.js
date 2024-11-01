// Cookie funcs copied, with permission, from http://www.quirksmode.org/js/cookies.html
function wpex_createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function wpex_readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function wpex_eraseCookie(name) {
	wpex_createCookie(name,"",-1);
}

// Copied, awaiting permission, from http://www.martienus.com/code/javascript-remove-duplicates-from-array.html
function wpex_unique(a) {
   var r = new Array();
   o:for(var i = 0, n = a.length; i < n; i++)
   {
      for(var x = 0, y = r.length; x < y; x++)
      {
         if(r[x]==a[i]) continue o;
      }
      r[r.length] = a[i];
   }
   return r;
}

function wp_exclude_post(post_id) {
	var cookie_val = wpex_readCookie('wp_exclude_posts');
	if (typeof(cookie_val) == 'string') {
		cookie_vals = cookie_val.split(',');
		cookie_vals[cookie_vals.length] = post_id;
		cookie_vals = wpex_unique(cookie_vals);
		cookie_val = cookie_vals.join(',');
	}
	else {
		cookie_val = post_id;
	}
	wpex_createCookie('wp_exclude_posts', cookie_val, 3650);
	
	// Reload the page so the post disappears
	location.reload(true);
}
