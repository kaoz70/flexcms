<? //INFO: check http://formvalidation.io/ for this new validator docs ?>

<div class="content">

    <?= form_open('submit/contacto/' . $diminutivo, array(
        'class' => 'form_contacto',
        'data-fv-framework' => 'bootstrap',
        'data-fv-icon-valid' => 'glyphicon glyphicon-ok',
        'data-fv-icon-invalid' => 'glyphicon glyphicon-remove',
        'data-fv-icon-validating' => 'glyphicon glyphicon-refresh',
    )); ?>

    <?php if(count($contactos) > 1) :?>
        <select name="contacto" class="select">
            <?php foreach($contactos as $row):?>
                <option id="contacto_<?=$row->contactoId?>" value="<?=$row->contactoId?>"><?=$row->contactoNombre?></option>
            <?php endforeach;?>
        </select>
    <?php endif;?>

    <?php foreach($campos as $row):?>
        <div class="form-group">

            <?php switch($row->inputTipoId): case 1: case 13: ?>
                <input <?=$row->contactoCampoRequerido ? 'required' : ''?>

                    placeholder="<?=$row->contactoCampoPlaceholder?>"
                    class="<?=$row->contactoCampoClase?>"
                    name="campos[<?=$row->contactoCampoId?>]"
                    id="campos_<?=$row->contactoCampoId?>"
                    type="text"

                    <? if($row->contactoCampoRequerido): ?>
                        data-fv-notempty="true"
                        data-fv-notempty-message="<?=lang('required')?>"
                    <? endif ?>

                    <? if($row->contactoCampoValidacion === 'email'): ?>
                        data-fv-emailaddress="true"
                        data-fv-emailaddress-message="<?=lang($row->contactoCampoValidacion)?>"
                    <? endif ?>

                    />
                <?php break; ?>
            <?php case 3:?>
                <textarea <?=$row->contactoCampoRequerido ? 'required' : ''?>

                    pattern="<?=$row->contactoCampoValidacion?>"
                    placeholder="<?=$row->contactoCampoPlaceholder?>"
                    rows="5"
                    cols="75"
                    class="<?=$row->contactoCampoClase?>"
                    name="campos[<?=$row->contactoCampoId?>]"
                    id="campos_<?=$row->contactoCampoId?>"

                    <? if($row->contactoCampoRequerido): ?>
                        data-fv-notempty="true"
                        data-fv-notempty-message="<?=lang('required')?>"
                    <? endif ?>

                    ></textarea>
                <?php break;?>
            <?php case 8:?>
                <select class="contacto_mapa <?=$row->contactoCampoClase?>" name="campos[<?=$row->contactoCampoId?>]" id="campos_<?=$row->contactoCampoId?>">
                    <? foreach ($ubicaciones as $key => $ubicacion): ?>
                        <option value="<?=$ubicacion->mapaUbicacionId?>"><?=$ubicacion->mapaUbicacionNombre?></option>
                    <? endforeach ?>
                </select>
                <?php break;?>
            <?php endswitch;?>

        </div>
    <?php endforeach;?>

    <input class="btn btn-primary" id="enviar" type="submit" value="<?=lang('ui_button_send')?>" />
    <input class="btn btn-danger" id="borrar" type="reset" value="<?=lang('ui_button_reset')?>" />
    <input type="hidden" value="<?=$diminutivo?>" name="idioma" />

    <? //TODO: change this to Foundation ?>
    <div class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">Info</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid ajax-response">
                        <?= lang('ui_sending') ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <?= form_close() ?>

</div>
