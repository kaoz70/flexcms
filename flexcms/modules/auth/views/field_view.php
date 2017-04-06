<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

    <?php

    $attributes = array('class' => 'form');
    echo form_open('admin/articulos/' . $link, $attributes);

    ?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">

            <div class="input">
                <label for="name">Nombre</label>
                <input class="required name"
                       name="name"
                       type="text"
                       value="<?= isset($name) ? $name : '' ?>"
                />
            </div>

            <div class="input">

                <fieldset>
                    <legend>Etiqueta</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="label_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <input class="required"
                               name="label[<?=$key?>]"
                               type="text"
                               value="<?= isset($trans->label) ? $trans->label : '' ?>"
                        />
                    <? endforeach ?>
                </fieldset>

                <fieldset>
                    <legend>Placehoder</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="placeholder_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <input class=""
                               name="placeholder[<?=$key?>]"
                               type="text"
                               value="<?= isset($trans->placeholder) ? $trans->placeholder : '' ?>"
                        />
                    <? endforeach ?>
                </fieldset>

            </div>

            <div class="input">
                <label for="input_id">Tipo:</label>
                <select id="input_id" name="input_id">
                    <?php foreach ($inputs as $row) : ?>
                        <option value="<?=$row->id;?>"
                            <?= isset($input_id) && $input_id == $row->id ? 'selected' : '';?>
                        ><?=$row->content;?></option>
                    <?php endforeach;?>
                </select>
            </div>

            <div class="input check">
                <input type="checkbox"
                       name="label_enabled"
                       id="label_enabled"
                    <?= isset($label_enabled) && $label_enabled ? 'checked' : '' ?> />
                <label for="label_enabled">Mostrar etiqueta</label>
            </div>

            <div class="input check">
                <input type="checkbox"
                       name="enabled"
                       id="enabled"
                    <?= isset($enabled) && $enabled ? 'checked' : '' ?> />
                <label for="enabled">Publicado</label>
            </div>

            <div class="input check">
                <input id="required"
                       name="required"
                       type="checkbox"
                    <?= isset($required) && $required ? 'checked' : '' ?> />
                <label for="required">Obligatorio:</label>
            </div>

            <div class="input">
                <label for="validation">Validación:</label>
                <input id="validation"
                       name="validation"
                       type="text"
                       value="<?= isset($validation) ? $validation : '' ?>"/>
            </div>

        </div>
    </div>

    <div class="field">
        <div class="header">Avanzado</div>
        <div class="content">
            <div class="input">
                <label for="css_class">Clase</label>
                <input name="css_class"
                       id="css_class"
                       type="text"
                       value="<?= isset($css_class) ? $css_class : '' ?>" />
            </div>
        </div>
    </div>

    <div class="field">
        <div class="header">Shopping Cart</div>
        <div class="content">
            <div class="input">
                <label for="type">Tipo:</label>
                <select id="type" name="type">
                    <option value="profile" <?= isset($data->type) && $data->type == 'profile' ? 'selected' : ''?> >Perfil</option>
                    <option value="billing" <?= isset($data->type) && $data->type == 'billing' ? 'selected' : ''?> >Billing / Facturaci&oacute;n</option>
                    <option value="shipping" <?= isset($data->type) && $data->type == 'shipping' ? 'selected' : ''?> >Shipping / Envio</option>
                </select>
            </div>
            <div class="input">
                <label for="cart_order_col">Empatar columna de BD para este campo:</label>
                <select id="cart_order_col" name="cart_order_col">

                    <option value="" <?= isset($data->cart_order_col) && $data->cart_order_col == '' ? 'selected' : ''?> >Ninguno</option>

                    <optgroup label="Billing / Facturaci&oacute;n">
                        <option value="ord_bill_first_name" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_first_name' ? 'selected' : ''?> >ord_bill_first_name</option>
                        <option value="ord_bill_last_name" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_last_name' ? 'selected' : ''?> >ord_bill_last_name</option>
                        <option value="ord_bill_company" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_company' ? 'selected' : ''?> >ord_bill_company</option>
                        <option value="ord_bill_address_01" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_address_01' ? 'selected' : ''?> >ord_bill_address_01</option>
                        <option value="ord_bill_address_02" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_address_02' ? 'selected' : ''?> >ord_bill_address_02</option>
                        <option value="ord_bill_city" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_city' ? 'selected' : ''?> >ord_bill_city</option>
                        <option value="ord_bill_state" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_state' ? 'selected' : ''?> >ord_bill_state</option>
                        <option value="ord_bill_post_code" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_post_code' ? 'selected' : ''?> >ord_bill_post_code</option>
                        <option value="ord_bill_country" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_country' ? 'selected' : ''?> >ord_bill_country</option>
                        <option value="ord_bill_phone" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_phone' ? 'selected' : ''?> >ord_bill_phone</option>
                        <option value="ord_bill_comments" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_bill_comments' ? 'selected' : ''?> >ord_bill_comments</option>
                    </optgroup>

                    <optgroup label="Shipping / Envio">
                        <option value="ord_ship_first_name" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_first_name' ? 'selected' : ''?> >ord_ship_first_name</option>
                        <option value="ord_ship_last_name" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_last_name' ? 'selected' : ''?> >ord_ship_last_name</option>
                        <option value="ord_ship_company" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_company' ? 'selected' : ''?> >ord_ship_company</option>
                        <option value="ord_ship_address_01" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_address_01' ? 'selected' : ''?> >ord_ship_address_01</option>
                        <option value="ord_ship_address_02" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_address_02' ? 'selected' : ''?> >ord_ship_address_02</option>
                        <option value="ord_ship_city" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_city' ? 'selected' : ''?> >ord_ship_city</option>
                        <option value="ord_ship_state" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_state' ? 'selected' : ''?> >ord_ship_state</option>
                        <option value="ord_ship_post_code" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_post_code' ? 'selected' : ''?> >ord_ship_post_code</option>
                        <option value="ord_ship_country" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_country' ? 'selected' : ''?> >ord_ship_country</option>
                        <option value="ord_ship_phone" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_phone' ? 'selected' : ''?> >ord_ship_phone</option>
                        <option value="ord_ship_comments" <?= isset($data->cart_order_col) && $data->cart_order_col == 'ord_ship_comments' ? 'selected' : ''?> >ord_ship_comments</option>
                    </optgroup>

                </select>
            </div>

            <div class="input">
                <label for="two_checkout_name">Empatar con campo de 2Checkout:</label>
                <select id="two_checkout_name" name="two_checkout_name">
                    <option value="" <?= isset($data->two_checkout_name) && $data->two_checkout_name == '' ? 'selected' : ''?> >Ninguno</option>
                    <option value="street_address" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'street_address' ? 'selected' : ''?> >street_address</option>
                    <option value="street_address2" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'street_address2' ? 'selected' : ''?> >street_address2</option>
                    <option value="city" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'city' ? 'selected' : ''?> >city</option>
                    <option value="state" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'state' ? 'selected' : ''?> >state</option>
                    <option value="zip" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'zip' ? 'selected' : ''?> >zip</option>
                    <option value="phone" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'phone' ? 'selected' : ''?> >phone</option>
                    <option value="ship_name" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'ship_name' ? 'selected' : ''?> >ship_name</option>
                    <option value="ship_street_address" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'ship_street_address' ? 'selected' : ''?> >ship_street_address</option>
                    <option value="ship_street_address2" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'ship_street_address2' ? 'selected' : ''?> >ship_street_address2</option>
                    <option value="ship_city" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'ship_city' ? 'selected' : ''?> >ship_city</option>
                    <option value="ship_state" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'ship_state' ? 'selected' : ''?> >ship_state</option>
                    <option value="ship_zip" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'ship_zip' ? 'selected' : ''?> >ship_zip</option>
                    <option value="ship_country" <?= isset($data->two_checkout_name) && $data->two_checkout_name == 'ship_country' ? 'selected' : ''?> >ship_country</option>
                </select>
            </div>

        </div>
    </div>

    <div class="field">
        <div class="header">Ayuda</div>
        <div class="content">

            <fieldset>
                <legend>Validación</legend>
                <ul>
                    <li>Alfabético: alpha</li>
                    <li>Alfanumérico: alpha_numeric</li>
                    <li>Entero: integer</li>
                    <li>Número: number</li>
                    <li>Contraseña: password</li>
                    <li>Tarjeta: card</li>
                    <li>CCV: cvv</li>
                    <li>Email: email</li>
                    <li>Link: url</li>
                    <li>Dominio: domain</li>
                    <li>Fecha - Hora: datetime</li>
                    <li>Fecha: date</li>
                    <li>Hora: time</li>
                    <li>Mes / Dia / Año: month_day_year</li>
                </ul>
            </fieldset>

            <fieldset>
                <legend>Otro</legend>
                <ul>
                    <li>Campo nombre (Clase): name</li>
                </ul>
            </fieldset>

        </div>
    </div>


    <?= form_close(); ?>

</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="auth/field/edit/" data-delete-url="auth/field/delete/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>