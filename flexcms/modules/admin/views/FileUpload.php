<md-button name="file"
           class="md-raised md-primary"
           ngf-select="upload($files)"
           ngf-multiple="multiple"
           ngf-pattern="'image/*'"
           ngf-accept="'image/*'"
           ngf-max-size="20MB">Seleccionar imagen</md-button>

<md-progress-linear md-mode="determinate"
                    ng-if="show_progress"
                    ng-value="progress"></md-progress-linear>

<div layout="row" style="flex-wrap: wrap">

    <md-card flex-xs flex="{{columnWidth}}" ng-repeat="file in model.files">
        <md-card-title>
            <md-card-title-text>
                {{file.file_name}}{{file.file_ext}}
            </md-card-title-text>
        </md-card-title>
        <md-card-content>

            <md-card-title-media>
                <div class="md-media-lg card-media">
                    <div class="crop-background hide-off-screen">
                        <ui-cropper image="file.url_path"
                                    area-type="rectangle"
                                    aspect-ratio="model.items[0].width / model.items[0].height"
                                    area-coords="model.areaCoords"
                                    result-image-size="resultImageSize"
                                    cropject="file.data.coords"
                                    init-max-area="true"
                                    change-on-fly="false"
                                    on-change="onChangeHandler($dataURI)"
                                    dominant-color="model.colors.dominantColor"
                                    palette-color="model.colors.paletteColor"
                                    result-image="file.resultImage"></ui-cropper>
                    </div>
                    <img class="img-fluid" ng-src="{{file.resultImage}}" />
                </div>
            </md-card-title-media>

            <md-card-actions layout="row" layout-align="end center">

                <md-button class="md-icon-button md-primary" aria-label="Editar" ng-click="edit(file, $event)">
                    <md-icon>mode_edit</md-icon>
                </md-button>
                <md-button class="md-icon-button md-warn" aria-label="Eliminar" ng-click="delete(file)">
                    <md-icon>delete</md-icon>
                </md-button>

            </md-card-actions>

        </md-card-content>
    </md-card>

</div>









