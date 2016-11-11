<div class="panel panel-primary small-width">
    <div class="panel-heading">
        <h3 class="panel-title">{{language.name}}<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3>
        <div class="panel-tools">
            <panel-dispose></panel-dispose>
        </div>
    </div>
    <div class="panel-body">

        <form class="ng-pristine ng-valid">
            <div class="form-heading">
                General
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Nombre</span>
                    <input type="text" class="form-control bg-default" ng-model="language.name">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">URL</span>
                    <input type="text" class="form-control bg-default" ng-model="language.slug">
                </div>
            </div>
        </form>

    </div>

    <div class="panel-controls">
        <div class="btn-group btn-group btn-group-justified">
            <a ng-click="save()" class="btn btn-info"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</a>
            <a ng-click="saveAndClose()" class="btn btn-warning"><i class="fa fa-check" aria-hidden="true"></i> Guardar y Cerrar</a>
        </div>
    </div>

</div>