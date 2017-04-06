<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="buscar">
    <input type="text" name="searchString" value="Buscar..." />
    <div class="searchButton"></div>
</div>

<ul class="contenido_col searchResults" style="bottom: 71px;">

    <? foreach($roles as $role): ?>
        <? if($role->id != 4): ?>
            <li class="pagina field">

                <h3 class="header"><?=$role->name?></h3>

                <ul id="list_<?=$role->id?>" class="content" >
                    <?php foreach($users as $user_key => $user): ?>
                        <? if($user->inRole($role)):?>
                            <li class="listado" id="<?=$user->id?>">
                                <a class="nombre modificar nivel2" href="<?=$url_edit;?><?=$user->id?>"><span><?=$user->first_name?> <?=$user->last_name?></span></a>
                                <?php if($user->id != 1): ?>
                                    <a href="<?=$url_delete;?><?=$user->id?>" class="eliminar" >eliminar</a>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                    <?php  endforeach; ?>
                </ul>
            </li>
        <? endif ?>
    <?php  endforeach; ?>

</ul>
<a id="crear" class="nivel2 ajax boton importante n4" href="<?=$url_create;?>"><?=$txt_usuarios;?></a>
<a class="nivel2 ajax boton n3" href="<?=base_url();?>admin/auth/roles">Roles</a>
<a class="nivel2 ajax boton n2" href="<?=base_url();?>admin/auth/field">Template</a>
<a class="nivel2 ajax boton n1" href="<?=base_url();?>admin/auth/config">Configuraci&oacute;n</a>

<script type="text/javascript">
    search.init('<?=base_url("admin/search/usuarios")?>', 'es');
</script>