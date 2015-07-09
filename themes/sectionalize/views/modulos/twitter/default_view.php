<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>

<script>
	new TWTR.Widget({
		version : 2,
		type : 'profile',
		rpp : 5,
		interval : 30000,
		width : 'auto',
		height : 300,
		theme : {
			shell : {
				background : '#224c80',
				color : '#ffffff'
			},
			tweets : {
				background : '#ffffff',
				color : '#141414',
				links : '#f18b07'
			}
		},

		features : {
			scrollbar : true,
			loop : false,
			live : false,
			behavior : 'all'
		}
	}).render().setUser('<?=$user?>').start();

</script>
