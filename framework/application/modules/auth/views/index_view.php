<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="buscar">
    <input type="text" name="searchString" value="Buscar..." />
    <div class="searchButton"></div>
</div>

<ul class="contenido_col searchResults" style="bottom: 71px;">

    <? foreach($roles as $role): ?>
        <li class="pagina field">

            <h3 class="header"><?=$role->name?></h3>

            <ul id="list_<?=$role->id?>" class="content" >
                <? foreach($role->users() as $user_key => $user): ?>
                    <li class="listado" id="<?=$user->user_id?>">
                        <a class="nombre modificar nivel2" href="<?=base_url();?>auth/admin/user/edit/<?=$user->user_id?>"><span><?=$user->first_name?> <?=$user->last_name?></span></a>
                        <? if($user->user_id != 1): ?>
                        <a href="<?=base_url();?>auth/admin/user/delete/<?=$user->user_id?>" class="eliminar" ></a>
                        <? endif; ?>
                    </li>
                <? endforeach; ?>
            </ul>

        </li>
    <? endforeach; ?>

</ul>
<a id="crear" class="nivel2 ajax boton importante n2" href="<?=base_url();?>auth/admin/user/create"><?=$txt_usuarios;?></a>
<a class="nivel2 ajax boton n1" href="<?=base_url();?>auth/admin/field">Template</a>

<script type="text/javascript">
    search.init('<?=base_url("admin/search/usuarios")?>', 'es');
</script>