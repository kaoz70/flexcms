<div class="panel panel-primary small-width">

    <div class="panel-header">
        <md-toolbar md-colors="{borderBottomColor: '{{primaryColor300}}'}">
            <div class="md-toolbar-tools">
                <h2>{{language.name}}</h2>
                <span flex></span>
                <panel-dispose close-callback="closeHandler"></panel-dispose>
            </div>
        </md-toolbar>
    </div>

    <md-content class="panel-body">

        <md-card>
            <md-card-title>
                <md-card-title-text>
                    <span class="md-headline">General</span>
                </md-card-title-text>
            </md-card-title>
            <md-card-content>

                <md-input-container class="md-block md-hue-1">
                    <input ng-model="language.name" type="text" placeholder="Nombre" ng-required="true">
                </md-input-container>

                <md-input-container class="md-block md-hue-1">
                    <input ng-model="language.slug" type="text" placeholder="URL" ng-required="true">
                </md-input-container>

            </md-card-content>
        </md-card>


    </md-content>

    <div class="panel-footer panel-controls">
        <md-toolbar class="md-secondary md-menu-toolbar">
            <div class="md-toolbar-tools" layout-align="end center">

                <md-button class="md-icon-button" ng-click="save()" >
                    <md-icon>save</md-icon>
                    <md-tooltip md-direction="bottom">Guardar</md-tooltip>
                </md-button>

            </div>
        </md-toolbar>
    </div>

</div>