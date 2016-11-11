var yimaPage = function() {
    var initilize = function() {
        //Editable DataTable
        function format(d) {
            // `d` is the original data object for the row
            return '<table class="table table-celled" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; margin-bottom:20px;">' +
                '<tr>' +
                '<td>Full name:</td>' +
                '<td>' + d.first_name + ' ' + d.last_name + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Position:</td>' +
                '<td>' + d.position + ' at ' + d.office + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Extra info:</td>' +
                '<td>And any further details here (images etc)...</td>' +
                '</tr>' +
                '</table>';
        }

        $(document).ready(function() {
            var table = $('#expandable-table').DataTable({
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    { "data": "first_name" },
                    { "data": "last_name" },
                    { "data": "position" },
                    { "data": "office" }
                ],
                "order": [[1, 'asc']],
                "filter": false,
                "paging": false
            });

            // Add event listener for opening and closing details
            $('#expandable-table tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data())).show();
                    tr.addClass('shown');
                }
            });
        });


        //Selectable DataTable
        var table = $('#selectable-table').DataTable({
            "filter": false,
            "columns": [
                {
                    "orderable": false,
                    "data": null,
                    "defaultContent": '<label><input type="checkbox"><span class="text"></span></label>'
                },
                { "data": "name" },
                { "data": "position" },
                { "data": "office" },
                { "data": "age" },
                { "data": "date" },
                { "data": "salary" }
            ],
            "order": [[1, 'asc']],
        });

        $('#selectable-table tbody').on('click', 'tr', function() {
            $(this).toggleClass('active');
            $(this).find('input[type=checkbox]').prop("checked", $(this).hasClass('active'));

        });

        $('#selectable-table thead th input[type=checkbox]').change(function() {
            var set = $("#selectable-table tbody tr input[type=checkbox]");
            var checked = $(this).is(":checked");
            $(set).each(function() {
                if (checked) {
                    $(this).prop("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).prop("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });

        });
        $('#selectable-table tbody tr input[type=checkbox]').change(function() {
            $(this).parents('tr').toggleClass("active");
        });


        //Editable Datatable
        var oTable = $('#editable-table').dataTable({
            "paging": false,
            "filter": false,
            "aoColumns": [
                null,
                null,
                null,
                null,
                { "bSortable": false }
            ]
        });

        var isEditing = null;

        //Add New Row
        $('#add-row-btn').click(function(e) {
            e.preventDefault();
            var aiNew = oTable.fnAddData([
                '', '', '', '',
                '<a href="#" class="btn btn-success save"><i class="fa fa-edit"></i> Save</a> <a href="#" class="btn btn-warning cancel" data-mode="new"><i class="fa fa-times"></i> Cancel</a>'
            ]);
            var nRow = oTable.fnGetNodes(aiNew[0]);
            editAddedRow(oTable, nRow);
            isEditing = nRow;
        });

        //Delete an Existing Row
        $('#editable-table').on("click", 'a.delete', function(e) {
            e.preventDefault();

            if (confirm("Are You Sure To Delete This Row?") == false) {
                return;
            }

            var nRow = $(this).parents('tr')[0];
            oTable.fnDeleteRow(nRow);
            alert("Row Has Been Deleted!");
        });

        //Cancel Editing or Adding a Row
        $('#editable-table').on("click", 'a.cancel', function(e) {
            e.preventDefault();
            if ($(this).attr("data-mode") == "new") {
                var nRow = $(this).parents('tr')[0];
                oTable.fnDeleteRow(nRow);
                isEditing = null;
            } else {
                restoreRow(oTable, isEditing);
                isEditing = null;
            }
        });

        //Edit A Row
        $('#editable-table').on("click", 'a.edit', function(e) {
            e.preventDefault();

            var nRow = $(this).parents('tr')[0];

            if (isEditing !== null && isEditing != nRow) {
                restoreRow(oTable, isEditing);
                editRow(oTable, nRow);
                isEditing = nRow;
            } else {
                editRow(oTable, nRow);
                isEditing = nRow;
            }
        });

        //Save an Editing Row
        $('#editable-table').on("click", 'a.save', function(e) {
            e.preventDefault();
            if (this.innerHTML.indexOf("Save") >= 0) {
                saveRow(oTable, isEditing);
                isEditing = null;
                //Some Code to Highlight Updated Row
            }
        });


        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            jqTds[0].innerHTML = '<input type="text" class="form-control" value="' + aData[0] + '">';
            jqTds[1].innerHTML = '<input type="text" class="form-control" value="' + aData[1] + '">';
            jqTds[2].innerHTML = '<input type="text" class="form-control" value="' + aData[2] + '">';
            jqTds[3].innerHTML = '<input type="text" class="form-control" value="' + aData[3] + '">';
            jqTds[4].innerHTML = '<a href="#" class="btn btn-success save"><i class="fa fa-save"></i> Save</a> <a href="#" class="btn btn-warning cancel"><i class="fa fa-times"></i> Cancel</a>';
        }

        function editAddedRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            jqTds[0].innerHTML = '<input type="text" class="form-control" value="' + aData[0] + '">';
            jqTds[1].innerHTML = '<input type="text" class="form-control" value="' + aData[1] + '">';
            jqTds[2].innerHTML = '<input type="text" class="form-control" value="' + aData[2] + '">';
            jqTds[3].innerHTML = '<input type="text" class="form-control" value="' + aData[3] + '">';
            jqTds[4].innerHTML = aData[4];
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
            oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
            oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
            oTable.fnUpdate('<a href="#" class="btn btn-info edit"><i class="fa fa-edit"></i>Edit</a> <a href="#" class="btn btn-danger delete"><i class="fa fa-trash-o"></i>Delete</a>', nRow, 4, false);
            oTable.fnDraw();
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
            oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
            oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
            oTable.fnUpdate('<a href="#" class="btn btn-info edit"><i class="fa fa-edit"></i>Edit</a> <a href="#" class="btn btn-danger delete"><i class="fa fa-trash-o"></i>Delete</a>', nRow, 4, false);
            oTable.fnDraw();
        }
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