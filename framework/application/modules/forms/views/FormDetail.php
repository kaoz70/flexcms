<div class="panel panel-primary small-width">

    <md-toolbar md-colors="{borderBottomColor: '{{primaryColor300}}'}">
        <div class="md-toolbar-tools">
            <h2>Formulario</h2>
            <span flex></span>
            <panel-dispose close-callback="closeHandler"></panel-dispose>
        </div>
    </md-toolbar>

    <md-content class="panel-body">

        <form name="form">

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">General</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <md-input-container class="md-block md-hue-1">
                        <input type="text" placeholder="Nombre del formulario" ng-model="formData.name" ng-required="true" />
                    </md-input-container>

                    <md-input-container class="md-block md-hue-1">
                        <input type="text" placeholder="Email de envio" ng-model="formData.email" ng-required="true" />
                    </md-input-container>

                </md-card-content>
            </md-card>

            <md-card>
                <md-card-title>
                    <md-card-title-text>
                        <span class="md-headline">Campos</span>
                    </md-card-title-text>
                </md-card-title>
                <md-card-content>

                    <md-button class="md-icon-button" ng-click="addField()" >
                        <md-icon>add</md-icon>
                        <md-tooltip md-direction="bottom">A&ntilde;adir campo</md-tooltip>
                    </md-button>

                    <div class="panel-tools" ng-show="deleteSelection.length">
                        <md-content>
                            <div class="tools md-padding" layout-align="end center">
                                <md-button class="md-icon-button" ng-click="delete($event)">
                                    <md-icon class="md-warn">delete</md-icon>
                                    <md-tooltip md-direction="bottom">
                                        Eliminar {{deleteSelection.length}} items
                                    </md-tooltip>
                                </md-button>
                            </div>
                            <md-divider></md-divider>
                        </md-content>
                    </div>

                    <div ui-tree="treeOptions" ng-show="fields.length">

                        <md-list ui-tree-nodes="" ng-model="items">

                            <div ng-repeat="node in fields | filter:query"
                                 ui-tree-node
                            >

                                <div class="node" md-colors="{backgroundColor: '{{node.selected ? 'default-accent-500' : 'default-background-800'}}'}">

                                    <div ui-tree-handle><md-icon>reorder</md-icon></div>

                                    <md-list-item ng-click="onItemClick(node, items, '#/' + section + '/field/edit/' + node.id, $event)" ng-class="{'selected': selected === row}" >
                                        <p class="item-name">
                                            <span>{{node.translations[0].translation.name}}</span>
                                        </p>
                                        <md-checkbox aria-label="Check to delete"
                                                     class="md-secondary delete"
                                                     ng-click="toggleDeleteSelection(node.id)"></md-checkbox>
                                    </md-list-item>

                                </div>

                            </div>

                        </md-list>
                    </div>

                </md-card-content>
            </md-card>

        </form>

    </md-content>

    <div class="panel-footer panel-controls">
        <md-toolbar class="md-secondary">
            <div class="md-toolbar-tools" layout-align="end center">

                <md-button class="md-icon-button" ng-click="save()" >
                    <md-icon>save</md-icon>
                    <md-tooltip md-direction="bottom">Guardar</md-tooltip>
                </md-button>

                <md-button class="md-icon-button" ng-click="saveAndClose()" >
                    <md-icon>check</md-icon>
                    <md-tooltip md-direction="bottom">Guardar y Cerrar</md-tooltip>
                </md-button>

            </div>
        </md-toolbar>
    </div>

</div>

<div app-view-segment="2"></div>