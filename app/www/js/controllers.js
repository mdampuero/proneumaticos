angular.module('starter.controllers', [])
        .controller('IngresarCtrl', function (LoadingService, $scope, WebService, $ionicModal, $ionicPopup, $state, $filter) {
            /********************** MODAL */
            $ionicModal.fromTemplateUrl('templates/repuestos.html', {
                scope: $scope
            }).then(function (modal) {
                $scope.select_amount = false;
                $scope.modal = modal;
            });
            $scope.close = function () {
                $scope.modal.hide();
                $scope.select_amount = false;
                $scope.tires = false;
                $scope.no_stock = false;
                $scope.select_stock = false;
                $scope.cover = false;
                $scope.bat = false;
            };
            $scope.add = function () {
                var option_amount = [];
                for (var i = 1; i <= 50; i++) {
                    option_amount.push({id: i, name: i});
                }
                $scope.modalform = {
                    option_stock: [
                        {id: '1', name: 'SI'},
                        {id: '0', name: 'NO'}
                    ],
                    option_amount: option_amount,
                    stock: {id: '1', name: 'SI'},
                    amount: {id: '1', name: '1'},
                };
                $scope.modal.show();
            };
            $scope.changeType = function () {
                if (isNaN($scope.modalform.type)) {
                    if ($scope.modalform.type == "LLANTA AUXILIAR" || $scope.modalform.type == "LLANTA DE POSICIÓN") {
                        LoadingService.show();
                        WebService.getLlantas()
                                .then(function (data) {
                                    $scope.modalform.option_tires = data;
                                    $scope.modalform.tires = $scope.modalform.option_tires[0];
//                                    console.log($scope.modalform.option_tires);
                                })
                                .finally(function () {
                                    LoadingService.hide();
                                });
                        $scope.tires = true;
                        $scope.cover = false;
                        $scope.bat = false;
                    } else if ($scope.modalform.type == "NEUMÁTICO") {
                        LoadingService.show();
                        WebService.getCubiertas()
                                .then(function (data) {
                                    $scope.modalform.option_cover = data;
                                    $scope.modalform.covers = $scope.modalform.option_cover[0];
//                                    console.log($scope.modalform.option_cover);
                                })
                                .finally(function () {
                                    LoadingService.hide();
                                });
                        $scope.tires = false;
                        $scope.cover = true;
                        $scope.bat = false;
                    } else if ($scope.modalform.type == "BATERÍA") {
                        LoadingService.show();
                        WebService.getBaterias()
                                .then(function (data) {
                                    $scope.modalform.option_bat = data;
                                    $scope.modalform.bats = $scope.modalform.option_bat[0];
//                                    console.log($scope.modalform.option_bat);
                                })
                                .finally(function () {
                                    LoadingService.hide();
                                });
                        $scope.tires = false;
                        $scope.cover = false;
                        $scope.bat = true;
                    }
                    $scope.select_amount = true;
                    $scope.select_stock = true;
                } else {
                    $scope.tires = false;
                    $scope.cover = false;
                    $scope.bat = false;
                    if ($scope.modalform.type != 0) {
                        $scope.select_amount = true;
                        $scope.select_stock = true;
                    } else {
                        $scope.select_amount = false;
                        $scope.select_stock = false;
                    }
                }
            }
            $scope.changeStock = function () {
                if ($scope.modalform.stock.id == "1") {
                    $scope.no_stock = false;
                } else {
                    $scope.no_stock = true;
                }
            }

            $scope.addRepuesto = function () {
                if (isNaN($scope.modalform.type)) {
                    if ($scope.modalform.type == "LLANTA AUXILIAR" || $scope.modalform.type == "LLANTA DE POSICIÓN") {
                        $scope.repuestos.push({
                            name: $scope.modalform.tires.tb_name,
                            type: $scope.modalform.type,
                            amount: $scope.modalform.amount.id,
                            stock: $scope.modalform.stock.id,
                        });
                        if ($scope.modalform.type == "LLANTA AUXILIAR") {
                            $scope.form.si_tb_id_au = $scope.modalform.tires.tb_id;
                            $scope.form.si_tamount_au = $scope.modalform.amount.id;
                            $scope.form.si_stock_au = $scope.modalform.stock.id;
                            $scope.form.si_au_pr_id = $scope.modalform.provider;
                            $scope.form.si_au_date_from = $filter('date')($scope.modalform.from, "yyyy-MM-dd");
                            $scope.form.si_au_date = $filter('date')($scope.modalform.to, "yyyy-MM-dd");
                            $scope.form.si_au_transport = $scope.modalform.transport;
                        } else {
                            $scope.form.si_tb_id_po = $scope.modalform.tires.tb_id;
                            $scope.form.si_tamount_po = $scope.modalform.amount.id;
                            $scope.form.si_stock_po = $scope.modalform.stock.id;
                            $scope.form.si_po_pr_id = $scope.modalform.provider;
                            $scope.form.si_po_date_from = $filter('date')($scope.modalform.from, "yyyy-MM-dd");
                            $scope.form.si_po_date = $filter('date')($scope.modalform.to, "yyyy-MM-dd");
                            $scope.form.si_po_transport = $scope.modalform.transport;
                        }
                    } else if ($scope.modalform.type == "NEUMÁTICO") {
                        $scope.repuestos.push({
                            name: $scope.modalform.covers.cb_name,
                            type: $scope.modalform.type,
                            amount: $scope.modalform.amount.id,
                            stock: $scope.modalform.stock.id
                        });
                        $scope.form.si_cb_id = $scope.modalform.covers.cb_id;
                        $scope.form.si_amount_co = $scope.modalform.amount.id;
                        $scope.form.si_stock_co = $scope.modalform.stock.id;
                        $scope.form.si_co_pr_id = $scope.modalform.provider;
                        $scope.form.si_co_date_from = $filter('date')($scope.modalform.from, "yyyy-MM-dd");
                        $scope.form.si_co_date = $filter('date')($scope.modalform.to, "yyyy-MM-dd");
                        $scope.form.si_co_transport = $scope.modalform.transport;
                    } else if ($scope.modalform.type == "BATERÍA") {
                        $scope.repuestos.push({
                            name: $scope.modalform.bats.bb_name,
                            type: $scope.modalform.type,
                            amount: $scope.modalform.amount.id,
                            stock: $scope.modalform.stock.id
                        });
                        $scope.form.si_bb_id = $scope.modalform.bats.cb_id;
                        $scope.form.si_amount_ba = $scope.modalform.amount.id;
                        $scope.form.si_stock_ba = $scope.modalform.stock.id;
                        $scope.form.si_ba_pr_id = $scope.modalform.provider;
                        $scope.form.si_ba_date_from = $filter('date')($scope.modalform.from, "yyyy-MM-dd");
                        $scope.form.si_ba_date = $filter('date')($scope.modalform.to, "yyyy-MM-dd");
                        $scope.form.si_ba_transport = $scope.modalform.transport;
                    }
                } else {
                    if ($scope.modalform.type != 0) {
                        $scope.repuestos.push({
                            name: $scope.form.accesories[$scope.modalform.type],
                            type: "ACCESORIOS O SERVICIOS",
                            amount: $scope.modalform.amount.id,
                            stock: $scope.modalform.stock.id
                        });
                        $scope.ac_id.push($scope.modalform.type);
                        $scope.amount.push($scope.modalform.amount.id);
                        $scope.stock.push($scope.modalform.stock.id);
                        $scope.sa_pr_id.push($scope.modalform.provider);
                        $scope.sa_date_from.push($filter('date')($scope.modalform.from, "yyyy-MM-dd"));
                        $scope.sa_date.push($filter('date')($scope.modalform.to, "yyyy-MM-dd"));
                        $scope.sa_transport.push($scope.modalform.transport);
                    }
                }
//                console.log($scope.repuestos);
                $scope.close();
            }
            /*/*************************** ** */
            $scope.init = function () {
                $scope.form = {};
                LoadingService.show();
                WebService.getConfig()
                        .then(function (data) {
                            $scope.form = data;
                            $scope.repuestos = [];
                            $scope.ac_id = [];
                            $scope.count = [];
                            $scope.amount = [];
                            $scope.stock = [];
                            $scope.sa_pr_id = [];
                            $scope.sa_date_from = [];
                            $scope.sa_date = [];
                            $scope.sa_transport = [];
//                            console.log(data);
                        })
                        .finally(function () {
                            LoadingService.hide();
                        });
            }
            $scope.changeBrand = function () {
                $scope.form.models_available = $scope.form.models[$scope.form.si_br_id];
            }
            $scope.changeCompany = function () {
                if ($scope.form.companies[$scope.form.si_co_id].co_observation != "") {
                    $ionicPopup.alert({
                        title: $scope.form.companies[$scope.form.si_co_id].co_name,
                        template: "<p>Recuerde que esta compañia requiere la siguiente Documentación:<br/><br/><b>" + $scope.form.companies[$scope.form.si_co_id].co_observation + "</b></p>",
                        buttons: [{text: 'Aceptar'}]
                    });
                }
            }

            // save
            $scope.submit = function () {
//                console.log($scope.form);
                var form = {
                    si_number: $scope.form.si_number,
                    si_co_id: $scope.form.si_co_id,
                    si_fullname: $scope.form.si_fullname,
                    si_email: $scope.form.si_email,
                    si_phone: $scope.form.si_phone,
                    si_domain: $scope.form.si_domain,
                    si_br_id: $scope.form.si_br_id,
                    si_mo_id: $scope.form.si_mo_id,
                    si_version: $scope.form.si_version,
                    si_customer_address: $scope.form.si_address,
                    si_observation_loan: $scope.form.si_observation_loan,
                    si_loan: $scope.form.si_loan,
                    ad_id: $scope.form.ad_id,
                    /* AUXILIAR*/
                    si_au_date: $scope.form.si_au_date,
                    si_au_date_from: $scope.form.si_au_date_from,
                    si_au_pr_id: $scope.form.si_au_pr_id,
                    si_au_transport: $scope.form.si_au_transport,
                    si_stock_au: $scope.form.si_stock_au,
                    si_tamount_au: $scope.form.si_tamount_au,
                    si_tb_id_au: $scope.form.si_tb_id_au,
                    /* POSICION*/
                    si_po_date: $scope.form.si_po_date,
                    si_po_date_from: $scope.form.si_po_date_from,
                    si_po_pr_id: $scope.form.si_po_pr_id,
                    si_po_transport: $scope.form.si_po_transport,
                    si_stock_po: $scope.form.si_stock_po,
                    si_amount_po: $scope.form.si_tamount_po,
                    si_tb_id_po: $scope.form.si_tb_id_po,
                    /* NUEMATICO */
                    si_cb_id: $scope.form.si_cb_id,
                    si_amount_co: $scope.form.si_amount_co,
                    si_stock_co: $scope.form.si_stock_co,
                    si_co_pr_id: $scope.form.si_co_pr_id,
                    si_co_date_from: $scope.form.si_co_date_from,
                    si_co_date: $scope.form.si_co_date,
                    si_co_transport: $scope.form.si_co_transport,
                    /* BATERIA */
                    si_bb_id: $scope.form.si_cb_id,
                    si_amount_ba: $scope.form.si_amount_ba,
                    si_stock_ba: $scope.form.si_stock_ba,
                    si_ba_pr_id: $scope.form.si_ba_pr_id,
                    si_ba_date_from: $scope.form.si_ba_date_from,
                    si_ba_date: $scope.form.si_ba_date,
                    si_ba_transport: $scope.form.si_ba_transport,
                    /*ACCESORIES*/
                    amount: $scope.amount,
                    ac_id: $scope.ac_id,
                    stock: $scope.stock,
                    sa_pr_id: $scope.sa_pr_id,
                    sa_date_from: $scope.sa_date_from,
                    sa_date: $scope.sa_date,
                    sa_transport: $scope.sa_transport,
                    count: $scope.repuestos.length,
                }
                LoadingService.show();
                WebService.push(form)
                        .then(function (data) {
                            $state.go('app.view', {si_id: data});
                        })
                        .catch(function (err) {
                            $ionicPopup.alert({
                                title: "ProNeumáticos",
                                template: err,
                                subTitle: 'Error!',
                                buttons: [{text: 'Aceptar'}]
                            });
                        })
                        .finally(function () {
                            LoadingService.hide();
                        });
//                console.log(form);
            };
        })
        .controller('ViewCtrl', function ($stateParams, LoadingService, $scope, $rootScope, $cordovaCamera, $ionicPopup, config, $sce, $state, $filter, WebService) {
            $scope.si_id = $stateParams.si_id;
            $scope.init = function () {
                LoadingService.show();
                WebService
                        .getSiniestrio($stateParams.si_id)
                        .then(function (data) {
                            $scope.sinister = data;
//                    console.log(data);
                        })
                        .finally(function () {
                            LoadingService.hide();
                        });
            }
            $scope.doRefresh = function () {
                $scope.init();
                $scope.$broadcast('scroll.refreshComplete');
            }
            $scope.show_detail = function (stock, pr_name, sa_date, sa_date_from, sa_transport) {
                if (stock > 0) {

                } else {
                    var from = "";
                    var to = "";
                    var provider = "";
                    if (sa_date_from != "0000-00-00")
                        from = $filter('date')(sa_date_from, 'dd/MM/yyyy');
                    if (sa_date != "0000-00-00")
                        to = $filter('date')(sa_date, 'dd/MM/yyyy');
                    if (pr_name != null)
                        provider = pr_name;
                    $ionicPopup.alert({
                        title: "ProNeumáticos",
                        subTitle: 'Sin stocksss',
                        template: "<p><b>F.Pedido: </b>" + from + "</p>\n\
<p><b>F.llegada: </b>" + to + "</p>\n\
<p><b>Proveedor: </b>" + provider + "</p>\n\
<p><b>Transporte: </b>" + sa_transport + "</p>",
                        buttons: [{text: 'Aceptar'}]
                    });
                }
            }
            $scope.take = function () {
                var options = {
                    quality: 50,
                    destinationType: Camera.DestinationType.FILE_URI,
                    sourceType: 1, // 0:Photo Library, 1=Camera, 2=Saved Photo Album
                    encodingType: 0, // 0=JPG 1=PNG
                    correctOrientation: true
                }
                navigator.camera.getPicture(onSuccess, onFail, options);
            }
            var onSuccess = function (FILE_URI) {
                $rootScope.picture = FILE_URI;
                $state.go('app.upload', {si_id: $scope.si_id});
            };
            var onFail = function (e) {
            }
        })
        .controller('RecientesCtrl', function ($state, $scope, $ionicModal, $timeout, config, $sce, $rootScope, LoadingService, WebService, $filter) {
            $scope.init = function () {
                if ($rootScope.recientes === undefined) {
                    LoadingService.show();
                    WebService
                            .siniestroRecientes(null, 10)
//                    .siniestroRecientes("si_date='" + $filter('date')(new Date(), 'yyyy-MM-dd') + "'", 10)
                            .then(function (data) {
                                $rootScope.recientes = {};
                                $rootScope.recientes = data;
                                $scope.recientes = $rootScope.recientes;
                                console.log($scope.recientes);
                            })
                            .finally(function () {
                                LoadingService.hide();
                            });
                }
                $scope.recientes = $rootScope.recientes;

            }
            $scope.goto = function (id) {
                $state.go('app.view', {si_id: id});
            }
            $scope.doRefresh = function () {
                delete $rootScope.recientes;
                $scope.init();
                $scope.$broadcast('scroll.refreshComplete');
            }

        })
        .controller('AppCtrl', function ($scope, $state, $rootScope, $cordovaCamera, $ionicModal, LoadingService, WebService, QrService, $ionicPopup) {
            $scope.exit = function () {
                ionic.Platform.exitApp();
            }
            $scope.qr = function () {
                QrService.scanBarcode()
                        .then(function (data) {
                            $state.go('app.view', {si_id: data});
                        })
                        .catch(function (response) {
                            $ionicPopup.alert({
                                template: response,
                                subTitle: 'Error!',
                                buttons: [{text: 'Aceptar'}]
                            });
                        });
            };
        })
        .controller('ConsultarCtrl', function ($scope, $ionicModal, $timeout, config, $sce, config, LoadingService, WebService, $rootScope, $state) {
            $scope.data = {};
            $scope.buscar = function () {
                if ($scope.data.consulta !== undefined && $scope.data.consulta.length > 0) {
                    LoadingService.show();
                    WebService
                            .siniestroRecientes("si_number LIKE '%" + $scope.data.consulta + "%' \n\
OR si_id LIKE '%" + $scope.data.consulta + "%' \n\
OR si_domain LIKE '%" + $scope.data.consulta + "%' \n\
OR si_fullname LIKE '%" + $scope.data.consulta + "%'", 10)
                            .then(function (data) {
                                $rootScope.resultados = {};
                                $rootScope.consulta = {};
                                $rootScope.resultados = data;
                                $rootScope.consulta = $scope.data.consulta;
                                $scope.resultados = data;
                            })
                            .finally(function () {
                                LoadingService.hide();
                            });
                }
            }
            $scope.init = function () {
                if ($rootScope.resultados !== undefined) {
                    $scope.resultados = $rootScope.resultados;
                    $scope.data.consulta = $rootScope.consulta;
                }
            }
            $scope.goto = function (id) {
                $state.go('app.view', {si_id: id});
            }
        })
        .controller('UploadCtrl', function ($scope, config, $ionicPopup, $state, $rootScope, $cordovaCamera, $ionicModal, LoadingService, WebService, $stateParams) {
            $scope.si_id = $stateParams.si_id;
            $scope.picture = $rootScope.picture;
            $scope.upload = function () {
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
                        function (res) {
                            LoadingService.hide();
                            $state.go('app.view', {si_id: $scope.si_id});
                        },
                        function (err) {
                        },
                        options);
            }
            $scope.cancel = function () {
                delete $rootScope.picture;
                $state.go('app.view', {si_id: $scope.si_id});
            }
        })
        ;
