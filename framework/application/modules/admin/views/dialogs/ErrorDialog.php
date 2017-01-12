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
                    <div><i class="pe-7s-attention"></i></div>
                    <div>{{status}}</div>
                </div>
                <h2>{{message}} [{{status}}]</h2>
                <div ng-show="detail">Detalle: <span ng-bind-html="detail"></span></div>
                <div ng-show="file">Archivo: {{file}}</div>
                <div ng-show="line">Linea: {{line}}</span></div>
            </div>
        </md-dialog-content>

        <md-dialog-actions layout="row">
            <md-button ng-show="showNotificationButton" ng-click="notify()">Notificar este error</md-button>
            <md-button ng-click="close()">Cerrar</md-button>
        </md-dialog-actions>
    </form>
</md-dialog>