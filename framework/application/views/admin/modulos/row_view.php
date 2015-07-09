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

			<? foreach ($column->modules as $modulo_id): ?>

				<li class="module" id="mod_<?=$modulo_id?>">

					<div class="move_module"></div>
					<div class="remove_module">X</div>

					<div class="content">

						<?

						$module_data = $modulo_model->getModule($modulo_id);
						$data['moduleData'] = $module_data;
						$data['moduleId'] = $modulo_id;

						if($module_data) {
							switch ($module_data->paginaModuloTipoId) {
								case 1:
									$data['publicaciones'] = $modulo_model->get_page_by_type(5);
									$data['moduleImages'] = $modulo_model->getImages(2);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/publicaciones/");
									$this->load->view('admin/modulos/publicaciones_view', $data);
									break;
								case 2:
									$data['categorias'] = $catalogo_model->getCategories();
									$data['moduleImages'] = $modulo_model->getImages(5);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/catalogo/product/");
									$this->load->view('admin/modulos/catalogoCategorias_view', $data);
									break;
								case 3:
									$this->load->view('admin/modulos/html_view', $data);
									break;
								case 4:
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/twitter/");
									$this->load->view('admin/modulos/twitter_view', $data);
									break;
								case 5:
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/facebook/");
									$this->load->view('admin/modulos/facebook_view', $data);
									break;

								case 8:
									$data['pageTypes'] = $pagina_model->getPageType();
									$data['paginas'] = $pagina_model->getPages();
									$this->load->view('admin/modulos/content_view', $data);
									break;

								case 9:
									$data['banners'] = $banner_model->getAll();
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/banners/");
									$this->load->view('admin/modulos/banner_view', $data);
									break;

								case 10:
									$data['categorias'] = $catalogo_model->getCategories();
									$data['moduleImages'] = $modulo_model->getImages(5);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/catalogo/product/");
									$this->load->view('admin/modulos/catalogoProductosDestacados_view', $data);
									break;

								case 11:
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/catalogo/menu/");
									$this->load->view('admin/modulos/catalogoMenu_view', $data);
									break;

								case 12:
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/titulo/");
									$this->load->view('admin/modulos/titulo_view', $data);
									break;

								case 13:
									$data['faq'] = $modulo_model->get_page_by_type(2);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/faq/");
									$this->load->view('admin/modulos/faq_view', $data);
									break;

								case 14:
									$data['enlaces'] = $modulo_model->get_page_by_type(10);
									$data['moduleImages'] = $modulo_model->getImages(1);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/enlaces/");
									$this->load->view('admin/modulos/enlaces_view', $data);
									break;

								case 15:
									$data['galeria'] = $gallery_model->getCategories();
									$data['moduleImages'] = $modulo_model->getImages(8);
									$this->load->view('admin/modulos/galeria_view', $data);
									break;

								case 16:
									$data['mapas'] = $mapas_model->getAll();
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/mapa/");
									$this->load->view('admin/modulos/mapas_view', $data);
									break;

								case 17:
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/catalogo/filtros/");
									$this->load->view('admin/modulos/catalogoFiltros_view', $data);
									break;

								case 18:
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/menu/");
									$this->load->view('admin/modulos/menu_view', $data);
									break;

								case 19:
									$data['categorias'] = $catalogo_model->getCategories();
									$data['moduleImages'] = $modulo_model->getImages(5);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/catalogo/product/");
									$this->load->view('admin/modulos/catalogoProductoAzar_view', $data);
									break;

								case 20:
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/contacto/formulario/");
									$this->load->view('admin/modulos/contacto_view', $data);
									break;

								case 21:
									$data['articulos'] = $article_model->all('es');
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/articulo/");
									$this->load->view('admin/modulos/articulo_view', $data);
									break;

								case 22:
									$data['servicios'] = $this->Modulo->get_page_by_type(12);
									$data['moduleImages'] = $modulo_model->getImages(10);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/servicios/");
									$this->load->view('admin/modulos/servicios_view', $data);
									break;

								case 23:
									$this->load->view('admin/modulos/breadcrumbs_view', $data);
									break;

								case 24:
									$data['moduleImages'] = $modulo_model->getImages(14);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/contacto/direcciones/");
									$this->load->view('admin/modulos/direcciones_view', $data);
									break;

								case 25:
									$this->load->view('admin/modulos/publicidad_view', $data);
									break;

								case 26:
									$data['categorias'] = $catalogo_model->getCategories();
									$data['moduleImages'] = $modulo_model->getImages(5);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/catalogo/product/");
									$this->load->view('admin/modulos/catalogoProductosDestacadosAzar_view', $data);
									break;

								case 27:
									$data['servicios'] = $this->Modulo->get_page_by_type(12);
									$data['moduleImages'] = $modulo_model->getImages(10);
									$data['moduleViews'] = directory_map("themes/" . $theme . "/views/modulos/servicios/");
									$this->load->view('admin/modulos/serviciosDestacados_view', $data);
									break;

								default:
									echo "[cree una vista para este m&oacute;dulo]";
									break;
							}
						}

						//Module was probably deleted
						else {
							$this->load->view('admin/modulos/removed_view', $data);
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