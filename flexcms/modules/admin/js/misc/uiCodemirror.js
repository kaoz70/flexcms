/**
 * Binds a CodeMirror widget to a <textarea> element.
 */
angular.module('app')
    .constant('uiCodemirrorConfig', {})
    .directive('uiCodemirror', function ($timeout, uiCodemirrorConfig) {

        function configOptionsWatcher(codemirrot, uiCodemirrorAttr, scope) {
            if (!uiCodemirrorAttr) {
                return;
            }

            const codemirrorDefaultsKeys = Object.keys(window.CodeMirror.defaults);

            function updateOptions(newValues, oldValue) {
                if (!angular.isObject(newValues)) {
                    return;
                }
                codemirrorDefaultsKeys.forEach((key) => {
                    if (newValues.hasOwnProperty(key)) {
                        if (oldValue && newValues[key] === oldValue[key]) {
                            return;
                        }

                        codemirrot.setOption(key, newValues[key]);
                    }
                });
            }

            scope.$watch(uiCodemirrorAttr, updateOptions, true);
        }

        function newCodemirrorEditor(iElement, codemirrorOptions) {
            let codemirrot;

            if (iElement[0].tagName === 'TEXTAREA') {
                // Might bug but still ...
                codemirrot = window.CodeMirror.fromTextArea(iElement[0], codemirrorOptions);
            } else {
                iElement.html('');
                codemirrot = new window.CodeMirror((cmEl) => {
                    iElement.append(cmEl);
                }, codemirrorOptions);
            }

            return codemirrot;
        }

        function configNgModelLink(codemirror, ngModel, scope) {
            if (!ngModel) {
                return;
            }
            // CodeMirror expects a string, so make sure it gets one.
            // This does not change the model.
            ngModel.$formatters.push((value) => {
                if (angular.isUndefined(value) || value === null) {
                    return '';
                } else if (angular.isObject(value) || angular.isArray(value)) {
                    throw new Error('ui-codemirror cannot use an object or an array as a model');
                }
                return value;
            });

            // Override the ngModelController $render method, which is what gets called when the model is updated.
            // This takes care of the synchronizing the codeMirror element with the
            // underlying model, in the case that it is changed by something else.
            ngModel.$render = () => {
                // Code mirror expects a string so make sure it gets one
                // Although the formatter have already done this, it can be possible that another
                // formatter returns undefined (for example the required directive)
                const safeViewValue = ngModel.$viewValue || '';
                codemirror.setValue(safeViewValue);
            };

            // Keep the ngModel in sync with changes from CodeMirror
            codemirror.on('change', (instance) => {
                const newValue = instance.getValue();
                if (newValue !== ngModel.$viewValue) {
                    scope.$evalAsync(() => {
                        ngModel.$setViewValue(newValue);
                    });
                }
            });
        }

        function configUiRefreshAttribute(codeMirror, uiRefreshAttr, scope) {
            if (!uiRefreshAttr) {
                return;
            }

            scope.$watch(uiRefreshAttr, (newVal, oldVal) => {
                // Skip the initial watch firing
                if (newVal !== oldVal) {
                    $timeout(() => {
                        codeMirror.refresh();
                    });
                }
            });
        }

        function postLink(scope, iElement, iAttrs, ngModel) {

            const codemirrorOptions = angular.extend(
                { value: iElement.text() },
                uiCodemirrorConfig.codemirror || {},
                scope.$eval(iAttrs.uiCodemirror),
                scope.$eval(iAttrs.uiCodemirrorOpts)
            );

            const codemirror = newCodemirrorEditor(iElement, codemirrorOptions);

            configOptionsWatcher(
                codemirror,
                iAttrs.uiCodemirror || iAttrs.uiCodemirrorOpts,
                scope
            );

            configNgModelLink(codemirror, ngModel, scope);

            configUiRefreshAttribute(codemirror, iAttrs.uiRefresh, scope);

            // Allow access to the CodeMirror instance through a broadcasted event
            // eg: $broadcast('CodeMirror', function(cm){...});
            scope.$on('CodeMirror', (event, callback) => {
                if (angular.isFunction(callback)) {
                    callback(codemirror);
                } else {
                    throw new Error('the CodeMirror event requires a callback function');
                }
            });

            // onLoad callback
            if (angular.isFunction(codemirrorOptions.onLoad)) {
                codemirrorOptions.onLoad(codemirror);
            }
        }

        return {
            require: '?ngModel',
            compile() {
                // Require CodeMirror
                if (angular.isUndefined(window.CodeMirror)) {
                    throw new Error('ui-codemirror needs CodeMirror to work... (o rly?)');
                }

                return postLink;
            },
        };
    });
