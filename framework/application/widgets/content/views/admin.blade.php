<div class="widget content">

    <h3 id="{{ $id }}">Contenido</h3>

    <p class="input small">
        <label for="module[{{ $id }}]">Tipo:</label>
        <select id="module[{{ $id }}]" name="module[{{ $id }}][content_type]">
            <? foreach ($types as $folder => $value):?>
                <option
                        <?= $data && $data->content_type == $folder ? 'selected' : '' ?>
                        value="{{ $folder }}">{{ ucfirst($folder) }}</option>
            <? endforeach ?>
        </select>
    </p>

</div>

<div data-id="{{ $id }}" class="save_module" style="display: none;">guardar</div>