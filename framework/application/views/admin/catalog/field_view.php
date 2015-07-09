<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?=form_open('admin/catalogo/' . $link, array('class' => 'form'));?>
    
    <div class="field">
		<div class="header">General</div>
		<div class="content">
			
			<fieldset>
				<legend>Nombre</legend>
				<? foreach ($idiomas as $key => $idioma): ?>
					<div class="input small">
					<label for="<?=$idioma['idiomaDiminutivo']?>_productoCampoValor"><?=$idioma['idiomaNombre']?></label>
					<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_productoCampoValor" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->productoCampoValor?>"/>
					<? else: ?>
						<input class="required name" name="<?=$idioma['idiomaDiminutivo']?>_productoCampoValor" type="text" value=""/>
					<? endif ?>
					</div>
				<? endforeach ?>
			</fieldset>
			
			<div class="input">
				<label for="inputId">Tipo</label>
				<select id="inputId" name="inputId">
		        	<?php

                    $inputTipoContenido = '';

					foreach ($inputs as $row) :

						if($inputId == $row->inputId){
                            $selected = "selected";
                            $inputTipoContenido = $row->inputTipoContenido;
                        }
						else {
                            $selected = '';
                        }

                    ?>
		       	  	    <option value="<?=$row->inputId;?>" <?=$selected;?> ><?=$row->inputTipoContenido;?></option>
		     		<?php endforeach; ?>
		       	</select>
			</div>
			
			<div class="input small">
				<label for="productoCampoClase">Clase</label>
				<input id="productoCampoClase" type="text" name="productoCampoClase" maxlength="250" value="<?=$productoCampoClase;?>" />
			</div>
			<div class="input check">
				<input id="productoCampoMostrarNombre" name="productoCampoMostrarNombre" type="checkbox" <?=$checkedVerNombre;?> />
				<label for="productoCampoMostrarNombre">Mostrar Nombre</label>
			</div>
			<div class="input check">
				<input id="productoCampoVerModulo" name="productoCampoVerModulo" type="checkbox" <?=$checkedVerModulo;?> />
				<label for="productoCampoVerModulo">Ver en Modulo</label>
			</div>
			<div class="input check">
				<input id="productoCampoVerListado" name="productoCampoVerListado" type="checkbox" <?=$checkedVerListado;?> />
				<label for="productoCampoVerListado">Ver en Listado</label>
			</div>
			<div class="input check">
				<input id="productoCampoVerPedido" name="productoCampoVerPedido" type="checkbox" <?=$checkedVerPedido;?> />
				<label for="productoCampoVerPedido">Ver en Pedidos</label>
			</div>
			<div class="input check">
				<input id="productoCampoVerFiltro" name="productoCampoVerFiltro" type="checkbox" <?=$checkedVerFiltro;?> />
				<label for="productoCampoVerFiltro">Ver en Modulo Filtros</label>
			</div>
			<div class="input check">
				<input id="productoCampoHabilitado" name="productoCampoHabilitado" type="checkbox" <?=$checkedHabilitado;?> />
				<label for="productoCampoHabilitado">Habilitado</label>
			</div>
		</div>
	</div>
	
  <?= form_close(); ?>
</div>

<a id="crear" class="nivel4 ajax boton n2" <?= $inputTipoContenido !== 'listado predefinido' ? 'style="display: none"' : '' ?> href="<?= base_url('admin/catalog/predefined_list_items/'.$campoId) ?>" >Editar Items</a>
<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="catalog/edit_field/" data-delete-url="catalog/delete_field/" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

  