<div class="panel panel-primary small-width">
    <div class="panel-heading">
        <h3 class="panel-title">Configuraci&oacute;n<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3>
        <div class="panel-tools">
            <panel-dispose></panel-dispose>
        </div>
    </div>
    <div class="panel-body">

        <div class="form-heading">General</div>

        <uib-tabset active="active">

            <uib-tab index="$index + 1" ng-repeat="lang in languages">
                <uib-tab-heading>
                    <span>{{lang.name}}</span>
                </uib-tab-heading>
                <div class="widget">
                    <div class="form-group">
                        <span>T&iacute;tulo</span>
                        <input type="text" class="form-control" ng-model="lang.translation.name">
                    </div>
                    <div class="form-group">
                        <span>Nombre del Men&uacute;</span>
                        <input type="text" class="form-control" ng-model="lang.translation.menu_name">
                    </div>
                </div>
            </uib-tab>

        </uib-tabset>

        <hr class="full-width">
        <div class="form-heading">Visibilidad</div>

        <div class="checkbox">
            <label>
                <input ng-model="page.enabled" type="checkbox" >
                <span class="text">Publicado</span>
            </label>
        </div>

        <div class="checkbox">
            <label>
                <input ng-model="page.popup" type="checkbox" >
                <span class="text">Mostrar en popup</span>
            </label>
        </div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">Visible para</span>
                <select ng-model="page.group_visibility" class="form-control">
                    <option ng-repeat="role in roles"
                            ng-selected="{{role.id == page.group_visibility}}"
                            value="{{role.id}}">
                        {{role.name}}
                    </option>
                </select>
            </div>
        </div>

        <hr class="full-width">
        <div class="form-heading">SEO</div>

        <uib-tabset active="active">

            <uib-tab index="$index + 1" ng-repeat="lang in languages">
                <uib-tab-heading>
                    <span>{{lang.name}}</span>
                </uib-tab-heading>
                <div class="widget">
                    <div class="form-group">
                        <span>Palabras Clave</span>
                        <ui-select multiple
                                   tagging
                                   tagging-label=""
                                   ng-model="lang.translation.meta_keywords"
                                   tagging-tokens=","
                                   theme="bootstrap"
                                   sortable="true">
                            <ui-select-match>{{$item}}</ui-select-match>
                            <ui-select-choices repeat="color in ctrl.availableColors | filter:$select.search">
                                {{color}}
                            </ui-select-choices>
                        </ui-select>
                    </div>
                    <div class="form-group">
                        <span>Meta T&iacute;tulo</span>
                        <input class="form-control" ng-model="lang.translation.meta_title">
                    </div>
                    <div class="form-group">
                        <span>Meta Descripci&oacute;n</span>
                        <textarea class="form-control" ng-model="lang.translation.meta_description"></textarea>
                    </div>
                </div>
            </uib-tab>

        </uib-tabset>

        <hr class="full-width">
        <div class="form-heading">Vistas</div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">Listado</span>
                <select ng-model="config.list_view" class="form-control">
                    <option ng-repeat="view in list_views"
                            ng-selected="{{view == config.list_view}}"
                            value="{{view}}">
                        {{view}}
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">Detalle</span>
                <select ng-model="config.detail_view" class="form-control">
                    <option ng-repeat="view in detail_views"
                            ng-selected="{{view == config.detail_view}}"
                            value="{{view}}">
                        {{view}}
                    </option>
                </select>
            </div>
        </div>

        <hr class="full-width">
        <div class="form-heading">Contenido</div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">Orden</span>
                <select ng-model="config.order" class="form-control">
                    <option ng-selected="{{'manual' == config.order}}" value="manual">Manual</option>
                    <option ng-selected="{{'date_asc' == config.order}}" value="date_asc">Fecha Ascendente</option>
                    <option ng-selected="{{'date_desc' == config.order}}" value="date_desc">Fecha Descendente</option>
                </select>
            </div>
        </div>

        <div class="checkbox">
            <label>
                <input ng-model="config.pagination" type="checkbox" >
                <span class="text">Paginar listado</span>
            </label>
        </div>

        <div class="form-group" ng-show="config.pagination">
            <div class="input-group">
                <span class="input-group-addon">Cantidad paginado</span>
                <input class="form-control" type="number" ng-model="config.quantity">
            </div>
        </div>

    </div>

    <div class="panel-footer panel-controls">
        <div class="btn-group btn-group btn-group-justified">
            <a ng-click="save()" class="btn btn-info"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</a>
            <a ng-click="saveAndClose()" class="btn btn-warning"><i class="fa fa-check" aria-hidden="true"></i> Guardar y Cerrar</a>
        </div>
    </div>

</div>