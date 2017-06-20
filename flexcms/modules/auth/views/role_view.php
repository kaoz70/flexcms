<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" <?= $hasPermissions ? 'style="width: 564px"' : '' ?>>

    <?php

    $attributes = array('class' => 'form');
    echo form_open('admin/articulos/' . $link, $attributes);

    ?>

    <table style="width: 100%">
        <tr>
            <td>

                <div class="field">
                    <div class="header">General</div>
                    <div class="content">

                        <div class="input">
                            <label for="name">Nombre</label>
                            <input name="name"  id="name" type="text" class="name" value="<?= $role->name; ?>" />
                        </div>

                        <div class="input">
                            <label for="slug">Diminutivo (slug)</label>
                            <input name="slug"  id="slug" type="text" value="<?= $role->slug; ?>" />
                        </div>

                        <div class="input check">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="admin"
                                   id="perm-admin"
                                <?= $role->hasAccess('admin') ? 'checked' : '' ?> />
                            <label for="perm-admin">Administrador</label>
                        </div>

                    </div>
                </div>

                <? if($user->inRole(\Cartalyst\Sentinel\Native\Facades\Sentinel::findRoleById(1))): ?>

                <div class="field">
                    <div class="header">Secciones Administrativas</div>
                    <div class="content">

                        <div class="input check">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="module.structure"
                                <?= $role->hasAccess('module.structure') ? 'checked' : '' ?>
                                   id="perm-sec-structure" />
                            <label for="perm-sec-structure">Estructura</label>
                        </div>

                        <? foreach ($menu as $item): ?>

                            <div class="input check">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="module.<?=$item->controller?>"
                                    <?= $role->hasAccess('module.' . $item->controller) ? 'checked' : '' ?>
                                       id="perm-sec-<?= $item->controller ?>" />
                                <label for="perm-sec-<?= $item->controller ?>"><?=$item->name->es?></label>
                            </div>

                        <? endforeach; ?>

                        <div class="input check">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="module.language"
                                <?= $role->hasAccess('module.language') ? 'checked' : '' ?>
                                   id="perm-sec-language" />
                            <label for="perm-sec-language">Idiomas</label>
                        </div>

                        <div class="input check">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="module.config"
                                <?= $role->hasAccess('module.config') ? 'checked' : '' ?>
                                   id="perm-sec-config" />
                            <label for="perm-sec-config">Configuraci&oacute;n</label>
                        </div>

                    </div>
                </div>

            </td>
            <? if($hasPermissions): ?>
                <td class="group" style="vertical-align: top; width: 50%">

                    <? if($children = $catalog_root_node->getChildren()): ?>
                        <div class="field">
                            <div class="header">Permisos Cat&aacute;logo</div>
                            <div class="content">

                                <ul class="category_discount">
                                    <?= admin_checkbox_categories($children, 'nivel3', $catalog_permissions, $catalog_names, 'catalog[]', true) ?>
                                </ul>

                            </div>
                        </div>
                    <? endif; ?>

                    <? if($children = $gallery_root_node->getChildren()): ?>
                        <div class="field">
                            <div class="header">Permisos Descargas</div>
                            <div class="content">

                                <ul class="category_discount">
                                    <?= admin_checkbox_categories($children, 'nivel3', $gallery_permissions, $gallery_names, 'gallery[]', true) ?>
                                </ul>

                            </div>
                        </div>
                    <? endif; ?>

                </td>
            <? endif; ?>
        </tr>
    </table>



    <? endif ?>

    <?= form_close(); ?>

</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="<?= $url_edit ?>" data-delete-url="<?= $url_delete ?>" class="guardar boton importante n1 no_sort <?=$nuevo?>" ><?=$txt_boton;?></a>