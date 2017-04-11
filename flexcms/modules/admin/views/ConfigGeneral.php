<div class="panel panel-primary small-width">

    <div class="panel-header">
        <md-toolbar class="md-accent" md-colors="{borderBottomColor: '{{accentColor300}}'}">
            <div class="md-toolbar-tools">
                <h2>Configuraci&oacute;n</h2>
                <span flex></span>
                <panel-dispose></panel-dispose>
            </div>
        </md-toolbar>
    </div>

    <md-content perfect-scrollbar class="panel-body">

        <md-card>
            <md-card-title>
                <md-card-title-text>
                    <span class="md-headline">General</span>
                </md-card-title-text>
            </md-card-title>
            <md-card-content>

                <md-input-container class="md-block md-hue-1">
                    <input ng-model="vm.config.site_name" type="text" placeholder="Nombre del Sitio" ng-required="true">
                </md-input-container>

                <div>
                    <md-input-container class="md-hue-1">
                        <label>P&aacute;gina inicial</label>
                        <md-select class="md-hue-1" ng-model="vm.config.index_page_id">
                            <md-option ng-repeat="page in vm.pages"
                                       ng-selected="page.id == vm.config.index_page_id"
                                       ng-value="page.id">
                                {{page.translation.name ? page.translation.name : '{missing translation}'}}
                            </md-option>
                        </md-select>
                    </md-input-container>
                </div>

                <div>
                    <md-input-container class="md-hue-1">
                        <label>Theme</label>
                        <md-select class="md-hue-1" ng-model="config.theme">
                            <md-option ng-repeat="theme in vm.themes"
                                       ng-selected="{{theme == vm.config.theme}}"
                                       ng-value="theme">
                                {{theme}}
                            </md-option>
                        </md-select>
                    </md-input-container>
                </div>

                <md-switch ng-model="vm.production"
                           ng-change="vm.onProductionChange(vm.production)"
                           aria-label="Producci&oacute;n">
                    Producci&oacute;n
                </md-switch>

            </md-card-content>
        </md-card>

        <md-card>
            <md-card-title>
                <md-card-title-text>
                    <span class="md-headline">Development</span>
                </md-card-title-text>
            </md-card-title>
            <md-card-content>

                <md-switch ng-model="vm.config.debug_bar" aria-label="Barra depuradora">
                    Barra depuradora
                </md-switch>

                <md-switch ng-model="vm.config.indent_html" aria-label="Indentar HTML">
                    Indentar HTML
                </md-switch>

            </md-card-content>
        </md-card>

    </md-content>

    <div class="panel-footer panel-controls">
        <md-toolbar class="md-secondary md-menu-toolbar">
            <div class="md-toolbar-tools" layout-align="end center">

                <md-button class="md-icon-button" ng-click="vm.saveAndClose()" >
                    <md-icon>save</md-icon>
                    <md-tooltip md-direction="bottom">Guardar y Cerrar</md-tooltip>
                </md-button>

            </div>
        </md-toolbar>
    </div>

</div>