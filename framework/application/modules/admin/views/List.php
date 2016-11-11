<div class="panel panel-primary small-width">
    <div class="panel-heading">
        <h3 class="panel-title">{{title}}<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3>
        <div class="panel-tools">
            <panel-dispose></panel-dispose>
        </div>
    </div>
    <div class="panel-body">

        <div class="dd dd-darker">
            <ul class="dd-list">
                <li class="dd-item" ng-repeat="row in records">
                    <div class="dd-handle">
                         <a ng-href="#/{{section}}/edit/{{row.id}}">{{row.name ? row.name : row.translation.name}}</a>
                        <a class="pull-right"><list-item-delete /></a>
                    </div>
                </li>
            </ul>
        </div>

    </div>
    <div class="panel-footer">
        <ul class="menu">
            <li ng-repeat="item in menu">
                <a class="btn btn-default" ng-href="#/{{item.url}}">{{item.title}}</a>
            </li>
        </ul>
    </div>
</div>