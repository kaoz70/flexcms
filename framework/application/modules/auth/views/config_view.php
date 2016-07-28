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

    </form>

</div>
<a class="guardar boton importante n1" href="<?=base_url();?>admin/config/save"><?=$txt_guardar;?></a>
