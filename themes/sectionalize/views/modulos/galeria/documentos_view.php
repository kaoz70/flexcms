<div class="content">
    <ul class="documentos">
        <?php foreach($archivos as $key => $value): ?>
        <?

        $ext = mb_strtolower(pathinfo('./docs/downloads/'.$value->descargaArchivo, PATHINFO_EXTENSION));

        switch($ext)
        {
            case 'pdf':
                $style = 'pdf';
                break;
            case 'xls':
                $style = 'xls';
                break;
            case 'xlsx':
                $style = 'xls';
                break;
            case 'doc':
                $style = 'doc';
                break;
            case 'docx':
                $style = 'doc';
                break;
            case 'ppt':
                $style = 'ppt';
                break;
            case 'pptx':
                $style = 'ppt';
                break;
            case 'jpeg':
                $style = 'jpg';
                break;
            case 'jpg':
                $style = 'jpg';
                break;
            default:
                $style = 'default';
                break;
        }

        ?>
        <li class="<?=$style?>">
            <? if($value->descargaArchivo != ''): ?>
            <a target="_blank" href="<?=base_url()?>docs/downloads/<?=$value->descargaArchivo?>" title="<?=$value->descargaNombre?>"><?=$value->descargaNombre?></a>
            <? endif?>
        </li>

        <?php endforeach;?>
    </ul>
    <?=$pagination?>
</div>