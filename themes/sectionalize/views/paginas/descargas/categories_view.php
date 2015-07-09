<ul id="categorias" class="large-block-grid-4 small-block-grid-2">
    <? foreach($categorias as $categoria): ?>
    <li>
        <a class="categoria" href="<?= base_url() . $diminutivo . '/' . $pagina_url . '/' . $categoria->id . '/' . $categoria->descargaCategoriaUrl ?>">
            <? if($categoria->descargaCategoriaImagen != ''): ?>
            <img src="<?=base_url()?>assets/public/images/downloads/cat_<?=$categoria->id?>.<?=$categoria->descargaCategoriaImagen?>" alt="<?=$categoria->descargaCategoriaNombre?>" />
            <? endif ?>
            <div class="name"><?=$categoria->descargaCategoriaNombre?></div>
        </a>
    </li>
    <? endforeach;?>
</ul>