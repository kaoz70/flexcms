<table>
    <tr>
        <th>D&iacute;a</th>
        <th>Hora</th>
        <th>Actividad</th>
    </tr>
    <? foreach ($days as $day): ?>
        <? foreach($day->activities as $key => $activity): ?>
            <tr>
                <? if(!$key):?>
                    <td rowspan="<?= count($day->activities) ?>"><?=$day->date?></td>
                <? endif ?>
                <td><?=$activity->time?></td>
                <td><?=$activity->data?></td>
            </tr>
        <? endforeach ?>
    <? endforeach ?>
</table>