<div class="panel panel-primary medium-width">
    <div class="panel-heading">
        <h3 class="panel-title">Contenido<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3>
        <div class="panel-tools">
            <panel-dispose></panel-dispose>
        </div>
    </div>
    <div class="panel-body">

        <div class="form-heading">General</div>

        <uib-tabset active="active">

            <uib-tab index="$index + 1" ng-repeat="trans in translations">
                <uib-tab-heading>
                    <span>{{trans.name}}</span>
                </uib-tab-heading>
                <div class="widget">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">T&iacute;tulo</span>
                            <input type="text" class="form-control" ng-model="trans.translation.name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Contenido</label>
                        <textarea rows="15" cols="5" class="form-control" ng-model="trans.translation.content" ui-tinymce="tinymceOptions" ></textarea>
                    </div>

                </div>
            </uib-tab>

        </uib-tabset>

        <hr class="full-width">
        <div class="form-heading">Im&aacute;genes</div>

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

        <hr class="full-width">
        <div class="form-heading">Publicaci&oacute;n</div>

        <div class="form-group">
            <div class="dropdown">
                <a class="dropdown-toggle" id="publication_start" role="button" data-toggle="dropdown">
                    <div class="input-group">
                        <label class="input-group-addon" for="publication_start">Fecha Inicio</label>
                        <input type="text" id="date" name="date" class="form-control" data-ng-model="content.publication_start">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <datetimepicker data-ng-model="content.publication_start"
                                    data-datetimepicker-config="{ dropdownSelector: '#publication_start' }"></datetimepicker>
                </ul>
            </div>
        </div>

        <div class="form-group">
            <div class="dropdown">
                <a class="dropdown-toggle" id="publication_end" role="button" data-toggle="dropdown">
                    <div class="input-group">
                        <label class="input-group-addon" for="publication_start">Fecha Fin</label>
                        <input type="text" id="date" name="date" class="form-control" data-ng-model="content.publication_end">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    <datetimepicker data-ng-model="content.publication_end"
                                    data-datetimepicker-config="{ dropdownSelector: '#publication_end' }"></datetimepicker>
                </ul>
            </div>
        </div>

        <div class="checkbox">
            <label>
                <input type="checkbox" {{content.important ? 'checked="checked"' : ''}}>
                <span class="text">Destacado</span>
            </label>
        </div>

        <div class="checkbox">
            <label>
                <input type="checkbox" {{content.enabled ? 'checked="checked"' : ''}}>
                <span class="text">Publicado</span>
            </label>
        </div>

        <hr class="full-width">
        <div class="form-heading">SEO</div>

        <uib-tabset active="active">

            <uib-tab index="$index + 1" ng-repeat="trans in translations">
                <uib-tab-heading>
                    <span>{{trans.name}}</span>
                </uib-tab-heading>
                <div class="widget">

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Palabras Clave</span>
                            <ui-select multiple tagging ng-model="trans.translation.meta_keywords" theme="bootstrap" sortable="true">
                                <ui-select-match>{{$item}}</ui-select-match>
                                <ui-select-choices repeat="color in ctrl.availableColors | filter:$select.search">
                                    {{color}}
                                </ui-select-choices>
                            </ui-select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Meta T&iacute;tulo</span>
                            <input class="form-control" ng-model="trans.translation.meta_title">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Meta Descripci&oacute;n</span>
                            <textarea class="form-control" ng-model="trans.translation.meta_description"></textarea>
                        </div>
                    </div>
                </div>
            </uib-tab>

        </uib-tabset>

        <hr class="full-width">
        <div class="form-heading">Avanzado</div>

        <div class="form-group">
            <div class="input-group">
                <label class="input-group-addon" for="css_class">Clase CSS</label>
                <input id="publication_end" class="form-control" name="css_class" type="datetime" value="{{content.css_class}}"/>
            </div>
        </div>

    </div>

    <div class="panel-footer panel-controls">
        <div class="btn-group btn-group btn-group-justified">
            <a ng-click="save()" class="btn btn-info"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</a>
            <a ng-click="saveAndClose()" class="btn btn-warning"><i class="fa fa-check" aria-hidden="true"></i> Guardar y Cerrar</a>
        </div>
    </div>

</div>