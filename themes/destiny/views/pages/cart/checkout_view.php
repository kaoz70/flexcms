<div id="checkout">
<?php if (! empty($message)) { ?>
    <div id="message">
        <?php echo $message; ?>
    </div>
<?php } ?>

<?php echo form_open(current_url(), array('data-abide' => '', 'class' => 'custom'));?>
    <fieldset>
        <legend><h2><?=$this->lang->line('datos_factura')?></h2></legend>

        <div class="row">
            <div class="column large-6">

                <div>
                    <input placeholder="<?=ucfirst($this->lang->line('name'))?>" required="" type="text" name="checkout[billing][name]" id="checkout_billing_name" value="<?= set_value('checkout[billing][name]');?>" />
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>

                <div>
                    <input placeholder="<?=$this->lang->line('company')?>" type="text" name="checkout[billing][company]" id="checkout_billing_company" value="<?= set_value('checkout[billing][company]');?>" />
                </div>

                <div>
                    <input placeholder="<?=$this->lang->line('address_1')?>" required="" type="text" name="checkout[billing][add_01]" id="checkout_billing_add_01" value="<?= set_value('checkout[billing][add_01]');?>" />
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>

                <div>
                    <input placeholder="<?=$this->lang->line('address_2')?>" type="text" name="checkout[billing][add_02]" id="checkout_billing_add_02" value="<?= set_value('checkout[billing][add_02]');?>" />
                </div>

            </div>

            <div class="column large-6">

                <div>
                    <input placeholder="<?=$this->lang->line('country')?>" required="" type="text" id="checkout_billing_country" name="checkout[billing][country]" value="<?=set_value('checkout[billing][country]')?>">
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>

                <div>
                    <input placeholder="<?=$this->lang->line('state')?>" required="" type="text" name="checkout[billing][state]" id="checkout_billing_state" value="<?=set_value('checkout[billing][state]')?>">
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>

                <div>
                    <input placeholder="<?=$this->lang->line('city')?>" required="" type="text" name="checkout[billing][city]" id="checkout_billing_city" value="<?php echo set_value('checkout[billing][city]');?>" />
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>
                <div>
                    <input placeholder="<?=$this->lang->line('phone')?>" required="" type="text" name="checkout[billing][phone]" id="checkout_billing_phone" value="<?php echo set_value('checkout[billing][phone]');?>" />
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>

            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><h2><?=$this->lang->line('datos_personales')?></h2></legend>

        <p><?=$this->lang->line('datos_personales_text')?></p>

        <div class="row">
            <div class="column large-6 medium-6">
                <div>
                    <input type="text" name="checkout[name]" placeholder="<?=ucfirst($this->lang->line('name'))?>" required="" value="<?=set_value('checkout[name]')?>">
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>
                <div>
                    <input type="text" name="checkout[phone]" placeholder="<?=$this->lang->line('phone')?>" required="" value="<?=set_value('checkout[phone]')?>">
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>
                <div>
                    <input type="email" name="checkout[email]" placeholder="* Email" required="" value="<?=set_value('checkout[email]')?>">
                    <small class="error"><?=$this->lang->line('required')?></small>
                    <small class="error"><?=$this->lang->line('email')?></small>
                </div>
                <div>
                    <input type="text" name="checkout[city]" placeholder="<?=$this->lang->line('city')?>" required="" value="<?=set_value('checkout[city]')?>">
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>
            </div>
            <div class="column large-6 medium-6">
                <div>
                    <input type="text" name="checkout[motive]" placeholder="<?=$this->lang->line('motive')?>" required="" value="<?=set_value('checkout[motive]')?>">
                    <small class="error"><?=$this->lang->line('required')?></small>
                </div>
                <textarea placeholder="<?=$this->lang->line('message')?>" name="checkout[comments]"><?=set_value('checkout[comments]')?></textarea>
            </div>
        </div>

    </fieldset>

    <a href="<?= base_url($diminutivo . '/' . $pagina_url) ?>" class="button"/><?=$this->lang->line('ui_cart_modify')?></a>
    <input type="submit" name="save_order" value="<?=$this->lang->line('ui_cart_send')?>" class="button red"/>

    <?php echo form_close();?>
</div>