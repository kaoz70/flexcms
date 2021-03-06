<md-dialog aria-label="Alerta">
    <form ng-cloak>
        <md-toolbar class="md-warn">
            <div class="md-toolbar-tools" layout-align="center center">
                <i class="pe-7s-attention"></i>
            </div>
        </md-toolbar>

        <md-dialog-content>
            <div class="md-dialog-content">
                <h1>Alerta</h1>
                <div ng-bind-html="message"></div>
            </div>
        </md-dialog-content>

        <md-dialog-actions class="md-warn" layout="row" layout-align="center">
            <md-button md-autofocus ng-click="cancel()">Cancelar</md-button>
            <md-button class="md-warn md-raised" ng-click="delete()">Eliminar</md-button>
        </md-dialog-actions>
    </form>
</md-dialog>