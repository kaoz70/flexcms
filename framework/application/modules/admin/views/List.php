<div class="panel panel-primary small-width">
    <div class="panel-heading">
        <h3 class="panel-title">{{title}}<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3>
        <div class="panel-tools">
            <panel-dispose></panel-dispose>
        </div>
    </div>
    <div class="panel-body">

        <ul dnd-list="records">

            <li ng-repeat="row in records"
                dnd-draggable="row"
                dnd-moved="records.splice($index, 1)"
                dnd-effect-allowed="move"
                dnd-selected="selected = row"
                dnd-dragend="onSortEnd()"
                ng-class="{'selected': selected === row}"
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