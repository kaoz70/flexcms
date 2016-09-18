<h2><?=$titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">
    <?php

    $attributes = array('class' => 'form');
    echo form_open('admin/contacto/' . $link, $attributes);
    ?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">

            <div class="input">
                <label for="css_class">Clase:</label>
                <input class="required name" id="name" name="name" type="text" value="<?=$field->name?>"/>
            </div>

            <fieldset>
                <legend>Etiqueta</legend>

                <? foreach ($translations as $key => $trans): ?>
                    <label for="label_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <input class=""
                           id="label_<?=$key?>"
                           name="label[<?=$key?>]"
                           type="text"
                           value="<?= isset($trans->label) ? $trans->label : '' ?>"/>
                <? endforeach ?>

            </fieldset>

            <fieldset>
                <legend>Placeholder</legend>

                <? foreach ($translations as $key => $trans): ?>
                    <label for="placeholder_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <input class=""
                           id="placeholder_<?=$key?>"
                           name="placeholder[<?=$key?>]"
                           type="text"
                           value="<?= isset($trans->label) ? $trans->placeholder : '' ?>"/>
                <? endforeach ?>

            </fieldset>

            <div class="input">
                <label for="input_id">Tipo:</label>
                <select id="input_id" name="input_id">
                    <? foreach ($inputs as $row) : ?>
                    <option value="<?=$row->id;?>" <?=$field->input_id === $row->id ? 'selected' : '' ?> ><?=$row->content;?></option>
                    <? endforeach;?>
                </select>
            </div>

            <div class="input">
                <label for="css_class">Clase:</label>
                <input id="css_class" name="css_class" type="text" value="<?=$field->css_class?>"/>
            </div>

            <div class="input check">
                <input id="required" name="required" type="checkbox" <?=$field->required ? 'checked' : '' ?> />
                <label for="required">Obligatorio</label>
            </div>

            <div class="input check">
                <input id="enabled" name="enabled" type="checkbox" <?=$field->enabled || $nuevo ? 'checked' : '' ?> />
                <label for="enabled">Publicado</label>
            </div>

            <div class="input">
                <label for="validation">Validación:</label>
                <input id="validation" name="validation" type="text" value="<?=$field->validation?>"/>
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

    <?= form_close();?>
</div>
<a href="<?= $link;?>" data-level="nivel2" data-edit-url="<?= $edit_url ?>" data-delete-url="<?= $delete_url ?>" class="guardar boton importante n1 contacto_form <?=$nuevo?>" ><?=$txt_boton;?></a>