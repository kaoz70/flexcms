<div id="checkout">
<?php if (! empty($message)) { ?>
    <div id="message">
        <?php echo $message; ?>
    </div>
<?php } ?>

<?php echo form_open(current_url(), array('data-abide', 'class' => 'custom'));?>
    <fieldset>
        <legend>Datos para la factura</legend>

        <div class="row">
            <div class="column large-6">

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_billing_name" class="prefix">Nombre</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[billing][name]" id="checkout_billing_name" value="<?php echo set_value('checkout[billing][name]');?>" />
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_billing_company" class="prefix">Empresa</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[billing][company]" id="checkout_billing_company" value="<?php echo set_value('checkout[billing][company]');?>" />
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_billing_add_01" class="prefix">Direcci&oacute;n 1</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[billing][add_01]" id="checkout_billing_add_01" value="<?php echo set_value('checkout[billing][add_01]');?>" />
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_billing_add_02" class="prefix">Direcci&oacute;n 2</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[billing][add_02]" id="checkout_billing_add_02" value="<?php echo set_value('checkout[billing][add_02]');?>" />
                    </div>
                </div>

            </div>

            <div class="column large-6">

                <div class="row collapse">
                    <div class="column small-4">
                        <label for="checkout_billing_country" class="prefix">Pa&iacute;s</label>
                    </div>
                    <div class="column small-8">
                        <select id="checkout_billing_country" name="checkout[billing][country]" class="country">
                            <option value="0"> Cargando... </option>
                        </select>
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-4">
                        <label for="checkout_billing_city" class="prefix">Estado / Provincia</label>
                    </div>
                    <div class="column small-8">
                        <select name="checkout[billing][state]" id="checkout_billing_state" class="state">
                            <option value="0"> Cargando... </option>
                        </select>
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-4">
                        <label for="checkout_billing_city" class="prefix">Cuidad / Pueblo</label>
                    </div>
                    <div class="column small-8">
                        <input type="text" name="checkout[billing][city]" id="checkout_billing_city" value="<?php echo set_value('checkout[billing][city]');?>" />
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-4">
                        <label for="checkout_billing_city" class="prefix">C&oacute;digo postal</label>
                    </div>
                    <div class="column small-8">
                        <input type="text" name="checkout[billing][post_code]" id="checkout_billing_post_code" value="<?php echo set_value('checkout[billing][post_code]', $this->flexi_cart->shipping_location_name(3));?>" />
                    </div>
                </div>

            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend>Datos para el envio</legend>

        <div>
            <label>
                <strong>Llenar con los mismos datos de facturaci&oacute;n</strong>
                <input type="checkbox" id="copy_billing_details" value="1"/>
            </label>
        </div>

        <div class="row">
            <div class="column large-6">

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_shipping_name" class="prefix">Nombre</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[shipping][name]" id="checkout_shipping_name" value="<?php echo set_value('checkout[shipping][name]');?>"/>
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_shipping_company" class="prefix">Empresa</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[shipping][company]" id="checkout_shipping_company" value="<?php echo set_value('checkout[shipping][company]');?>" />
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_shipping_add_01" class="prefix">Direcci&oacute;n 1</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[shipping][add_01]" id="checkout_shipping_add_01" value="<?php echo set_value('checkout[shipping][add_01]');?>" />
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_shipping_add_02" class="prefix">Direcci&oacute;n 2</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[shipping][add_02]" id="checkout_shipping_add_02" value="<?php echo set_value('checkout[shipping][add_02]');?>" />
                    </div>
                </div>

            </div>
            <div class="column large-6">

                <div class="row collapse">
                    <div class="column small-4">
                        <label for="checkout_shipping_country" class="prefix">Pa&iacute;s</label>
                    </div>
                    <div class="column small-8">
                        <select id="checkout_shipping_country" name="checkout[shipping][country]" class="country">
                            <option value="0"> Cargando... </option>
                        </select>
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-4">
                        <label for="checkout_shipping_state" class="prefix">Estado / Provincia</label>
                    </div>
                    <div class="column small-8">
                        <select name="checkout[shipping][state]" id="checkout_shipping_state" class="state">
                            <option value="0"> Cargando... </option>
                        </select>
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-4">
                        <label for="checkout_shipping_city" class="prefix">Cuidad / Pueblo</label>
                    </div>
                    <div class="column small-8">
                        <input type="text" name="checkout[shipping][city]" id="checkout_shipping_city" value="<?php echo set_value('checkout[shipping][city]');?>" />
                    </div>
                </div>

                <div class="row collapse">
                    <div class="column small-4">
                        <label for="checkout_shipping_post_code" class="prefix">C&oacute;digo postal</label>
                    </div>
                    <div class="column small-8">
                        <?php if (!($this->flexi_cart->shipping_location_name(3))) { ?>
                            <input type="text" name="checkout[shipping][post_code]" id="checkout_shipping_post_code" value="<?php echo set_value('checkout[shipping][post_code]');?>"/>
                        <?php } else { ?>
                            <?php echo $this->flexi_cart->shipping_location_name(3);?>
                            <input type="hidden" name="checkout[shipping][post_code]" value="<?php echo set_value('checkout[shipping][post_code]', $this->flexi_cart->shipping_location_name(3));?>"/>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend>Detalles de contacto</legend>

        <div class="row">

            <div class="column small-6">

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_email" class="prefix">Email</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[email]" id="checkout_email" value="<?php echo set_value('checkout[email]');?>" />
                    </div>
                </div>

            </div>

            <div class="column small-6">

                <div class="row collapse">
                    <div class="column small-3">
                        <label for="checkout_phone" class="prefix">Tel&eacute;fono</label>
                    </div>
                    <div class="column small-9">
                        <input type="text" name="checkout[phone]" id="checkout_phone" value="<?php echo set_value('checkout[phone]');?>" />
                    </div>
                </div>

            </div>

        </div>

        <label for="checkout_comments">Comentarios / Observaciones:</label>
        <textarea name="checkout[comments]" id="checkout_comments" rows="2" ><?php echo set_value('checkout[comments]');?></textarea>

    </fieldset>

    <a href="<?= base_url($diminutivo . '/' . $pagina_url) ?>" class="button"/>Modificar Carrito</a>
    <input type="submit" name="save_order" value="Enviar Pedido" class="button red"/>

    <?php echo form_close();?>
</div>