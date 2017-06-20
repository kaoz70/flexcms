<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" <?= $hasPermissions ? 'style="width: 564px"' : '' ?>>

    <?php

    $attributes = array('class' => 'form');
    echo form_open('admin/usuarios/' . $link, $attributes);

    ?>

    <table style="width: 100%">
        <tr>
            <td style="vertical-align: top">

                <div class="field">
                    <div class="header">General</div>
                    <div class="content">

                        <fieldset id="upload-image-usuario">
                            <legend><?=$txt_botImagen;?></legend>
                            <div>
                                <input class="fileselect" type="file" name="fileselect[]" />
                                <div class="filedrag">o arrastre el archivo aquí</div>
                            </div>
                            <ul class="list">
                                <? if($imagen != ''): ?>
                                    <li class="image">
                                        <?=$imagen?>
                                    </li>
                                <? endif; ?>
                            </ul>
                        </fieldset>

                        <div class="input">
                            <label for="first_name" class="required">Nombre</label>
                            <input id="first_name" type="text" class="required" name="first_name" value="<?= $user->first_name?>" />
                        </div>

                        <div class="input">
                            <label for="last_name" class="required">Apellido</label>
                            <input id="last_name" type="text" class="required" name="last_name" value="<?= $user->last_name?>" />
                        </div>

                        <div class="input">
                            <label for="email" class="required">Email</label>
                            <input id="email" type="text" class="required" name="email" value="<?= $nuevo ? '' : $user->email?>" />
                        </div>

                        <div class="input check">
                            <input id="active" type="checkbox" <?=$active?> name="active" />
                            <label for="active" class="required">Activo</label>
                        </div>

                        <? if($user->id != 1): ?>

                            <div class="input">
                                <label for="roles" class="required">Rol</label>
                                <select id="roles" name="roles" class="selectbox">
                                    <? foreach ($roles as $role): ?>
                                        <option <?= $user->inRole($role) ? 'selected' : '' ?> value="<?=$role->id?>"><?=$role->name?></option>
                                    <? endforeach ?>
                                </select>
                            </div>

                        <? endif ?>


                        <?

                        $required = '';

                        if($nuevo) {
                            $required = 'required';
                        }

                        ?>

                        <div class="input">
                            <label for="userPass1">Contraseña</label>
                            <input id="userPass1" type="password" class="password-strong password-same <?=$required?>" name="userPass1" value="" autocomplete="off" />
                        </div>

                        <div class="input">
                            <label for="userPass2">Contraseña de nuevo</label>
                            <input id="userPass2" type="password" class="<?=$required?>" name="userPass2" value="" autocomplete="off" />
                        </div>

                    </div>
                </div>

                <div class="field">
                    <div class="header">Campos</div>
                    <div class="content">

                        <? foreach($fields as $field): ?>

                            <? switch($field->input()->content): case 'texto': ?>

                                <div class="input">
                                    <label for="field[<?=$field->id?>]"><?=$field->name?></label>
                                    <input id="field[<?=$field->id?>]"
                                           type="text"
                                           class=""
                                           name="field[<?=$field->id?>]"
                                           value="<?=$field->fieldData($user)->data?>" />
                                </div>

                            <? break ?>

                            <? case 'texto multilinea': ?>
                                <div class="input">
                                    <label for="field[<?=$field->id?>]"><?=$field->name?></label>
                                    <textarea class="editor"
                                              id="field[<?=$field->id?>]"
                                              name="field[<?=$field->id?>]"
                                              rows="20"
                                              cols="85"><?=$field->fieldData($user)->data?></textarea>
                                </div>
                            <?break?>

                            <? case 'checkbox': ?>
                            <?
                            $count = 0;
                            $display = '';
                            ?>
                            <? foreach($field->traducciones as $key => $trad): ?>
                            <?
                            if($count > 0) {
                                $display = 'display:none;';
                            }

                            $checked = '';
                            if($trad->fieldData($user)->data == 'on') {
                                $checked = 'checked="checked"';
                            }

                            ?>
                            <input id="field[<?=$field->id?>][<?=$key?>]" class="field_<?=$field->id?>" type="checkbox" style="<?=$display?>" <?=$checked?> name="field[<?=$field->id?>][<?=$key?>]" />
                            <? $count++ ?>
                            <? endforeach ?>
                                <script type="text/javascript">
                                    checkboxEnablerLanguage('.field_<?=$field->id?>');
                                </script>
                            <?break?>

                            <? case 'fecha': ?>
                                <div class="input">
                                    <label for="field[<?=$field->id?>]"><?=$field->name?></label>
                                    <input id="field[<?=$field->id?>]" type="text" class="fecha" name="field[<?=$field->id?>]" value="<?=$field->fieldData($user)->data?>" />
                                </div>
                                <script type="text/javascript">
                                    initDatePicker();
                                </script>
                            <? break ?>

                            <? case 'país': ?>
                                <div class="input">
                                    <label for="field[<?=$field->id?>]"><?=$field->name?></label>
                                    <select id="field[<?=$field->id?>]" name="field[<?=$field->id?>]">
                                        <? foreach($countries as $country): ?>
                                            <option value="<?=$country->country_id?>" <?= $country->country_id === $field->fieldData($user)->data ? 'selected' : '' ?>><?=$country->short_name?></option>
                                        <? endforeach ?>
                                    </select>
                                </div>
                                <?break?>

                            <? endswitch ?>

                        <? endforeach ?>

                    </div>

                </div>

            </td>
            <? if(FALSE && $hasPermissions): ?>
                <td class="group" style="vertical-align: top; width: 50%">

                    <p class="info">Estos permisos tienen prioridad sobre los permisos seteados en el Rol</p>

                    <? if($children = $catalog_root_node->getChildren()): ?>
                        <div class="field">
                            <div class="header">Permisos Cat&aacute;logo</div>
                            <div class="content">

                                <ul class="category_discount">
                                    <?= admin_checkbox_permissions($children, 'nivel3', $catalog_permissions, $catalog_names, 'catalog') ?>
                                </ul>

                            </div>
                        </div>
                    <? endif; ?>

                    <? if($children = $gallery_root_node->getChildren()): ?>
                        <div class="field">
                            <div class="header">Permisos Descargas</div>
                            <div class="content">

                                <ul class="category_discount">
                                    <?= admin_checkbox_permissions($children, 'nivel3', $gallery_permissions, $gallery_names, 'gallery') ?>
                                </ul>

                            </div>
                        </div>
                    <? endif; ?>

                </td>
            <? endif; ?>
        </tr>
    </table>



    <input id="userId" type="hidden" name="userId" value="<?=$user->id;?>" />
    <input id="imagen-usuario" type="hidden" name="image_extension" value="<?=$imagenExtension;?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="image_coord" value="<?=$image_coord;?>" />

    <?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="<?= $url_edit ?>" data-delete-url="<?= $url_delete ?>" class="guardar boton importante n1 usuarios selectbox no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>

<script type="text/javascript">
    upload.image('upload-image-usuario', 'imagen-usuario', '<?=base_url();?>admin/imagen/usuario/<?=$user->id?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
    initEditor();
</script>