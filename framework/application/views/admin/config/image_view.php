<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div id="imagen" class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/noticias/' . $link, $attributes);

?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input small">
				<label for="imagenNombre">Nombre</label>
				<input class="required name" id="imagenNombre" name="imagenNombre" type="text" value="<?=$imagenNombre?>"/>
			</div>
            <div class="input">
                <label for="seccionId">Sección</label>
                <select class="selectbox" name="seccionId" id="seccionId">
                    <? foreach($secciones as $grupos): ?>
                        <optgroup label="<?=$grupos[0]->adminSeccionNombre?>">
                        <? foreach($grupos as $seccion): ?>
                            <? if($seccion->imagenSeccionId == $imagenSeccionId): ?>
                                <option selected="selected" value="<?=$seccion->imagenSeccionId?>"><?=$seccion->imagenSeccionNombre?></option>
                            <? else: ?>
                                <option value="<?=$seccion->imagenSeccionId?>"><?=$seccion->imagenSeccionNombre?></option>
                            <? endif ?>
                        <?endforeach?>
                        </optgroup>
                    <?endforeach?>
                </select>
            </div>
            <div class="input small">
                <label for="imagenSufijo">Sufijo</label>
                <input id="imagenSufijo" name="imagenSufijo" type="text" value="<?=$imagenSufijo?>"/>
            </div>
            <div class="input small">
                <label for="imagenAncho">Ancho</label>
                <input class="required" id="imagenAncho" name="imagenAncho" type="text" value="<?=$imagenAncho?>"/>
            </div>
            <div class="input small">
                <label for="imagenAlto">Alto</label>
                <input class="required" id="imagenAlto" name="imagenAlto" type="text" value="<?=$imagenAlto?>"/>
            </div>
            <div class="input check">
                <input id="imagenCrop" name="imagenCrop" type="checkbox" <?=$imagenCrop?> value="1"/>
				<label for="imagenCrop">Usar para corte:</label>
            </div>
		</div>
	</div>
	

	<input id="bannerId" type="hidden" name="bannerId" value="<?=$imagenId;?>" />

<?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="config/edit_image/" data-delete-url="config/delete_image/" class="guardar boton importante n1 selectbox <?=$nuevo?>" ><?=$txt_boton;?></a>