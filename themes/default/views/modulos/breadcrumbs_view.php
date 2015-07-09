<ol class="breadcrumbs">
<? foreach ($breadcrumbs as $item): ?>
    <li>
        <? if(isset($item->url)): ?>
        <a href="<?=$item->url?>"><?=$item->nombre?></a>
        <? else: ?>
            <?=$item->nombre?>
        <?endif?>

    </li>
<? endforeach ?>
</ol>