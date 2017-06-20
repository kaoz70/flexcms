<div class="main_content">

    <?= form_open($link, array('data-abide' => '')); ?>
        <h2><?=$this->lang->line('ui_auth_header_login')?></h2>
        <?=$mensaje?>
        <div class="content">
            <div class="input">
                <label for="email"><?=$this->lang->line('ui_auth_email')?> <small><?=$this->lang->line('required_short')?></small></label>
                <input id="email" required="" name="email" type="text" value="" />
                <? if(form_error('email')): ?>
                    <?= form_error('email'); ?>
                <? else: ?>
                    <small class="error"><?=$this->lang->line('required')?></small>
                <? endif ?>
            </div>
            <div class="input">
                <label for="password"><?=$this->lang->line('ui_auth_password')?> <small><?=$this->lang->line('required_short')?></small></label>
                <input id="password" required="" name="password" type="password" autocomplete="off" />
                <? if(form_error('password')): ?>
                    <?= form_error('password'); ?>
                <? else: ?>
                    <small class="error"><?=$this->lang->line('required')?></small>
                <? endif ?>
            </div>
            <div class="input">
                <label for="remember"><?=$this->lang->line('ui_auth_rememeber')?></label>
                <input id="remember" name="remember" type="checkbox" />
            </div>
        </div>
		<input class="small button" type="submit" name="submit" value="<?=$this->lang->line('ui_auth_login')?>" />
        <a class="forgot" href="<?=$linkForgotPassword?>"><?=$this->lang->line('ui_auth_forgot_password')?></a>
	</form>

    <div class="register_cont">
        <p><?=$this->lang->line('ui_auth_are_you_new')?></p>
        <a class="small button" href="<?=base_url($diminutivo.'/' . $pagAutenticacion->paginaNombreURL . '/register')?>"><?=$this->lang->line('ui_auth_create_account')?></a>
    </div>


</div>
