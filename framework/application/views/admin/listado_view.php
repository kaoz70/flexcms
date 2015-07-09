<h2><?=$txt_titulo?><a class="cerrar" href="#" >cancelar</a></h2>

<? if($search): ?>
    <div class="buscar">
        <input data-page-id="<?=$this->uri->segment(4)?>" type="text" name="searchString" value="Buscar..." />
        <div class="searchButton"></div>
    </div>
<? endif ?>

<ul id="<?=$list_id?>" class="contenido_col listado_general<?=$search ? ' searchResults' : ''?> sorteable" style="bottom: <?=$bottomMargin?>px" data-sort="<?=$url_sort?>" rel="<?=$url_rel?>">

	<?php foreach($items as $item): ?>

		<li class="listado drag" id="<?=$item[$idx_id];?>">
            <?if($drag):?>
			<div class="mover">mover</div>
            <? endif ?>
			<a class="nombre <?= isset($select) && $select ? 'seleccionar' : 'modificar'?> <?=$nivel?>" href="<?=$url_modificar . '/' . $item[$idx_id];?>"><span><?=$item[$idx_nombre]?></span></a>
			<a href="<?=$url_eliminar . '/' . $item[$idx_id];?>" class="eliminar" >eliminar</a>
			<? if(isset($additional_buttons)): ?>
				<? foreach (array_reverse($additional_buttons) as $button): ?>
					<? if(!array_key_exists('function', $button) OR $button['function']['name']($item[$button['function']['params']])): ?>
						<a href="<?=$button['link'] . '/' . $item[$idx_id]?>" class="<?=$button['class']?>"><?=$button['text']?></a>
					<? endif ?>
				<? endforeach ?>
			<? endif ?>
		</li>
		
	<?php  endforeach; ?>
	
</ul>

<?if($drag):?>
<script type="text/javascript">
    initSortables($('<?=$list_id?>'));
</script>
<? endif ?>

<? if($search): ?>
<script type="text/javascript">
    search.init('<?=$url_search?>', 'es');
</script>
<? endif ?>

<?foreach($menu as $item): ?>
    <?=$item?>
<? endforeach ?>