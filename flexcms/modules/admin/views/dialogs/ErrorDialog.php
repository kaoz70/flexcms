<md-dialog aria-label="Alerta">
    <form ng-cloak>
        <md-toolbar class="md-error">
            <div class="md-toolbar-tools" layout-align="center center">
                <i class="pe-7s-shield"></i>
            </div>
        </md-toolbar>

        <md-dialog-content>
            <div class="md-dialog-content">
                <h1><span ng-bind-html="message"></span> <span ng-show="status">[{{status}}]</span></h1>
                <div ng-show="detail">Detalle: <span ng-bind-html="detail"></span></div>
                <div ng-show="file">Archivo: {{file}}</div>
                <div ng-show="line">Linea: {{line}}</span></div>
            </div>
        </md-dialog-content>

        <md-dialog-actions class="md-error" layout="row" layout-align="center">
            <md-button ng-hide="notified"
                       ng-show="showNotificationButton"
                       ng-disabled="notified"
                       class="md-focused"
                       ng-click="notify()">Notificar este error</md-button>
            <md-button class="md-warn md-raised" ng-click="close()">Cerrar</md-button>
        </md-dialog-actions>
    </form>
</md-dialog>