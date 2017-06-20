<h2>Usuario<a class="cerrar" href="#" ><?=lang('cancel')?></a></h2>
<div class="contenido_col">

<?php

$attributes = array('class' => 'form');
echo form_open('admin/usuarios/' . $link, $attributes);

?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">

            <fieldset id="upload-image-usuario">
                <legend><?=lang('upload_image')?></legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag"><?=lang('drag_file_here')?></div>
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
				<input id="email" type="text" class="required" name="email" value="<?= $user->email?>" />
			</div>

            <div class="input check">
                <input id="active" type="checkbox" <?= $active ? 'checked' : '' ?> name="active" />
                <label for="active" class="required">Activo</label>
            </div>
			
			<div class="input">
				<label for="roles" class="required">Roles</label>
				<select name="roles[]" id="roles[]" multiple="multiple">
					<? foreach ($roles as $role): ?>
					<option value="<?= $role->id ?>" <?= in_array($role->id, $userRoles) ? ' selected="selected"' : null ?>><?= $role->name ?></option>
					<? endforeach ?>
				</select>
			</div>

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

            <? foreach($fields as $campo): ?>

                <? switch($campo->inputTipoContenido): case 'texto': ?>

                    <div class="input">
                        <label for="campo[<?=$campo->userFieldId?>]"><?=$campo->userFieldLabel?></label>
                        <input id="campo[<?=$campo->userFieldId?>]" type="text" class="" name="campo[<?=$campo->userFieldId?>]" value="<?=$campo->userFieldRelContent?>" />
                    </div>

                    <? break ?>

                <? case 'texto multilinea': ?>
                    <div class="input">
                        <label for="campo[<?=$campo->userFieldId?>]"><?=$campo->userFieldLabel?></label>
                        <textarea id="campo[<?=$campo->userFieldId?>]" name="campo[<?=$campo->userFieldId?>]" rows="20" cols="85"><?=$campo->userFieldRelContent?></textarea>
                        <script type="text/javascript">
                            initEditor('campo[<?=$campo->userFieldId?>]');
                        </script>
                    </div>
                    <?break?>

                <? case 'checkbox': ?>
                    <?
                        $count = 0;
                        $display = '';
                    ?>
                    <? foreach($campo->traducciones as $key => $trad): ?>
                        <?
                            if($count > 0) {
                                $display = 'display:none;';
                            }

                            $checked = '';
                            if($trad->userFieldRelContent == 'on') {
                                $checked = 'checked="checked"';
                            }

                        ?>
                        <input id="campo[<?=$campo->userFieldId?>][<?=$key?>]" class="campo_<?=$campo->userFieldId?>" type="checkbox" style="<?=$display?>" <?=$checked?> name="campo[<?=$campo->userFieldId?>][<?=$key?>]" />
                        <? $count++ ?>
                    <? endforeach ?>
                    <script type="text/javascript">
                        checkboxEnablerLanguage('.campo_<?=$campo->userFieldId?>');
                    </script>
                    <?break?>

                <? case 'fecha': ?>
                    <div class="input">
                        <label for="campo[<?=$campo->userFieldId?>]"><?=$campo->userFieldLabel?></label>
                        <input id="campo[<?=$campo->userFieldId?>]" type="text" class="fecha" name="campo[<?=$campo->userFieldId?>]" value="<?=$campo->userFieldRelContent?>" />
                    </div>
                    <script type="text/javascript">
                        initDatePicker();
                    </script>
                    <? break ?>

                <? case 'país': ?>
                    <div class="input">
                        <label for="campo[<?=$campo->userFieldId?>]"><?=$campo->userFieldLabel?></label>
                        <select id="campo[<?=$campo->userFieldId?>]" name="campo[<?=$campo->userFieldId?>]">
                            <? foreach($countries as $country): ?>
                                <option value="<?=$country->country_id?>" <?= $country->country_id === $campo->userFieldRelContent ? 'selected' : '' ?>><?=$country->short_name?></option>
                            <? endforeach ?>
                        </select>
                    </div>
                    <?break?>

                <? endswitch ?>

            <? endforeach ?>

            </div>

    </div>
	
	<input id="userId" type="hidden" name="userId" value="<?=$user->id;?>" />
    <input id="imagen-usuario" type="hidden" name="usuarioImagen" value="<?=$imagenExtension;?>" data-orig="<?=$imagenOrig?>" />
    <input class="coord" type="hidden" name="usuarioImagenCoord" value="<?=$usuarioImagenCoord;?>" />

<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="users/user/edit/" data-delete-url="users/user/delete/" class="guardar boton importante n1 usuarios selectbox no_sort <?=$nuevo?>" ><?=lang('save')?></a>

<script type="text/javascript">
    upload.image('upload-image-usuario', 'imagen-usuario', '<?=base_url();?>admin/imagen/usuario/<?=$user->id?>', <?=$cropDimensions->imagenAncho?>, <?=$cropDimensions->imagenAlto?>, <?=$cropDimensions->imagenCrop ? 'true' : 'false'?>);
</script>