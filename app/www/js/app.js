// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers', 'ngCordova', 'starter.services'])
        .constant('config', {
            title: 'ProNeumáticos',
            urlBase: 'http://pro-neumaticos.com.ar/public/',
            apiWS: 'http://pro-neumaticos.com.ar/public/api/',
//            urlBase: 'http://190.15.194.212/ProNeumaticos/web/public/',
//            apiWS: 'http://190.15.194.212/ProNeumaticos/web/public/api/',
//            urlBase: 'http://192.168.0.102/ProNeumaticos/web/public/',
//            apiWS: 'http://192.168.0.102/ProNeumaticos/web/public/api/',
//            urlBase: 'http://localhost/Freelance/ProNeumaticos/web/public/',
//            apiWS: 'http://localhost/Freelance/ProNeumaticos/web/public/api/',
            keyWS: 'Secreta007',
            hardcode: false,
            db: {
                name: 'sg.db',
                version: '1.0',
                desrciption: 'Shop Gallery',
                size: 200000,
            },
            message: 'Su dispositivo no está conectado a Internet.',
            analyticsId: 'UA-64314022-2'
        })
        .run(function($ionicPlatform) {
            $ionicPlatform.ready(function() {
                // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
                // for form inputs)
                if (window.cordova && window.cordova.plugins.Keyboard) {
                    cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
                    cordova.plugins.Keyboard.disableScroll(true);

                }
                if (window.StatusBar) {
                    // org.apache.cordova.statusbar required
                    StatusBar.styleDefault();
                }
            });
        })

        .config(function($stateProvider, $urlRouterProvider) {
            $stateProvider

                    .state('app', {
                        url: '/app',
                        abstract: true,
                        templateUrl: 'templates/menu.html',
                        controller: 'AppCtrl'
                    })

                    .state('app.ingresar', {
                        url: '/ingresar',
                        cache: false,
                        views: {
                            'menuContent': {
                                templateUrl: 'templates/ingresar.html',
                                controller: 'IngresarCtrl'
                            }
                        }
                    })
                    .state('app.consultar', {
                        url: '/consultar',
                        cache: false,
                        views: {
                            'menuContent': {
                                templateUrl: 'templates/consultar.html',
                                controller: 'ConsultarCtrl'
                            }
                        }
                    })
                    .state('app.recientes', {
                        url: '/recientes',
                        cache: false,
                        views: {
                            'menuContent': {
                                templateUrl: 'templates/recientes.html',
                                controller: 'RecientesCtrl'
                            }
                        }
                    })
                    .state('app.view', {
                        url: '/view/:si_id',
                        views: {
                            'menuContent': {
                                templateUrl: 'templates/view.html',
                                controller: 'ViewCtrl'
                            }
                        }
                    })
                    .state('app.view2', {
                        url: '/view2/:si_id',
                        views: {
                            'menuContent': {
                                templateUrl: 'templates/view2.html',
                                controller: 'View2Ctrl'
                            }
                        }
                    })
                    .state('app.view3', {
                        url: '/view3/:si_id',
                        views: {
                            'menuContent': {
                                templateUrl: 'templates/view3.html',
                                controller: 'View3Ctrl'
                            }
                        }
                    })
                    .state('app.view4', {
                        url: '/view4/:si_id',
                        views: {
                            'menuContent': {
                                templateUrl: 'templates/view4.html',
                                controller: 'View4Ctrl'
                            }
                        }
                    })
                    .state('app.upload', {
                        url: '/upload/:si_id',
                        cache: false,
                        views: {
                            'menuContent': {
                                templateUrl: 'templates/upload.html',
                                controller: 'UploadCtrl'
                            }
                        }
                    });
            // if none of the above states are matched, use this as the fallback
            $urlRouterProvider.otherwise('/app/recientes');
        });
