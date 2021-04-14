angular.module('starter.services')
        .service('LoadingService', function($ionicLoading) {
            return {
                show: function() {
                    $ionicLoading.show({
                        template: 'Cargando...',
                        content: 'Loading',
                        animation: 'fade-in',
                        showBackdrop: true,
                        maxWidth: 200,
                        showDelay: 0
                    });                        // retorna el resultado de las promesas
                },
                hide: function() {
                    $ionicLoading.hide();                        // retorna el resultado de las promesas
                }
            }
        })