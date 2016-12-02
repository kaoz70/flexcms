<md-dialog aria-label="Alerta">
    <form ng-cloak>
        <md-toolbar class="md-warn">
            <div class="md-toolbar-tools">
                <h2><i class="pe-7s-attention"></i> Alerta</h2>
                <span flex></span>
                <md-button class="md-icon-button" ng-click="cancel()">
                    <md-icon aria-label="Close dialog">close</md-icon>
                </md-button>
            </div>
        </md-toolbar>

        <md-dialog-content>
            <div class="md-dialog-content">
                {{message}}
            </div>
        </md-dialog-content>

        <md-dialog-actions layout="row">
            <md-button ng-click="cancel()">Cancelar</md-button>
            <md-button ng-click="delete()">Eliminar</md-button>
        </md-dialog-actions>
    </form>
</md-dialog>