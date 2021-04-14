angular.module('starter.services')
        .service('QrService', function($q, $cordovaBarcodeScanner) {
            return {
                scanBarcode: function() {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    // deferred.resolve(1);
                    //deferred.reject("chupala");
//                    
                    $cordovaBarcodeScanner.scan().then(function(imageData) {
//                        deferred.reject(imageData.text);
                        if (imageData.format != "QR_CODE") {
                            deferred.reject("El Código QR no es válido");
                        } else if (imageData.text.length <= 0) {
                            deferred.reject("El Código QR no se pudo leer, intente nuevamente.");
                        } else if (imageData.text.length > 0) {
                            deferred.resolve(imageData.text);
                        } else {
                            deferred.reject("No se pudo leer el Código QR");
                        }
                    }, function(error) {
                        deferred.reject(error);
                    });
                    return promise;                         // retorna el resultado de las promesas
                }
            }
        })