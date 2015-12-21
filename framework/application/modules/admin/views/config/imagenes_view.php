<h2>Imágenes</h2>
<ul class="contenido_col listado_general" rel="<?=base_url('admin/config/images')?>">

    <? foreach($secciones as $grupos): ?>
        <li class="pagina field">
            <h3 class="header">Sección: <?=$grupos[0]->adminSeccionNombre?></h3>
            <ul class="content">
            <? foreach($grupos as $seccion): ?>

                <?if(count($grupos) > 1): ?>
                <h4><?=$seccion->imagenSeccionNombre?></h4>
                <? endif ?>

                <ul id="list_<?=$seccion->imagenSeccionId?>" class="sorteable content grupo" data-sort="<?=base_url('admin/config/reorder_images/'.$seccion->imagenSeccionId)?>">
                    <?php foreach($imagenes as $imagen): ?>
                        <?php if($imagen->imagenSeccionId == $seccion->imagenSeccionId): ?>
                            <li class="listado drag" id="<?=$imagen->imagenId?>">
                                <div class="mover">mover</div>
                                <a class="nombre modificar nivel3" href="<?=base_url();?>admin/config/edit_image/<?=$imagen->imagenId?>"><span><?=$imagen->imagenNombre?></span></a>
                                <a href="<?=base_url();?>admin/config/delete_image/<?=$imagen->imagenId?>" class="eliminar" >eliminar</a>
                            </li>
                        <?php endif; ?>
                    <?php  endforeach; ?>
                </ul>

                <script type="text/javascript">
                    initSortables($('list_<?=$seccion->imagenSeccionId?>'));
                </script>
            <?  endforeach; ?>
            </ul>
        </li>
    <?  endforeach; ?>

</ul>
<a id="crear" class="nivel3 ajax importante n1 boton" href="<?=base_url();?>admin/config/create_image">Crear Imágen</a>
