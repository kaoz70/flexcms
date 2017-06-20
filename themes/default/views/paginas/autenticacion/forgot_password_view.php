<div class="main_content">

	<?=$this->lang->line('ui_auth_forgot_password_header_text')?>

    <?= form_open($diminutivo.'/' . $pagAutenticacion->paginaNombreURL . '/'.'password/confirm', array('data-abide' => '')); ?>
        <?=$message?>
		<div>
			<label for="email"><?=$this->lang->line('ui_auth_email')?> <small><?=$this->lang->line('required_short')?></small></label>
			<input required="" id="email" type="text" name="email" value="<?=$email?>" />
            <small class="error"><?=$this->lang->line('required')?></small>
		</div>

        <input type="submit" class="small button" value="<?=$this->lang->line('ui_button_send')?>" />
	      
	    <input type="hidden" name="redirect" value="<?=current_url()?>" />
	    <input type="hidden" name="success" value="<?=$linkForgotPasswordSuccess?>" />
	      
	<?php echo form_close();?>

</div>
