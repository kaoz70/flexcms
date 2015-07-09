<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>

<ul id="usuarios_campos" class="contenido_col" rel="<?=base_url('admin/usuarios/template')?>" data-sort="<?=base_url('admin/usuarios/reorganizarCampos')?>">

    <? foreach($campos as $campo): ?>
        <li class="listado" id="<?=$campo->userFieldId?>">
            <div class="mover">mover</div>
            <a class="nombre modificar nivel3" href="<?=base_url();?>admin/usuarios/modificarCampo/<?=$campo->userFieldId?>"><span><?=$campo->userFieldLabel?></span></a>
            <a href="<?=base_url();?>admin/usuarios/eliminarCampo/<?=$campo->userFieldId?>" class="eliminar" >eliminar</a>
        </li>
    <?php  endforeach; ?>

</ul>
<a id="crear" class="nivel3 ajax boton importante n1" href="<?=base_url();?>admin/usuarios/crearCampo">Crear Campo</a>

<script type="text/javascript">
    initSortables($('usuarios_campos'));
</script>