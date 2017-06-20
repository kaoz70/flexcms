/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('LayoutService', function ($mdDialog, $mdSidenav) {
        this.rows = [];

        this.init = (rows) => {
            this.rows = rows;
            return this;
        };

        const addColumn = (columns) => {
            return {
                class: '',
                span: {
                    large: 12 / columns,
                    medium: 12 / columns,
                    small: 12 / columns,
                },
                offset: {
                    large: 0,
                    medium: 0,
                    small: 0,
                },
                push: {
                    large: 0,
                    medium: 0,
                    small: 0,
                },
                pull: {
                    large: 0,
                    medium: 0,
                    small: 0,
                },
                widgets: [],
            };
        };

        function buildToggler(componentId) {
            return () => {
                $mdSidenav(componentId).toggle();
            };
        }

        this.toggleRight = buildToggler('right');

        this.addRow = (columnAmount) => {
            const cols = [];
            for (let i = 0; i < columnAmount; i++) {
                cols.push(addColumn(columnAmount));
            }

            this.rows.push({
                class: '',
                columns: cols,
                expanded: false,
            });
        };

        this.deleteRow = (ev, index) => {
            const confirm = $mdDialog.confirm()
                .title('Quiere eliminar esta fila?')
                .textContent('Se eliminaran todas las columnas y widgets.')
                .ariaLabel('Eliminar fila')
                .targetEvent(ev)
                .ok('Eliminar')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(() => {
                this.rows.splice(index, 1);
            });
        };

        this.deleteColumn = (ev, index, columns) => {
            const confirm = $mdDialog.confirm()
                .title('Quiere eliminar esta columna?')
                .textContent('Se eliminaran todos los widgets.')
                .ariaLabel('Eliminar columna')
                .targetEvent(ev)
                .ok('Eliminar')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(() => {
                columns.splice(index, 1);
            });
        };

        this.addColumn = (ev, row) => {
            const confirm = $mdDialog.confirm()
                .title('Añadir nueva columna')
                .textContent('Desea añadir una nueva columna?')
                .ariaLabel('Añadir columna')
                .targetEvent(ev)
                .ok('Añadir')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(() => {
                row.columns.push(addColumn(1));
            });
        };
    });
