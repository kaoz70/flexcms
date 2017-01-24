<div class="panel panel-primary small-width">

    <md-toolbar md-colors="{borderBottomColor: '{{primaryColor300}}'}">
        <div class="md-toolbar-tools">
            <h2>Configucaci&oacute;n de Imagen</h2>
            <span flex></span>
            <panel-dispose></panel-dispose>
        </div>
    </md-toolbar>

    <md-content class="panel-body">

        <form name="form">

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">General</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <md-input-container class="md-block md-hue-1">
                        <input ng-model="image.name" type="text" placeholder="Nombre" ng-required="true">
                    </md-input-container>

                    <md-input-container class="md-block md-hue-1">
                        <input ng-model="image.sufix" type="text" placeholder="Sufijo" ng-required="true">
                    </md-input-container>

                    <md-switch ng-model="image.force_jpg"
                               aria-label="Forzar imagen JPG">
                        Forzar JPG
                    </md-switch>

                    <div label="Color de fondo (solo PNG)"
                         md-color-picker
                         ng-model="image.background_color"></div>

                    <md-slider-container>
                        <span>Calidad</span>
                        <md-slider flex min="0" max="100"
                                   ng-model="image.quality"
                                   aria-label="Calidad"
                                   default="#fff"
                                   id="quality-slider">
                        </md-slider>
                        <md-input-container class="md-hue-1">
                            <input flex type="number" ng-model="image.quality" aria-label="Calidad" aria-controls="quality-slider">
                        </md-input-container>
                    </md-slider-container>

                </md-card-content>
            </md-card>

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">Dimensiones</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <md-switch ng-model="image.restrict_proportions"
                               aria-label="Restringir proporciones">
                        Restringir proporciones
                    </md-switch>

                    <div ng-show="image.restrict_proportions">

                        <md-switch ng-model="image.crop"
                                   aria-label="Recortar">
                            Recortar
                        </md-switch>

                        <div layout="row">
                            <md-input-container class="md-block md-hue-1">
                                <input ng-model="image.width" type="number" ng-attr-placeholder="Ancho {{image.crop ? '' : 'm&aacute;ximo'}}" ng-required="image.restrict_proportions">
                            </md-input-container>
                            <md-input-container class="md-block md-hue-1">
                                <input ng-model="image.height" type="number" ng-attr-placeholder="Alto {{image.crop ? '' : 'm&aacute;ximo'}}" ng-required="image.restrict_proportions">
                            </md-input-container>
                        </div>

                    </div>

                </md-card-content>
            </md-card>

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">Marca de agua</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <md-switch ng-model="image.watermark"
                               aria-label="Marca de agua">
                        Usar marca de agua
                    </md-switch>

                    <div layout="column" ng-show="image.watermark">

                        <upload-file model="watermark_data"></upload-file>

                        <md-input-container>
                            <label>Posici&oacute;n</label>
                            <md-select ng-model="image.watermark_position">
                                <md-option value="top-left">Superior izquierda</md-option>
                                <md-option value="top">Superior centro</md-option>
                                <md-option value="top-right">Superior derecha</md-option>
                                <md-option value="left">Izquierda centro</md-option>
                                <md-option value="center">Centro</md-option>
                                <md-option value="right">Derecha centro</md-option>
                                <md-option value="bottom-left">Inferior izquierda</md-option>
                                <md-option value="bottom">Inferior centro</md-option>
                                <md-option value="bottom-right">Inferior derecha</md-option>
                            </md-select>
                        </md-input-container>

                        <md-slider-container>
                            <span>Alpha</span>
                            <md-slider flex min="0" max="100"
                                       ng-model="image.watermark_alpha"
                                       aria-label="Transparencia"
                                       id="watermark-alpha-slider">
                            </md-slider>
                            <md-input-container class="md-hue-1">
                                <input flex type="number" ng-model="image.watermark_alpha" aria-label="Transparencia" aria-controls="watermark-alpha-slider">
                            </md-input-container>
                        </md-slider-container>

                        <md-switch ng-model="image.watermark_repeat"
                                   aria-label="Repetir">
                            Repetir
                        </md-switch>

                    </div>


                </md-card-content>
            </md-card>

        </form>

    </md-content>

    <div class="panel-footer panel-controls">
        <md-toolbar class="md-secondary">
            <div class="md-toolbar-tools" layout-align="end center">

                <md-button class="md-icon-button" ng-click="saveAndClose()" >
                    <md-icon>save</md-icon>
                    <md-tooltip md-direction="bottom">Guardar y Cerrar</md-tooltip>
                </md-button>

            </div>
        </md-toolbar>
    </div>

</div>