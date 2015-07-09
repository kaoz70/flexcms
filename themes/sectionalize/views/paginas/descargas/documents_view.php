<div class="main_content">
	<div id="text">
		
		<ul id="document_downloads">
			<?php foreach($archivos as $document): ?>
			<li>
				<div class="name"><?=$document->descargaNombre?></div>
				<a class="descargar" href="<?= base_url() ?>assets/public/files/downloads/<?=$document->descargaArchivo?>" target="_blank" ><?=$this->lang->line('ui_download')?></a>
			</li>
			<?php endforeach;?>
		</ul>
	</div>
</div>