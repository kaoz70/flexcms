<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>

<ul class="contenido_col listado_general" rel="<?=base_url('admin/contacto')?>" style="bottom: 104px;">
    <li class="pagina field">
        <h3 class="header">Emails de Contacto</h3>
        <ul id="list_contacto" class="sorteable content" data-sort="<?=base_url('admin/contact/field/reorder')?>">

            <?php foreach($people as $row): ?>

                <li class="listado" id="<?=$row->id;?>">
                    <a class="nombre modificar nivel2" href="<?=base_url();?>admin/contact/person/edit/<?=$row->id;?>"><span><?=$row->name;?></span></a>
                    <a href="<?=base_url();?>admin/contact/person/delete/<?=$row->id;?>" class="eliminar" ></a>
                </li>

            <?php endforeach;?>

        </ul>
    </li>
    <li class="pagina field">
        <h3 class="header">Formularios</h3>
        <ul id="list_contacto" class="sorteable content" data-sort="<?=base_url('admin/contact/field/reorder')?>">

            <?php foreach($forms as $row): ?>

                <li class="listado" id="<?=$row->id;?>">
                    <a class="nombre modificar nivel2" href="<?=base_url();?>admin/contact/form/edit/<?=$row->id;?>"><span><?=$row->name;?></span></a>
                    <a href="<?=base_url();?>admin/contact/form/delete/<?=$row->id;?>" class="eliminar" ></a>
                </li>

            <?php endforeach;?>

        </ul>
    </li>
</ul>

<a id="crear_contacto" class="nivel2 ajax boton n2" href="<?=base_url();?>admin/contact/form/create"><?=$new_form;?></a>
<a id="crear_contacto" class="nivel2 ajax boton n1" href="<?=base_url();?>admin/contact/person/create"><?=$new_person;?></a>
<!--<a id="crear_elemento" class="nivel2 ajax boton n1" href="<?/*=base_url();*/?>admin/contact/field/create"><?/*=$txt_nuevoElem;*/?></a>-->
