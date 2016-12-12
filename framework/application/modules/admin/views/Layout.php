<!-- Markup for lists inside the dropzone. It's inside a seperate template
     because it will be used recursively. The dnd-list directive enables
     to drop elements into the referenced array. The dnd-draggable directive
     makes an element draggable and will transfer the object that was
     assigned to it. If an element was dragged away, you have to remove
     it from the original list yourself using the dnd-moved attribute -->
<script type="text/ng-template" id="list.html">
    <ul dnd-list="row">
        <li ng-repeat="column in columns"
            dnd-draggable="item"
            dnd-effect-allowed="move"
            dnd-moved="list.splice($index, 1)"
            dnd-selected="models.selected = item"
            ng-class="{selected: models.selected === column}"
            ng-include="column.type + '.html'">
        </li>
    </ul>
</script>

<!-- This template is responsible for rendering a container element. It uses
     the above list template to render each container column -->
<script type="text/ng-template" id="container.html">
    <div class="container-element box box-blue">
        <h3>Container {{item.id}}</h3>
        <div class="column" ng-repeat="list in item.columns" ng-include="'list.html'"></div>
        <div class="clearfix"></div>
    </div>
</script>

<!-- Template for a normal list item -->
<script type="text/ng-template" id="item.html">
    <div class="item">Item {{item.id}}</div>
</script>


