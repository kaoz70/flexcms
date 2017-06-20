<md-dialog class="fullscreen-dialog" role="dialog" aria-label="Editar imagen">
    <md-toolbar>
        <div class="md-toolbar-tools">
            <h2>Editar imagen "{{model.name}}"</h2>
            <span flex></span>
            <md-button class="md-icon-button tools-action" aria-label="Close" ng-click="closeDialog()">
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
                                <input ng-model="file.name" type="text" placeholder="Nombre" ng-required="true">
                            </md-input-container>

                            <md-input-container flex class="md-block md-hue-1">
                                <input ng-model="file.image_alt" type="text" placeholder="Texto alterno" ng-required="true">
                            </md-input-container>

                        </div>

                    </md-card-content>

                </md-card>

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Corte </span>
                            <span>{{width}}px x {{height}}px</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <md-card-title-media>
                            <div class="crop-background">
                                <ui-cropper image="file.url_path"
                                            area-type="rectangle"
                                            area-coords="model.areaCoords"
                                            cropject="model.cropObject"
                                            aspect-ratio="width / height"
                                            area-init-size="initialSize"
                                            area-init-coords="initialCoords"
                                            change-on-fly="false"
                                            dominant-color="model.colors.dominantColor"
                                            palette-color="model.colors.paletteColor"
                                            result-image-size="resultImageSize"
                                            result-image="file.resultImage"></ui-cropper>
                            </div>
                        </md-card-title-media>

                    </md-card-content>
                </md-card>

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Archivos generados</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>
                        <div ng-repeat="conf in model.items">{{file.name | slugify}}{{conf.sufix}}{{conf.force_jpg ? '.jpg' : file.file_ext}}</div>
                    </md-card-content>

                </md-card>

            </div>

            <div layout="column">

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Resultado</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <md-card-title-media>
                            <div class="md-media-lg card-media" style="min-width: 200px">
                                <img ng-src="{{file.resultImage}}" />
                            </div>
                        </md-card-title-media>

                    </md-card-content>
                </md-card>

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Colores</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <h2>Predominante</h2>

                        <md-grid-list
                                md-cols="3"
                                md-row-height="1:1"
                                md-gutter="4px">
                            <md-grid-tile
                                    ng-style="{'background': generateRGB(model.colors.dominantColor)}"
                                    md-colspan="3"
                                    md-rowspan="1">
                                <h3 ng-class="'text-' + model.colors.textColor">Text: {{model.colors.textColor}}</h3>
                            </md-grid-tile>
                        </md-grid-list>

                        <h2>Paleta</h2>

                        <md-grid-list
                                md-cols="3"
                                md-row-height="1:1"
                                md-gutter="4px">
                            <md-grid-tile
                                    ng-repeat="color in model.colors.paletteColor"
                                    ng-style="{'background': generateRGB(color)}">
                            </md-grid-tile>
                        </md-grid-list>

                    </md-card-content>
                </md-card>
            </div>
        </div>

    </md-content>

    <div>
        <div layout="row">
            <md-button md-autofocus flex class="md-primary" ng-click="save()">
                <md-icon>save</md-icon> Guardar
            </md-button>
        </div>
    </div>

</md-dialog>