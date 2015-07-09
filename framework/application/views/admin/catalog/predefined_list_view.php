<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" style="width: 540px" rel="configuracion">
    <form class="form" action="<?=base_url();?>admin/configuracion/guardar" method="post">

        <table id="pageData">
            <tr>
                <td>
                    <div class="field">
                        <div class="header">Items Seleccionados</div>
                        <ul id="seccionesAsignadas" class="content secciones">
                            <?php foreach($items_seleccionados as $item): ?>

                                <li class="listado" id="<?=$item->productoCamposListadoPredefinidoId?>">
                                    <div class="mover">mover</div>
                                    <a class="nombre" href="#"><span><?=$item->productoCamposListadoPredefinidoTexto?></span></a>
                                </li>

                            <?php  endforeach; ?>
                        </ul>
                    </div>
                </td>
                <td>
                    <div class="field">
                        <div class="header">Items Disponibles</div>

                        <ul class="content secciones">
                            <?php foreach($items_todos as $item): ?>
                                <li class="listado" id="<?=$item['productoCamposListadoPredefinidoId']?>">
                                    <div class="mover">mover</div>
                                    <a class="nombre" href="#"><span><?=$item['productoCamposListadoPredefinidoTexto']?></span></a>
                                </li>

                            <?php  endforeach; ?>
                        </ul>

                    </div>
                </td>
            </tr>
        </table>

        <input type="hidden" name="<?=$this->security->get_csrf_token_name(); ?>" value="<?=$this->security->get_csrf_hash(); ?>" />
        <input id="seccionesAdmin" type="hidden" name="seccionesAdmin" value="<?=$seccionesAdmin?>" />
        <input id="productoId" type="hidden" name="productoId" value="<?=$productoId?>" />
        <input id="productoCampoId" type="hidden" name="productoCampoId" value="<?=$productoCampoId?>" />

    </form>

</div>
<a class="guardar boton importante n1" href="<?=base_url();?>admin/catalog/update_predefined_list"><?=$txt_guardar;?></a>

<script type="text/javascript">
    seccionesAdmin();
</script>
