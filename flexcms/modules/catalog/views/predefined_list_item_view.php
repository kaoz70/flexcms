<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?php

	$attributes = array('class' => 'form');
	echo form_open('admin/catalogo/subirVideoGaleriaProducto', $attributes);
    ?>

	<div class="field">
		<div class="header">General</div>
		<div class="content">

			<fieldset>
				<legend>Nombre / Valor</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div>
					<label for="<?=$idioma['idiomaDiminutivo']?>_productoCamposListadoPredefinidoTexto"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_productoCamposListadoPredefinidoTexto" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoCamposListadoPredefinidoTexto?>"/>
					<? else: ?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_productoCamposListadoPredefinidoTexto" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>

			<div class="input check">
				<input type="checkbox" name="productoCamposListadoPredefinidoPublicado" id="productoCamposListadoPredefinidoPublicado" <?= $productoCamposListadoPredefinidoPublicado; ?> />
				<label for="productoCamposListadoPredefinidoPublicado">Publicado</label>
			</div>

		</div>
	</div>

    <div class="field">
        <div class="header">Avanzado</div>
        <div class="content">

            <div class="input">
                <label for="productoCamposListadoPredefinidoClase">Clase</label>
                <input type="text" name="productoCamposListadoPredefinidoClase" id="productoCamposListadoPredefinidoClase" value="<?= $productoCamposListadoPredefinidoClase; ?>" />
            </div>

        </div>
    </div>

    <input type="hidden" name="productoCampoId" value="<?=$productoCampoId?>" />
    <input type="hidden" name="productoCamposListadoPredefinidoId" value="<?=$productoCamposListadoPredefinidoId?>" />

	<?= form_close(); ?>
</div>
<a href="<?= $link; ?>" data-level="nivel5" data-edit-url="catalog/predefinedListItem/edit/" data-delete-url="catalog/predefinedListItem/delete/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton; ?></a>
