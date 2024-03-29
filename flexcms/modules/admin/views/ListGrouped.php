<script type="text/ng-template" id="nodes_list_renderer.html">
    <div class="node" md-colors="{backgroundColor: '{{node.selected ? 'default-accent-500' : 'default-background-50'}}'}">

        <div ui-tree-handle ng-show="showReorder"><md-icon>reorder</md-icon></div>

        <md-list-item ng-click="selection.onItemClick(node, items, '#/' + section + '/edit/' + node.id, $event)" ng-class="{'selected': selected === row}" >
            <p class="item-name">
                <md-icon ng-show="node.icon">{{node.icon}}</md-icon>
                <span>{{node.name ? node.name : node.translation.name}}</span>
            </p>
            <md-checkbox aria-label="Check to delete"
                         class="md-secondary delete"
                         ng-show="showDelete"
                         ng-click="selection.toggle(node)"></md-checkbox>
        </md-list-item>

    </div>
    <ol class="node-children" ui-tree-nodes="" ng-model="node.children" >
        <li ng-repeat="node in node.children | filter:query"
             ui-tree-node
             ng-include="'nodes_list_renderer.html'"
             ng-class="{'selected': selected === node}"
        >
        </li>
    </ol>

</script>

<div class="panel panel-primary small-width">

    <div class="panel-header">
        <md-toolbar md-colors="{borderBottomColor: '{{primaryColor300}}'}">
            <div class="md-toolbar-tools">
                <h2 ng-bind-html="title"></h2>
                <span flex></span>
                <panel-dispose></panel-dispose>
            </div>
        </md-toolbar>
    </div>


    <div class="search">

        <md-content class="md-padding">
            <md-input-container class="md-block md-hue-1">
                <md-icon>search</md-icon>
                <input ng-model="query" type="text" placeholder="Buscar..." >
            </md-input-container>
        </md-content>

    </div>

    <div class="panel-tools" ng-show="selection.getLength()">
        <md-content>
            <div class="tools md-padding" layout-align="end center">
                <md-button class="md-icon-button" ng-click="selection.delete($event)">
                    <md-icon class="md-warn">delete</md-icon>
                    <md-tooltip md-direction="bottom">
                        Eliminar {{selection.length}} items
                    </md-tooltip>
                </md-button>
            </div>
            <md-divider></md-divider>
        </md-content>
    </div>

    <md-content perfect-scrollbar class="panel-body">

        <div ng-repeat="group in items">

            <md-subheader class="md-no-sticky">
                <div flex="row">
                    {{group.name}}
                    <span flex></span>
                    <md-button class="md-icon-button" ng-href="#/{{section}}/{{group.id}}/create" >
                        <md-icon>add</md-icon>
                        <md-tooltip md-direction="bottom">nuevo</md-tooltip>
                    </md-button>
                </div>

            </md-subheader>

            <div ui-tree="treeOptions" ng-show="group.items.length" ng-class="keepOne">

                <md-list ui-tree-nodes="" ng-model="group.items">

                    <li ng-repeat="node in group.items | filter:query"
                        ui-tree-node
                        ng-include="'nodes_list_renderer.html'"
                    ></li>

                </md-list>
            </div>

            <md-divider></md-divider>

        </div>


    </md-content>

    <div class="panel-footer panel-controls list" ng-show="menu.length">
        <md-toolbar class="md-secondary">
            <div class="md-toolbar-tools" layout="row" layout-align="end center">
                <md-button class="md-icon-button" ng-repeat="item in menu" ng-href="#/{{item.url}}" >
                    <md-icon>{{item.icon}}</md-icon>
                    <md-tooltip md-direction="bottom">{{item.title}}</md-tooltip>
                </md-button>
            </div>
        </md-toolbar>
    </div>

</div>