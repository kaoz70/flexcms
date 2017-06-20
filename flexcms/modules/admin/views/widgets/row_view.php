<table id="row_<?=$key?>" data-page-id="<?=$page_id?>" data-row-id="<?=$key?>">

<tr class="module_row">
<? foreach ($row->columns as $col_key => $column): ?>
	<td class="content_column">

		<div class="input small">
			<label>Clase</label>
			<input name="fila[<?=$key?>][columns][<?=$col_key?>][class]" type="text" value="<?=$column->class?>" />
		</div>

		<div class="click hidden">Grid / Source Ordering</div>

		<div class="source_ordering" style="display: none;">

			<div class="column_spans">
				<div class="title">Spans</div>
				<div class="width_33">
					<label>Large:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][span][large]" type="text" value="<?= isset($column->span->large) ? $column->span->large : 12 ?>" />
				</div>
				<div class="width_33">
					<label>Medium:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][span][medium]" type="text" value="<?= isset($column->span->medium) ? $column->span->medium : 12 ?>" />
				</div>
				<div class="width_33">
					<label>Small:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][span][small]" type="text" value="<?= isset($column->span->small) ? $column->span->small : 12 ?>" />
				</div>
			</div>

			<div class="column_offsets">
				<div class="title">Offsets</div>
				<div class="width_33">
					<label>Large:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][offset][large]" type="text" value="<?= isset($column->offset->large) ? $column->offset->large : 0 ?>" />
				</div>
				<div class="width_33">
					<label>Medium:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][offset][medium]" type="text" value="<?= isset($column->offset->medium) ? $column->offset->medium : 0 ?>" />
				</div>
				<div class="width_33">
					<label>Small:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][offset][small]" type="text" value="<?= isset($column->offset->small) ? $column->offset->small : 0 ?>" />
				</div>
			</div>

			<div class="column_pull">
				<div class="title">Pull</div>
				<div class="width_33">
					<label>Large:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][pull][large]" type="text" value="<?= isset($column->pull->large) ? $column->pull->large : 0 ?>" />
				</div>
				<div class="width_33">
					<label>Medium:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][pull][medium]" type="text" value="<?= isset($column->pull->medium) ? $column->pull->medium : 0 ?>" />
				</div>
				<div class="width_33">
					<label>Small:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][pull][small]" type="text" value="<?= isset($column->pull->small) ? $column->pull->small : 0 ?>" />
				</div>
			</div>

			<div class="column_push">
				<div class="title">Push</div>
				<div class="width_33">
					<label>Large:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][push][large]" type="text" value="<?= isset($column->push->large) ? $column->push->large : 0 ?>" />
				</div>
				<div class="width_33">
					<label>Medium:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][push][medium]" type="text" value="<?= isset($column->push->medium) ? $column->push->medium : 0 ?>" />
				</div>
				<div class="width_33">
					<label>Small:</label>
					<input name="fila[<?=$key?>][columns][<?=$col_key?>][push][small]" type="text" value="<?= isset($column->push->small) ? $column->push->small : 0 ?>" />
				</div>
			</div>

		</div>



		<ul class="modules">

			<? foreach ($column->widgets as $widget_id): ?>

				<li class="module" id="mod_<?=$widget_id?>">

					<div class="move_module"></div>
					<div data-id="<?=$widget_id?>" class="remove_module">X</div>

					<div class="content">

						<?

						if($widget = \App\Widget::find($widget_id)) {
							//Load the widget's view
							$class = "\\App\\Widget\\{$widget->type}";
							echo $class::admin($widget_id);
						}

						//Widget was probably deleted
						else {
							$this->load->view('admin/widgets/removed_view', $data);
						}

						?>
					</div>
				</li>

			<? endforeach ?>

		</ul>
	</td>
<? endforeach ?>
</tr>
<tr>
<? foreach ($row->columns as $col_key => $column): ?>
	<td class="addModule">
		<div class="add_module">añadir modulo</div>
	</td>
<? endforeach ?>
</tr>
</table>