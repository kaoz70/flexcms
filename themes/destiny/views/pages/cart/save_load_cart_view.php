<div class="row">

    <?php if (! empty($message)) { ?>
        <div id="message">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <h3>Cargar datos del carrito guardados</h3>
    <p>
        La siguente tabla lista las sesiones que se han guardado anteriormente.
    </p>
    <table>
        <thead>
            <tr>
                <th>
                    Fecha
                </th>
                <th class="spacer_125 align_ctr">
                    Eliminar
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
            if ($saved_cart_data) {
                foreach($saved_cart_data as $row) {
        ?>
            <tr>
                <td>
                    <a href="<?= base_url($diminutivo . '/' . $pagina_url) ?>/load_data/<?php echo $row[$this->flexi_cart->db_column('db_cart_data','id')];?>">
                        <?=strftime("%A, %d de %B de %Y a las %H:%M:%S", strtotime($row[$this->flexi_cart->db_column('db_cart_data','date')]))?>
                    </a>
                </td>
                <td class="align_ctr">
                    <a href="<?= base_url($diminutivo . '/' . $pagina_url) ?>/delete_data/<?php echo $row[$this->flexi_cart->db_column('db_cart_data','id')];?>">
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php } } else { ?>
            <tr>
                <td colspan="2">
                    No hay ning&uacute;na sesi&oacute;n que cargar.
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <small>Nota: Solo los datos de los pedidos que NO HAN SIDO COMPLETADOS est&aacute;n listados en esta secci&oacute;n.</small>

</div>