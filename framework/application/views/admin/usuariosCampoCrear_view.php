<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

    <?php

    $attributes = array('class' => 'form');
    echo form_open('admin/articulos/' . $link, $attributes);

    ?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">
            <div class="input">

                <fieldset>
                    <legend>Etiqueta</legend>
                    <? foreach ($idiomas as $key => $idioma): ?>
                        <div>
                            <label for="<?=$idioma['idiomaDiminutivo']?>_userFieldLabel"><?=$idioma['idiomaNombre']?></label>
                            <? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
                                <input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_userFieldLabel" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->userFieldLabel?>"/>
                            <? else: ?>
                                <input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_userFieldLabel" type="text" value=""/>
                            <? endif ?>
                        </div>
                    <? endforeach ?>
                </fieldset>

                <fieldset>
                    <legend>Placehoder</legend>
                    <? foreach ($idiomas as $key => $idioma): ?>
                        <div>
                            <label for="<?=$idioma['idiomaDiminutivo']?>_userFieldPlaceholder"><?=$idioma['idiomaNombre']?></label>
                            <? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
                                <input class="" name="<?=$idioma['idiomaDiminutivo']?>_userFieldPlaceholder" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->userFieldPlaceholder?>"/>
                            <? else: ?>
                                <input class="" name="<?=$idioma['idiomaDiminutivo']?>_userFieldPlaceholder" type="text" value=""/>
                            <? endif ?>
                        </div>
                    <? endforeach ?>
                </fieldset>

            </div>

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

            <div class="input check">
				<input type="checkbox" name="userFieldActive" id="userFieldActive" <?= $habilitado; ?> />
                <label for="userFieldActive">Publicado</label>
            </div>

            <div class="input check">
				<input id="userFieldRequired" name="userFieldRequired" type="checkbox" <?=$userFieldRequired?> />
                <label for="userFieldRequired">Obligatorio:</label>
            </div>

            <div class="input">
                <label for="userFieldValidation">Validación:</label>
                <input id="userFieldValidation" name="userFieldValidation" type="text" value="<?=$userFieldValidation?>"/>
            </div>

        </div>
    </div>

    <div class="field">
        <div class="header">Avanzado</div>
        <div class="content">
            <div class="input">
                <label for="userFieldClass">Clase</label>
                <input name="userFieldClass"  id="userFieldClass" type="text" value="<?= $userFieldClass; ?>" />
            </div>
        </div>
    </div>

    <input id="userFieldId" type="hidden" name="userFieldId" value="<?=$userFieldId;?>" />

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


    <?= form_close(); ?>

</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="usuarios/modificarCampo/" data-delete-url="usuarios/eliminarCampo/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>