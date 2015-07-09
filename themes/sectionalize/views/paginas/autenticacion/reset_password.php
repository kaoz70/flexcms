<h1>Reseteo de Contraseña</h1>

<div class="main_content">

    <?
    $attributes = array(
        'data-abide' => '',
        'class' => 'custom'
    );
    ?>

    <?= form_open($link, $attributes);?>

    <div class="input">
        <label for="new"><?=$this->lang->line('ui_auth_password')?> <small><?=$this->lang->line('required_short')?></small></label>
        <input id="new" required="" name="new" type="password" value="" autocomplete="off" />
        <? if(form_error('new')): ?>
            <?= form_error('new'); ?>
        <? else: ?>
            <small class="error"><?=$this->lang->line('required')?></small>
        <? endif ?>
    </div>

    <div class="input">
        <label for="new_confirm"><?=$this->lang->line('ui_auth_password_again')?> <small><?=$this->lang->line('required_short')?></small></label>
        <input id="new_confirm" required="" name="new_confirm" type="password" value="" autocomplete="off" />
        <? if(form_error('new_confirm')): ?>
            <?= form_error('new_confirm'); ?>
        <? else: ?>
            <small class="error"><?=$this->lang->line('required')?></small>
        <? endif ?>
    </div>

    <?= form_input($user_id);?>
    <?= form_hidden($csrf); ?>

    <input type="submit" class="small button" value="<?=$this->lang->line('ui_button_send')?>" />

    <?= form_close();?>

</div>