<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

    <?=form_open('admin/catalogo/' . $link, array('class' => 'form'));?>
    
    <div class="field">
        <div class="header">General</div>
        <div class="content">

            <div class="input">
                <label for="name">Nombre</label>
                <input class="required name"
                       name="name"
                       type="text"
                       value="<?= $field->name ?>"
                />
            </div>

            <fieldset>
                <legend>Etiqueta</legend>
                <? foreach ($translations as $key => $trans): ?>
                    <label for="label_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <input class="required"
                           name="label[<?=$key?>]"
                           type="text"
                           value="<?= isset($trans->label) ? $trans->label : '' ?>"
                    />
                <? endforeach ?>
            </fieldset>

            <div class="input">
                <label for="input_id">Tipo:</label>
                <select id="input_id" name="input_id">
                    <?php foreach ($inputs as $row) : ?>
                        <option value="<?=$row->id;?>"
                            <?= isset($field->input_id) && $field->input_id == $row->id ? 'selected' : '';?>
                        ><?=$row->content;?></option>
                    <?php endforeach;?>
                </select>
            </div>

            <div class="input check">
                <input type="checkbox"
                       name="label_enabled"
                       id="label_enabled"
                    <?= isset($field->label_enabled) && $field->label_enabled ? 'checked' : '' ?> />
                <label for="label_enabled">Mostrar Nombre</label>
            </div>

            <div class="input check">
                <input type="checkbox"
                       name="view_in_widget"
                       id="view_in_widget"
                    <?= isset($data->view_in_widget) && $data->view_in_widget ? 'checked' : '' ?> />
                <label for="view_in_widget">Ver en widget</label>
            </div>

            <div class="input check">
                <input type="checkbox"
                       name="view_in_list"
                       id="view_in_list"
                    <?= isset($data->view_in_list) && $data->view_in_list ? 'checked' : '' ?> />
                <label for="view_in_list">Ver en Listado</label>
            </div>

            <div class="input check">
                <input type="checkbox"
                       name="view_in_cart"
                       id="view_in_cart"
                    <?= isset($data->view_in_cart) && $data->view_in_cart ? 'checked' : '' ?> />
                <label for="view_in_cart">Ver en Carrito</label>
            </div>

            <div class="input check">
                <input type="checkbox"
                       name="view_in_filters"
                       id="view_in_filters"
                    <?= isset($data->view_in_filters) && $data->view_in_filters ? 'checked' : '' ?> />
                <label for="view_in_filters">Ver en Filtros</label>
            </div>

            <div class="input check">
                <input type="checkbox"
                       name="enabled"
                       id="enabled"
                    <?= isset($field->enabled) && $field->enabled ? 'checked' : '' ?> />
                <label for="enabled">Publicado</label>
            </div>

            <div class="input">
                <label for="css_class">Clase</label>
                <input name="css_class"
                       id="css_class"
                       type="text"
                       value="<?= $field->css_class ?>" />
            </div>

        </div>
    </div>

  <?= form_close(); ?>
</div>

<!--<a id="crear" class="nivel4 ajax boton n2" <?/*= $inputTipoContenido !== 'listado predefinido' ? 'style="display: none"' : '' */?> href="<?/*= base_url('admin/catalog/predefinedListItem/index/'.$campoId) */?>" >Editar Items</a>-->
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="<?= $url_edit?>" data-delete-url="<?= $url_delete ?>" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

  