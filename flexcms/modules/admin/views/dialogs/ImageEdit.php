<md-dialog role="dialog" aria-label="Editar imagen">
    <md-toolbar>
        <div class="md-toolbar-tools">
            <h2>Editar imagen</h2>
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
                            <span class="md-headline">Imagen</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>
                        <img ng-src="{{vm.file.url_path}}" style="max-width: 300px">
                    </md-card-content>
                </md-card>

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

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Archivos generados</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>
                        <div ng-repeat="conf in vm.model.items">{{vm.file.name | slugify}}{{conf.sufix}}{{conf.force_jpg ? '.jpg' : vm.file.file_ext}}</div>
                    </md-card-content>
                </md-card>

            </div>

        </div>

    </md-content>

    <div layout="row">
        <md-button md-autofocus flex class="md-primary" ng-click="vm.save()">
            <md-icon>save</md-icon> Guardar
        </md-button>
    </div>

</md-dialog>
