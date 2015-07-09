<div class="main_content">
    <h2><?=$this->lang->line('ui_auth_header_pregister')?></h2>

    <? if(false): //disable ii temporarily ?>
    <p>Puede registrarse usando:</p>

    <fb:login-button
        registration-url="<?=base_url($diminutivo . '/' . $pagAutenticacion->paginaNombreURL . '/register/facebook')?>" ></fb:login-button>

    <p>O, directamente con nuestro formulario:</p>
    <? endif ?>

    <?
        $attributes = array(
            'data-abide' => '',
            'class' => 'custom'
        );
        echo form_open($diminutivo . '/' . $pagAutenticacion->paginaNombreURL . '/register/add', $attributes);
    ?>

        <?=$message?>

        <div class="input <?= form_error('first_name') == '' ? '' : 'error' ?>">
            <label for="first_name"><?=$this->lang->line('ui_auth_name')?> <small><?=$this->lang->line('required_short')?></small></label>
            <input id="first_name" required="" type="text" name="first_name" value="<?= $user->first_name?>" />
            <? if(form_error('first_name')): ?>
                <?= form_error('first_name'); ?>
            <? else: ?>
                <small class="error"><?=$this->lang->line('required')?></small>
            <? endif ?>
        </div>

        <div class="input <?= form_error('last_name') == '' ? '' : 'error' ?>">
            <label for="last_name"><?=$this->lang->line('ui_auth_surname')?> <small><?=$this->lang->line('required_short')?></small></label>
            <input id="last_name" type="text" required="" name="last_name" value="<?= $user->last_name?>" />
            <? if(form_error('last_name')): ?>
                <?= form_error('last_name'); ?>
            <? else: ?>
                <small class="error"><?=$this->lang->line('required')?></small>
            <? endif ?>
        </div>

        <div class="input <?= form_error('email') == '' ? '' : 'error' ?>">
            <label for="email">Email <small><?=$this->lang->line('required_short')?></small></label>
            <input id="email" type="text" required="" placeholder="usuario@dominio.com" name="email" value="<?= $user->email?>" />
            <? if(form_error('email')): ?>
                <?= form_error('email'); ?>
            <? else: ?>
                <small class="error"><?=$this->lang->line('required')?></small>
                <small class="error"><?=$this->lang->line('email')?></small>
            <? endif ?>
        </div>

        <? foreach($user->campos as $campo): ?>
            <? switch($campo->inputTipoContenido): case 'texto': ?>
                <div class="input <?= form_error('campo['.$campo->userFieldId.']') == '' ? '' : 'error' ?>">
                    <label for="campo_<?=$campo->userFieldId?>"><?=$campo->userFieldLabel?> <?= $campo->userFieldRequired ? '<small>' . $this->lang->line('required_short') . '</small>' : '' ?></label>
                    <input placeholder="<?=$campo->userFieldPlaceholder?>" <?= $campo->userFieldRequired ? 'required' : '' ?> pattern="<?=$campo->userFieldValidation?>" id="campo_<?=$campo->userFieldId?>" type="text"  class="<?=$campo->userFieldClass?>" name="campo[<?=$campo->userFieldId?>]" value="<?=$campo->userFieldRelContent?>" />
                    <? if(form_error('campo['.$campo->userFieldId.']')): ?>
                        <?= form_error('campo['.$campo->userFieldId.']'); ?>
                    <? else: ?>
                        <small class="error"><?=$this->lang->line('required')?></small>
                    <? endif ?>
                </div>
            <? break ?>

            <? case 'texto multilinea': ?>
                <div class="input <?= form_error('campo['.$campo->userFieldId.']') == '' ? '' : 'error' ?>">
                    <label for="campo_<?=$campo->userFieldId?>"><?=$campo->userFieldLabel?> <?= $campo->userFieldRequired ? '<small>' . $this->lang->line('required_short') . '</small>' : '' ?></label>
                    <textarea placeholder="<?=$campo->userFieldPlaceholder?>" <?= $campo->userFieldRequired ? 'required' : '' ?> pattern="<?=$campo->userFieldValidation?>" id="campo_<?=$campo->userFieldId?>"  class="<?=$campo->userFieldClass?>" name="campo[<?=$campo->userFieldId?>]" ><?=$campo->userFieldRelContent?></textarea>
                    <? if(form_error('campo['.$campo->userFieldId.']')): ?>
                        <?= form_error('campo['.$campo->userFieldId.']'); ?>
                    <? else: ?>
                        <small class="error"><?=$this->lang->line('required')?></small>
                    <? endif ?>
                </div>
            <? break ?>

            <? case 'fecha': ?>
                <div class="input <?= form_error('campo['.$campo->userFieldId.']') == '' ? '' : 'error' ?>">
                    <label for="campo[<?=$campo->userFieldId?>]"><?=$campo->userFieldLabel?> <?= $campo->userFieldRequired ? '<small>' . $this->lang->line('required_short') . '</small>' : '' ?></label>
                    <input placeholder="<?=$campo->userFieldPlaceholder?>" <?= $campo->userFieldRequired ? 'required' : '' ?> pattern="<?=$campo->userFieldValidation?>" id="campo_<?=$campo->userFieldId?>" type="text"  class="<?=$campo->userFieldClass?>" name="campo[<?=$campo->userFieldId?>]" value="<?=$campo->userFieldRelContent?>" />
                    <? if(form_error('campo['.$campo->userFieldId.']')): ?>
                        <?= form_error('campo['.$campo->userFieldId.']'); ?>
                    <? else: ?>
                        <small class="error"><?=$this->lang->line('required')?></small>
                    <? endif ?>
                </div>
                <script>
                    $(function() {
                        $( "#campo_<?=$campo->userFieldId?>" ).datepicker();
                    });
                </script>
                <? break ?>

            <? case 'país': ?>
                <div class="input <?= form_error('campo['.$campo->userFieldId.']') == '' ? '' : 'error' ?>">
                    <label for="campo[<?=$campo->userFieldId?>]"><?=$campo->userFieldLabel?></label>
                    <select id="campo[<?=$campo->userFieldId?>]" name="campo[<?=$campo->userFieldId?>]" required="" <?= $campo->userFieldRequired ? 'required' : '' ?>>
                        <option value="">-- <?=$this->lang->line('ui_select')?> --</option>
                        <? foreach($countries as $country): ?>
                            <option value="<?=$country->country_id?>" <?= $country->country_id === $campo->userFieldRelContent ? 'selected' : '' ?>><?=$country->short_name?></option>
                        <? endforeach ?>
                    </select>
                    <? if(form_error('campo['.$campo->userFieldId.']')): ?>
                        <?= form_error('campo['.$campo->userFieldId.']'); ?>
                    <? else: ?>
                        <small class="error"><?=$this->lang->line('required')?></small>
                    <? endif ?>
                </div>
                <?break?>

            <? endswitch ?>
        <? endforeach ?>

        <div class="input <?= form_error('password') == '' ? '' : 'error' ?>">
            <label for="password"><?=$this->lang->line('ui_auth_password')?></label>
            <input id="password" type="password" required name="password" value="" autocomplete="off" />
            <? if(form_error('password')): ?>
                <?= form_error('password'); ?>
            <? else: ?>
                <small class="error"><?=$this->lang->line('required')?></small>
                <small class="error"><?=$this->lang->line('password')?></small>
            <? endif ?>
        </div>

        <div class="input <?= form_error('password_confirm') == '' ? '' : 'error' ?>">
            <label for="password_confirm"><?=$this->lang->line('ui_auth_password_again')?></label>
            <input id="password_confirm" type="password" required name="password_confirm" value="" autocomplete="off" />
            <? if(form_error('password_confirm')): ?>
                <?= form_error('password_confirm'); ?>
            <? else: ?>
                <small class="error"><?=$this->lang->line('required')?></small>
                <small class="error"><?=$this->lang->line('password')?></small>
            <? endif ?>
        </div>

		<input class="button small" type="submit" name="submit" value="<?=$this->lang->line('ui_button_register')?>" />
        <input class="button small" type="reset" name="reset" value="<?=$this->lang->line('ui_button_reset')?>" />
	</form>
	
	<div class="iniciar_sesion">
		<p class="ya_miembro">¿Ya eres miembro? <a href="<?=base_url($diminutivo . '/' . $pagAutenticacion->paginaNombreURL)?>">Iniciar sesión</a></p>
	</div>

</div>
