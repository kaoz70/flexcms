<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div class="contenido_col" style="width: 1200px;">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/paginas/' . $link, $attributes);

?>
	<table id="pageData">
		<tr>
			<td>
				
				<div class="field">
					<div class="header">General</div>
					<div class="content">
						<div class="input">
							
							<fieldset>
								<legend>Título</legend>
								<? foreach ($idiomas as $key => $idioma): ?>
									<label for="<?=$idioma['idiomaDiminutivo']?>_paginaNombre"><?=$idioma['idiomaNombre']?></label>
									<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
										<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_paginaNombre" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->paginaNombre?>"/>
									<? else: ?>
										<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_paginaNombre" type="text" value=""/>
									<? endif ?>
								<? endforeach ?>
							</fieldset>
							
						</div>
						<div class="input">
							
							<fieldset>
								<legend>Nombre del Menu</legend>
								<? foreach ($idiomas as $key => $idioma): ?>
									<label for="<?=$idioma['idiomaDiminutivo']?>_paginaNombreMenu"><?=$idioma['idiomaNombre']?></label>
									<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
										<input class="required unique-name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_paginas" data-columna="paginaNombreURL" data-columna-id="paginaId" data-id="<?=$paginaId;?>" name="<?=$idioma['idiomaDiminutivo']?>_paginaNombreMenu" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->paginaNombreMenu?>"/>
									<? else: ?>
										<input class="required unique-name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_paginas" data-columna="paginaNombreURL" data-columna-id="paginaId" data-id="<?=$paginaId;?>" name="<?=$idioma['idiomaDiminutivo']?>_paginaNombreMenu" type="text" value=""/>
									<? endif ?>
								<? endforeach ?>
							</fieldset>
							
						</div>

                        <div class="input check">
							<input id="paginaEnabled" type="checkbox" name="paginaEnabled" value="1" <?=$paginaEnabled;?> />
                            <label for="paginaEnabled">Habilitado</label>
                        </div>

					</div>
				</div>
				
				<div class="field">
					<div class="header">Avanzado</div>
					<div class="content">
						
						<div class="input">
							
							<fieldset>
								<legend>Meta Keywords</legend>
								<? foreach ($idiomas as $key => $idioma): ?>
									<label for="<?=$idioma['idiomaDiminutivo']?>_paginaKeywords"><?=$idioma['idiomaNombre']?></label>
									<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
										<input name="<?=$idioma['idiomaDiminutivo']?>_paginaKeywords" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->paginaKeywords?>"/>
									<? else: ?>
										<input name="<?=$idioma['idiomaDiminutivo']?>_paginaKeywords" type="text" value=""/>
									<? endif ?>
								<? endforeach ?>
							</fieldset>
							
						</div>

                        <div class="input">

                            <fieldset>
                                <legend>Meta Descripción</legend>
                                <? foreach ($idiomas as $key => $idioma): ?>
                                    <label for="<?=$idioma['idiomaDiminutivo']?>_paginaDescripcion"><?=$idioma['idiomaNombre']?></label>
                                    <? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
                                        <textarea class="descripcion" name="<?=$idioma['idiomaDiminutivo']?>_paginaDescripcion" ><?=$traducciones[$idioma['idiomaDiminutivo']]->paginaDescripcion?></textarea>
                                    <? else: ?>
                                        <textarea class="descripcion" name="<?=$idioma['idiomaDiminutivo']?>_paginaDescripcion" ></textarea>
                                    <? endif ?>
                                <? endforeach ?>
                            </fieldset>

                        </div>

                        <div class="input">

                            <fieldset>
                                <legend>Meta Titulo</legend>
                                <? foreach ($idiomas as $key => $idioma): ?>
                                    <label for="<?=$idioma['idiomaDiminutivo']?>_paginaTitulo"><?=$idioma['idiomaNombre']?></label>
                                    <? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
                                        <textarea class="descripcion" name="<?=$idioma['idiomaDiminutivo']?>_paginaTitulo" ><?=$traducciones[$idioma['idiomaDiminutivo']]->paginaTitulo?></textarea>
                                    <? else: ?>
                                        <textarea class="descripcion" name="<?=$idioma['idiomaDiminutivo']?>_paginaTitulo" ></textarea>
                                    <? endif ?>
                                <? endforeach ?>
                            </fieldset>

                        </div>
						
						<div class="input">
							<label for="paginaClase">clase</label>
							<input id="paginaClase" type="text" name="paginaClase" value="<?= $pagina_info->paginaClase; ?>" />
						</div>
						
						<div class="input check">
							<input id="esPopup" type="checkbox" name="esPopup" value="1" <?=$paginaEsPopup;?> />
							<label for="esPopup">Mostrar en Popup</label>
						</div>
						
						<div class="input">
							<label for="paginaVisiblePara" class="required">Visible para</label>
							<select id="paginaVisiblePara" name="paginaVisiblePara">
							<? foreach ($groups as $key => $group): ?>
								<? if($paginaVisiblePara == $group->id) : ?>
								<option selected="selected" value="<?=$group->id?>"><?=$group->description?></option>
								<? else: ?>
								<option value="<?=$group->id?>"><?=$group->description?></option>
								<? endif ?>
							<? endforeach ?>
							</select>
						</div>
						
					</div>
				</div>
					
			</td>
			<td>
				<div class="field" id="template">
					<div class="header">Estructura</div>
						<div class="content">

							<div id="module_manager">

								<ul id="rows">
									<? foreach ($estructura as $key => $row): ?>
										<li class="row">

											<div class="move_row"></div>
											<div class="remove_row">X</div>

											<div class="row_controls">
												<div class="input check">
													<input id="fila_<?=$key?>_expanded" type="checkbox" name="fila[<?=$key?>][expanded]" <?=$row->expanded ? 'checked': '' ?> value="1">
													<label for="fila_<?=$key?>_expanded">Expandida</label>
												</div>
												<div class="input small">
													<label>Clase</label>
													<input type="text" name="fila[<?=$key?>][class]" value="<?=$row->class != '' ? $row->class : '' ?>">
												</div>
											</div>
											<div>

												<?

												$data['row'] = $row;
												$data['key'] = $key;
												$data['page_id'] = $paginaId;

												$this->load->view('admin/modulos/row_view', $data);

												?>

											</div>
										</li>
									<? endforeach ?>
								</ul>

								<div id="add_row">
									<div class="text">A&ntilde;adir Fila</div>
									<ul id="row_types">
										<li class="rows" id="row_1"><img src="<?=base_url()?>assets/admin/images/template/1.jpg" /></li>
										<li class="rows" id="row_2"><img src="<?=base_url()?>assets/admin/images/template/2.jpg" /></li>
										<li class="rows" id="row_3"><img src="<?=base_url()?>assets/admin/images/template/3.jpg" /></li>
										<li class="rows" id="row_4"><img src="<?=base_url()?>assets/admin/images/template/4.jpg" /></li>
									</ul>
								</div>

								<div id="options">
									<div class="text">Opciones</div>
									<div class="content">
										<label>Copiar estructura de:</label>
										<select>
											<option value="0"> --- P&aacute;gina --- </option>
											<? foreach ($paginas as $pagina): ?>
												<option value="<?=$pagina['paginaId']?>"><?=$pagina['paginaNombreMenu']?></option>
											<? endforeach ?>
										</select>
										<a id="copiar_estructura" data-pagina-id="<?=$paginaId?>" href="#" class="boton importante">Copiar</a>
									</div>
								</div>

							</div>
							<input type="hidden" value="" name="temp_paginaColumnas" id="temp_paginaColumnas" />
					</div>
				</div>
			</td>
		</tr>
		
	</table>
	
	<input id="paginaId" type="hidden" name="paginaId" value="<?=$paginaId;?>" />

<?= form_close(); ?>

</div>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="structure/edit/" data-delete-url="structure/delete/" class="guardar boton importante tree n1 page <?=$nuevo ?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
	moduleManager.init();
</script>