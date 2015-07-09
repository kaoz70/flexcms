<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" rel="configuracion">
    <?= form_open('admin/configuracion/guardar', array('class' => 'form')); ?>

        <div class="field">
            <div class="header">Facebook</div>
            <div class="content">

                <div class="input">
                    <label for="facebook_login">Login con Facebook</label>
					<select name="facebook_login">
						<option <?= $config->facebook_login === '0' ? 'selected' : '' ?> value="0">No usar</option>
						<option <?= $config->facebook_login === '1' ? 'selected' : '' ?> value="1">Usar</option>
					</select>
                </div>

                <div class="input">
                    <label for="facebook_app_id">App ID</label>
                    <input id="facebook_app_id" name="facebook_app_id" type="text" value="<?=$config->facebook_app_id?>" />
                </div>

                <div class="input">
                    <label for="facebook_app_secret">App Secret</label>
                    <input id="facebook_app_secret" name="facebook_app_secret" type="text" value="<?=$config->facebook_app_secret?>" />
                </div>

            </div>
        </div>

    </form>

</div>
<a class="guardar boton importante n1" href="<?=base_url();?>admin/config/save"><?=$txt_guardar;?></a>

<script type="text/javascript">
    seccionesAdmin();
</script>
