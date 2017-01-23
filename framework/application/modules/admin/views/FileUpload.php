<md-button ng-model="image.watermark" name="file"
           class="md-raised md-primary"
           ngf-select="upload($file)"
           ngf-pattern="'image/*'"
           ngf-accept="'image/*'"
           ngf-max-size="20MB">Seleccionar imagen</md-button>

<md-progress-linear md-mode="determinate"
                    ng-if="show_progress"
                    ng-value="progress"></md-progress-linear>

<img class="img-fluid" ngf-thumbnail="file">