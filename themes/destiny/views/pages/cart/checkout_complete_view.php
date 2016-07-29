<h3><?=$this->lang->line('ui_cart_checkout_complete')?></h3>
<fieldset>
    <legend><h2><?=$this->lang->line('email_order_details')?></h2></legend>
    <h4><?=$this->lang->line('email_order_number')?>: <?php echo $order_number; ?></h4>
    <p><?=$this->lang->line('email_sent_success')?>: <?php echo $user_email; ?>.</p>
</fieldset>

