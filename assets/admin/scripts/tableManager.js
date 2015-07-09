var tableManager = {
	
	sortable: null,
	
	init: function() {

        var max_width = 0;

        $$('.table_editor').each(function(item){

            var elem = item.getElement('.tableGrid'),
                width = item.getDimensions().x;

            item.addEvent('click:relay(.add_column)', function(ev, el) {
                tableManager.addColumn(el);
            });

            item.addEvent('click:relay(.add_row)', function(ev, el) {
                tableManager.addRow(el);
            });

            elem.addEvent('click:relay(.bot_deleteColumn)', tableManager.removeColumn);
            elem.addEvent('click:relay(.bot_deleteRow)', tableManager.removeRow);
            elem.addEvent('click:relay(.table_editor th, .table_editor td)', tableManager.editCell);

            if (width > max_width) {
                max_width = width + 150;
                item.getParent('.contenido_col').setStyle('width', max_width);
            }

            tableManager.addDeleteControls(elem);

            tableManager.resizeRowButon(elem);
            tableManager.resizeColumnButon(elem);

            tableManager.sortable = new Sortables(elem.getElement('tbody'), {
                constrain: true,
                revert: true,
                handle : '.bot_sortRow',
                clone : true,
                opacity : 0
            });
        });

	},
	
	resizeRowButon : function(elem) {
		var tableSize = elem.getSize();
	
		elem.getParent('.table_editor').getElements('.add_row')[0].set('styles', {
			width : tableSize.x
		});

	},
	
	resizeColumnButon : function(elem) {
		var tableSize = elem.getSize();
	
		elem.getParent('.table_editor').getElements('.add_column')[0].set('styles', {
			height : tableSize.y
		});
		
	},
	
	addDeleteControls : function(elem) {
	
		//Column delete Controls
		var columns = elem.getElements('th');
	
		var rowHtml = '';
	
		Array.each(columns, function(row, index) {
			if(index != 0)
				rowHtml += '<td class="delete_column"><div class="bot_deleteColumn"></div></td>';
			else
				rowHtml += '<td class="delete_column"></td>';
		});
	
		if(columns.length == 1)
			rowHtml = '<td class="delete_column"></td>';
	
		var row = new Element('tr', {
			html : rowHtml,
			'class' : 'delete_column'
		});
	
		row.inject(elem.getElement('tbody'), 'top');
	
		//Row delete controls
		var rows = elem.getElements('tr');
	
		//console.log(rows);
	
		Array.each(rows, function(row, index) {
			if(index == 0) {
	
				var emptyCell = new Element('td', {
					'class' : 'delete_column'
				});
				
				emptyCell.inject(row, 'top');
	
			} else if(index == 1) {
	
				var emptyCell = new Element('th', {
					'class' : 'delete_row'
				});
	
				emptyCell.inject(row, 'top');
	
			} else if(index == 2) {
	
				var emptyCell = new Element('td', {
					'class' : 'delete_row',
					html : '<div class="bot_sortRow"></div>'
				});
				
				//row.addClass('dropable');
				emptyCell.inject(row, 'top');
	
			} else {
				var botDelete = new Element('td', {
					'class' : 'delete_row',
					html : '<div class="bot_deleteRow"></div><div class="bot_sortRow"></div>'
				});
				
				//row.addClass('dropable');
				botDelete.inject(row, 'top');
			}
	
		});
	
	},
	
	addColumn : function(elem) {

        var table = elem.getParent().getPrevious().getElement('.tableGrid'),
            rows = table.getElements('tr');

		Array.each(rows, function(row, index) {

			if(index == 0) {
				var botDelete = new Element('td', {
					'class' : 'delete_column',
					html : '<div class="bot_deleteColumn"></div>'
				});
	
				botDelete.inject(row);
	
			} else if(index == 1) {
				var header = new Element('th', {
					html : 'nombre cabecera'
				});
	
				header.inject(row);
			} else {
				var column = new Element('td');
	
				column.inject(row);
			}
	
		});

		tableManager.resizeRowButon(table);
		
		tableManager.resizeContainer(table);
	
	},
	
	addRow : function(elem) {

        var table = elem.getPrevious().getElement('.tableGrid'),
            columns = table.getElements('th'),
            columnHtml = '';

		Array.each(columns, function(column, index) {
			if(index == 0)
				columnHtml += '<td class="delete_row"><div class="bot_deleteRow"></div><div class="bot_sortRow"></div></td>';
			else
				columnHtml += '<td></td>';
		});
	
		var row = new Element('tr', {
			html : columnHtml
		});
		
		tableManager.sortable.addItems(row);
	
		row.inject(table.getChildren()[0]);
	
		tableManager.resizeColumnButon(table);
	
	},
	
	removeColumn : function(event, elem) {
	
		var parent = elem.getParent('tr');
		var items = parent.getChildren();
		var indexToDelete;
		var parentTable = elem.getParent('table');
	
		Array.each(items, function(item, index) {
			if(item == elem.getParent('td')) {
				indexToDelete = index;
			}
		});
	
		alerta = new StickyWin({
			content : StickyWin.ui('Alerta', 'Esta seguro que desea eliminar esta columna?', {
				width : '400px',
				buttons : [{
					text : 'eliminar',
					onClick : function() {
	
						var rows = elem.getParent('tbody').getChildren('tr');
	
						Array.each(rows, function(item, index) {
							item.getChildren()[indexToDelete].destroy();
						});
						
						//console.log(parentTable);
						
						tableManager.resizeContainer(parentTable);
						tableManager.resizeRowButon(parentTable);
	
					}
				}, {
					text : 'cancelar'
				}]
			})
		});
		
	},
	
	removeRow : function(event, elem) {
	
		var parentTable = elem.getParent('table');
	
		alerta = new StickyWin({
			content : StickyWin.ui('Alerta', 'Esta seguro que desea eliminar esta fila?', {
				width : '400px',
				buttons : [{
					text : 'eliminar',
					onClick : function() {
						elem.getParent('tr').destroy();
	
						tableManager.resizeColumnButon(parentTable);
					}
				}, {
					text : 'cancelar'
				}]
			})
		});
	
	},
	
	editCell : function(event, elem) {
	
		//console.log(elem.hasClass('delete_column'));
	
		if(!elem.hasClass('delete_column') && !elem.hasClass('delete_row') && !elem.hasClass('no_edit')) {
			Array.each($$('.cellinput'), function(item, index) {
				item.destroy();
			});
	
			var text = elem.get('html').replace(/\<br \/\>|\<br\>/g, "\n");
	
			var cellInput = new Element('textarea', {
				'value' : text,
				'class' : 'cellinput',
				events : {
					blur : function() {
						tableManager.setCellInputValue(this, elem);
					},
					keypress : function(e) {
						/*if (e.keyCode == '13'){
						 tableManager.setCellInputValue(this, elem);
						 }*/
					}
				}
			});
	
			var parentDiv = elem.getParent('.table_editor');
			var position = elem.getPosition(parentDiv);
			var size = elem.getSize();
			cellInput.setPosition(position);
			cellInput.inject(parentDiv);
			cellInput.set('styles', {
				width : size.x - 3,
				height : size.y - 5
			});
			cellInput.focus();
	
		}
	
	},
	
	setCellInputValue : function(elem, target) {
		var text = elem.get('value').replace(/\n/g, "<br />");
	
		target.set('html', text);
		elem.destroy();
	},
	
	resizeContainer : function(elem) {
		var size = elem.getParent('.contenido_col').scrollWidth,
            margen = 0,
            columna = elem.getParent('.columnas');

        columna.setStyles({
			'width' : size + margen,
			'min-width' : size + margen
		});

        elem.getParent('.contenido_col').setStyles({
			'width' : size - 10,
			'min-width' : size - 10
		});

        columna.retrieve('scrollable').reposition();

	}
}