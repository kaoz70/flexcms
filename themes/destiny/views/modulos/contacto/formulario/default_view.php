<div class="content">
    <?= form_open('submit/contacto/' . $diminutivo, array('class' => 'form_contacto', 'data-abide' => '')); ?>
        <?php if(count($contactos) > 1) :?>
        <select name="contacto" class="select">
            <?php foreach($contactos as $row):?>
            <option id="contacto_<?=$row->contactoId?>" value="<?=$row->contactoId?>"><?=$row->contactoNombre?></option>
            <?php endforeach;?>
        </select>
        <?php endif;?>
        <?php foreach($campos as $row):?>
        <div class="campos" id="campo_<?=$row->contactoCampoId?>">

            <?php switch($row->inputTipoId): case 1: case 13: ?>
                <input <?=$row->contactoCampoRequerido ? 'required' : ''?> pattern="<?=$row->contactoCampoValidacion?>" placeholder="<?=$row->contactoCampoPlaceholder?>" class="<?=$row->contactoCampoClase?>" name="campos[<?=$row->contactoCampoId?>]" id="campos_<?=$row->contactoCampoId?>" type="text" />
                <?php break; ?>
                <?php case 3:?>
                <textarea <?=$row->contactoCampoRequerido ? 'required' : ''?> pattern="<?=$row->contactoCampoValidacion?>" placeholder="<?=$row->contactoCampoPlaceholder?>" rows="8" cols="75" class="<?=$row->contactoCampoClase?>" name="campos[<?=$row->contactoCampoId?>]" id="campos_<?=$row->contactoCampoId?>"></textarea>
                <?php break;?>
                <?php case 8:?>
                <select class="contacto_mapa <?=$row->contactoCampoClase?>" name="campos[<?=$row->contactoCampoId?>]" id="campos_<?=$row->contactoCampoId?>">
                    <? foreach ($ubicaciones as $key => $ubicacion): ?>
                    <option value="<?=$ubicacion->mapaUbicacionId?>"><?=$ubicacion->mapaUbicacionNombre?></option>
                    <? endforeach ?>
                </select>
                <?php break;?>
            <?php endswitch;?>

            <? if($row->contactoCampoRequerido): ?>
                <small class="error"><?=lang('required')?></small>
            <? endif ?>
            <small class="error"><?=lang($row->contactoCampoValidacion)?></small>

        </div>
            <?php endforeach;?>
        <input class="small button" id="enviar" type="submit" value="<?=lang('ui_button_send')?>" />
        <input class="small button" id="borrar" type="reset" value="<?=lang('ui_button_reset')?>" />
        <input type="hidden" value="<?=$diminutivo?>" name="idioma" />
    </form>
</div>