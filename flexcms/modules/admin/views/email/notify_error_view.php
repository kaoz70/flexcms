<? foreach ($data as $key => $item): ?>
    <div style="border: 1px solid #7a7983; padding: 5px; margin: 5px">
        <h4><?=$key?>:</h4>
        <?
        $array = $item;
        if(!is_array($array) && json_decode($item, true)) {
            $array = json_decode($item, true);
        }
        ?>
        <? if(is_array($array)): ?>
            <?= $this->load->view('admin/email/notify_error_view', [
                'data' => $array
            ]); ?>
        <? elseif(is_string($item)): ?>
            <?= $item ?>
        <? else: ?>
            <?= var_export($item) ?>
        <? endif ?>
    </div>
<? endforeach; ?>
