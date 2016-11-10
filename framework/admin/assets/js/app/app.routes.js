(function() {
    'use strict';



    angular
        .module("app")
        .config(routeConfig);

    function routeConfig($routeSegmentProvider, $routeProvider) {
        //$urlRouterProvider.otherwise("/dashboard/multiplebox");

        $routeSegmentProvider.options.autoLoadTemplates = true;
        //$routeProvider.otherwise({redirectTo: '/language'});

        /*$routeSegmentProvider
            .when('/language', 'language')
            .when('/language/edit/:id', 'language.edit')
            .segment('language', {
                'default': true,
                templateUrl: basePath + 'views/List.html',
                controller: 'LanguageCtrl',
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    'framework/admin/assets/js/app/controllers/LanguageCtrl.js',
                                    'framework/admin/assets/js/app/services/Language.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .within()
                .segment('edit', {
                    templateUrl: basePath + 'views/language/Detail.html',
                    controller: 'LanguageEditCtrl',
                    dependencies: ['id']
                });*/

        $routeProvider.otherwise({redirectTo: '/'});

        /*$stateProvider.state('dashboard', {
                abstract: true,
                template: "<ui-view/>",
                ncyBreadcrumb: {
                    label: 'Dashboards'
                },
                data: {
                    icon: 'pe-7s-monitor',
                },
            })
            .state('dashboard.multiplebox', {
                url: "/dashboard/multiplebox",
                templateUrl: $basePath + 'views/DashboardMultipleBox.html',
                ncyBreadcrumb: {
                    label: 'Simple'
                },
                data: {
                    icon: 'pe-7s-box2',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/easypiechart/jquery.easypiechart.min.js',
                                    $assetBasePath + 'js/lib/easypiechart/easypiechart-init.js',
                                    $assetBasePath + 'js/lib/sparkline/jquery.sparkline.min.js',
                                    $assetBasePath + 'js/lib/sparkline/sparkline-init.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.tooltip.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.resize.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.crosshair.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.stack.min.js',
                                    $assetBasePath + 'js/lib/flot/curvedLines.min.js',
                                    $assetBasePath + 'js/lib/datatables/jquery.dataTables.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.responsive.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.bootstrap.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.fixedHeader.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.fixedColumns.min.js',
                                    $assetBasePath + 'js/pages/dashboard-multiplebox.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('dashboard.minimal', {
                url: "/dashboard/minimal",
                templateUrl: $basePath + 'views/DashboardMinimal.html',
                ncyBreadcrumb: {
                    label: 'Minimal'
                },
                data: {
                    icon: 'pe-7s-graph1',
                    hasFullContainer: true,
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/easypiechart/jquery.easypiechart.min.js',
                                    $assetBasePath + 'js/lib/easypiechart/easypiechart-init.js',
                                    $assetBasePath + 'js/lib/sparkline/jquery.sparkline.min.js',
                                    $assetBasePath + 'js/lib/sparkline/sparkline-init.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.orderBars.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.tooltip.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.resize.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.selection.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.crosshair.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.stack.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.time.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.pie.min.js',
                                    $assetBasePath + 'js/lib/flot/curvedLines.min.js',
                                    $assetBasePath + 'js/lib/echarts/echarts-all.js',
                                    $assetBasePath + 'js/lib/echarts/theme.js',
                                    $assetBasePath + 'js/pages/dashboard-minimal.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('dashboard.realtime', {
                url: "/dashboard/realtime",
                templateUrl: $basePath + 'views/DashboardRealtime.html',
                ncyBreadcrumb: {
                    label: 'Real-time'
                },
                data: {
                    icon: 'pe-7s-refresh-2',
                    hasFullContainer: true,
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/flot/jquery.flot.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.tooltip.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.resize.min.js',
                                    $assetBasePath + 'js/pages/dashboard-realtime.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('dashboard.multipleBoxContrast', {
                url: "/dashboard/multipleBoxContrast",
                templateUrl: $basePath + 'views/DashboardMultipleBoxContrast.html',
                ncyBreadcrumb: {
                    label: 'High Contrast'
                },
                data: {
                    icon: 'pe-7s-sun',
                    hasFullContainer: true,
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/easypiechart/jquery.easypiechart.min.js',
                                    $assetBasePath + 'js/lib/easypiechart/easypiechart-init.js',
                                    $assetBasePath + 'js/lib/sparkline/jquery.sparkline.min.js',
                                    $assetBasePath + 'js/lib/sparkline/sparkline-init.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.tooltip.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.resize.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.crosshair.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.stack.min.js',
                                    $assetBasePath + 'js/lib/flot/curvedLines.min.js',
                                    $assetBasePath + 'js/lib/jqvmap/jquery.vmap.min.js',
                                    $assetBasePath + 'js/lib/jqvmap/maps/jquery.vmap.usa.js',
                                    $assetBasePath + 'js/pages/dashboard-multiplebox-contrast.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('widgets', {
                abstract: true,
                template: '<ui-view/>',
                ncyBreadcrumb: {
                    label: 'Widgets'
                },
                data: {
                    icon: 'pe-7s-box2',
                },
            })
            .state('widgets.boxes', {
                url: "/widgets/boxes",
                templateUrl: $basePath + 'views/WidgetBox.html',
                ncyBreadcrumb: {
                    label: 'Boxes'
                },
                data: {
                    icon: 'fa fa-bar-chart',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/easypiechart/jquery.easypiechart.min.js',
                                    $assetBasePath + 'js/lib/easypiechart/easypiechart-init.js',
                                    $assetBasePath + 'js/lib/sparkline/jquery.sparkline.min.js',
                                    $assetBasePath + 'js/lib/sparkline/sparkline-init.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.orderBars.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.tooltip.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.resize.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.selection.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.crosshair.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.stack.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.time.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.pie.min.js',
                                    $assetBasePath + 'js/pages/widget-box.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('widgets.panel', {
                url: "/widgets/panel",
                templateUrl: $basePath + 'views/WidgetPanel.html',
                controller: "WidgetsPanelController",
                ncyBreadcrumb: {
                    label: 'Panels'
                },
                data: {
                    icon: 'pe-7s-box2',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    {
                                        type: 'js',
                                        path: '//maps.google.com/maps/api/js?sensor=true&callback=initialize'
                                    },
                                    $assetBasePath + 'js/lib/gmaps/gmaps.min.js',
                                    $assetBasePath + 'js/pages/widget-panel.js',
                                    $assetBasePath + 'js/app/controllers/widgets.panel.controller.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('widgets.complex', {
                url: "/widgets/complex",
                templateUrl: $basePath + 'views/WidgetComplex.html',
                ncyBreadcrumb: {
                    label: 'Complex Widgets'
                },
                data: {
                    icon: 'fa fa-line-chart',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/easypiechart/jquery.easypiechart.min.js',
                                    $assetBasePath + 'js/lib/easypiechart/easypiechart-init.js',
                                    $assetBasePath + 'js/lib/sparkline/jquery.sparkline.min.js',
                                    $assetBasePath + 'js/lib/sparkline/sparkline-init.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.orderBars.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.tooltip.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.resize.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.selection.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.crosshair.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.stack.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.time.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.pie.min.js',
                                    $assetBasePath + 'js/lib/flot/curvedLines.min.js',
                                    $assetBasePath + 'js/pages/widget-complex.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('inbox', {
                url: "/inbox",
                templateUrl: $basePath + 'views/Inbox.html',
                ncyBreadcrumb: {
                    label: 'Mail'
                },
                data: {
                    icon: 'pe-7s-mail"',
                    hasFullContainer: true,
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/summernote/summernote.min.js',
                                    $assetBasePath + 'js/pages/inbox.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('calendar', {
                url: "/calendar",
                templateUrl: $basePath + 'views/Calendar.html',
                ncyBreadcrumb: {
                    label: 'Calendar'
                },
                data: {
                    icon: 'pe-7s-date',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/fullcalendar/moment.min.js',
                                    $assetBasePath + 'js/lib/fullcalendar/jquery-ui.custom.min.js',
                                    $assetBasePath + 'js/lib/fullcalendar/fullcalendar.min.js',
                                    $assetBasePath + 'js/lib/mini-calendar/mini-calendar.min.js',
                                    $assetBasePath + 'js/pages/calendar.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('profile', {
                url: "/profile",
                templateUrl: $basePath + 'views/Profile.html',
                controller: "ProfileController",
                ncyBreadcrumb: {
                    label: 'Profile'
                },
                data: {
                    icon: 'pe-7s-id',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    {
                                        type: 'js',
                                        path: '//maps.google.com/maps/api/js?sensor=true&callback=initialize'
                                    },
                                    $assetBasePath + 'js/lib/gmaps/gmaps.min.js',
                                    $assetBasePath + 'js/pages/profile.js',
                                    $assetBasePath + 'js/app/controllers/profile.controller.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('charts', {
                abstract: true,
                template: '<ui-view/>',
                ncyBreadcrumb: {
                    label: 'Charts'
                },
                data: {
                    icon: 'pe-7s-graph1',
                },
            })
            .state('charts.flotChart', {
                url: "/charts/flotChart",
                templateUrl: $basePath + 'views/FlotChart.html',
                ncyBreadcrumb: {
                    label: 'Flot Charts'
                },
                data: {
                    icon: 'fa fa-bar-chart',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/flot/jquery.flot.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.orderBars.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.tooltip.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.resize.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.selection.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.crosshair.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.stack.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.time.min.js',
                                    $assetBasePath + 'js/lib/flot/jquery.flot.pie.min.js',
                                    $assetBasePath + 'js/pages/flotchart.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('charts.chartist', {
                url: "/charts/chartist",
                templateUrl: $basePath + 'views/Chartist.html',
                ncyBreadcrumb: {
                    label: 'Chartist'
                },
                data: {
                    icon: 'pe-7s-graph2',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/chartist/chartist.min.js',
                                    $assetBasePath + 'js/lib/chartist/chartist-plugin-tooltip.min.js',
                                    $assetBasePath + 'js/pages/chartist.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('charts.chartJs', {
                url: "/charts/chartJs",
                templateUrl: $basePath + 'views/ChartJs.html',
                ncyBreadcrumb: {
                    label: 'Chart.js'
                },
                data: {
                    icon: 'fa fa-line-chart',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/chartjs/Chart.min.js',
                                    $assetBasePath + 'js/pages/chartjs.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('charts.sparkline', {
                url: "/charts/sparkline",
                templateUrl: $basePath + 'views/Sparkline.html',
                ncyBreadcrumb: {
                    label: 'Sparkline Charts'
                },
                data: {
                    icon: 'fa fa-area-chart',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/sparkline/jquery.sparkline.min.js',
                                    $assetBasePath + 'js/lib/sparkline/sparkline-init.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('charts.easyPieChart', {
                url: "/charts/easyPieChart",
                templateUrl: $basePath + 'views/EasyPieChart.html',
                ncyBreadcrumb: {
                    label: 'Easy Pie Chart'
                },
                data: {
                    icon: 'pe-7s-graph',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/easypiechart/jquery.easypiechart.min.js',
                                    $assetBasePath + 'js/lib/easypiechart/easypiechart-init.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('charts.eCharts', {
                url: "/charts/eCharts",
                templateUrl: $basePath + 'views/ECharts.html',
                ncyBreadcrumb: {
                    label: 'E-Charts'
                },
                data: {
                    icon: 'pe-7s-graph3',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/echarts/echarts-all.js',
                                    $assetBasePath + 'js/lib/echarts/theme.js',
                                    $assetBasePath + 'js/pages/e-charts.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('uiElements', {
                abstract: true,
                template: '<ui-view/>',
                ncyBreadcrumb: {
                    label: 'UI Elements'
                },
                data: {
                    icon: 'pe-7s-paint-bucket',
                },
            })
            .state('uiElements.elements', {
                url: "/uiElements/elements",
                templateUrl: $basePath + 'views/Elements.html',
                ncyBreadcrumb: {
                    label: 'Basic Elements'
                },
                data: {
                    icon: 'pe-7s-ticket',
                },
            })
            .state('uiElements.buttons', {
                url: "/uiElements/buttons",
                templateUrl: $basePath + 'views/Buttons.html',
                ncyBreadcrumb: {
                    label: 'Buttons'
                },
                data: {
                    icon: 'pe-7s-plus',
                },
            })
            .state('uiElements.notifications', {
                url: "/uiElements/notifications",
                templateUrl: $basePath + 'views/Notifications.html',
                ncyBreadcrumb: {
                    label: 'Notifications'
                },
                data: {
                    icon: 'pe-7s-bell',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/notifications/snap.svg-min.js',
                                    $assetBasePath + 'js/lib/notifications/notificationFx.js',
                                    $assetBasePath + 'js/pages/notifications.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('uiElements.strokeIcons', {
                url: "/uiElements/strokeIcons",
                templateUrl: $basePath + 'views/StrokeIcons.html',
                ncyBreadcrumb: {
                    label: '7 Stroke Icons'
                },
                data: {
                    icon: 'pe-7s-arc',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/pages/icons.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('uiElements.fontAwesome', {
                url: "/uiElements/fontAwesome",
                templateUrl: $basePath + 'views/FontAwesome.html',
                ncyBreadcrumb: {
                    label: 'FontAwesome Icons'
                },
                data: {
                    icon: 'fa fa-rocket',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/pages/icons.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('uiElements.glyphicons', {
                url: "/uiElements/glyphicons",
                templateUrl: $basePath + 'views/Glyphicons.html',
                ncyBreadcrumb: {
                    label: 'Glyphicons'
                },
                data: {
                    icon: 'glyphicon glyphicon-apple',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/pages/icons.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('uiElements.panels', {
                url: "/uiElements/panels",
                templateUrl: $basePath + 'views/Panels.html',
                ncyBreadcrumb: {
                    label: 'Panels & Accordions'
                },
                data: {
                    icon: 'pe-7s-browser',
                },
            })
            .state('uiElements.tabs', {
                url: "/uiElements/tabs",
                templateUrl: $basePath + 'views/Tabs.html',
                ncyBreadcrumb: {
                    label: 'Tabs'
                },
                data: {
                    icon: 'pe-7s-folder',
                },
            })
            .state('uiElements.modals', {
                url: "/uiElements/modals",
                templateUrl: $basePath + 'views/Modals.html',
                ncyBreadcrumb: {
                    label: 'Modals'
                },
                data: {
                    icon: 'pe-7s-chat',
                },
            })
            .state('uiElements.nestableList', {
                url: "/uiElements/nestableList",
                templateUrl: $basePath + 'views/NestableList.html',
                ncyBreadcrumb: {
                    label: 'Nestable Lists'
                },
                data: {
                    icon: 'pe-7s-menu',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/nestable/jquery.nestable.min.js',
                                    $assetBasePath + 'js/pages/nestablelist.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('uiElements.tree', {
                url: "/uiElements/tree",
                templateUrl: $basePath + 'views/Tree.html',
                ncyBreadcrumb: {
                    label: 'Tree'
                },
                data: {
                    icon: 'pe-7s-plus',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/tree/tree.min.js',
                                    $assetBasePath + 'js/pages/tree.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('forms', {
                abstract: true,
                template: '<ui-view/>',
                ncyBreadcrumb: {
                    label: 'Forms'
                },
                data: {
                    icon: 'pe-7s-note',
                },
            })
            .state('forms.formInputs', {
                url: "/forms/formInputs",
                templateUrl: $basePath + 'views/FormInputs.html',
                ncyBreadcrumb: {
                    label: 'Form Inputs'
                },
                data: {
                    icon: 'pe-7s-ticket',
                },
            })
            .state('forms.formAdvancedInputs', {
                url: "/forms/formAdvancedInputs",
                templateUrl: $basePath + 'views/FormAdvancedInputs.html',
                ncyBreadcrumb: {
                    label: 'Advanced Inputs'
                },
                data: {
                    icon: 'pe-7s-plus',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/select2/select2.full.min.js',
                                    $assetBasePath + 'js/lib/tagsinput/bootstrap-tagsinput.min.js',
                                    $assetBasePath + 'js/lib/datepicker/bootstrap-datepicker.min.js',
                                    $assetBasePath + 'js/lib/timepicker/bootstrap-timepicker.min.js',
                                    $assetBasePath + 'js/lib/moment/moment.min.js',
                                    $assetBasePath + 'js/lib/daterangepicker/daterangepicker.min.js',
                                    $assetBasePath + 'js/lib/autosize/jquery.autosize.min.js',
                                    $assetBasePath + 'js/lib/spinbox/spinbox.min.js',
                                    $assetBasePath + 'js/lib/knob/jquery.knob.min.js',
                                    $assetBasePath + 'js/lib/colorpicker/jquery.minicolors.min.js',
                                    $assetBasePath + 'js/lib/slider/ion.rangeSlider.min.js',
                                    $assetBasePath + 'js/lib/dropzone/dropzone.min.js',
                                    $assetBasePath + 'js/lib/rating/jquery.rateit.min.js',
                                    $assetBasePath + 'js/lib/mockjax/jquery.mockjax.min.js',
                                    $assetBasePath + 'js/lib/xeditable/bootstrap-editable.min.js',
                                    $assetBasePath + 'js/pages/formadvancedinputs.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('forms.formInputMask', {
                url: "/forms/formInputMask",
                templateUrl: $basePath + 'views/FormInputMask.html',
                ncyBreadcrumb: {
                    label: 'Input Masks'
                },
                data: {
                    icon: 'pe-7s-bell',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/inputmask/jasny-bootstrap.min.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('forms.formEditors', {
                url: "/forms/formEditors",
                templateUrl: $basePath + 'views/FormEditors.html',
                ncyBreadcrumb: {
                    label: 'Editors'
                },
                data: {
                    icon: 'pe-7s-edit',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/summernote/summernote.min.js',
                                    $assetBasePath + 'js/lib/ckeditor/ckeditor.js',
                                    $assetBasePath + 'js/pages/formeditors.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('forms.formLayouts', {
                url: "/forms/formLayouts",
                templateUrl: $basePath + 'views/FormLayouts.html',
                ncyBreadcrumb: {
                    label: 'Form Layouts'
                },
                data: {
                    icon: 'pe-7s-photo-gallery',
                },
            })
            .state('forms.formValidation', {
                url: "/forms/formValidation",
                templateUrl: $basePath + 'views/FormValidation.html',
                ncyBreadcrumb: {
                    label: 'Form Validation'
                },
                data: {
                    icon: 'pe-7s-shield',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/validation/jquery.validate.min.js',
                                    $assetBasePath + 'js/lib/validation/jquery.validate.defaults.js',
                                    $assetBasePath + 'js/pages/formvalidation.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('forms.formWizard', {
                url: "/forms/formWizard",
                templateUrl: $basePath + 'views/FormWizard.html',
                ncyBreadcrumb: {
                    label: 'Form Wizard'
                },
                data: {
                    icon: 'pe-7s-play',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/wizard/wizard.min.js',
                                    $assetBasePath + 'js/pages/formwizard.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('tables', {
                abstract: true,
                template: '<ui-view/>',
                ncyBreadcrumb: {
                    label: 'Tables'
                },
                data: {
                    icon: 'pe-7s-keypad',
                },
            })
            .state('tables.tables', {
                url: "/tables/tables",
                templateUrl: $basePath + 'views/Tables.html',
                ncyBreadcrumb: {
                    label: 'Tables Styles'
                },
                data: {
                    icon: 'pe-7s-paint-bucket',
                },
            })
            .state('tables.responsiveTables', {
                url: "/tables/responsiveTables",
                templateUrl: $basePath + 'views/ResponsiveTables.html',
                ncyBreadcrumb: {
                    label: 'Responsive Tables'
                },
                data: {
                    icon: 'pe-7s-exapnd2',
                },
            })
            .state('tables.datatablesInit', {
                url: "/tables/datatablesInit",
                templateUrl: $basePath + 'views/DatatablesInit.html',
                ncyBreadcrumb: {
                    label: 'Layouts'
                },
                data: {
                    icon: 'pe-7s-display2',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/datatables/jquery.dataTables.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.responsive.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.bootstrap.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.fixedHeader.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.fixedColumns.min.js',
                                    $assetBasePath + 'js/pages/datatables-init.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('tables.datatablesSearch', {
                url: "/tables/datatablesSearch",
                templateUrl: $basePath + 'views/DatatablesSearch.html',
                ncyBreadcrumb: {
                    label: 'Search'
                },
                data: {
                    icon: 'pe-7s-search',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/datatables/jquery.dataTables.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.bootstrap.min.js',
                                    $assetBasePath + 'js/pages/datatables-search.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('tables.datatablesExport', {
                url: "/tables/datatablesExport",
                templateUrl: $basePath + 'views/DatatablesExport.html',
                ncyBreadcrumb: {
                    label: 'Export and Print'
                },
                data: {
                    icon: 'pe-7s-print',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/datatables/jquery.dataTables.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.tableTools.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.bootstrap.min.js',
                                    $assetBasePath + 'js/pages/datatables-export.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('tables.datatablesCrud', {
                url: "/tables/datatablesCrud",
                templateUrl: $basePath + 'views/DatatablesCrud.html',
                ncyBreadcrumb: {
                    label: 'Data Manipulation'
                },
                data: {
                    icon: 'pe-7s-pen',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/datatables/jquery.dataTables.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.tableTools.min.js',
                                    $assetBasePath + 'js/lib/datatables/dataTables.bootstrap.min.js',
                                    $assetBasePath + 'js/pages/datatables-crud.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('maps', {
                abstract: true,
                template: '<ui-view/>',
                ncyBreadcrumb: {
                    label: 'Maps'
                },
                data: {
                    icon: 'pe-7s-map-marker',
                },
            })
            .state('maps.gMaps', {
                url: "/maps/gMaps",
                templateUrl: $basePath + 'views/GMaps.html',
                controller: "GMapsController",
                ncyBreadcrumb: {
                    label: 'GMaps'
                },
                data: {
                    icon: 'pe-7s-map-2',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    {
                                        type: 'js',
                                        path: '//maps.google.com/maps/api/js?sensor=true&callback=initialize'
                                    },
                                    $assetBasePath + 'js/lib/gmaps/gmaps.min.js',
                                    $assetBasePath + 'js/pages/gmaps.js',
                                    $assetBasePath + 'js/app/controllers/gmaps.controller.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('maps.jqvMap', {
                url: "/maps/jqvMap",
                templateUrl: $basePath + 'views/JqvMap.html',
                ncyBreadcrumb: {
                    label: 'JQV Map'
                },
                data: {
                    icon: 'pe-7s-world',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/lib/jqvmap/jquery.vmap.min.js',
                                    $assetBasePath + 'js/lib/jqvmap/maps/jquery.vmap.germany.js',
                                    $assetBasePath + 'js/lib/jqvmap/maps/jquery.vmap.europe.js',
                                    $assetBasePath + 'js/lib/jqvmap/maps/jquery.vmap.usa.js',
                                    $assetBasePath + 'js/lib/jqvmap/maps/jquery.vmap.russia.js',
                                    $assetBasePath + 'js/lib/jqvmap/maps/jquery.vmap.world.js',
                                    $assetBasePath + 'js/lib/jqvmap/maps/continents/jquery.vmap.africa.js',
                                    $assetBasePath + 'js/lib/jqvmap/maps/data/jquery.vmap.sampledata.js',
                                    $assetBasePath + 'js/pages/jqvmaps.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('pages', {
                abstract: true,
                template: '<ui-view/>',
                ncyBreadcrumb: {
                    label: 'Pages'
                },
                data: {
                    icon: 'pe-7s-display1',
                },
            })
            .state('pages.timeline', {
                url: "/pages/timeline",
                templateUrl: $basePath + 'views/Timeline.html',
                ncyBreadcrumb: {
                    label: 'Timeline'
                },
                data: {
                    icon: 'pe-7s-clock',
                },
            })
            .state('pages.error500', {
                url: "/pages/error500",
                templateUrl: $basePath + 'views/Error500.html',
                ncyBreadcrumb: {
                    label: '500'
                },
                data: {
                    icon: 'pe-7s-server',
                },
            })
            .state('pages.error404', {
                url: "/pages/error404",
                templateUrl: $basePath + 'views/Error404.html',
                ncyBreadcrumb: {
                    label: '404'
                },
                data: {
                    icon: 'pe-7s-attention',
                },
            })
            .state('pages.error401', {
                url: "/pages/error401",
                templateUrl: $basePath + 'views/Error401.html',
                ncyBreadcrumb: {
                    label: '401'
                },
                data: {
                    icon: 'pe-7s-user',
                },
            })
            .state('pages.blank', {
                url: "/pages/blank",
                templateUrl: $basePath + 'views/Blank.html',
                ncyBreadcrumb: {
                    label: 'Blank Page'
                },
                data: {
                    icon: 'pe-7s-browser',
                },
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    $assetBasePath + 'js/pages/blank.js',
                                ]
                            });
                        }
                    ]
                }
            })

            //TODO: all my custom states
            .state('language', {
                abstract: true,
                templateUrl: $basePath + 'views/Blank.html',
                controller: "LanguageCtrl",
                resolve: {
                    deps: [
                        '$ocLazyLoad', function($ocLazyLoad) {
                            return $ocLazyLoad.load({
                                serie: true,
                                files: [
                                    'framework/admin/assets/js/app/controllers/LanguageCtrl.js',
                                    'framework/admin/assets/js/app/services/Language.js',
                                ]
                            });
                        }
                    ]
                }
            })
            .state('language.index', {
                url: "/language/index",
                controller: "LanguageCtrl",
                templateUrl: $basePath + 'views/List.html',
                ncyBreadcrumb: {
                    label: 'Idiomas'
                },
                data: {
                    icon: 'pe-7s-display1',
                },
            })
            .state('language.edit', {
                url: "/language/edit/:id",
                controller: "LanguageEditCtrl",
                templateUrl: $basePath + 'views/language/Detail.html',
                ncyBreadcrumb: {
                    label: 'Editar'
                },
                data: {
                    icon: 'pe-7s-display1',
                },
            })*/
    }
}());