<div class="content">
	<form id="module_filtros" data-destacados="<?=$productosDestacados?>">
        <h3>Categorias</h3>
        <ul id="filtro_categorias">
            <? foreach ($categorias as $key => $categoria): ?>
            <li>
                <input name="filtro_cat_<?=$categoria['categoriaId']?>" data-id="<?=$categoria['categoriaId']?>" id="filtro_cat_<?=$categoria['categoriaId']?>" type="checkbox" value="<?=$categoria['productoCategoriaUrl']?>">
                <label for="filtro_cat_<?=$categoria['categoriaId']?>"><?=$categoria['productoCategoriaNombre']?></label>
            </li>
            <? endforeach ?>
        </ul>

        <? foreach ($filtros as $key1 => $filtro): ?>
        <h3><?=$filtro -> productoCampoValor ?></h3>
        <ul class="campos" data-id="<?=$filtro->productoCampoId?>">
            <? foreach ($filtro->productoFiltros as $key2 => $value): ?>
            <li>
                <input name="filtro_<?=$filtro->productoCampoId?>_<?=$key2 ?>" id="filtro_<?=$filtro->productoCampoId?>_<?=$key2 ?>" type="checkbox" value="<?=$value->productoCampoRelContenido ?>">
                <label for="filtro_<?=$filtro->productoCampoId?>_<?=$key2 ?>"><?=$value->productoCampoRelContenido ?></label>
            </li>
            <? endforeach ?>
        </ul>
        <? endforeach ?>
	</form>
</div>