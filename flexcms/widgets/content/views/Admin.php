<md-card md-theme="default">
    <md-card-header>
        <md-card-avatar>
            <md-icon>{{widget.config.icon}}</md-icon>
        </md-card-avatar>
        <md-card-header-text>
            <span class="md-title">{{widget.config.name[languages[0].slug]}}</span>
            <span class="md-subhead">{{widget.config.description[languages[0].slug]}}</span>
        </md-card-header-text>
    </md-card-header>

    <md-card-content>

        <md-input-container>
            <label>Tipo</label>
            <md-select class="md-hue-1" ng-model="page.group_visibility">
                <md-option><em>None</em></md-option>
                <md-option ng-repeat="type in widget.types"
                           ng-selected="{{widget.content.type == type.menu.name[languages[0].slug]}}"
                           ng-value="type.menu.name[languages[0].slug]">
                    {{type.menu.name[languages[0].slug]}}
                </md-option>
            </md-select>
        </md-input-container>

    </md-card-content>
</md-card>