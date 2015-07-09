<? foreach($campos as $campo): ?>
    <p><strong><?=$campo->data->contactoCampoValor?>:</strong> <?=$campo->post_data?></p>
<? endforeach ?>