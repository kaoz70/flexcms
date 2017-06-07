<div class="text">

    <ol id="preguntas">
        <?php foreach($faqs as $key => $value): ?>
        <li class="<?=$value['faqClase']?>"><a href="#respuesta_<?=$value['faqId']?>"><?=$value['faqPregunta']?></a></li>
        <?php endforeach;?>
    </ol>

    <ol class="respuestas">
        <?php foreach($faqs as $key => $value): ?>
        <li class="<?=$value['faqClase']?>">
            <h2 id="respuesta_<?=$value['faqId']?>" name="respuesta_<?=$value['faqId']?>"><?=$value['faqPregunta']?></h2>
            <div class="respuesta"><?=$value['faqRespuesta']?></div>
            <a class="subir" href="#preguntas"><?=lang('ui_top')?></a>
        </li>
        <?php endforeach;?>
    </ol>

</div>