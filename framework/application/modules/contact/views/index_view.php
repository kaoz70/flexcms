<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>

<ul class="contenido_col listado_general" rel="<?=base_url('admin/contacto')?>" style="bottom: 104px;">
	<li class="pagina field">
		<h3 class="header">Departamento / Persona</h3>
		<ul class="content" id="persona">

			<?php foreach($result_contactos as $row):?>

				<li class="listado" id="<?=$row->contactoId?>">
					<a class="nombre modificar nivel2" href="<?=base_url();?>admin/contact/person/edit/<?=$row->contactoId?>"><span><?=$row->contactoNombre;?></span></a>
					<a href="<?=base_url();?>admin/contact/person/delete/<?=$row->contactoId?>" class="eliminar" ></a>
				</li>
			
			<?php endforeach;?>
		</ul>

	</li>
    <li class="pagina field">
        <h3 class="header">Direcciones</h3>
        <ul id="list_direccion" class="sorteable content" data-sort="<?=base_url('admin/contact/address/reorder')?>">

            <?php foreach($result_direcciones as $row): ?>

                <li class="listado drag" id="<?=$row->contactoDireccionId;?>">
                    <div class="mover"></div>
                    <a class="nombre modificar nivel2" href="<?=base_url();?>admin/contact/address/edit/<?=$row->contactoDireccionId;?>"><span><?=$row->contactoDireccionNombre;?></span></a>
                    <a href="<?=base_url();?>admin/contact/address/delete/<?=$row->contactoDireccionId;?>" class="eliminar" ></a>
                </li>

            <?php endforeach;?>
        </ul>
        <script type="text/javascript">
            initSortables($('list_direccion'));
        </script>
    </li>
	<li class="pagina field">
		<h3 class="header">Elmentos del Formulario</h3>
		<ul id="list_contacto" class="sorteable content" data-sort="<?=base_url('admin/contact/field/reorder')?>">

			<?php foreach($result_elementos as $row): ?>
			
				<li class="listado drag" id="<?=$row->contactoCampoId;?>">
					<div class="mover"></div>
					<a class="nombre modificar nivel2" href="<?=base_url();?>admin/contact/field/edit/<?=$row->contactoCampoId;?>"><span><?=$row->contactoCampoValor;?></span></a>
					<a href="<?=base_url();?>admin/contact/field/delete/<?=$row->contactoCampoId;?>" class="eliminar" ></a>
				</li>
			
			<?php endforeach;?>
		</ul>
		<script type="text/javascript">
			initSortables($('list_contacto'));
		</script>
	</li>
</ul>

<a id="crear_contacto" class="nivel2 ajax boton n3" href="<?=base_url();?>admin/contact/address/create"><?=$txt_nuevaDireccion;?></a>
<a id="crear_contacto" class="nivel2 ajax boton n2" href="<?=base_url();?>admin/contact/person/create"><?=$txt_nuevoContacto;?></a>
<a id="crear_elemento" class="nivel2 ajax boton n1" href="<?=base_url();?>admin/contact/field/create"><?=$txt_nuevoElem;?></a>
