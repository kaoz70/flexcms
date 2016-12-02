<div class="panel panel-primary small-width">

    <md-toolbar class="md-accent" md-colors="{borderBottomColor: '{{accentColor300}}'}">
        <div class="md-toolbar-tools">
            <h2>Configuraci&oacute;n</h2>
            <span flex></span>
            <panel-dispose></panel-dispose>
        </div>
    </md-toolbar>

    <md-content class="panel-body">

        <md-card>
            <md-card-title>
                <md-card-title-text>
                    <span class="md-headline">General</span>
                </md-card-title-text>
            </md-card-title>
            <md-card-content>

                <md-tabs class="md-hue-1" md-dynamic-height="" md-border-bottom="">
                    <md-tab ng-repeat="lang in languages" label="{{lang.name}}">

                        <md-input-container class="md-block md-hue-1">
                            <input ng-model="lang.translation.name" type="text" placeholder="T&iacute;tulo" ng-required="true">
                        </md-input-container>

                        <md-input-container class="md-block md-hue-1">
                            <input ng-model="lang.translation.menu_name" type="text" placeholder="Nombre del Men&uacute;" ng-required="true">
                        </md-input-container>

                    </md-tab>
                </md-tabs>

            </md-card-content>
        </md-card>

        <md-card>
            <md-card-title>
                <md-card-title-text>
                    <span class="md-headline">Visibilidad</span>
                </md-card-title-text>
            </md-card-title>
            <md-card-content>

                <md-switch ng-model="page.enabled" aria-label="Publicado">
                    Publicado
                </md-switch>

                <md-switch ng-model="page.popup" aria-label="Mostrar en popup">
                    Mostrar en popup
                </md-switch>

                <md-input-container>
                    <label>Visible para</label>
                    <md-select class="md-hue-1" ng-model="page.group_visibility">
                        <md-option><em>None</em></md-option>
                        <md-option ng-repeat="role in roles"
                                   ng-selected="{{role.id == page.group_visibility}}"
                                   ng-value="role.id">
                            {{role.name}}
                        </md-option>
                    </md-select>
                </md-input-container>

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
                    <md-tab ng-repeat="lang in languages" label="{{lang.name}}">

                        <md-input-container class="md-block  md-hue-1">
                            <md-chips ng-model="lang.translation.meta_keywords"
                                      class="md-hue-1"
                                      md-separator-keys="keys"
                                      placeholder="Palabras Clave"
                                      secondary-placeholder="Separados por coma"
                                      md-enable-chip-edit="true"
                                      md-removable="ctrl.removable" md-max-chips="15">
                            </md-chips>
                        </md-input-container>

                        <md-input-container class="md-block  md-hue-1">
                            <input ng-model="lang.translation.meta_title" type="text" placeholder="Meta T&iacute;tulo">
                        </md-input-container>

                        <md-input-container class="md-block  md-hue-1">
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
                    <span class="md-headline">Vistas</span>
                </md-card-title-text>
            </md-card-title>
            <md-card-content>

                <md-input-container class="md-hue-1">
                    <label>Listado</label>
                    <md-select class="md-hue-1" ng-model="config.list_view">
                        <md-option ng-repeat="view in list_views"
                                   ng-selected="{{view == config.list_view}}"
                                   ng-value="view">
                            {{view}}
                        </md-option>
                    </md-select>
                </md-input-container>

                <md-input-container class="md-hue-1">
                    <label>Detalle</label>
                    <md-select class="md-hue-1" ng-model="config.detail_view">
                        <md-option ng-repeat="view in list_views"
                                   ng-selected="{{view == config.detail_view}}"
                                   ng-value="view">
                            {{view}}
                        </md-option>
                    </md-select>
                </md-input-container>

            </md-card-content>
        </md-card>

        <md-card>
            <md-card-title>
                <md-card-title-text>
                    <span class="md-headline">Contenido</span>
                </md-card-title-text>
            </md-card-title>
            <md-card-content>

                <md-input-container class="md-hue-1">
                    <label>Orden</label>
                    <md-select class="md-hue-1" ng-model="config.order">
                        <md-option ng-selected="{{'manual' == config.order}}" value="manual">Manual</md-option>
                        <md-option ng-selected="{{'date_asc' == config.order}}" value="date_asc">Fecha Ascendente</md-option>
                        <md-option ng-selected="{{'date_desc' == config.order}}" value="date_desc">Fecha Descendente</md-option>
                    </md-select>
                </md-input-container>

                <md-switch ng-model="config.pagination" aria-label="Paginar listado">
                    Paginar listado
                </md-switch>

                <md-slider-container ng-show="config.pagination">
                    <span>Items</span>
                    <md-slider flex min="0" max="255"
                               ng-model="config.quantity"
                               aria-label="Cantidad paginado"
                               id="pagination-slider">
                    </md-slider>
                    <md-input-container class="md-hue-1">
                        <input flex type="number" ng-model="config.quantity" aria-label="red" aria-controls="pagination-slider">
                    </md-input-container>
                </md-slider-container>

            </md-card-content>
        </md-card>

    </md-content>

    <div class="panel-footer panel-controls">
        <md-toolbar class="md-accent">
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