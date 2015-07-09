<ul id="categorias" class="large-block-grid-3 medium-block-grid-3 small-block-grid-2">
    <? foreach($categorias as $categoria): ?>
    <li>
        <a class="categoria" href="<?= base_url() . $diminutivo . '/' . $pagina_url . '/' . $categoria->id . '/' . $categoria->descargaCategoriaUrl ?>">
            <? if($categoria->descargaCategoriaImagen != ''): ?>
            <img src="<?=base_url()?>assets/public/images/downloads/cat_<?=$categoria->id?>.<?=$categoria->descargaCategoriaImagen?>" alt="<?=$categoria->descargaCategoriaNombre?>" />
            <? endif ?>
            <h2><?=$categoria->descargaCategoriaNombre?></h2>
        </a>
    </li>
    <? endforeach;?>
</ul>