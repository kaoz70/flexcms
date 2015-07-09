<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<ul class="contenido_col" rel="<?=base_url('admin/paginas/cargarListadoPaginas')?>">
<?php foreach($paginas as $pagina): ?>
    <li class="listado" id="<?=$pagina['paginaId'];?>">
        <a id="<?=$pagina['paginaId'];?>" class="nombre seleccionar" href="paginas/seleccionarPagina/<?=$pagina['paginaId'];?>"><span><?=$pagina['paginaNombreMenu']?></span></a>
    </li>
<?php endforeach;?>
</ul>
