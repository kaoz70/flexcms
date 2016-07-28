<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/contacto/' . $link, $attributes);

?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">

            <fieldset>
                <legend>Nombre</legend>
                <input class="required name" name="name" type="text" value="<?= $person->name ?>"/>
            </fieldset>

            <div class="input">
                <label>Email:</label>
                <input class="required validate-email" id="email" name="email" type="email" value="<?= $person->email ?>"/>
            </div>
        </div>
    </div>

<?= form_close(); ?>
</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="<?= $edit_url ?>" data-delete-url="<?= $delete_url?>" class="guardar boton importante n1 contacto_persona no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>