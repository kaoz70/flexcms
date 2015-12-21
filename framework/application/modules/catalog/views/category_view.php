<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div class="contenido_col" style="width: 797px">

	<?=form_open('admin/catalogo/' . $link, array('class' => 'form'));?>
	
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			
			<fieldset>
				<legend>Nombre</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div class="input small">
					<label for="<?=$idioma['idiomaDiminutivo']?>_productoCategoriaNombre"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input class="required name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_producto_categorias" data-columna="productoCategoriaUrl" data-columna-id="productoCategoriaId" data-id="<?=$categoriaId;?>" name="<?=$idioma['idiomaDiminutivo']?>_productoCategoriaNombre" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaNombre?>"/>
					<? else: ?>
						<input class="required name" data-seccion="<?=$idioma['idiomaDiminutivo']?>_producto_categorias" data-columna="productoCategoriaUrl" data-columna-id="productoCategoriaId" data-id="<?=$categoriaId;?>" name="<?=$idioma['idiomaDiminutivo']?>_productoCategoriaNombre" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

            <fieldset>
				<legend>Descripción</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_productoCategoriaDescripcion"><?=$idioma['idiomaNombre']?></label>
                    <textarea id="<?=$idioma['idiomaDiminutivo']?>_editor" class="editor" rows="20" cols="85" name="<?=$idioma['idiomaDiminutivo']?>_productoCategoriaDescripcion"><?=$traducciones[$idioma['idiomaDiminutivo']]->productoCategoriaDescripcion?></textarea>
                    <script type="text/javascript">initEditor("<?=$idioma['idiomaDiminutivo']?>_editor");</script>
					</div>
				<? endforeach ?>
			</fieldset>
			
            <fieldset id="upload-image-category">
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
			
		</div>
	</div>
	
	<input id="imagen-categoria" type="hidden" name="categoriaImagen" value="<?=$imagenExtension;?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="categoriaImagenCoord" value="<?=$categoriaImagenCoord;?>" />
	<?= form_close(); ?> 
	
</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="catalog/category/edit/" data-delete-url="catalog/category/delete/" data-id="<?=$categoriaId?>" data-reorder="catalog/category/reorder/" class="guardar boton importante n1 tree categoria <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    upload.image('upload-image-category', 'imagen-categoria', '<?=base_url();?>admin/imagen/catalogoCategoria/<?=$categoriaId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>