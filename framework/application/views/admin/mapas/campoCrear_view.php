<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php
	
	$attributes = array('class' => 'form');
	echo form_open('admin/mapas/' . $link, $attributes);
	
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			
            <fieldset>
                <legend>Nombre</legend>
                <? foreach ($idiomas as $key => $idioma): ?>
                    <div>
                        <label for="<?=$idioma['idiomaDiminutivo']?>_mapaCampoLabel"><?=$idioma['idiomaNombre']?></label>
                        <? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
                            <input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_mapaCampoLabel" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->mapaCampoLabel?>"/>
                        <? else: ?>
                            <input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_mapaCampoLabel" type="text" value=""/>
                        <? endif ?>
                    </div>
                <? endforeach ?>
            </fieldset>

            <div class="input">
                <label for="inputId">Tipo</label>
                <select id="inputId" name="inputId">
                    <?php
                    foreach ($inputs as $row) :
                        if($inputId == $row->inputId)
                            $selected = "selected";
                        else
                            $selected = '';
                        ?>
                        <option value="<?=$row->inputId;?>" <?=$selected;?> ><?=$row->inputTipoContenido;?></option>
                    <?php endforeach; ?>
                </select>
            </div>
			
			<div class="input check">
				<input type="checkbox" name="mapaCampoPublicado" id="mapaCampoPublicado" <?= $mapaCampoPublicado; ?> />
				<label for="mapaCampoPublicado">Publicado</label>
			</div>

            <div class="input">
				<label for="mapaCampoClase">Clase</label>
				<input type="text" name="mapaCampoClase" id="mapaCampoClase" value="<?= $mapaCampoClase; ?>"/>
			</div>

		</div>
	</div>
	
	<?= form_close(); ?>
	
</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="mapas/modificarCampo/" data-delete-url="mapas/eliminarCampo/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>