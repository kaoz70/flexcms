<div id="fb-root"></div>
<script>
( function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];

	if(d.getElementById(id))
		return;

	js = d.createElement(s);
	js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?=$appId?>";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<div class="fb-like-box" data-href="<?=$url ?>" data-width="243" data-show-faces="true" data-stream="true" data-header="true"></div>
