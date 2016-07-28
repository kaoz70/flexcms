<div class="button-group filter-button-group">
    <button data-filter="*">todos</button>
    <? foreach ($groups as $group): ?>
        <button data-filter=".<?=$group?>"><?=$group?></button>
    <? endforeach ?>
</div>

<div id="widgets" class="grid">
    <? foreach ($widgets as $widget): ?>
        <div data-class="<?=$widget->name?>" class="grid-item <?=implode(' ', $widget->groups)?>"><?=$widget->name?></div>
    <? endforeach ?>
</div>