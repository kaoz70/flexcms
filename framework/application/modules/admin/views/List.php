<div class="panel panel-primary small-width">
    <div class="panel-heading">

        <div>
            <h3 class="panel-title">{{title}}<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3>
            <div class="panel-tools">
                <panel-dispose></panel-dispose>
            </div>
        </div>

        <div class="search form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                <input ng-model="query" class="form-control" />
            </div>
        </div>

    </div>
    <div class="panel-body">

        <ul class="list-group" dnd-list="records">

            <li ng-repeat="row in records | filter:query"
                dnd-draggable="row"
                dnd-moved="records.splice($index, 1)"
                dnd-effect-allowed="move"
                dnd-selected="selected = row"
                dnd-dragend="onSortEnd()"
                ng-class="{'selected': selected === row}"
                class="list-group-item"
            >
                <a ng-href="#/{{section}}/edit/{{row.id}}">{{row.name ? row.name : row.translation.name}}</a>
                <a class="pull-right"><list-item-delete item="row" /></a>
            </li>
        </ul>

    </div>
    <div class="panel-footer">
        <ul class="menu">
            <li ng-repeat="item in menu">
                <a class="btn btn-default btn-block" ng-href="#/{{item.url}}" ng-bind-html="item.title"></a>
            </li>
        </ul>
    </div>
</div>