<md-dialog aria-label="Alerta">
    <form ng-cloak>
        <md-toolbar class="md-warn">
            <div class="md-toolbar-tools">
                <span flex></span>
                <md-button class="md-icon-button" ng-click="close()">
                    <md-icon aria-label="Close dialog">close</md-icon>
                </md-button>
            </div>
        </md-toolbar>

        <md-dialog-content>
            <div class="md-dialog-content">
                <div class="icon" md-colors="{backgroundColor: 'default-warn'}">
                    <i class="pe-7s-attention"></i>
                </div>
                <h2 ng-bind-html="message"></h2>
            </div>
        </md-dialog-content>

        <md-dialog-actions layout="row">
            <md-button ng-click="close()">Cerrar</md-button>
        </md-dialog-actions>
    </form>
</md-dialog>