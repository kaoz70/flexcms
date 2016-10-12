var yimaPage = function() {
    var initilize = function() {
        $(document).ready(function() {
            $('#simple-table').dataTable();

            $('#scroll-verticall-table').dataTable({
                "scrollY": "195px",
                "scrollCollapse": true,
                "paging": false,
                "filter": false,
            });

            $('#complex-header-table').dataTable({
                "columnDefs": [
                    {
                        "visible": false,
                        "targets": -1
                    }
                ]
            });

            var table = $('#fixed-header-table').DataTable({
                "paging": false,
                "filter": false,
            });
            new $.fn.dataTable.FixedHeader(table);


            var table2 = $('#fixed-column-table').DataTable({
                "scrollY": "558px",
                "scrollX": "100%",
                "scrollCollapse": true,
                "paging": false,
                "filter": false
            });
            new $.fn.dataTable.FixedColumns(table2);


            $('#responsive-table').DataTable({
                "filter": false,
                responsive: true
            });
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