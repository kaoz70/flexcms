<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?=form_open($link, array('class' => 'form'));?>
	
	
	<div class="field">
		<div class="header">General</div>
		<div class="content">
			<div class="input check">
				<? if($productoMostarProductoInicio):?>
				<input id="productoMostarProductoInicio" type="checkbox" checked="checked" name="productoMostarProductoInicio" />
				<? else: ?>
				<input ="productoMostarProductoInicio" type="checkbox" name="productoMostarProductoInicio" />
				<? endif ?>
				<label for="productoMostarProductoInicio">Mostrar un producto en vez de categorias al inicio?</label>
				<?= form_close(); ?> 
			</div>
	</div>
	<?= form_close(); ?> 
	
</div>

<a href="<?= $link; ?>" class="guardar boton importante n1" ><?=$txt_boton;?></a>
