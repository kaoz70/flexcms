function stackGalleryReady(){

	jQuery(document).ready(function($) {

		gallery = $('#banner_<?=$bannerId?>').stackGallery(<?=$config?>);

		$("#componentWrapper a[data-rel^='prettyPhoto']").prettyPhoto({
			theme:'pp_default',
			callback: function(){/* Called when prettyPhoto is closed */
				gallery.checkSlideshow2();
			}
		});

	});
}