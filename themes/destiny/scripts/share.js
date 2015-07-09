;$(document).ready(function(){
	initSocial();
});

function initSocial() {
	
	/**
	 * init twitter share button
	 */
	if($('a.twitter-share-button').get(0)) {
		/**
		 * update count post tweetted
		 */
		$('a.twitter-share-button').each(function () {
			var url = 'http://destinyhotel.ec/';
			var rel = $(this).attr('rel');
			if (rel) {
				var data = eval('(' + rel + ')');
				if (data) {
					var url = data['data-url'];
					$(this).attr('data-url', data['data-url']);
					$(this).attr('data-count', data['data-count']);
					$(this).attr('data-via', data['data-via']);
					$(this).attr('data-text', data['data-text']);
				}
			}
			var tc = document.createElement('script');
			tc.async = true;
			tc.src = 'http://urls.api.twitter.com/1/urls/count.json?url='+url+'&callback=tweetsShowCount';
			(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(tc);
		});
		
	}
	
	/**
	 * init facebook share button
	 */
	if($('a.facebook-share-button').get(0)) {
		$('a.facebook-share-button').each(function() {
			var rel = $(this).attr('rel');
			if (rel) {
				var data = eval('(' + rel + ')');
				if (data) {
					$(this).removeAttr('rel');
					$(this).attr('name', data['name']);	
					$(this).attr('share_url', data['share_url']);
					$(this).attr('type', data['type']);
				}
			}
			var fs = document.createElement('script');
			fs.async = true;
			fs.src = 'http://static.ak.fbcdn.net/connect.php/js/FB.Share';
			(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(fs);
		});
	}

    getLikeCount('https://www.facebook.com/lovedestinyhotel');
	
}

setInterval("initShareReady()",1000);
function initShareReady() {
	$('div.fshare span.fb_share_no_count span.fb_share_count_inner').text('0');
	$('div.social').show();
}

/**
 * This get executed in the URL callback
 */
function tweetsShowCount(json) {

	var $link = $('a.twitter-share-button[data-url="'+json.url+'"]');
	if($link.get(0)) {
		var text = 'Destiny Hotel';
		var count = $link.attr('data-count');
		var original_referer = window.location;
		var share_url = 'http://twitter.com/share?count=vertical&original_referer='+urlEncode(original_referer)+'&text='+text+'&url='+urlEncode(json.url);
		var html = '<span class="tb-container '+count+'">\
						<span class="tb">\
							<a target="_blank" href="'+share_url+'">\
							    <span class="fa-stack fa-2x">\
                                    <i class="fa fa-circle fa-stack-2x"></i>\
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>\
                                </span>\
							</a>\
						</span>\
						<span class="t-count enabled">\
							<a target="_blank" href="http://twitter.com/#search?q='+json.url+'">'+json.count+'</a>\
						</span>\
					</span>';
		$link.replaceWith(html);
	}
}

function getLikeCount(site) {

    $.ajax({
        url: 'https://graph.facebook.com/?ids=' + site
    })
        .done(function(data) {
            if(data) {
                $('.fb_share_count_inner').text(data[site].likes);
            }
        });

}

function urlEncode(s) {
  return encodeURIComponent( s ).replace( /\%20/g, '+' ).replace( /!/g, '%21' ).replace( /'/g, '%27' ).replace( /\(/g, '%28' ).replace( /\)/g, '%29' ).replace( /\*/g, '%2A' ).replace( /\~/g, '%7E' );
}

function urlDecode(s) {
  return decodeURIComponent( s.replace( /\+/g, '%20' ).replace( /\%21/g, '!' ).replace( /\%27/g, "'" ).replace( /\%28/g, '(' ).replace( /\%29/g, ')' ).replace( /\%2A/g, '*' ).replace( /\%7E/g, '~' ) );
}