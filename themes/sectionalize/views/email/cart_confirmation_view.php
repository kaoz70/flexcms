<table border="1">
    <tr>
        <td>Número</td>
        <td>Producto</td>
        <td>Cantidad</td>
        <? foreach($pedidos[0]->data->precios as $precio): ?>
            <td><?= $precio->contenido->productoCampoValor ?></td>
        <? endforeach ?>
    </tr>
    <? foreach ($pedidos as $key => $producto): ?>
    <tr>
        <td>#<?= $key + 1 ?></td>
        <td><?= $producto->data->productoNombre ?></td>
        <td><?= $producto->pedido ?></td>
        <? foreach($producto->data->precios as $precio): ?>
            <td>$<?= $precio->contenido->productoCampoRelContenido ?></td>
        <? endforeach ?>
    </tr>
    <? endforeach ?>
</table>