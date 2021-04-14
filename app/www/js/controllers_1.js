angular.module('starter.controllers', [])
        .controller('IngresarCtrl', function(LoadingService, $scope, $ionicModal, $timeout, config, $sce) {
            LoadingService.show();
            load = function() {
                LoadingService.hide();
            }
            $scope.trustSrc = function(src) {
                return $sce.trustAsResourceUrl(src);
            }
            $scope.movie = {src: config.urlBase + 'index/index', title: "Ingresar Siniestro"};

        })
        .controller('ViewCtrl', function($stateParams, LoadingService, $scope, $rootScope, $cordovaCamera, $ionicPopup, config, $sce, $state) {
            LoadingService.show();
            $scope.si_id = $stateParams.si_id;
            load = function() {
                LoadingService.hide();
            }
            $scope.init = function() {
                
            }
            $scope.refreshIframe = function() {
                LoadingService.show();
                var ifr = document.getElementsByName('view')[0];
                ifr.src = ifr.src;
            }
            $scope.trustSrc = function(src) {
                return $sce.trustAsResourceUrl(src);
            }
            $scope.movie = {src: config.urlBase + 'index/view/id/' + $scope.si_id, title: "Ingresar Siniestro"};
            
        })
        .controller('View2Ctrl', function($stateParams, LoadingService, $scope, $rootScope, $cordovaCamera, $ionicPopup, config, $sce, $state) {
            LoadingService.show();
            $scope.si_id = $stateParams.si_id;
            load = function() {
                LoadingService.hide();
            }
            $scope.init = function() {
                
            }
            $scope.refreshIframe = function() {
                LoadingService.show();
                var ifr = document.getElementsByName('view2')[0];
                ifr.src = ifr.src;
            }
            $scope.trustSrc = function(src) {
                return $sce.trustAsResourceUrl(src);
            }
            $scope.movie = {src: config.urlBase + 'index/view2/id/' + $scope.si_id, title: "Ingresar Siniestro"};
        })
        .controller('View3Ctrl', function($stateParams, LoadingService, $scope, $rootScope, $cordovaCamera, $ionicPopup, config, $sce, $state) {
            LoadingService.show();
            $scope.si_id = $stateParams.si_id;
            load = function() {
                LoadingService.hide();
            }
            $scope.init = function() {
                
            }
            $scope.refreshIframe = function() {
                LoadingService.show();
                var ifr = document.getElementsByName('view3')[0];
                ifr.src = ifr.src;
            }
            $scope.trustSrc = function(src) {
                return $sce.trustAsResourceUrl(src);
            }
            $scope.movie = {src: config.urlBase + 'index/view3/id/' + $scope.si_id, title: "Ingresar Siniestro"}; 
        })
        .controller('View4Ctrl', function($stateParams, LoadingService, $scope, $rootScope, $cordovaCamera, $ionicPopup, config, $sce, $state) {
            LoadingService.show();
            $scope.si_id = $stateParams.si_id;
            load = function() {
                LoadingService.hide();
            }
            $scope.init = function() {
                
            }
            $scope.refreshIframe = function() {
                LoadingService.show();
                var ifr = document.getElementsByName('view4')[0];
                ifr.src = ifr.src;
            }
            $scope.trustSrc = function(src) {
                return $sce.trustAsResourceUrl(src);
            }
            $scope.movie = {src: config.urlBase + 'index/view4/id/' + $scope.si_id, title: "Ingresar Siniestro"};
            $scope.take = function() {
                var options = {
                    quality: 50,
                    destinationType: Camera.DestinationType.FILE_URI,
                    sourceType: 1, // 0:Photo Library, 1=Camera, 2=Saved Photo Album
                    encodingType: 0, // 0=JPG 1=PNG
                    correctOrientation: true
                }
                navigator.camera.getPicture(onSuccess, onFail, options);
            }
            var onSuccess = function(FILE_URI) {
                $rootScope.picture = FILE_URI;
                $state.go('app.upload', {si_id: $scope.si_id});
            };
            var onFail = function(e) {
                $ionicPopup.alert({
                    title: config.title,
                    subtitle: 'Ocurrió un error, vuelva a intentarlo',
                    buttons: [{
                            text: 'Aceptar'
                        }]
                });
            }
        })
        .controller('RecientesCtrl', function($scope, $ionicModal, $timeout, config, $sce, $rootScope, LoadingService, WebService, $filter) {
            $scope.init = function() {
                if ($rootScope.recientes === undefined) {
                    LoadingService.show();
                    WebService
                            .siniestroRecientes(null, 10)
//                    .siniestroRecientes("si_date='" + $filter('date')(new Date(), 'yyyy-MM-dd') + "'", 10)
                            .then(function(data) {
                                $rootScope.recientes = {};
                                $rootScope.recientes = data;
                                $scope.recientes = $rootScope.recientes;
                            })
                            .finally(function() {
                                LoadingService.hide();
                            });
                }
                $scope.recientes = $rootScope.recientes;
            }
            $scope.doRefresh = function() {
                delete $rootScope.recientes;
                $scope.init();
                $scope.$broadcast('scroll.refreshComplete');
            }

        })
        .controller('AppCtrl', function($scope, $state, $rootScope, $cordovaCamera, $ionicModal, LoadingService, WebService) {
            $scope.exit = function() {
                ionic.Platform.exitApp();
            }

        })
        .controller('ConsultarCtrl', function($scope, $ionicModal, $timeout, config, $sce, config, LoadingService, WebService, $rootScope, $state) {
            $scope.data = {};
            $scope.buscar = function() {
                if ($scope.data.consulta !== undefined && $scope.data.consulta.length > 0) {
                    LoadingService.show();
                    WebService
                            .siniestroRecientes("si_number LIKE '%" + $scope.data.consulta + "%' \n\
OR si_id LIKE '%" + $scope.data.consulta + "%' \n\
OR si_domain LIKE '%" + $scope.data.consulta + "%' \n\
OR si_fullname LIKE '%" + $scope.data.consulta + "%'", 10)
                            .then(function(data) {
                                $rootScope.resultados = {};
                                $rootScope.consulta = {};
                                $rootScope.resultados = data;
                                $rootScope.consulta = $scope.data.consulta;
                                $scope.resultados = data;
                            })
                            .finally(function() {
                                LoadingService.hide();
                            });
                }
            }
            $scope.init = function() {
                if ($rootScope.resultados !== undefined) {
                    $scope.resultados = $rootScope.resultados;
                    $scope.data.consulta = $rootScope.consulta;
                }

            }
        })
        .controller('UploadCtrl', function($scope, config, $ionicPopup, $state, $rootScope, $cordovaCamera, $ionicModal, LoadingService, WebService, $stateParams) {
            $scope.si_id = $stateParams.si_id;
            $scope.picture = $rootScope.picture;
            $scope.upload = function() {
                LoadingService.show();
                var picture = $scope.picture;
                var options = new FileUploadOptions();
                options.fileKey = "picture";
                options.chunkedMode = false;
                options.fileName = "foto.jpg";
                options.mimeType = "image/jpg";
                var params = {};
                params.si_id = $scope.si_id;
                options.params = params;
                var ft = new FileTransfer();
                ft.upload(picture, encodeURI(config.apiWS + 'picture/put'),
                        function(res) {
                            LoadingService.hide();
//                            console.log('Ok' + res);
                            $state.go('app.view4', {si_id: $scope.si_id});
                        },
                        function(err) {
                            LoadingService.hide();
                            $ionicPopup.alert({
                                title: err,
                                buttons: [{text: 'Aceptar', onTap: function(e) {
                                            $state.go('app.view4', {si_id: $scope.si_id});
                                        }}]
                            });
//                            console.log('Fail' + res);
                        },
                        options);
            }
            $scope.cancel = function() {
                delete $rootScope.picture;
                $state.go('app.view4', {si_id: $scope.si_id});
            }
        })

