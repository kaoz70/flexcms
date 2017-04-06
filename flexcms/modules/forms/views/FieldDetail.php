<div class="panel panel-primary small-width">

    <div class="panel-header">
        <md-toolbar md-colors="{borderBottomColor: '{{primaryColor300}}'}">
            <div class="md-toolbar-tools">
                <h2>Campo</h2>
                <span flex></span>
                <panel-dispose></panel-dispose>
            </div>
        </md-toolbar>
    </div>

    <md-content perfect-scrollbar class="panel-body">

        <form name="form">

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">General</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <md-tabs class="md-hue-1" md-dynamic-height="" md-border-bottom="">
                        <md-tab ng-repeat="lang in field.translations" label="{{lang.name}}">

                            <md-input-container class="md-block md-hue-1">
                                <input ng-model="lang.data.name" type="text" placeholder="Etiqueta" ng-required="true">
                            </md-input-container>

                            <md-input-container class="md-block md-hue-1">
                                <input ng-model="lang.data.placeholder" type="text" placeholder="Placeholder">
                            </md-input-container>

                        </md-tab>
                    </md-tabs>

                    <div>
                        <md-input-container>
                            <label>Tipo</label>
                            <md-select ng-model="field.input_id" ng-required="true">
                                <md-option ng-value="type.id" ng-repeat="type in types">{{type.content}}</md-option>
                            </md-select>
                        </md-input-container>
                    </div>

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
                        <input ng-model="field.css_class"
                               type="text"
                               placeholder="Clase CSS">
                    </md-input-container>

                    <md-switch ng-model="field.required" aria-label="Obligatorio">
                        Obligatorio
                    </md-switch>

                    <md-switch ng-model="field.label_enabled" aria-label="Mostrar etiqueta">
                        Mostrar etiqueta
                    </md-switch>

                    <md-switch ng-model="field.enabled" aria-label="Publicado">
                        Publicado
                    </md-switch>

                    <div>
                        <md-input-container>
                            <label>Validaci&oacute;n</label>
                            <md-select ng-model="field.validation">
                                <md-option value="alpha">Alfabético</md-option>
                                <md-option value="alpha_numeric">Alfanumérico</md-option>
                                <md-option value="integer">Entero</md-option>
                                <md-option value="number">Número</md-option>
                                <md-option value="password">Contraseña</md-option>
                                <md-option value="card">Tarjeta</md-option>
                                <md-option value="cvv">CCV</md-option>
                                <md-option value="email">Email</md-option>
                                <md-option value="url">Link</md-option>
                                <md-option value="domain">Dominio</md-option>
                                <md-option value="datetime">Fecha - Hora</md-option>
                                <md-option value="date">Fecha</md-option>
                                <md-option value="time">Hora</md-option>
                                <md-option value="month_day_year">Mes / Dia / Año</md-option>
                            </md-select>
                        </md-input-container>
                    </div>

                </md-card-content>
            </md-card>

        </form>

    </md-content>

    <div class="panel-footer panel-controls">
        <md-toolbar class="md-secondary md-menu-toolbar">
            <div class="md-toolbar-tools" layout-align="end center">

                <md-button class="md-icon-button" ng-click="save()" >
                    <md-icon>check</md-icon>
                    <md-tooltip md-direction="bottom">Guardar y Cerrar</md-tooltip>
                </md-button>

            </div>
        </md-toolbar>
    </div>

</div>