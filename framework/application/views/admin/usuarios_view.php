<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="buscar">
    <input type="text" name="searchString" value="Buscar..." />
    <div class="searchButton"></div>
</div>

<ul class="contenido_col searchResults" style="bottom: 71px;" rel="<?=base_url('admin/usuarios')?>">

    <? foreach($groups as $group): ?>
        <? if($group->id != 4): ?>
        <li class="pagina field">

            <h3 class="header"><?=$group->description?></h3>

            <ul id="list_<?=$group->id?>" class="content" >
                <?php foreach($usuarios as $user_key => $user): ?>
                    <? if($user->group_id == $group->id):?>
                    <li class="listado" id="<?=$user->user_id?>">
                        <a class="nombre modificar nivel2" href="<?=base_url();?>admin/usuarios/modificar/<?=$user->user_id?>"><span><?=$user->first_name?> <?=$user->last_name?></span></a>
                        <?php if($user->user_id != 1): ?>
                        <a href="<?=base_url();?>admin/usuarios/eliminar/<?=$user->user_id?>" class="eliminar" >eliminar</a>
                        <?php endif; ?>
                    </li>
                    <?php endif; ?>
                <?php  endforeach; ?>
            </ul>
        </li>
        <? endif ?>
    <?php  endforeach; ?>

</ul>
<a id="crear" class="nivel2 ajax boton importante n2" href="<?=base_url();?>admin/usuarios/crear"><?=$txt_usuarios;?></a>
<a class="nivel2 ajax boton n1" href="<?=base_url();?>admin/usuarios/template">Template</a>

<script type="text/javascript">
    search.init('<?=base_url("admin/search/usuarios")?>', 'es');
</script>