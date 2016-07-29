<h2>Configuraci&oacute;n<a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" rel="configuracion">
    
    <?= form_open('admin/configuracion/guardar', array('class' => 'form')); ?>

    <div class="field">
        <div class="header">Vistas</div>
        <div class="content">
            <div class="input">
                <label for="list_view">Vista de listado:</label>
                <select name="list_view">
                    <? foreach ($list_views as $view): ?>
                        <option <?= $config->list_view === $view ? 'selected' : '' ?> value="<?=$view?>"><?=$view?></option>
                    <? endforeach ?>
                </select>
            </div>
            <div class="input">
                <label for="detail_view">Vista de detalle:</label>
                <select name="detail_view">
                    <? foreach ($detail_views as $view): ?>
                        <option <?= $config->detail_view === $view ? 'selected' : '' ?> value="<?=$view?>"><?=$view?></option>
                    <? endforeach ?>
                </select>
            </div>
        </div>
    </div>

    <div class="field">
        <div class="header">Contenido</div>
        <div class="content">

            <div class="input">
                <label for="order">Orden:</label>
                <select name="order">
                    <option <?= $config->order === 'manual' ? 'selected' : '' ?> value="manual">Manual</option>
                    <option <?= $config->order === 'date_asc' ? 'selected' : '' ?> value="date_asc">Fecha Ascendente</option>
                    <option <?= $config->order === 'date_desc' ? 'selected' : '' ?> value="date_desc">Fecha Descendente</option>
                </select>
            </div>

            <div class="input check">
                <input id="pagination" name="pagination" type="checkbox" <?=$config->pagination ? 'checked' : ''?> value="1"/>
                <label for="pagination">Paginar listado</label>
            </div>

            <div class="input">
                <label for="quantity">Cantidad paginado:</label>
                <input name="quantity" type="number" value="<?= $config->quantity ?>">
            </div>
        </div>
    </div>

    <?= form_close() ?>

</div>
<a class="guardar boton importante n1" href="<?=$save_url?>">Guardar</a>

<script type="text/javascript">
    seccionesAdmin();
</script>
