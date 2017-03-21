<div class="panel panel-primary medium-width">

    <div class="panel-header">

        <md-toolbar md-colors="{borderBottomColor: '{{primaryColor300}}'}">
            <div class="md-toolbar-tools">
                <h2>Contenido</h2>
                <span flex></span>
                <panel-dispose close-callback="closeHandler"></panel-dispose>
            </div>
        </md-toolbar>
    </div>

    <md-content class="panel-body">

        <form name="form">

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">General</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <md-tabs class="md-hue-1" md-dynamic-height="" md-border-bottom="">
                        <md-tab ng-repeat="lang in content.translations" label="{{lang.name}}">

                            <md-input-container class="md-block md-hue-1">
                                <input ng-model="lang.translation.name" type="text" placeholder="T&iacute;tulo" ng-required="true">
                            </md-input-container>

                            <editor content-model="lang.translation.content" editor-init="editorInit"></editor>

                        </md-tab>
                    </md-tabs>

                </md-card-content>
            </md-card>

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">Im&aacute;genes</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <div ng-repeat="imageConfig in content.images">

                        <h3>{{imageConfig.name}}</h3>

                        <upload-file model="imageConfig"
                                     existing-files="imageConfig.files"
                                     multiple="imageConfig.multiple_upload"></upload-file>

                    </div>

                </md-card-content>
            </md-card>

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">Publicaci&oacute;n</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-addon">Zona Horaria</label>
                            <timezone-selector
                                    ng-model="content.timezone"
                                    display-utc="true"
                                    sort-by="offset"
                                    show-local="true"
                                    set-local="true"
                                    primary-choices="America/Guayaquil America/Bogota"
                            ></timezone-selector>
                        </div>
                    </div>

                    <div layout-gt-xs="row" class="form-group">

                        <div flex-gt-xs class="md-hue-1">
                            <h4>Fecha inicial</h4>
                            <md-datepicker ng-model="content.publication_start"
                                           class="md-hue-1"
                                           md-placeholder="Fecha Inicio"
                                           md-open-on-focus></md-datepicker>
                        </div>

                        <div flex-gt-xs class="md-hue-1">
                            <h4>Fecha final</h4>
                            <md-datepicker ng-model="content.publication_end"
                                           class="md-hue-1"
                                           md-placeholder="Fecha Fin"
                                           md-open-on-focus></md-datepicker>
                        </div>

                    </div>

                    <md-input-container class="md-block">
                        <md-divider></md-divider>
                    </md-input-container>

                    <md-switch ng-model="content.important" aria-label="Destacado">
                        Destacado
                    </md-switch>

                    <md-switch ng-model="content.enabled" aria-label="Publicado">
                        Publicado
                    </md-switch>

                </md-card-content>
            </md-card>

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">SEO</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <md-tabs class="md-hue-1" md-dynamic-height="" md-border-bottom="">
                        <md-tab ng-repeat="lang in content.translations" label="{{lang.name}}">

                            <md-input-container class="md-block md-hue-1">
                                <md-chips ng-model="lang.translation.meta_keywords"
                                          class="md-hue-1"
                                          md-separator-keys="keys"
                                          placeholder="Palabras Clave"
                                          secondary-placeholder="Separados por coma"
                                          md-enable-chip-edit="true"
                                          md-removable="ctrl.removable" md-max-chips="15">
                                </md-chips>
                            </md-input-container>

                            <md-input-container class="md-block md-hue-1">
                                <input ng-model="lang.translation.meta_title"
                                       type="text"
                                       placeholder="Meta T&iacute;tulo">
                            </md-input-container>

                            <md-input-container class="md-block md-hue-1">
                                <textarea placeholder="Meta Descripci&oacute;n"
                                          ng-model="lang.translation.meta_description"></textarea>
                            </md-input-container>

                        </md-tab>
                    </md-tabs>

                </md-card-content>
            </md-card>

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">Avanzado</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <md-input-container class="md-block md-hue-1">
                        <input type="text" placeholder="Clase CSS" ng-model="content.css_class" />
                    </md-input-container>

                </md-card-content>
            </md-card>

        </form>

    </md-content>

    <div class="panel-footer panel-controls">
        <md-toolbar class="md-secondary md-menu-toolbar">
            <div class="md-toolbar-tools" layout-align="end center">

                <md-button class="md-icon-button" ng-click="save()" >
                    <md-icon>save</md-icon>
                    <md-tooltip md-direction="bottom">Guardar</md-tooltip>
                </md-button>

                <md-button class="md-icon-button" ng-click="saveAndClose()" >
                    <md-icon>check</md-icon>
                    <md-tooltip md-direction="bottom">Guardar y Cerrar</md-tooltip>
                </md-button>

            </div>
        </md-toolbar>
    </div>

</div>