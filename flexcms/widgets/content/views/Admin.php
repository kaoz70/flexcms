<md-card md-theme="content-widget">
    <md-card-header>
        <md-card-avatar>
            <md-icon>{{widget.config.icon}}</md-icon>
        </md-card-avatar>
        <md-card-header-text>
            <span class="md-title">{{widget.config.name[languages[0].slug]}}</span>
            <span class="md-subhead">{{widget.config.description[languages[0].slug]}}</span>
        </md-card-header-text>
        <md-button ng-click="delete($event)" class="md-icon-button" aria-label="Eliminar">
            <md-tooltip md-direction="bottom">Eliminar</md-tooltip>
            <md-icon>delete</md-icon>
        </md-button>
    </md-card-header>

    <md-card-content>

        <md-input-container>
            <label>Tipo</label>
            <md-select class="md-hue-1" ng-model="widget.data.content_type">
                <md-option><em>None</em></md-option>
                <md-option ng-repeat="(key, type) in widget.types"
                           ng-selected="{{widget.data.content_type == key}}"
                           ng-value="key">
                    {{type.menu.name[languages[0].slug]}}
                </md-option>
            </md-select>
        </md-input-container>

    </md-card-content>

    <md-card-footer layout="row">
        <md-button ng-show="changed" flex ng-click="save()" class="md-raised md-primary">Guardar</md-button>
    </md-card-footer>

</md-card>