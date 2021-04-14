angular.module('starter.services')
        .service('WebService', function($cordovaNetwork, $q, $http, $rootScope, $state, config, $filter, $ionicPopup, LoadingService) {
            return {
                getOneProduct: function(product_id) {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
//                    if ($cordovaNetwork.isOnline()) {
                    var req = {
                        method: 'POST',
                        url: config.apiWS + 'product/get/',
                        data: $.param({'id': product_id}),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'key': config.keyWS
                        }
                    };
                    $http(req)
                            .success(function(result) {
                                if (result.response == 1) {
                                    result.data.product_detail = false;
                                    if (result.data.pr_stock_available > 0) {
                                        result.data.stock = true;
                                        result.data.availableAmounts = [];
                                        for (var j = 1; j <= result.data.pr_stock_available; j++) {
                                            result.data.availableAmounts.push({value: j});
                                        }
                                        result.data.amount_select = {value: 1};
                                    }
                                    deferred.resolve(result.data);
                                } else {
                                    deferred.reject(result.message);
                                }
                            })
                            .error(function(error) {
                                deferred.reject(error);
                            });
//                    } else {
//                        deferred.reject("Su dispositivo no está conectado a Internet");
//                    }
                    return promise;
                },
                getConfig: function() {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    var req = {
                        method: 'GET',
                        url: config.apiWS + 'sinister/post',
                        headers: {
                            'key': config.keyWS
                        }
                    };
                    $http(req)
                            .success(function(data) {
                                if (data.response == 1) {
                                    deferred.resolve(data.data);
                                } else {
                                    deferred.reject(data.message);
                                }
                            })
                            .error(function(error) {
                                deferred.reject(error);
                            });
//                    } else {
//                        deferred.reject("Su dispositivo no está conectado a Internet");
//                    }
                    return promise;
                },
                getLlantas: function() {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    var req = {
                        method: 'GET',
                        url: config.apiWS + 'sinister/llantas',
                        headers: {
                            'key': config.keyWS
                        }
                    };
                    $http(req)
                            .success(function(data) {
                                if (data.response == 1) {
                                    deferred.resolve(data.data);
                                } else {
                                    deferred.reject(data.message);
                                }
                            })
                            .error(function(error) {
                                deferred.reject(error);
                            });
                    return promise;
                },
                getCubiertas: function() {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    var req = {
                        method: 'GET',
                        url: config.apiWS + 'sinister/cubiertas',
                        headers: {
                            'key': config.keyWS
                        }
                    };
                    $http(req)
                            .success(function(data) {
                                if (data.response == 1) {
                                    deferred.resolve(data.data);
                                } else {
                                    deferred.reject(data.message);
                                }
                            })
                            .error(function(error) {
                                deferred.reject(error);
                            });
                    return promise;
                },
                getBaterias: function() {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    var req = {
                        method: 'GET',
                        url: config.apiWS + 'sinister/baterias',
                        headers: {
                            'key': config.keyWS
                        }
                    };
                    $http(req)
                            .success(function(data) {
                                if (data.response == 1) {
                                    deferred.resolve(data.data);
                                } else {
                                    deferred.reject(data.message);
                                }
                            })
                            .error(function(error) {
                                deferred.reject(error);
                            });
                    return promise;
                },
                siniestroRecientes: function(where, limit) {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
//                    if ($cordovaNetwork.isOnline()) {
                    var req = {
                        method: 'POST',
                        url: config.apiWS + 'sinister',
                        data: $.param({
                            'where': where,
                            'limit': limit
                        }),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'key': config.keyWS
                        }
                    };
                    $http(req)
                            .success(function(data) {
                                if (data.response == 1) {
                                    if (data.data.length > 0) {
                                        deferred.resolve(data.data);
                                    } else {
                                        deferred.reject("No hay siniestros");
                                    }
                                } else {
                                    deferred.reject(data.message);
                                }
                            })
                            .error(function(error) {
                                deferred.reject(error);
                            });
//                    } else {
//                        deferred.reject("Su dispositivo no está conectado a Internet");
//                    }
                    return promise;
                },
                getSiniestrio: function(si_id) {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
//                    if ($cordovaNetwork.isOnline()) {
                    var req = {
                        method: 'POST',
                        url: config.apiWS + 'sinister/get/',
                        data: $.param({
                            'si_id': si_id
                        }),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'key': config.keyWS
                        }
                    };
                    $http(req)
                            .success(function(data) {
                                if (data.response == 1) {
                                     deferred.resolve(data.data);
                                } else {
                                    deferred.reject(data.message);
                                }
                            })
                            .error(function(error) {
                                if (error !== null) {
                                    deferred.reject(error);
                                } else {
                                    deferred.reject("No se pudo conectar con el servidor");
                                }
                            });
//                    } else {
//                        deferred.reject("Su dispositivo no está conectado a Internet");
//                    }
                    return promise;
                },
                checkOrder: function(order_id) {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
//                    if ($cordovaNetwork.isOnline()) {
                    var req = {
                        method: 'POST',
                        url: config.apiWS + 'order/post/',
                        data: $.param({
                            'id': order_id
                        }),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'key': config.keyWS
                        }
                    };
                    $http(req)
                            .success(function(data) {

                                if (data.response == 1) {
                                    deferred.resolve(data.data);
                                } else {
                                    deferred.reject(data.message);
                                }
                            })
                            .error(function(error) {
                                deferred.reject(error);
                            });
