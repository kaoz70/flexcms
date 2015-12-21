<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<? if ($mapaId): ?>
<div class="contenido_col" style="width: <?=$imageSize[0] + 353?>px">
<? else: ?>
<div class="contenido_col" style="width: 403px">
<? endif ?>

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/faq/' . $link, $attributes);
	
	?>
	
	<table id="pageData">
		<tr>
			<td>
	
				<div class="field">
					<div class="header">General</div>
					<div class="content">
						
						<div class="input">
							<fieldset>
								<legend>Nombre</legend>
								<div>
									<input class="required name" id="mapaUbicacionNombre" name="mapaUbicacionNombre" type="text" value="<?=$mapaUbicacionNombre?>"/>
								</div>
							</fieldset>
							
							<p class="input">Mapa: <a class="nivel3 pagina_nombre boton importante" href="<?=base_url('admin/maps/map/view_all/1')?>" id="<?=$mapaId;?>"><?=$mapaNombre;?></a></p>
							
                            <fieldset id="upload-image-mapa-ubicacion">
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

                            <fieldset>
                                <legend>Ubicacion</legend>
                                <div>
                                    <label for="mapaUbicacionX">X</label>
                                    <input class="required validate-integer" id="mapaUbicacionX" name="mapaUbicacionX" type="text" value="<?=$mapaUbicacionX?>"/>
                                </div>
                                <div>
                                    <label for="mapaUbicacionY">Y</label>
                                    <input class="required validate-integer" id="mapaUbicacionY" name="mapaUbicacionY" type="text" value="<?=$mapaUbicacionY?>"/>
                                </div>
                            </fieldset>

                            <? if(count($campos) > 0): ?>
                                <div class="field">
                                    <div class="header">Campos</div>

                                    <div class="content">

                                        <?php foreach($campos as $row) : ?>

                                            <fieldset>
                                                <legend><?=$row['mapaCampoLabel']?></legend>
                                                <? foreach ($idiomas as $key => $idioma): ?>
                                                    <div>

                                                        <label for="<?=$idioma['idiomaDiminutivo']?>_campo[<?=$row['mapaCampoId']?>]"><?=$idioma['idiomaNombre']?></label>

                                                        <? switch($row['inputTipoNombre']):

                                                            case 'input': ?>
                                                                <? if(isset($row['mapaCampoTexto'][$idioma['idiomaDiminutivo']]['mapaCampoTexto'])): ?>
                                                                    <input id="<?=$idioma['idiomaDiminutivo']?>_campo[<?=$row['mapaCampoId']?>]" type="text" name="<?=$idioma['idiomaDiminutivo']?>_campo[<?=$row['mapaCampoId']?>]" value="<?=$row['mapaCampoTexto'][$idioma['idiomaDiminutivo']]['mapaCampoTexto']?>">
                                                                <? else: ?>
                                                                    <input id="<?=$idioma['idiomaDiminutivo']?>_campo[<?=$row['mapaCampoId']?>]" type="text" name="<?=$idioma['idiomaDiminutivo']?>_campo[<?=$row['mapaCampoId']?>]" value="">
                                                                <? endif ?>
                                                                <? break; ?>

                                                            <? case 'textarea': ?>
                                                                <textarea id="<?=$idioma['idiomaDiminutivo']?>_editor_<?=$row['mapaCampoId']?>" class="editor" rows="20" cols="85" name="<?=$idioma['idiomaDiminutivo']?>_campo[<?=$row['mapaCampoId']?>]"><?=$row['mapaCampoTexto'][$idioma['idiomaDiminutivo']]['mapaCampoTexto']?></textarea>
                                                                <script type="text/javascript">initEditor("<?=$idioma['idiomaDiminutivo']?>_editor_<?=$row['mapaCampoId']?>");</script>
                                                                <? break; ?>

                                                            <? default: ?>

                                                            <? endswitch ?>
                                                    </div>
                                                <? endforeach ?>
                                            </fieldset>

                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <? endif ?>
							
							<fieldset>
								<legend>Clase</legend>
								<div>
									<input name="mapaUbicacionClase" type="text" value="<?=$mapaUbicacionClase?>"/>
								</div>
							</fieldset>
							
						</div>
						
						<div class="input check">
							<input type="checkbox" name="mapaUbicacionPublicado" id="mapaUbicacionPublicado" <?= $mapaUbicacionPublicado; ?> />
							<label for="mapaUbicacionPublicado">Publicado</label>
						</div>
					</div>
				</div>
				
				<input class="pagina_seleccion" type="hidden" name="mapaId" value="<?=$mapaId;?>" />
				<input id="imagen-mapa-ubicacion" type="hidden" name="mapaUbicacionImagen" value="<?=$mapaUbicacionImagen;?>" data-orig="<?=$imagenOrig?>" />
                <input class="coord" type="hidden" name="mapaUbicacionImagenCoord" value="<?=$mapaUbicacionImagenCoord;?>" />
				
				<?= form_close(); ?>
				
		</td>
		
		
		<? if($mapaId == ''): ?>
		<td id="mapa" style="visibility:hidden">
		<? else: ?>
		<td id="mapa">
		<? endif ?>
			<div class="field">
				<div class="header">Ubicacion en el Mapa:</div>
				<div class="content">
					<div id="mapaContent">
						<? if($mapaId != ''): ?>
						<img width="<?=$imageSize[0]?>" height="<?=$imageSize[1]?>" src="<?=$imagenMapa?>" id="mapaImagen">
						<? endif ?>
						<div id="ubicacion" style="top:<?=$mapaUbicacionY?>px; left:<?=$mapaUbicacionX?>px"><?=$mapaUbicacionNombre?></div>
					</div>
				</div>
			</div>
		</td>
	</tr>
</table>
	
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="maps/location/edit/" data-delete-url="maps/location/delete/" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
	
	var myDrag = new Drag.Move('ubicacion', {
		container : $('mapaContent'),
	    onDrag: function(el){
	        
	    	var position = el.getPosition($('mapaContent'));
	        
	       	$('mapaUbicacionX').set('value', position.x);
	        $('mapaUbicacionY').set('value', position.y);
	        
	    }
	});

    upload.image('upload-image-mapa-ubicacion', 'imagen-mapa-ubicacion', '<?=base_url();?>admin/imagen/mapaUbicacion/<?=$mapaUbicacionId?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>