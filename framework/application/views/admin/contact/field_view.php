<h2><?=$titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">
	<?php

	$attributes = array('class' => 'form');
	echo form_open('admin/contacto/' . $link, $attributes);
	?>
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			
			<fieldset>
				<legend>Etiqueta</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_contactoCampoValor"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_contactoCampoValor" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoValor?>"/>
					<? else: ?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_contactoCampoValor" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

            <fieldset>
                <legend>Placeholder</legend>
                <? foreach ($idiomas as $key => $idioma): ?>
                    <div>
                        <label for="<?=$idioma['idiomaDiminutivo']?>_contactoCampoPlaceholder"><?=$idioma['idiomaNombre']?></label>
                        <? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
                            <input name="<?=$idioma['idiomaDiminutivo']?>_contactoCampoPlaceholder" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->contactoCampoPlaceholder?>"/>
                        <? else: ?>
                            <input name="<?=$idioma['idiomaDiminutivo']?>_contactoCampoPlaceholder" type="text" value=""/>
                        <? endif ?>
                    </div>
                <? endforeach ?>
            </fieldset>
		
			<div class="input">
				<label for="inputId">Tipo:</label>
				<select id="inputId" name="inputId">
					<?php foreach ($inputs as $row) : ?>
					<?php 
						if($inputId == $row->inputId)
							$selected = "selected";
						else
							$selected = '';
					?>
					<option value="<?=$row->inputId;?>" <?=$selected;?> ><?=$row->inputTipoContenido;?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="input">
				<label for="contactoCampoClase">Clase:</label>
				<input id="contactoCampoClase" name="contactoCampoClase" type="text" value="<?=$contactoCampoClase?>"/>
			</div>

            <div class="input check">
                <input id="contactoCampoRequerido" name="contactoCampoRequerido" type="checkbox" <?=$contactoCampoRequerido?> />
				<label for="contactoCampoRequerido">Obligatorio</label>
            </div>

            <div class="input">
                <label for="contactoCampoValidacion">Validación:</label>
                <input id="contactoCampoValidacion" name="contactoCampoValidacion" type="text" value="<?=$contactoCampoValidacion?>"/>
            </div>

		</div>

	</div>

    <div class="field">
        <div class="header">Ayuda</div>
        <div class="content">

            <fieldset>
                <legend>Validación</legend>
                <ul>
                    <li>Alfabético: alpha</li>
                    <li>Alfanumérico: alpha_numeric</li>
                    <li>Entero: integer</li>
                    <li>Número: number</li>
                    <li>Contraseña: password</li>
                    <li>Tarjeta: card</li>
                    <li>CCV: cvv</li>
                    <li>Email: email</li>
                    <li>Link: url</li>
                    <li>Dominio: domain</li>
                    <li>Fecha - Hora: datetime</li>
                    <li>Fecha: date</li>
                    <li>Hora: time</li>
                    <li>Mes / Dia / Año: month_day_year</li>
                </ul>
            </fieldset>

            <fieldset>
                <legend>Otro</legend>
                <ul>
                    <li>Campo nombre (Clase): name</li>
                </ul>
            </fieldset>

        </div>
    </div>
	
	<input id="contactoCampoId" type="hidden" name="contactoCampoId" value="<?=$contactoCampoId;?>" />
	<?= form_close();?>
</div>
<a href="<?= $link;?>" data-level="nivel2" data-edit-url="contact/field/edit/" data-delete-url="contact/field/delete/" class="guardar boton importante n1 contacto_form <?=$nuevo?>" ><?=$txt_boton;?></a>