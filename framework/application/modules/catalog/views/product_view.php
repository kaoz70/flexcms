<h2><?=$title; ?><a class="cerrar" href="#" >cancelar</a></h2>

<div class="contenido_col">

    <?php if($categories || $product->category_id): ?>

    <?=form_open('admin/catalogo/' . $link, array('class' => 'form'));?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">

            <fieldset>
                <legend>Nombre</legend>
                <? foreach ($translations as $key => $trans): ?>
                    <label for="name_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <input class="required name"
                           name="name[<?=$key?>]"
                           type="text"
                           value="<?= isset($trans->name) ? $trans->name : '' ?>"
                    />
                <? endforeach ?>
            </fieldset>


            <!--<fieldset id="upload-image-product">
                <legend><?/*=$txt_botImagen;*/?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
                <ul class="list">
                    <?/* if($imagen != ''): */?>
                        <li class="image">
                            <?/*=$imagen*/?>
                        </li>
                    <?/* endif; */?>
                </ul>
            </fieldset>-->

            <div class="input">
                <label for="category_id">Categor&iacute;a</label>
                <select class="selectbox" id="category_id" name="category_id">
                    <?= admin_select_tree($categories, $product->category_id) ?>
                </select>
            </div>

            <div class="input small">
                <label for="stock_quantity">Stock</label>
                <input id="stock_quantity" name="stock_quantity" type="text" value="<?= $product->stock_quantity ?>">
            </div>

            <div class="input small">
                <label for="weight">Peso</label>
                <input id="weight" name="weight" type="text" value="<?= $product->weight ?>">
            </div>

            <div class="input check">
                <input id="important" type="checkbox" name="important" value="1" <?=$product->important ? 'checked' : ''?>/>
                <label for="important">Producto Destacado</label>
            </div>

            <div class="input check">
                <input id="enabled" name="enabled" type="checkbox"  value="1" <?=$product->enabled ? 'checked' : ''?> />
                <label for="enabled">Publicado</label>
            </div>

        </div>
    </div>
        
    <? if($fields): ?>
    <div class="field">
        <div class="header">Campos</div>

        <div class="content">

            <?php foreach($fields as $field): ?>

            <fieldset>
                <legend><?=$field->name?></legend>

                <? foreach ($translations as $key => $trans): ?>

                    <?
                    //Set the language to use for the field
                    $field->setLang($key);
                    ?>

                    <label for="name_<?=$key?>"><?= \App\Language::find($key)->name ?></label>

                    <? switch($field->input()->content): case 'texto': ?>

                        <div class="input">
                            <label for="field[<?=$field->id?>][<?=$key?>]"><?=$field->name?></label>
                            <input id="field[<?=$field->id?>][<?=$key?>]" type="text" class="" name="field[<?=$field->id?>][<?=$key?>]" value="<?=$field->fieldData($product)->data?>" />
                        </div>

                    <? break ?>

                    <? case 'texto multilinea': ?>
                        <div class="input">
                            <label for="field[<?=$field->id?>][<?=$key?>]"><?=$field->name?></label>
                            <textarea class="editor" id="field[<?=$field->id?>][<?=$key?>]" name="field[<?=$field->id?>][<?=$key?>]" rows="20" cols="85"><?=$field->fieldData($product)->data?></textarea>
                        </div>
                    <?break?>

                    <? case 'tabla': ?>
                        <div class="table_editor">
                            <div class="tbleColumnCont">
                                <table>
                                    <tr>
                                        <td class="no_edit">
                                            <? if($row->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido == ''): ?>
                                                <table class="tableGrid" id="<?=$idioma['idiomaDiminutivo']?>_editor_grid_<?=$row->productoCampoId?>">
                                                    <tbody>
                                                    <tr>
                                                        <th>nombre cabecera</th>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            <? else : ?>
                                                <?=$row->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido?>
                                            <? endif; ?>
                                        </td>
                                        <td class="no_edit">
                                            <div class="add_column"></div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="add_row"></div>
                            <textarea class="tableGridInput" id="input_editor_grid_<?=$row->productoCampoId?>" name="<?=$idioma['idiomaDiminutivo']?>_<?=$row->productoCampoId?>" ><?=$row->productoCampoRelContenido[$idioma['idiomaDiminutivo']]->productoCampoRelContenido?></textarea>
                        </div>
                        <? break; ?>

                    <? case 'archivos': ?>
                        <a class="nivel4 ajax boton importante" href="<?= base_url()?>admin/catalog/file/index/<?=$productoId?>/<?=$row->productoCampoId?>">Modificar <?=$row->productoCampoValor?></a>
                        <? break 2; ?>

                    <? case 'select': ?>

                        <? if($row->inputTipoContenido === 'listado predefinido'): ?>
                            <a class="nivel4 ajax boton importante" href="<?= base_url()?>admin/catalog/predefinedList/index/<?=$productoId?>/<?=$row->productoCampoId?>">Modificar <?=$row->productoCampoValor?></a>
                        <? else: ?>
                            <a class="nivel4 ajax boton importante" href="<?= base_url()?>admin/catalog/field_list/<?=$productoId?>/<?=$row->productoCampoId?>">Modificar <?=$row->productoCampoValor?></a>
                        <? endif ?>

                        <? break 2; ?>

                    <? default: ?>


                    <? endswitch ?>

                <? endforeach ?>

            </fieldset>

            <? endforeach; ?>
        </div>
    </div>
    <? endif ?>

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
        <div class="header">Azanzado</div>
        <div class="content">

            <div class="input">
                <label for="css_class">clase</label>
                <input id="css_class" type="text" name="css_class" value="<?= $product->css_class ?>" />
            </div>

            <div class="input">
                <label for="visible_to" class="required">Visible para</label>
                <select id="visible_to" name="visible_to">
                    <option value="public">Public</option>
                    <? foreach ($groups as $key => $group): ?>
                        <option <?= $product->visible_to == $group->slug ? 'selected="selected"' : '' ?> value="<?=$group->slug?>"><?=$group->name?></option>
                    <? endforeach ?>
                </select>
            </div>

        </div>
    </div>

    <?= form_close(); ?>

    <?php else: ?>
    <div class="error">Necesita crear primero una categoría para poder crear un producto</div>
    <?php endif?>

</div>

<?php if($categories): ?>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="<?=$url_edit?>" data-delete-url="<?= $url_delete ?>" class="guardar boton importante n1 productos selectbox <?=$nuevo?>" ><?=$txt_boton;?></a>

<!--<script type="text/javascript">
    tableManager.init();
    upload.image('upload-image-product', 'imagen-producto', '<?/*=base_url();*/?>admin/imagen/producto/<?/*=$productoId*/?>', <?/*=$cropDimensions->imagenAncho*/?>, <?/*=$cropDimensions->imagenAlto*/?>, <?/*=$cropDimensions->imagenCrop ? 'true' : 'false'*/?>);
</script>-->
<?php endif?>