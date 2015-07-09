<h2><?=$titulo; ?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col">

	<?=form_open('admin/slideshows/' . $link, array('class' => 'form'));?>
    
    <div class="field">
		<div class="header">Elemento</div>
		<div class="content">
			<div class="input">
				<label for="bannerCampoNombre">Nombre:</label>
				<input class="required name" id="bannerCampoNombre" type="text" name="bannerCampoNombre" maxlength="250"  value="<?=$bannerCampoNombre;?>"/>
			</div>
			<div class="input">
				<label for="inputId">Tipo</label>
				<select id="inputId" name="inputId">
		        	<?php 
					foreach ($result as $row) :
						if($inputId == $row->inputId)
							$selected = "selected";	
						else
							$selected = '';
					?>	
		       	  	<option value="<?=$row->inputId;?>" <?=$selected;?> ><?=$row->inputTipoContenido;?></option> 
		     		<?php endforeach; ?>
		       	</select>
			</div>
			<div class="input">
				<fieldset>
					<legend>Etiqueta</legend>
					<? foreach ($idiomas as $key => $idioma): ?>
					<div>
						<label for="<?=$idioma['idiomaDiminutivo']?>_bannerCampoLabel"><?=$idioma['idiomaNombre']?></label>
						<? if(count($traducciones[$idioma['idiomaDiminutivo']]) > 0):?>
							<input name="<?=$idioma['idiomaDiminutivo']?>_bannerCampoLabel" type="text" value="<?=$traducciones[$idioma['idiomaDiminutivo']]->bannerCampoLabel?>"/>
						<? else: ?>
							<input name="<?=$idioma['idiomaDiminutivo']?>_bannerCampoLabel" type="text" value=""/>
						<? endif ?>
					</div>
					<? endforeach ?>
				</fieldset>
			</div>
			<div class="input">
				<label for="bannerCampoClase">Clase:</label>
				<input id="bannerCampoClase" type="text" name="bannerCampoClase" maxlength="250" value="<?=$bannerCampoClase;?>" />
			</div>
			<div class="input check">
				<input id="bannerCampoLabelHabilitado" name="bannerCampoLabelHabilitado" type="checkbox"  value="s" <?=$checked;?> />
				<label for="bannerCampoLabelHabilitado">Mostrar Etiqueta:</label>
			</div>
		</div>
	</div>
    
  	<input type="hidden" name="campoId" value="<?=$campoId;?>" />
    
  <?= form_close(); ?> 
	
</div>

<a href="<?= $link; ?>" data-level="nivel3" data-edit-url="banners/modificarElemento/<?=$campoId?>" data-delete-url="banners/deleteCampos/<?=$campoId?>" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>

  