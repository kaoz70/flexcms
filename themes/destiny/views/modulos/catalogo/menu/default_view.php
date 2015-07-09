<div class="content">
    <? render_generic_menu($menu['tree'], $menu['path'], $page, $show_products, 'catalog_view', array('id' => 'acc_' . $module->moduloId, 'class' => 'category accordion')); ?>
</div>
<script  type="text/javascript" language="JavaScript">
    $(document).ready(function(){
        initProductAccordionMenu("#acc_<?=$module->moduloId?>");
    });
</script>