//                    } else {
//                        deferred.reject("Su dispositivo no está conectado a Internet");
//                    }
                    return promise;
                },
                push: function(data) {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    var req = {
                        method: 'POST',
                        url: config.apiWS + 'sinister/put/',
                        data: $.param({
                            'data': data
                        }),
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'key': config.keyWS
                        }
                    };
                    $http(req)
                            .success(function(data) {
                                if (data.response == 1) {
                                    deferred.resolve(data.data);
                                } else {
                                    deferred.reject(data.message);
                                }
                            })
                            .error(function(error) {
                                deferred.reject(error);
                            });
                    return promise;
                },
//                generatePurchaseOrder: function(name, email, documentnumber, gift, json) {
////                    if ($cordovaNetwork.isOnline()) {
//                    LoadingService.show();
//                    var req = {
//                        method: 'POST',
//                        url: config.apiWS + 'order/put/',
//                        data: $.param({
//                            'name': name,
//                            'email': email,
//                            'docnumber': documentnumber,
//                            'gift': gift,
//                            'items': json,
//                        }),
//                        headers: {
//                            'Content-Type': 'application/x-www-form-urlencoded',
//                            'key': config.keyWS
//                        }
//                    };
//                    $http(req)
//                            .success(function(data) {
//                                if (data.response == 1) {
//                                    delete $rootScope.carrito; //Limpiar carrito
//                                    $rootScope.carrito = [];
////                                    $scope.data = {};           //Limpiar campos de payment
//                                    $state.go('orderview', {
//                                        id: data.data,
//                                        name: name
//                                    });
//                                } else if (data.response == 2) {
//                                    $ionicPopup.alert({
//                                        title: data.message,
//                                        buttons: [{
//                                                text: 'Volver al Carro',
//                                                onTap: function(e) {
//                                                    $state.go('shoppingcart');
//                                                }
//                                            }]
//                                    });
//                                } else {
//                                    $ionicPopup.alert({
//                                        title: data.message,
//                                        buttons: [{text: 'Aceptar'}]
//                                    });
//                                }
//                            })
//                            .error(function(error) {
//                                $ionicPopup.alert({
//                                    title: error,
//                                    buttons: [{text: 'Aceptar'}]
//                                });
//                            })
//                            .finally(function(error) {
//                                LoadingService.hide();
//                            });
////                    } else {
////                        //deferred.reject("Su dispositivo no está conectado a Internet");
////                        $ionicPopup.alert({
////                            title: "Su dispositivo no está conectado a Internet",
////                            buttons: [{text: 'Aceptar'}]
////                        });
////                    }
//                },
                getFlight: function(idairline, flight) {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
//                    if ($cordovaNetwork.isOnline()) {
                    if (config.hardcode == true) {
                        deferred.resolve(config.hardcode);
                    }
                    var req = {
                        method: 'GET',
                        url: config.apiAA + 'Vuelos?idairline=' + idairline + '&flight=' + flight + '&movtp=D&f=' + $filter('date')(new Date(), 'dd/MM/yyyy'),
                        headers: {
                            'key': config.keyAA,
                        }
                    };
                    $http(req)
                            .success(function(data) {
                                if (data.length > 0) {
                                    angular.forEach(data, function(value, key) {
                                        angular.forEach(value, function(val, index) {
                                            if (index == 'destorig' && val == 'Aeroparque') {
                                                response = value;
                                            }
                                        });
                                    });
                                    if (response != null) {
                                        deferred.resolve(response);
                                    } else {
                                        deferred.reject("El destino de su vuelo no es Aeroparque.");
                                    }
                                } else {
                                    deferred.reject("No se pudo localizar el vuelo.");
                                }
                            })
                            .error(function(error) {
                                deferred.reject(error);
                            });
//                    } else {
//                        deferred.reject("Su dispositivo no está conectado a Internet");
//                    }
                    return promise;
                }
            };
        });