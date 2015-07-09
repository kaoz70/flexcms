<div class="content">

    <?= form_open('ajax/cart/add', array('class' => 'pedido row custom', 'data-abide' => 'ajax')) ?>

    <div class="column">
        <select id="habitaciones" required="">
            <option value=""><?=$this->lang->line('select_room_type')?></option>
            <? foreach ($productos as $producto): ?>
                <option value="<?=$producto->productoId?>"><?=$producto->productoNombre?></option>
            <? endforeach ?>
        </select>
        <small class="error"><?=$this->lang->line('required')?></small>
    </div>

    <? foreach($producto->listado_predefinido as $listado):?>

        <div class="column medium-6">
            <div class="campo">
                <select data-list-id="<?=$listado->campo_id?>" data-name="<?= $listado->nombre ?>" required="" name="campo[<?=$listado->campo_id?>]">
                    <? if($listado->mostrar_nombre): ?>
                        <option value=""><?= $listado->nombre ?></option>
                    <? endif ?>
                </select>
                <small class="error"><?=$this->lang->line('required')?></small>
            </div>
        </div>

    <? endforeach; ?>

    <div class="column">
        <div class="campo">
            <label><?=$this->lang->line('date_enter')?>:</label>
            <input required="" class="date" name="fecha-inicio" type="text">
            <small class="error"><?=$this->lang->line('required')?></small>
        </div>

    </div>

    <div class="column">
        <div>
            <label><?=$this->lang->line('date_leave')?>:</label>
            <input required="" class="date" name="fecha-fin" type="text">
            <small class="error"><?=$this->lang->line('required')?></small>
        </div>
    </div>

    <div class="column large-6 align-center">
        <img src="<?=base_url()?>themes/destiny/images/descuento.png" alt="5% de decuento por reserva online" >
    </div>
    <div class="column large-6">
        <input class="button add" type="submit" value="<?=$this->lang->line('reserve')?>">
    </div>

    <input type="hidden" value="<?=$diminutivo?>" name="idioma" />
    <input type="hidden" name="cantidad" value="1">
    <input class="id" type="hidden" name="productoId" value="">

    <?= form_close() ?>

    <div id="message"></div>

</div>

<?

//Create the room var structure with only the data we need
$products = array();
foreach ($productos as $key => $producto) {
    $lists = array();
    foreach($producto->listado_predefinido as $listado) {
        $items =  array();
        foreach ($listado->contenido as $key3 => $item) {
            $items[$item->productoCamposListadoPredefinidoTexto] = $item->productoCamposListadoPredefinidoTexto;
        }
        $lists[$listado->campo_id] = $items;
    }
    $products[$producto->productoId] = $lists;
}

?>

<script>
    var habitaciones = <?=json_encode($products)?>;
</script>