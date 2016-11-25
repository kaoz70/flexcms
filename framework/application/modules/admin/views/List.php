<div class="panel panel-primary small-width">

    <md-toolbar>
        <div class="md-toolbar-tools">
            <h2>{{title}}</h2>
            <span flex></span>
            <panel-dispose></panel-dispose>
        </div>
    </md-toolbar>

    <div class="search">

        <md-content class="md-padding">
            <md-input-container class="md-block">
                <md-icon>search</md-icon>
                <input ng-model="query" type="text" placeholder="Buscar..." >
            </md-input-container>
        </md-content>

    </div>

    <div class="panel-tools" ng-show="deleteSelection.length">
        <md-content>
            <div class="tools md-padding" layout-align="end center" ng-show="deleteSelection.length">
                <md-button class="md-icon-button">
                    <md-icon class="md-warn">delete</md-icon>
                    <md-tooltip md-direction="bottom">
                        Eliminar {{deleteSelection.length}} items
                    </md-tooltip>
                </md-button>
            </div>
            <md-divider ></md-divider>
        </md-content>
    </div>

    <md-content class="panel-body">


        <md-list dnd-list="records">

            <md-list-item ng-repeat="row in records | filter:query"
                          dnd-draggable="row"
                          dnd-moved="records.splice($index, 1)"
                          dnd-effect-allowed="move"
                          dnd-selected="selected = row"
                          dnd-dragend="onSortEnd()"
                          ng-click="onItemClick(row.id)"
                          ng-class="{'selected': selected === row}"
            >
                <p>{{row.name ? row.name : row.translation.name}}</p>
                <md-checkbox class="md-secondary" ng-model="topping.wanted" ng-click="toggleDeleteSelection(row.id)"></md-checkbox>
                <list-item-delete class="md-secondary" item="row" />
            </md-list-item>


        </md-list>
    </md-content>

    <div class="panel-footer panel-controls list">
        <md-toolbar class="md-accent">
            <div class="md-toolbar-tools" layout="row" layout-align="end center">
                <md-button class="md-icon-button" ng-repeat="item in menu" ng-href="#/{{item.url}}" >
                    <md-icon>{{item.icon}}</md-icon>
                    <md-tooltip md-direction="bottom">{{item.title}}</md-tooltip>
                </md-button>
            </div>
        </md-toolbar>
    </div>

</div>