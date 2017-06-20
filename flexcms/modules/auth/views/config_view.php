<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" rel="configuracion">

    <?= form_open('admin/configuracion/guardar', array('class' => 'form')); ?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">
            <div class="input">
                <label for="automatic_activation">Activaci&oacute;n de usuarios</label>
                <select name="automatic_activation">
                    <option <?= (int)$config['automatic_activation'] === 1 ? 'selected' : '' ?> value="1">Autom&aacute;tica</option>
                    <option <?= (int)$config['automatic_activation'] === 0 ? 'selected' : '' ?> value="0">Manual</option>
                </select>
            </div>
            <div class="input">
                <label for="registered_role">Rol del usuario registrado</label>
                <select name="registered_role">
                    <? foreach ($roles as $role): ?>
                        <option <?= $role->slug === $config['registered_role'] ? 'selected' : '' ?> value="<?= $role->slug ?>"><?= $role->name ?></option>
                    <? endforeach; ?>
                </select>
            </div>
            <div class="input">
                <label for="login_identity">Columna para validar login</label>
                <input type="text" name="login_identity" value="<?= $config['login_identity'] ?>">
            </div>
        </div>
    </div>

    <div class="field">
        <div class="header">Validaci&oacute;n de contrase&ntilde;a</div>
        <div class="content">
            <div class="input">
                <label for="password_min_length">Largo m&iacute;nimo</label>
                <input type="text" name="password_min_length" value="<?= $config['password_min_length'] ?>">
            </div>
            <div class="input">
                <label for="password_max_length">Largo m&aacute;ximo</label>
                <input type="text" name="password_max_length" value="<?= $config['password_max_length'] ?>">
            </div>
        </div>
    </div>

    <div class="field">
        <div class="header">Facebook</div>
        <div class="content">

            <div class="input">
                <label for="facebook_login">Sessi&oacute;n con Facebook</label>
                <select name="facebook_login">
                    <option <?= $config['facebook_login'] === '0' ? 'selected' : '' ?> value="0">No usar</option>
                    <option <?= $config['facebook_login'] === '1' ? 'selected' : '' ?> value="1">Usar</option>
                </select>
            </div>

            <div class="input">
                <label for="facebook_app_id">App ID</label>
                <input id="facebook_app_id" name="facebook_app_id" type="text" value="<?=$config['facebook_app_id']?>" />
            </div>

            <div class="input">
                <label for="facebook_app_secret">App Secret</label>
                <input id="facebook_app_secret" name="facebook_app_secret" type="text" value="<?=$config['facebook_app_secret']?>" />
            </div>

        </div>
    </div>

    <div class="field">
        <div class="header">Twitter</div>
        <div class="content">

            <div class="input">
                <label for="twitter_login">Sessi&oacute;n con Twitter</label>
                <select name="twitter_login">
                    <option <?= $config['twitter_login'] === '0' ? 'selected' : '' ?> value="0">No usar</option>
                    <option <?= $config['twitter_login'] === '1' ? 'selected' : '' ?> value="1">Usar</option>
                </select>
            </div>

            <div class="input">
                <label for="twitter_consumer_key">Consumer Key</label>
                <input id="twitter_consumer_key" name="twitter_consumer_key" type="text" value="<?=$config['twitter_consumer_key']?>" />
            </div>

            <div class="input">
                <label for="twitter_consumer_secret">Consumer Secret</label>
                <input id="twitter_consumer_secret" name="twitter_consumer_secret" type="text" value="<?=$config['twitter_consumer_secret']?>" />
            </div>

        </div>
    </div>

    <?= form_close() ?>

</div>
<a class="guardar boton importante n1" href="<?=base_url();?>admin/config/save"><?=$txt_guardar;?></a>
