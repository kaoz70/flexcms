<h2><?php echo $titulo;?><a class="cerrar" href="#" >cancelar</a></h2>
<div class="contenido_col" rel="configuracion" style="width: 542px">
    <form class="form" action="<?=base_url();?>admin/configuracion/guardar" method="post">

        <table id="pageData">
            <tr>
                <td>
                    <div class="field">
                        <div class="header">Secciones visibles para el Cliente</div>
                        <ul id="seccionesAsignadas" class="content secciones">
                            <?php foreach($secciones_cliente as $seccion): ?>

                                <li class="listado" id="<?=$seccion->adminSeccionId?>">
                                    <div class="mover">mover</div>
                                    <a class="nombre" href="#"><span><?=$seccion->adminSeccionNombre?></span></a>
                                </li>

                            <?php  endforeach; ?>
                        </ul>
                    </div>
                </td>
                <td>
                    <div class="field">
                        <div class="header">Secciones Disponibles</div>

                        <ul class="content secciones">
                            <?php foreach($secciones_todas as $seccion): ?>
                                <li class="listado" id="<?=$seccion->adminSeccionId?>">
                                    <div class="mover">mover</div>
                                    <a class="nombre" href="#"><span><?=$seccion->adminSeccionNombre?></span></a>
                                </li>

                            <?php  endforeach; ?>
                        </ul>

                    </div>
                </td>
            </tr>
        </table>

        <input id="seccionesAdmin" type="hidden" name="seccionesAdmin" value="<?=$seccionesAdmin?>" />
        <input id="csrf_test" type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />

    </form>

</div>
<a class="guardar boton importante n1" href="<?=base_url();?>admin/config/save_sections"><?=$txt_guardar;?></a>

<script type="text/javascript">
    seccionesAdmin();
</script>
