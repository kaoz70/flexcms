var yimaPage = function() {
    var initilize = function() {
        $(document).ready(function() {
            var table = $('#tools-table').DataTable({
		    
            });
            var tt = new $.fn.dataTable.TableTools(table, {
                "sSwfPath": yima.getAssetPath("assets/swf/copy_csv_xls_pdf.swf")
            });

            $(tt.fnContainer()).insertBefore('div.dataTables_wrapper');
        });
    }

    return {
        init: initilize
    }
}();

jQuery(document).ready(function() {
    if (yima.isAngular() === false) {
        yimaPage.init();
    }
});