<h2><?php echo $txt_titulo;?><a class="cerrar" href="#" >cancelar</a></h2>

<div class="buscar">
	<input type="text" name="searchString" value="Buscar..." />
	<div class="searchButton"></div>
</div>

<ul class="contenido_col searchResults listado_general" rel="<?=base_url('admin/mailchimp/subscribers')?>" style="bottom: <?=$bottomMargin?>px">

	<li class="pagina field">
		<h3 class="header">Suscritos</h3>
		<ul class="content" id="subscribed">
			<?php foreach($subscribed as $row):?>
				<li class="listado" id="<?=$row['LEID']?>">
					<a class="nombre modificar <?=$nivel?>" href="<?=base_url();?>admin/mailchimp/subscriber/edit/<?=$list_id?>/<?=$row['LEID']?>"><span><?=$row['Email Address']?></span></a>
					<a href="<?=base_url();?>admin/mailchimp/subscriber/delete/<?=$list_id?>/<?=$row['LEID']?>" class="eliminar" ></a>
					<a href="<?=base_url();?>admin/mailchimp/subscriber/unsubscribe/<?=$list_id?>/<?=$row['LEID']?>" class="unsubscribe" ></a>
				</li>
			<?php endforeach;?>
		</ul>
	</li>

	<li class="pagina field">
		<h3 class="header">Desuscritos</h3>
		<ul class="content" id="unsubscribed">
			<?php foreach($unsubscribed as $row):?>
				<li class="listado" id="<?=$row['LEID']?>">
					<a class="nombre modificar <?=$nivel?>" href="<?=base_url();?>admin/mailchimp/subscriber/edit/<?=$list_id?>/<?=$row['LEID']?>"><span><?=$row['Email Address']?></span></a>
					<a href="<?=base_url();?>admin/mailchimp/subscriber/delete/<?=$list_id?>/<?=$row['LEID']?>" class="eliminar" ></a>
				</li>
			<?php endforeach;?>
		</ul>
	</li>

</ul>

<?foreach($menu as $item): ?>
	<?=$item?>
<? endforeach ?>
