<h2><?=$titulo; ?><a class="cerrar" href="#" data-delete="<?=$removeUrl?>" >cancelar</a></h2>
<div class="contenido_col" style="width: 1200px;">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/paginas/' . $link, $attributes);

?>
    <table id="pageData">
        <tr>
            <td>

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
                                        data-id="<?=$id;?>"
                                        name="menu_name[<?=$key?>]"
                                        type="text"
                                        value="<?= isset($trans->menu_name) ? $trans->menu_name : '' ?>"
                                        />
                                <? endforeach ?>
                            </fieldset>

                        </div>

                        <div class="input check">
                            <input id="enabled" type="checkbox" name="enabled" value="1" <?=$enabled ? 'checked' : '';?> />
                            <label for="enabled">Habilitado</label>
                        </div>

                    </div>
                </div>

                <div class="field">
                    <div class="header">Avanzado</div>
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

                        <div class="input">
                            <label for="css_class">clase</label>
                            <input id="css_class" type="text" name="css_class" value="<?= $css_class ?>" />
                        </div>

                        <div class="input check">
                            <input id="popup" type="checkbox" name="popup" value="1" <?= $popup ? 'checked' : '' ?> />
                            <label for="popup">Mostrar en Popup</label>
                        </div>

                        <div class="input">
                            <label for="group_visibility" class="required">Visible para</label>
                            <select id="group_visibility" name="group_visibility">
                                <option value="0">Public</option>
                                <? foreach ($groups as $key => $group): ?>
                                    <option <?= $group_visibility == $group->id ? 'selected="selected"' : '' ?> value="<?=$group->id?>"><?=$group->name?></option>
                                <? endforeach ?>
                            </select>
                        </div>

                    </div>
                </div>

            </td>
            <td>
                <div class="field" id="template">
                    <div class="header">Estructura</div>
                        <div class="content">

                            <div id="module_manager">

                                <ul id="rows">
                                    <? foreach ($structure as $key => $row): ?>
                                        <li class="row">

                                            <div class="move_row"></div>
                                            <div class="remove_row">X</div>

                                            <div class="row_controls">
                                                <div class="input check">
                                                    <input id="fila_<?=$key?>_expanded" type="checkbox" name="fila[<?=$key?>][expanded]" <?=$row->expanded ? 'checked': '' ?> value="1">
                                                    <label for="fila_<?=$key?>_expanded">Expandida</label>
                                                </div>
                                                <div class="input small">
                                                    <label>Clase</label>
                                                    <input type="text" name="fila[<?=$key?>][class]" value="<?=$row->class != '' ? $row->class : '' ?>">
                                                </div>
                                            </div>
                                            <div>

                                                <?

                                                $row_data['row'] = $row;
                                                $row_data['key'] = $key;
                                                $row_data['page_id'] = $id;

                                                $this->load->view('widgets/row_view', $row_data);

                                                ?>

                                            </div>
                                        </li>
                                    <? endforeach ?>
                                </ul>

                                <div id="add_row">
                                    <div class="text">A&ntilde;adir Fila</div>
                                    <ul id="row_types">
                                        <li class="rows" id="row_1"><img src="<?=base_url()?>assets/admin/images/template/1.jpg" /></li>
                                        <li class="rows" id="row_2"><img src="<?=base_url()?>assets/admin/images/template/2.jpg" /></li>
                                        <li class="rows" id="row_3"><img src="<?=base_url()?>assets/admin/images/template/3.jpg" /></li>
                                        <li class="rows" id="row_4"><img src="<?=base_url()?>assets/admin/images/template/4.jpg" /></li>
                                    </ul>
                                </div>

                                <div id="options">
                                    <div class="text">Opciones</div>
                                    <div class="content">
                                        <label>Copiar estructura de:</label>
                                        <select>
                                            <option value="0"> --- P&aacute;gina --- </option>
                                            <?= admin_select_tree($pages, []) ?>
                                        </select>
                                        <a id="copiar_estructura" data-pagina-id="<?=$id?>" href="#" class="boton importante">Copiar</a>
                                    </div>
                                </div>

                            </div>
                    </div>
                </div>
            </td>
        </tr>

    </table>

    <input id="id" type="hidden" name="id" value="<?=$id?>" />

<?= form_close(); ?>

</div>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="structure/edit/" data-delete-url="structure/delete/" class="guardar boton importante tree n1 page <?=$nuevo ?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    moduleManager.init();
</script>