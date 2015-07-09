<ol class="breadcrumbs">

    <? //------------ PAGES -------------- ?>

    <? foreach ($breadcrumbs['page']['nodes'] as $key => $item): ?>
        <? if($item->id != 1): ?>
        <li>
            <?
            if(
                $key < count($breadcrumbs['page']['path']) -1
                OR $breadcrumbs['catalog']['nodes']
                OR $breadcrumbs['publications']['item']
                OR $breadcrumbs['services']['item']
                OR $breadcrumbs['gallery']['nodes']
            ):
            ?>
                <a href="<?=base_url("{$lang}/{$item->paginaNombreURL}")?>"><?=$item->paginaNombreMenu?></a>
            <? else: ?>
                <?=$item->paginaNombreMenu?>
            <? endif ?>
        </li>
        <?endif?>
    <? endforeach ?>

    <? //------------ CATALOG -------------- ?>

    <? foreach ($breadcrumbs['catalog']['nodes'] as $key => $item): ?>
        <? if($item->id != 1): ?>
            <li>
                <? if($key < count($breadcrumbs['catalog']['nodes']) -1 OR $breadcrumbs['catalog']['item'] ): ?>
                    <a href="<?=base_url("{$lang}/{$pagina_url}/{$item->productoCategoriaUrl}")?>"><?=$item->productoCategoriaNombre?></a>
                <? else: ?>
                    <?=$item->productoCategoriaNombre?>
                <? endif ?>
            </li>
        <? endif ?>
    <? endforeach ?>

    <? if($breadcrumbs['catalog']['item']): ?>
        <li><?=$breadcrumbs['catalog']['item']?></li>
    <?endif?>

    <? //------------ GALLERY -------------- ?>

    <? foreach ($breadcrumbs['gallery']['nodes'] as $key => $item): ?>
        <? if($item->id != 1): ?>
            <li>
                <? if($key < count($breadcrumbs['gallery']['nodes']) -1): ?>
                    <a href="<?=base_url("{$lang}/{$pagina_url}/{$item->descargaCategoriaUrl}")?>"><?=$item->descargaCategoriaNombre?></a>
                <? else: ?>
                    <?=$item->descargaCategoriaNombre?>
                <? endif ?>
            </li>
        <? endif ?>
    <? endforeach ?>

    <? //------------ PUBLICATIONS -------------- ?>

    <? if($breadcrumbs['publications']['item']): ?>
        <li><?=$breadcrumbs['publications']['item']?></li>
    <?endif?>

    <? //------------ SERVICIOS -------------- ?>

    <? if($breadcrumbs['services']['item']): ?>
        <li><?=$breadcrumbs['services']['item']?></li>
    <?endif?>

</ol>