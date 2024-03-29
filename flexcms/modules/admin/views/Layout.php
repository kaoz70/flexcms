

<!-- Template for a widget list item -->
<script type="text/ng-template" id="widget.html">
    <md-card-header>
        <md-card-avatar>
            <md-icon>{{widget.icon}}</md-icon>
        </md-card-avatar>
        <md-card-header-text>
            <span class="md-title">{{widget.name[vm.languages[0].slug]}}</span>
            <span class="md-subhead">{{widget.groups.join(", ")}}</span>
        </md-card-header-text>
    </md-card-header>

    <md-card-content>
        <p>{{widget.description[vm.languages[0].slug]}}</p>
    </md-card-content>
</script>


<div class="panel panel-primary large-width">

    <div class="panel-header">
        <md-toolbar md-colors="{borderBottomColor: '{{primaryColor300}}'}">
            <div class="md-toolbar-tools">
                <h2>{{vm.page.translation.name}}</h2>
                <span flex></span>
                <md-button ng-click="vm.layoutService.toggleRight()" class="md-icon-button" aria-label="Settings">
                    <md-icon>view_module</md-icon>
                </md-button>
                <panel-dispose></panel-dispose>
            </div>
        </md-toolbar>
    </div>

    <md-content perfect-scrollbar class="panel-body">

        <div layout="row">

            <div flex="25">

                <md-card>
                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Avanzado</span>
                        </md-card-title-text>
                    </md-card-title>
                    <md-card-content>

                        <md-input-container class="md-block md-hue-1">
                            <input type="text" placeholder="Clase CSS" ng-model="vm.page.css_class" />
                        </md-input-container>

                    </md-card-content>
                </md-card>

            </div>

            <div flex="75">

                <md-card>

                    <md-card-title>
                        <md-card-title-text>
                            <span class="md-headline">Estructura</span>
                        </md-card-title-text>
                    </md-card-title>

                    <md-card-content layout-margin>

                        <div layout="row" layout-padding layout-align="center center">

                            <md-button class="md-icon-button" ng-click="vm.device = 'none'">
                                <md-tooltip md-direction="bottom">No preview</md-tooltip>
                                <md-icon ng-class="vm.device == 'none' ? 'md-accent' : ''">phonelink_off</md-icon>
                            </md-button>

                            <md-button class="md-icon-button" ng-click="vm.device = 'large'">
                                <md-tooltip md-direction="bottom">Desktop</md-tooltip>
                                <md-icon ng-class="vm.device == 'large' ? 'md-accent' : ''">desktop_windows</md-icon>
                            </md-button>

                            <md-button class="md-icon-button" ng-click="vm.device = 'medium'">
                                <md-tooltip md-direction="bottom">Tablet</md-tooltip>
                                <md-icon ng-class="vm.device == 'medium' ? 'md-accent' : ''">tablet</md-icon>
                            </md-button>
                            <md-button class="md-icon-button" ng-click="vm.device = 'small'">
                                <md-tooltip md-direction="bottom">Phone</md-tooltip>
                                <md-icon ng-class="vm.device == 'small' ? 'md-accent' : ''">phone</md-icon>
                            </md-button>

                            <div flex></div>

                            <md-button ng-repeat="item in [1,2,3,4]" class="md-icon-button" ng-click="vm.layoutService.addRow(item)">
                                <md-tooltip md-direction="bottom">A&ntilde;adir {{item}} columna{{item > 1 ? 's' : ''}}</md-tooltip>
                                <md-icon>filter_{{item}}</md-icon>
                            </md-button>

                        </div>

                        <div id="layout" dnd-list="vm.page.data.structure" dnd-allowed-types="['row-type']">

                            <md-card ng-repeat="row in vm.page.data.structure"
                                     md-theme="layout-rows"
                                     dnd-draggable="row"
                                     dnd-type="'row-type'"
                                     dnd-effect-allowed="move"
                                     ng-init="hideRowConfig = true"
                                     dnd-moved="vm.page.data.structure.splice($index, 1)"
                            >

                                <md-toolbar class="md-hue-1">
                                    <div class="md-toolbar-tools">
                                        <md-icon>open_with</md-icon>
                                        <span flex></span>
                                        <md-button ng-click="hideRowConfig = !hideRowConfig" class="md-icon-button" aria-label="Configuraci&oacute;n">
                                            <md-tooltip md-direction="bottom">Configuraci&oacute;n</md-tooltip>
                                            <md-icon>settings</md-icon>
                                        </md-button>
                                        <md-button ng-click="vm.layoutService.addColumn($event, row)" class="md-icon-button" aria-label="A&ntilde;adir columna">
                                            <md-tooltip md-direction="bottom">A&ntilde;adir columna</md-tooltip>
                                            <md-icon>add</md-icon>
                                        </md-button>
                                        <md-button ng-click="vm.layoutService.deleteRow($event, $index)" class="md-icon-button" aria-label="Eliminar fila">
                                            <md-tooltip md-direction="bottom">Eliminar fila</md-tooltip>
                                            <md-icon>delete</md-icon>
                                        </md-button>
                                    </div>
                                </md-toolbar>

                                <div class="config" collapse="hideRowConfig">
                                    <md-card-content md-theme="layout-widgets">
                                        <div class="tools">
                                            <md-switch ng-model="row.expanded" aria-label="Expandida">
                                                Expandida
                                            </md-switch>

                                            <md-input-container class="md-block">
                                                <input ng-model="row.class" type="text" placeholder="Clase CSS">
                                            </md-input-container>
                                        </div>
                                    </md-card-content>
                                </div>

                                <md-card-content dnd-list="row.columns"
                                                 class="columns"
                                                 layout="row"
                                                 dnd-allowed-types="['col-type']"
                                                 dnd-horizontal-list="true">

                                    <div class="col-{{column.span[vm.device]}} offset-{{column.offset[vm.device]}} push-{{column.push[vm.device]}} pull-{{column.pull[vm.device]}}"
                                         ng-repeat="column in row.columns"
                                         dnd-effect-allowed="move"
                                         dnd-type="'col-type'"
                                         dnd-draggable="column"
                                         dnd-moved="row.columns.splice($index, 1)"
                                         md-theme="layout-columns"
                                         ng-init="hideColConfig = true"
                                    >

                                        <md-card layout-fill>

                                            <dnd-nodrag>

                                                <md-toolbar dnd-handle class="md-hue-1">
                                                    <div class="md-toolbar-tools">
                                                        <md-icon>open_with</md-icon>
                                                        <span flex></span>
                                                        <md-button ng-click="hideColConfig = !hideColConfig" class="md-icon-button" aria-label="Configuraci&oacute;n">
                                                            <md-tooltip md-direction="bottom">Configuraci&oacute;n</md-tooltip>
                                                            <md-icon>settings</md-icon>
                                                        </md-button>
                                                        <md-button ng-click="vm.layoutService.deleteColumn($event, $index, row.columns)" class="md-icon-button" aria-label="Eliminar columna">
                                                            <md-tooltip md-direction="bottom">Eliminar columna</md-tooltip>
                                                            <md-icon>delete</md-icon>
                                                        </md-button>
                                                    </div>
                                                </md-toolbar>

                                                <div class="config" collapse="hideColConfig">

                                                    <md-card-content md-theme="layout-widgets">

                                                        <div class="tools">

                                                            <md-input-container class="md-block md-hue-1">
                                                                <input ng-model="column.class" type="text" placeholder="Clase CSS">
                                                            </md-input-container>

                                                            <h3>Spans</h3>

                                                            <md-slider-container>
                                                                <span class="md-body-1">L</span>
                                                                <md-slider flex md-discrete
                                                                           id="large-span"
                                                                           ng-model="column.span.large"
                                                                           step="1"
                                                                           min="1"
                                                                           max="12"
                                                                           aria-label="Large">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.span.large" aria-label="Large" aria-controls="large-span">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <md-slider-container>
                                                                <span class="md-body-1">M</span>
                                                                <md-slider flex md-discrete
                                                                           id="medium-span"
                                                                           ng-model="column.span.medium"
                                                                           step="1"
                                                                           min="1"
                                                                           max="12"
                                                                           aria-label="Medium">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.span.medium" aria-label="Medium" aria-controls="medium-span">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <md-slider-container>
                                                                <span class="md-body-1">S</span>
                                                                <md-slider flex md-discrete
                                                                           id="small-span"
                                                                           ng-model="column.span.small"
                                                                           step="1"
                                                                           min="1"
                                                                           max="12"
                                                                           aria-label="Small">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.span.small" aria-label="Small" aria-controls="small-span">
                                                                </md-input-container>
                                                            </md-slider-container>


                                                            <h4>Offset</h4>

                                                            <md-slider-container>
                                                                <span class="md-body-1">L</span>
                                                                <md-slider flex md-discrete
                                                                           id="large-offset"
                                                                           ng-model="column.offset.large"
                                                                           step="1"
                                                                           min="0"
                                                                           max="11"
                                                                           aria-label="Large">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.offset.large" aria-label="Large" aria-controls="large-offset">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <md-slider-container>
                                                                <span class="md-body-1">M</span>
                                                                <md-slider flex md-discrete
                                                                           id="medium-offset"
                                                                           ng-model="column.offset.medium"
                                                                           step="1"
                                                                           min="0"
                                                                           max="11"
                                                                           aria-label="Medium">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.offset.medium" aria-label="Medium" aria-controls="medium-offset">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <md-slider-container>
                                                                <span class="md-body-1">S</span>
                                                                <md-slider flex md-discrete
                                                                           id="small-offset"
                                                                           ng-model="column.offset.small"
                                                                           step="1"
                                                                           min="0"
                                                                           max="11"
                                                                           aria-label="Small">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.offset.small" aria-label="Small" aria-controls="small-offset">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <h4>Push</h4>
                                                            <md-slider-container>
                                                                <span class="md-body-1">L</span>
                                                                <md-slider flex md-discrete
                                                                           id="large-push"
                                                                           ng-model="column.push.large"
                                                                           step="1"
                                                                           min="0"
                                                                           max="11"
                                                                           aria-label="Large">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.push.large" aria-label="Large" aria-controls="large-push">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <md-slider-container>
                                                                <span class="md-body-1">M</span>
                                                                <md-slider flex md-discrete
                                                                           id="medium-push"
                                                                           ng-model="column.push.medium"
                                                                           step="1"
                                                                           min="0"
                                                                           max="11"
                                                                           aria-label="Medium">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.push.medium" aria-label="Medium" aria-controls="medium-push">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <md-slider-container>
                                                                <span class="md-body-1">S</span>
                                                                <md-slider flex md-discrete
                                                                           id="small-push"
                                                                           ng-model="column.push.small"
                                                                           step="1"
                                                                           min="0"
                                                                           max="11"
                                                                           aria-label="Small">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.push.small" aria-label="Small" aria-controls="small-push">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <h4>Pull</h4>
                                                            <md-slider-container>
                                                                <span class="md-body-1">L</span>
                                                                <md-slider flex md-discrete
                                                                           id="large-pull"
                                                                           ng-model="column.pull.large"
                                                                           step="1"
                                                                           min="0"
                                                                           max="11"
                                                                           aria-label="Large">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.pull.large" aria-label="Large" aria-controls="large-pull">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <md-slider-container>
                                                                <span class="md-body-1">M</span>
                                                                <md-slider flex md-discrete
                                                                           id="medium-pull"
                                                                           ng-model="column.pull.medium"
                                                                           step="1"
                                                                           min="0"
                                                                           max="11"
                                                                           aria-label="Medium">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.pull.medium" aria-label="Medium" aria-controls="medium-pull">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                            <md-slider-container>
                                                                <span class="md-body-1">S</span>
                                                                <md-slider flex md-discrete
                                                                           id="small-pull"
                                                                           ng-model="column.pull.small"
                                                                           step="1"
                                                                           min="0"
                                                                           max="11"
                                                                           aria-label="Small">
                                                                </md-slider>
                                                                <md-input-container>
                                                                    <input flex type="number" ng-model="column.pull.small" aria-label="Small" aria-controls="small-pull">
                                                                </md-input-container>
                                                            </md-slider-container>

                                                        </div>

                                                    </md-card-content>

                                                </div>

                                                <md-card-content>
                                                    <div class="widget-list"
                                                         dnd-list="column.widgets"
                                                         dnd-drop="vm.dropCallback(item)"
                                                         dnd-allowed-types="['widget-type']">
                                                        <div ng-repeat="id in column.widgets"
                                                             dnd-type="'widget-type'"
                                                             dnd-draggable="id"
                                                             dnd-effect-allowed="move"
                                                             dnd-moved="column.widgets.splice($index, 1)">
                                                            <widget widget-id="id"
                                                                    list="column.widgets"
                                                                    list-index="$index"
                                                                    category-id="vm.page.id"></widget>
                                                        </div>
                                                    </div>
                                                </md-card-content>

                                            </dnd-nodrag>



                                        </md-card>

                                    </div>

                                </md-card-content>


                            </md-card>

                        </div>

                    </md-card-content>
                </md-card>

            </div>
        </div>

    </md-content>

    <md-sidenav class="md-sidenav-right"
                md-component-id="right"
                md-disable-backdrop md-whiteframe="4">

        <md-toolbar class="md-theme-indigo">
            <div class="md-toolbar-tools">
                <md-button ng-click="vm.closeRight()" class="md-icon-button" aria-label="Settings">
                    <md-icon>close</md-icon>
                </md-button>
                <h1 class="md-toolbar-tools"> Widgets</h1>
            </div>
        </md-toolbar>

        <md-content layout-margin>

            <md-input-container class="md-block md-hue-1">
                <md-icon>search</md-icon>
                <input ng-model="query" type="text" placeholder="Buscar..." >
            </md-input-container>

            <md-card ng-repeat="widget in vm.widgets | filter:query"
                     dnd-draggable="widget"
                     dnd-type="'widget-type'"
                     dnd-effect-allowed="copy"
                     dnd-copied="console.log(2)"
                     ng-include="'widget.html'"></md-card>

        </md-content>

    </md-sidenav>

    <div class="panel-footer panel-controls">
        <md-toolbar class="md-secondary">
            <div class="md-toolbar-tools" layout-align="end center">

                <md-button class="md-icon-button" ng-click="vm.save()" >
                    <md-icon>save</md-icon>
                    <md-tooltip md-direction="bottom">Guardar</md-tooltip>
                </md-button>

                <md-button class="md-icon-button" ng-click="vm.saveAndClose()" >
                    <md-icon>check</md-icon>
                    <md-tooltip md-direction="bottom">Guardar y Cerrar</md-tooltip>
                </md-button>

            </div>
        </md-toolbar>
    </div>

</div>
