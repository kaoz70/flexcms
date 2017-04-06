<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

    <?=form_open('admin/slideshows/' . $link, array('class' => 'form'));?>
    
    <div class="field">
        <div class="header">Elemento</div>
        <div class="content">
            <div class="input">
                <label for="name">Nombre:</label>
                <input class="required name" id="name" type="text" name="name" maxlength="250"  value="<?= isset($name) ? $name : '' ?>"/>
            </div>
            <div class="input">
                <label for="input_id">Tipo</label>
                <select id="input_id" name="input_id">
                    <?php foreach ($inputs as $input): ?>
                        <option value="<?=$input->id;?>" <?=(isset($input_id) && $input_id == $input->id) ? 'selected' : ''?> ><?=$input->content;?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input">
                <fieldset>
                    <legend>Etiqueta</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="label_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <input class=""
                            name="label[<?=$key?>]"
                            type="text"
                            value="<?= isset($trans->label) ? $trans->label : '' ?>"
                        />
                    <? endforeach ?>
                </fieldset>
            </div>
            <div class="input">
                <label for="css_class">Clase:</label>
                <input id="css_class" type="text" name="css_class" maxlength="250" value="<?= isset($css_class) ? $css_class : '' ?>" />
            </div>
            <div class="input check">
                <input id="label_enabled" name="label_enabled" type="checkbox" <?= isset($label_enabled) && $label_enabled ? 'checked' : ''?> value="1" />
                <label for="label_enabled">Mostrar Etiqueta:</label>
            </div>
        </div>
    </div>

    <input type="hidden" name="section" value="slider">
    
  <?= form_close(); ?>

</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="sliders/field/edit/" data-delete-url="sliders/field/delete/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

  