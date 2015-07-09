<? if($articulos): ?><div class="resultSet articulos"><h4>Articulos</h4><? endif ?>
<? foreach($articulos as $row): ?>
    <div>
        <a href="<?= base_url($lang . '/' . $row->paginaNombreURL) ?>"><?=$row->articuloTitulo?></a>
    </div>
<? endforeach ?>
<? if($articulos): ?></div><? endif ?>

<? if($faqs): ?><div class="resultSet faq"><h4>Preguntas Frecuentes</h4><? endif ?>
<? foreach($faqs as $row): ?>
    <div>
        <a href="<?= base_url($lang . '/' . $row->pagina . '#respuesta_' . $row->id) ?>"><?=$row->pregunta?></a>
    </div>
<? endforeach ?>
<? if($faqs): ?></div><? endif ?>

<? if($publicaciones): ?><div class="resultSet publicaciones"><h4>Publicaciones</h4><? endif ?>
<? foreach($publicaciones as $pagina): ?>
    <div class="page">
        <h5><?= $pagina->paginaNombre ?></h5>
        <? foreach($pagina->publicaciones as $row): ?>
        <div>
            <a href="<?= base_url($lang . '/' . $pagina->paginaNombreURL . '/' . $row->publicacionUrl) ?>">
                <? if($row->publicacionImagen): ?>
                    <img src="<?= base_url('assets/public/images/noticias/noticia_' . $row->publicacionId . '_search.' . $row->publicacionImagen) ?>">
                <? endif ?>
                <?=$row->publicacionNombre?>
            </a>
        </div>
        <? endforeach ?>
    </div>
<? endforeach ?>
<? if($publicaciones): ?></div><? endif ?>

<? if($descargas): ?><div class="resultSet gallery"><h4>Galeria</h4><? endif ?>
<? foreach($descargas as $row): ?>
    <div>
        <a href="<?= base_url($lang . '/' . $row->descargaUrl) ?>">
            <? if($row->descargaArchivo): ?>
                <img src="<?= base_url('assets/public/images/downloads/img_' . $row->id . '_search.' . $row->descargaArchivo) ?>">
            <? endif ?>
            <?=$row->descargaNombre?>
        </a>
    </div>
<? endforeach ?>
<? if($descargas): ?></div><? endif ?>

<? if($productos): ?><div class="resultSet catalog"><h4>Productos</h4><? endif ?>
<? foreach($productos as $row): ?>
    <div>
        <a href="<?= base_url($lang . '/' . $row->pagina . '/' . $row->categoriaId . '/' . $row->categoriaUrl . '/' . $row->productoUrl) ?>">
            <? if($row->extension): ?>
                <img src="<?= base_url('assets/public/images/catalog/prod_' . $row->id . '_search.' . $row->extension) ?>">
            <? endif ?>
            <?=$row->nombre?>
        </a>
    </div>
<? endforeach ?>
<? if($productos): ?></div><? endif ?>