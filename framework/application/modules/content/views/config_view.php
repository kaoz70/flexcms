<h2>Configuraci&oacute;n<a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" rel="configuracion">
    
    <?= form_open('admin/configuracion/guardar', array('class' => 'form')); ?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">
            <div class="input">

                <fieldset>
                    <legend>Título</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="name_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <input
                            class="required name"
                            name="name[<?=$key?>]"
                            type="text"
                            value="<?= isset($trans->name) ? $trans->name : '' ?>"
                        />
                    <? endforeach ?>
                </fieldset>

            </div>
            <div class="input">

                <fieldset>
                    <legend>Nombre del Menu</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="menu_name_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <input
                            class="required unique-name"
                            data-lang="<?= \App\Language::find($key)->id ?>"
                            data-id="<?=$page->id;?>"
                            name="menu_name[<?=$key?>]"
                            type="text"
                            value="<?= isset($trans->menu_name) ? $trans->menu_name : '' ?>"
                        />
                    <? endforeach ?>
                </fieldset>

            </div>

            <div class="input check">
                <input id="enabled" type="checkbox" name="enabled" value="1" <?=$page->enabled ? 'checked' : '';?> />
                <label for="enabled">Habilitado</label>
            </div>

            <div class="input check">
                <input id="popup" type="checkbox" name="popup" value="1" <?= $page->popup ? 'checked' : '' ?> />
                <label for="popup">Mostrar en Popup</label>
            </div>

            <div class="input">
                <label for="group_visibility" class="required">Visible para</label>
                <select id="group_visibility" name="group_visibility">
                    <option value="0">Public</option>
                    <? foreach ($roles as $key => $role): ?>
                        <option <?= $page->group_visibility == $role->id ? 'selected="selected"' : '' ?> value="<?=$role->id?>"><?=$role->name?></option>
                    <? endforeach ?>
                </select>
            </div>

        </div>
    </div>

    <div class="field">
        <div class="header">SEO</div>
        <div class="content">

            <div class="input">

                <fieldset>
                    <legend>Meta Keywords</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="meta_keywords_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <textarea name="meta_keywords[<?=$key?>]" ><?= isset($trans->meta_keywords) ? implode(', ', $trans->meta_keywords) : '' ?></textarea>
                    <? endforeach ?>
                </fieldset>

            </div>

            <div class="input">

                <fieldset>
                    <legend>Meta Descripción</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="meta_description_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <textarea name="meta_description[<?=$key?>]" ><?= isset($trans->meta_description) ? $trans->meta_description : '' ?></textarea>
                    <? endforeach ?>
                </fieldset>

            </div>

            <div class="input">

                <fieldset>
                    <legend>Meta Titulo</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="meta_title_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <textarea name="meta_title[<?=$key?>]" ><?= isset($trans->meta_title) ? $trans->meta_title : '' ?></textarea>
                    <? endforeach ?>
                </fieldset>

            </div>

        </div>
    </div>

    <div class="field">
        <div class="header">Vistas</div>
        <div class="content">
            <div class="input">
                <label for="list_view">Vista de listado:</label>
                <select name="list_view">
                    <? foreach ($list_views as $view): ?>
                        <option <?= $config->list_view === $view ? 'selected' : '' ?> value="<?=$view?>"><?=$view?></option>
                    <? endforeach ?>
                </select>
            </div>
            <div class="input">
                <label for="detail_view">Vista de detalle:</label>
                <select name="detail_view">
                    <? foreach ($detail_views as $view): ?>
                        <option <?= $config->detail_view === $view ? 'selected' : '' ?> value="<?=$view?>"><?=$view?></option>
                    <? endforeach ?>
                </select>
            </div>
        </div>
    </div>

    <div class="field">
        <div class="header">Contenido</div>
        <div class="content">

            <div class="input">
                <label for="order">Orden:</label>
                <select name="order">
                    <option <?= $config->order === 'manual' ? 'selected' : '' ?> value="manual">Manual</option>
                    <option <?= $config->order === 'date_asc' ? 'selected' : '' ?> value="date_asc">Fecha Ascendente</option>
                    <option <?= $config->order === 'date_desc' ? 'selected' : '' ?> value="date_desc">Fecha Descendente</option>
                </select>
            </div>

            <div class="input check">
                <input id="pagination" name="pagination" type="checkbox" <?=$config->pagination ? 'checked' : ''?> value="1"/>
                <label for="pagination">Paginar listado</label>
            </div>

            <div class="input">
                <label for="quantity">Cantidad paginado:</label>
                <input name="quantity" type="number" value="<?= $config->quantity ?>">
            </div>
        </div>
    </div>

    <?= form_close() ?>

</div>
<a class="guardar boton importante n1" href="<?=$save_url?>">Guardar</a>

<script type="text/javascript">
    seccionesAdmin();
</script>
