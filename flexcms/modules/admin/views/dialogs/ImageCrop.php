<md-dialog class="fullscreen-dialog" role="dialog" aria-label="Editar imagen">
    <md-toolbar>
        <div class="md-toolbar-tools">
            <h2>Editar imagen "{{vm.config.name}}"</h2>
            <span flex></span>
            <md-button class="md-icon-button tools-action" aria-label="Close" ng-click="vm.closeDialog()">
                <md-icon>close</md-icon>
            </md-button>
        </div>
    </md-toolbar>

    <md-content>

        <div layout="row">

            <div flex>

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Informaci&oacute;n</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <div layout="row">

                            <md-input-container flex class="md-block md-hue-1">
                                <input ng-model="vm.file.name" type="text" placeholder="Nombre" ng-required="true">
                            </md-input-container>

                            <md-input-container flex class="md-block md-hue-1">
                                <input ng-model="vm.file.image_alt" type="text" placeholder="Texto alterno" ng-required="true">
                            </md-input-container>

                        </div>

                    </md-card-content>

                </md-card>

                <div layout="row">
                    <md-card flex="">
                        <md-card-title>
                            <md-card-title-text>
                                <span class="md-headline">Corte</span>
                            </md-card-title-text>
                        </md-card-title>
                        <md-card-content>

                            <md-card-title-media>
                                <div class="crop-background">
                                    <ui-cropper image="vm.file.url_path"
                                                area-type="rectangle"
                                                area-coords="vm.file.data.coords.areaCoords"
                                                cropject="vm.file.data.coords"
                                                aspect-ratio="vm.width / vm.height"
                                                area-init-size="vm.initialSize"
                                                area-init-coords="vm.initialCoords"
                                                change-on-fly="false"
                                                dominant-color="vm.file.data.colors.dominantColor"
                                                palette-color="vm.file.data.colors.paletteColor"
                                                result-image-size="vm.resultImageSize"
                                                result-image="vm.file.resultImage"></ui-cropper>
                                </div>
                            </md-card-title-media>

                        </md-card-content>
                    </md-card>

                    <md-card flex="33">
                        <md-card-title>
                            <md-card-title-text>
                                <span class="md-headline">Resultado</span>
                            </md-card-title-text>
                        </md-card-title>
                        <md-card-content>

                            <md-card-title-media>
                                <div class="md-media-lg card-media">
                                    <div>
                                        <img id="preview" ng-src="{{vm.file.resultImage}}" />
                                    </div>
                                </div>
                            </md-card-title-media>

                            <ul>
                                <li><strong>Tama&ntilde;o:</strong> {{vm.width}}px x {{vm.height}}px</li>
                                <li><strong>Estado:</strong> <span ng-bind-html="vm.status"></span></li>
                            </ul>

                        </md-card-content>
                    </md-card>
                </div>

            </div>

            <div flex="20" layout="column">

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Archivos generados</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>
                        <ul ng-repeat="conf in vm.config.items">
                            <li>{{vm.file.name | slugify}}{{conf.sufix}}{{conf.force_jpg ? '.jpg' : vm.file.file_ext}}</li>
                        </ul>
                    </md-card-content>

                </md-card>

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Colores obtenidos</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <h2>Predominante</h2>

                        <md-grid-list
                                md-cols="3"
                                md-row-height="1:1"
                                md-gutter="4px">
                            <md-grid-tile
                                    ng-style="{'background': vm.generateRGB(vm.file.data.colors.dominantColor)}"
                                    md-colspan="3"
                                    md-rowspan="1">
                                <h3 ng-class="'text-' + vm.file.data.colors.textColor">Text: {{vm.file.data.colors.textColor}}</h3>
                            </md-grid-tile>
                        </md-grid-list>

                        <h2>Paleta</h2>

                        <md-grid-list
                                md-cols="3"
                                md-row-height="1:1"
                                md-gutter="4px">
                            <md-grid-tile
                                    ng-repeat="color in vm.file.data.colors.paletteColor"
                                    ng-style="{'background': vm.generateRGB(color)}">
                            </md-grid-tile>
                        </md-grid-list>

                    </md-card-content>
                </md-card>
            </div>
        </div>

    </md-content>

    <div>
        <div layout="row">
            <md-button md-autofocus flex class="md-primary" ng-click="vm.save()">
                <md-icon>save</md-icon> Guardar
            </md-button>
        </div>
    </div>

</md-dialog>
