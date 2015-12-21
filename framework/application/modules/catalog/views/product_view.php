<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>

<?

$style = '';

if(count($campos) > 0) {
    foreach($campos as $row) {
        switch($row->inputTipoNombre) {
            case 'textarea':
                $style = 'style="width: 780px"';
                break;
        }
    }
}

?>


<div class="contenido_col" <?=$style?>>
	
	<?php if(count($categorias) > 0): ?>

	<?=form_open('admin/catalogo/' . $link, array('class' => 'form'));?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<input type="hidden" name="productoId" value="<?=$productoId;?>" />
			
			<fieldset>
				<legend>Nombre</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div class="input small">
					<label for="<?=$idioma['idiomaDiminutivo']?>_productoNombre"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input class="required name unique-name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_productos" data-columna="productoUrl" data-columna-id="productoId" data-id="<?=$productoId;?>" name="<?=$idioma['idiomaDiminutivo']?>_productoNombre" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoNombre?>"/>
					<? else: ?>
						<input class="required name unique-name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_productos" data-columna="productoUrl" data-columna-id="productoId" data-id="<?=$productoId;?>" name="<?=$idioma['idiomaDiminutivo']?>_productoNombre" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>
			
            <fieldset id="upload-image-product">
                <legend><?=$txt_botImagen;?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <? if($imagen != ''): ?>
                        <li class="image">
                            <?=$imagen?>
                        </li>
                    <? endif; ?>
                </ul>
            </fieldset>
			
            <div class="input">
                <label for="categoriaId">Categor&iacute;a</label>
                <select class="selectbox" id="categoriaId" name="categoriaId">
                    <?= admin_select_tree($categorias, $categoriaId, 'productoCategoriaNombre') ?>
                </select>
            </div>

			<div class="input small">
				<label for="stock_quantity">Stock</label>
				<input id="stock_quantity" name="stock_quantity" type="text" value="<?= isset($stock_quantity) ? $stock_quantity : '' ?>">
			</div>

			<div class="input small">
				<label for="weight">Peso</label>
				<input id="weight" name="weight" type="text" value="<?= isset($weight) ? $weight : '' ?>">
			</div>

			<div class="input check">
				<input id="productoDeldia" type="checkbox" name="productoDeldia" value="s" <?=$checkedPD;?>/>
				<label for="productoDeldia">Producto Destacado</label>
			</div>

			<div class="input check">
				<input id="productoEnable" name="productoEnable" type="checkbox"  value="s" <?=$habilitado;?> />
				<label for="productoEnable">Habilitado</label>
			</div>

		</div>
	</div>
	<? if(count($campos) > 0): ?>
	<div class="field">
		<div class="header">Campos</div>
		
		<div class="content">
			<?php foreach($campos as $row) : ?>
			
			<fieldset>
				<legend><?=$row->productoCampoValor?></legend>
				<? foreach ($idiomas as $key => $idioma): ?>
				<div>

                    <? if($row->inputTipoNombre != 'imagenes' && $row->inputTipoNombre != 'videos' && $row->inputTipoNombre != 'archivos' && $row->inputTipoNombre != 'audio' && $row->inputTipoNombre != 'select'): ?>
					<label><?=$idioma['idiomaNombre']?></label>
                    <? endif ?>

					<? switch($row->inputTipoNombre):
					
						case 'input': ?>
							<? if(isset($row->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido)): ?>
							<input type="text" name="<?=$idioma['idiomaDiminutivo']?>_<?=$row->productoCampoId?>" value="<?=$row->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido?>">
							<? else: ?>
							<input type="text" name="<?=$idioma['idiomaDiminutivo']?>_<?=$row->productoCampoId?>" value="">
							<? endif ?>
							<? break; ?>
							
						<? case 'textarea': ?>
							<textarea id="<?=$idioma['idiomaDiminutivo']?>_editor_<?=$row->productoCampoId?>" class="editor" rows="20" cols="85" name="<?=$idioma['idiomaDiminutivo']?>_<?=$row->productoCampoId?>"><?=$row->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido?></textarea>
							<script type="text/javascript">initEditor("<?=$idioma['idiomaDiminutivo']?>_editor_<?=$row->productoCampoId?>");</script>
							<? break; ?>
							
						<? case 'tabla': ?>
							<div class="table_editor">
								<div class="tbleColumnCont">
                                    <table>
                                        <tr>
                                            <td class="no_edit">
                                                <? if($row->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido == ''): ?>
                                                    <table class="tableGrid" id="<?=$idioma['idiomaDiminutivo']?>_editor_grid_<?=$row->productoCampoId?>">
                                                        <tbody>
                                                        <tr>
                                                            <th>nombre cabecera</th>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                <? else : ?>
                                                    <?=$row->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido?>
                                                <? endif; ?>
                                            </td>
                                            <td class="no_edit">
                                                <div class="add_column"></div>
                                            </td>
                                        </tr>
                                    </table>
								</div>
								<div class="add_row"></div>
								<textarea class="tableGridInput" id="input_editor_grid_<?=$row->productoCampoId?>" name="<?=$idioma['idiomaDiminutivo']?>_<?=$row->productoCampoId?>" ><?=$row->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido?></textarea>
							</div>
							<? break; ?>

                        <? case 'archivos': ?>
                            <a class="nivel4 ajax boton importante" href="<?= base_url()?>admin/catalog/file/index/<?=$productoId?>/<?=$row->productoCampoId?>">Modificar <?=$row->productoCampoValor?></a>
                            <? break 2; ?>

                        <? case 'select': ?>

                            <? if($row->inputTipoContenido === 'listado predefinido'): ?>
                            <a class="nivel4 ajax boton importante" href="<?= base_url()?>admin/catalog/predefinedList/index/<?=$productoId?>/<?=$row->productoCampoId?>">Modificar <?=$row->productoCampoValor?></a>
                            <? else: ?>
                            <a class="nivel4 ajax boton importante" href="<?= base_url()?>admin/catalog/field_list/<?=$productoId?>/<?=$row->productoCampoId?>">Modificar <?=$row->productoCampoValor?></a>
                            <? endif ?>

                            <? break 2; ?>

						<? default: ?>
							
					<? endswitch ?>
				</div>
				<? endforeach ?>
			</fieldset>
			
	        <?php endforeach; ?>
		</div>
	</div>
	<? endif ?>

		<div class="field">
			<div class="header">SEO</div>
			<div class="content">

				<fieldset>
					<legend>Palabras Clave</legend>
					<small>Separados por coma ","</small>
					<? foreach ($idiomas as $key => $idioma): ?>
						<div>
							<label for="<?=$idioma['idiomaDiminutivo']?>_productoKeywords"><?=$idioma['idiomaNombre']?></label>
							<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
								<input name="<?=$idioma['idiomaDiminutivo']?>_productoKeywords" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoKeywords?>"/>
							<? else: ?>
								<input name="<?=$idioma['idiomaDiminutivo']?>_productoKeywords" type="text" value=""/>
							<? endif ?>
						</div>
					<? endforeach ?>
				</fieldset>

				<fieldset>
					<legend>T&iacute;tulo</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
						<div>
							<label for="<?=$idioma['idiomaDiminutivo']?>_productoMetaTitulo"><?=$idioma['idiomaNombre']?></label>
							<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
								<input name="<?=$idioma['idiomaDiminutivo']?>_productoMetaTitulo" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoMetaTitulo?>"/>
							<? else: ?>
								<input name="<?=$idioma['idiomaDiminutivo']?>_productoMetaTitulo" type="text" value=""/>
							<? endif ?>
						</div>
					<? endforeach ?>
				</fieldset>

				<fieldset>
					<legend>Descripci&oacute;n</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
						<div>
							<label for="<?=$idioma['idiomaDiminutivo']?>_productoDescripcion"><?=$idioma['idiomaNombre']?></label>
							<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
								<textarea style="width: 100%; resize: vertical" name="<?=$idioma['idiomaDiminutivo']?>_productoDescripcion" ><?= $traducciones[$idioma['idiomaDiminutivo']]->productoDescripcion ?></textarea>
							<? else: ?>
								<textarea style="width: 100%; resize: vertical" name="<?=$idioma['idiomaDiminutivo']?>_productoDescripcion" ></textarea>
							<? endif ?>
						</div>
					<? endforeach ?>
				</fieldset>

			</div>
		</div>
	
	<input id="imagen-producto" type="hidden" name="productoImagen" value="<?=$imagenExtension;?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="productoImagenCoord" value="<?=$productoImagenCoord;?>" />
	<?= form_close(); ?>
	
	<?php else: ?>
	<div class="error">Necesita crear primero una categoría para poder crear un producto</div>
	<?php endif?>
	
</div>

<?php if(count($categorias) > 0): ?>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="catalog/product/edit/" data-delete-url="catalog/product/delete/" class="guardar boton importante n1 productos selectbox <?=$nuevo1?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    tableManager.init();
    upload.image('upload-image-product', 'imagen-producto', '<?=base_url();?>admin/imagen/producto/<?=$productoId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>
<?php endif?>