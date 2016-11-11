<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/contacto/' . $link, $attributes);

?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">

            <div class="input">
                <label for="name">Nombre</label>
                <input class="required name" name="name" type="text" value="<?= $form->name ?>"/>
            </div>

            <div class="input">
                <label for="contact_id">Email</label>
                <select name="contact_id">
                    <? foreach ($people as $person): ?>
                        <option <?= ($form->contact_id == $person->id) ? 'selected' : '' ?> value="<?= $person->id ?>"><?= $person->email ?></option>
                    <? endforeach; ?>
                </select>
            </div>

        </div>
    </div>

<?= form_close(); ?>
</div>

<a href="<?= $fields_url; ?>" class="ajax nivel2 boton n2" >Campos</a>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="<?= $edit_url ?>" data-delete-url="<?= $delete_url ?>" class="guardar boton importante n1 contacto_direccion <?=$nuevo?>" ><?=$txt_boton;?></a>
