<h2><?=$title; ?><a class="cerrar" href="#">cancelar</a></h2>
<div class="contenido_col" style="width: 780px;">

    <?php

    $attributes = array('class' => 'form');
    echo form_open('admin/articles/', $attributes);

    ?>

    <div class="field">
        <div class="header">General</div>
        <div class="content">

            <div class="input">

                <fieldset>
                    <legend>Título</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="name_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <input class="required name"
                               name="name[<?=$key?>]"
                               type="text"
                               value="<?= isset($trans->name) ? $trans->name : '' ?>"
                        />
                    <? endforeach ?>
                </fieldset>

            </div>

            <div class="input">

                <fieldset>
                    <legend>Contenido</legend>
                    <? foreach ($translations as $key => $trans): ?>
                        <label for="content_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                        <textarea id="<?=$key?>_editor"
                                  class="editor"
                                  name="content[<?=$key?>]"
                                  rows="20"
                                  cols="85"><?= isset($trans->content) ? $trans->content : '' ?></textarea>
                    <? endforeach ?>
                </fieldset>

            </div>

            <div class="input check">
                <input type="checkbox" name="important" id="important" <?= $content->important ? 'checked' : ''; ?> value="1" />
                <label for="important">Destacado</label>
            </div>

        </div>
    </div>

    <div class="field">
        <div class="header">Im&aacute;genes</div>
        <div class="content">

            <fieldset id="upload-image">
                <legend>Im&aacute;gen Principal</legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
            </fieldset>

            <fieldset id="upload-image">
                <legend>Galeria</legend>
                <div>
                    <input class="fileselect" type="file" name="fileselect[]" />
                    <div class="filedrag">o arrastre el archivo aquí</div>
                </div>
            </fieldset>

        </div>
    </div>

    <div class="field">
        <div class="header">Publicaci&oacute;n</div>
        <div class="content">
            <div class="input small">
                <label for="publication_start">Fecha Inicio Publicaci&oacute;n:</label>
                <input id="publication_start" class="fecha" name="publication_start" type="datetime" value="<?=$content->publication_start?>"/>
            </div>

            <div class="input small">
                <label for="publication_end">Fecha Fin Publicaci&oacute;n:</label>
                <input id="publication_end" class="fecha" name="publication_end" type="datetime" value="<?=$content->publication_end?>"/>
            </div>

            <div class="input check">
                <input type="checkbox" name="enabled" id="enabled" <?= $content->enabled ? 'checked' : ''; ?> />
                <label for="enabled">Publicado</label>
            </div>
        </div>
    </div>

    <div class="field">
        <div class="header">SEO</div>
        <div class="content">

            <fieldset>
                <legend>Palabras Clave</legend>
                <small>Separados por coma ","</small>
                <? foreach ($translations as $key => $trans): ?>
                    <label for="meta_keywords_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <textarea id="<?=$key?>_editor"
                              name="meta_keywords[<?=$key?>]"
                              rows="5"
                              cols="85"><?= isset($trans->meta_keywords) ? implode(', ', $trans->meta_keywords) : '' ?></textarea>
                <? endforeach ?>
            </fieldset>

            <fieldset>
                <legend>Meta T&iacute;tulo</legend>
                <? foreach ($translations as $key => $trans): ?>
                    <label for="meta_title_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <input class=""
                           name="meta_title[<?=$key?>]"
                           type="text"
                           value="<?= isset($trans->meta_title) ? $trans->meta_title : '' ?>"
                    />
                <? endforeach ?>
            </fieldset>

            <fieldset>
                <legend>Meta Descripci&oacute;n</legend>
                <? foreach ($translations as $key => $trans): ?>
                    <label for="meta_description_<?=$key?>"><?= \App\Language::find($key)->name ?></label>
                    <textarea id="<?=$key?>_editor"
                              name="meta_description[<?=$key?>]"
                              rows="5"
                              cols="85"><?= isset($trans->meta_description) ? $trans->meta_description : '' ?></textarea>
                <? endforeach ?>
            </fieldset>

        </div>
    </div>

    <div class="field">
        <div class="header">Avanzado</div>
        <div class="content">
            <div class="input small">
                <label for="css_class">Clase</label>
            <input name="css_class"  id="css_class" type="text" value="<?= $content->css_class ?>" />
            </div>
        </div>
    </div>

    <input type="hidden" name="page_id" value="<?=$content->category_id?>" />

    <?= form_close(); ?>

</div>

<a href="<?= $link; ?>" data-level="nivel2" data-edit-url="<?=$edit_url?>" data-delete-url="<?=$delete_url?>" class="guardar boton importante n1 <?=$nuevo?>" ><?=$txt_boton;?></a>