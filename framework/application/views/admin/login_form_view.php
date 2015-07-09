<?= form_open($form_action, array('class' => 'shadow', 'id' => 'login')); ?>
    <div class="field">
        <div class="header"><img id="logo" src="<?=base_url('assets/admin/images/logo.png')?>" /></div>
        <?=$error?>
        <div class="content">
            <div class="input small">
                <label for="username">Usuario:</label>
                <input id="username" name="username" type="text" value="" />
            </div>
            <div class="input small">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" autocomplete="off" />
            </div>
        </div>
    </div>
    <input type="submit" name="submit" value="ingresar" />
</form>