<div class="panel panel-primary large-width">

    <md-toolbar md-colors="{borderBottomColor: '{{primaryColor300}}'}">
        <div class="md-toolbar-tools">
            <h2>Estructura</h2>
            <span flex></span>
            <panel-dispose></panel-dispose>
        </div>
    </md-toolbar>

    <md-content class="panel-body">

        <div layout="row">

            <div flex="30">

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">General</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <md-tabs class="md-hue-1" md-dynamic-height="" md-border-bottom="">
                            <md-tab ng-repeat="lang in languages" label="{{lang.name}}">

                                <md-input-container class="md-block md-hue-1">
                                    <input ng-model="lang.translation.name" type="text" placeholder="T&iacute;tulo" ng-required="true">
                                </md-input-container>

                                <md-input-container class="md-block md-hue-1">
                                    <input ng-model="lang.translation.menu_name" type="text" placeholder="Nombre del Men&uacute;" ng-required="true">
                                </md-input-container>

                            </md-tab>
                        </md-tabs>

                    </md-card-content>
                </md-card>

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Visibilidad</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <md-switch ng-model="page.enabled" aria-label="Publicado">
                            Publicado
                        </md-switch>

                        <md-switch ng-model="page.popup" aria-label="Mostrar en popup">
                            Mostrar en popup
                        </md-switch>

                        <md-input-container>
                            <label>Visible para</label>
                            <md-select class="md-hue-1" ng-model="page.group_visibility">
                                <md-option><em>None</em></md-option>
                                <md-option ng-repeat="role in roles"
                                           ng-selected="{{role.id == page.group_visibility}}"
                                           ng-value="role.id">
                                    {{role.name}}
                                </md-option>
                            </md-select>
                        </md-input-container>

                    </md-card-content>
                </md-card>

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Avanzado</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <md-input-container class="md-block md-hue-1">
                            <input type="text" placeholder="Clase CSS" ng-model="page.css_class" />
                        </md-input-container>

                    </md-card-content>
                </md-card>

            </div>

            <div flex="70">

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Estructura</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <div dnd-list="rows" dnd-allowed-types="['row']">

                            <div ng-repeat="row in rows"
                                 layout="column"
                                 dnd-draggable="row"
                                 dnd-type="'row'"
                                 dnd-effect-allowed="move"
                                 dnd-moved="rows.splice($index, 1)"
                                 dnd-selected="selected = row"
                            >

                                <div layout="row">

                                    <div dnd-handle class="handle">
                                        <md-icon>open_with</md-icon>
                                    </div>

                                    <md-switch ng-model="row.expanded" aria-label="Expandida">
                                        Expandida
                                    </md-switch>

                                    <md-input-container class="md-block md-hue-1">
                                        <input ng-model="row.class" type="text" placeholder="Clase CSS">
                                    </md-input-container>

                                </div>

                                <div dnd-list="row.columns" layout="row" dnd-allowed-types="['column']">

                                    <div ng-repeat="column in row.columns"
                                         dnd-type="'column'"
                                         flex="calculateSpans(column, row.columns.length)"
                                         class="column"
                                         ng-class="{selected: selected === column}"
                                    >

                                        <div>

                                            <div dnd-handle class="handle">
                                                <md-icon>open_with</md-icon>
                                            </div>

                                            <md-input-container class="md-block md-hue-1">
                                                <input ng-model="column.class" type="text" placeholder="Clase CSS">
                                            </md-input-container>

                                            <h4>Spans</h4>
                                            <div layout="row">
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.span.large" type="number" placeholder="Large">
                                                </md-input-container>
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.span.medium" type="number" placeholder="Medium">
                                                </md-input-container>
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.span.small" type="number" placeholder="Small">
                                                </md-input-container>
                                            </div>

                                            <h4>Offset</h4>
                                            <div layout="row">
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.offset.large" type="number" placeholder="Large">
                                                </md-input-container>
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.offset.medium" type="number" placeholder="Medium">
                                                </md-input-container>
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.offset.small" type="number" placeholder="Small">
                                                </md-input-container>
                                            </div>

                                            <h4>Push</h4>
                                            <div layout="row">
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.push.large" type="number" placeholder="Large">
                                                </md-input-container>
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.push.medium" type="number" placeholder="Medium">
                                                </md-input-container>
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.push.small" type="number" placeholder="Small">
                                                </md-input-container>
                                            </div>

                                            <h4>Pull</h4>
                                            <div layout="row">
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.pull.large" type="number" placeholder="Large">
                                                </md-input-container>
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.pull.medium" type="number" placeholder="Medium">
                                                </md-input-container>
                                                <md-input-container class="md-block md-hue-1">
                                                    <input ng-model="column.pull.small" type="number" placeholder="Small">
                                                </md-input-container>
                                            </div>

                                        </div>

                                        <div dnd-list="column" dnd-allowed-types="['widget']">
                                            <div ng-repeat="widget in column.widgets"
                                                 layout="row"
                                                 dnd-type="'widget'"
                                                 dnd-draggable="widget"
                                                 dnd-effect-allowed="move"
                                                 dnd-moved="column.splice($index, 1)"
                                                 dnd-selected="selected = item"
                                                 ng-class="{selected: selected === widget}">
                                                {{widget}}
                                            </div>
                                        </div>

                                    </div>

                                </div>


                            </div>

                        </div>

                    </md-card-content>
                </md-card>




                <div view-source="nested"></div>

                <div class="toolbox box box-grey box-padding">
                    <h3>New Elements</h3>
                    <ul>
                        <!-- The toolbox only allows to copy objects, not move it. After a new
                             element was created, dnd-copied is invoked and we generate the next id -->
                        <li ng-repeat="item in models.templates"
                            dnd-draggable="item"
                            dnd-effect-allowed="copy"
                            dnd-copied="item.id = item.id + 1"
                        >
                            <button type="button" class="btn btn-default btn-lg" disabled="disabled">{{item.type}}</button>
                        </li>
                        <li ng-click="addRow(1)">add 1 column</li>
                        <li ng-click="addRow(2)">add 2 column</li>
                        <li ng-click="addRow(3)">add 3 column</li>
                        <li ng-click="addRow(4)">add 4 column</li>
                    </ul>
                </div>

                <div ng-if="selected" class="box box-grey box-padding">
                    <h3>Selected</h3>
                    <strong>Type: </strong> {{selected.type}}<br>
                    <input type="text" ng-model="selected.id" class="form-control" style="margin-top: 5px" />
                </div>

                <div class="trashcan box box-grey box-padding">
                    <!-- If you use [] as referenced list, the dropped elements will be lost -->
                    <h3>Trashcan</h3>
                    <ul dnd-list="[]">
                        <li><md-icon>delete</md-icon></li>
                    </ul>
                </div>

                <h2>Generated Model</h2>
                <pre>{{modelAsJson}}</pre>




            </div>
        </div>

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