//        .controller('ViewCtrl', function($scope, $ionicPopup, config, $sce, config, $cordovaCamera, LoadingService, WebService, $rootScope, $state, $stateParams) {
//            $scope.images = [];
//            $scope.hide = true;
//            $scope.si_id = $stateParams.si_id;
//            $scope.doRefresh = function() {
//                $scope.init();
//                $scope.$broadcast('scroll.refreshComplete');
//            }
////            LoadingService.show();
//            load = function() {
//                LoadingService.hide();
//            }
//            $scope.trustSrc = function(src) {
//                return $sce.trustAsResourceUrl(src);
//            }
//            $scope.movie = {src: config.urlBase + 'index/index', title: "Ingresar Siniestro"};
////            $scope.init = function() {
////                LoadingService.show();
////                WebService
////                        .getSiniestrio($stateParams.si_id)
////                        .then(function(data) {
//////                            console.log(data);
////                            $scope.data = data;
////                        })
////                        .catch(function(response) {
////                            $ionicPopup.alert({
////                                title: response,
////                                buttons: [{text: 'Aceptar', onTap: function(e) {
////                                            $state.go('app.consultar');
////                                        }}]
////                            });
////                        })
////                        .finally(function() {
////                            $scope.hide = false;
////                            LoadingService.hide();
////                        });
////                for (var i = 0; i < 20; i++) {
////                    $scope.images.push({id: i, src: "http://placehold.it/50x50"});
////                }
////            }
//            $scope.take = function() {
//                var options = {
//                    quality: 50,
//                    destinationType: Camera.DestinationType.FILE_URI,
//                    sourceType: 1, // 0:Photo Library, 1=Camera, 2=Saved Photo Album
//                    encodingType: 0, // 0=JPG 1=PNG
//                    correctOrientation: false
//                }
//                navigator.camera.getPicture(onSuccess, onFail, options);
//            }
//            var onSuccess = function(FILE_URI) {
//                $rootScope.picture = FILE_URI;
//                $state.go('app.upload', {si_id: $scope.si_id});
//            };
//            var onFail = function(e) {
//                $ionicPopup.alert({
//                    title: config.title,
//                    subtitle: 'Ocurrió un error, vuelva a intentarlo',
//                    buttons: [{
//                            text: 'Aceptar'
//                        }]
//                });
//            }
//        })
        ;
