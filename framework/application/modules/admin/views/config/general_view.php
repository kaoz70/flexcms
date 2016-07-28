<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" rel="configuracion">
    <?= form_open('admin/configuracion/guardar', array('class' => 'form')); ?>

        <div class="field">
            <div class="header">General</div>
            <div class="content">
                <div class="input">
                    <label for="site_name">Nombre del Sitio:</label>
                    <input id="site_name" name="site_name" type="text" value="<?=$config['site_name']?>" />
                </div>
                <div class="input">
                    <label for="index_page_id">Página inicial:</label>
                    <select name="index_page_id">
                    <? foreach ($paginas as $pagina): ?>
                        <option <?= $config['index_page_id'] === $pagina['paginaId'] ? 'selected' : '' ?> value="<?=$pagina['paginaId']?>"><?=$pagina['paginaNombreMenu']?></option>
                    <? endforeach ?>
                    </select>
                </div>
                <div class="input">
                    <label for="theme">Theme:</label>
                    <select name="theme">
                        <? foreach ($themes as $theme): ?>
                            <option <?= $config['theme'] === $theme ? 'selected' : '' ?> value="<?=$theme?>"><?=$theme?></option>
                        <? endforeach ?>
                    </select>
                </div>
                <div class="input">
                    <label for="environment">Estado:</label>
                    <select name="environment">
                        <option <?= $config['environment'] === 'development' ? 'selected' : '' ?> value="development">Desarrollo</option>
                        <option <?= $config['environment'] === 'testing' ? 'selected' : '' ?> value="testing">Pruebas</option>
                        <option <?= $config['environment'] === 'production' ? 'selected' : '' ?> value="production">Producci&oacute;n</option>
                        <option <?= $config['environment'] === 'offline' ? 'selected' : '' ?> value="offline">Offline</option>
                    </select>
                </div>
            </div>
        </div>

		<div class="field">
			<div class="header">Development</div>
			<div class="content">
				<div class="input">
					<label for="debug_bar">Barra depuradora:</label>
					<select name="debug_bar">
						<option <?= $config['debug_bar'] === '0' ? 'selected' : '' ?> value="0">Desactivado</option>
						<option <?= $config['debug_bar'] === '1' ? 'selected' : '' ?> value="1">Activo</option>
					</select>
				</div>
			</div>
		</div>

    </form>

</div>
<a class="guardar boton importante n1" href="<?=base_url();?>admin/config/save"><?=$txt_guardar;?></a>

<script type="text/javascript">
    seccionesAdmin();
</script>
