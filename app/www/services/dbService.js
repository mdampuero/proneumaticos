angular.module('starter.services')
        .service('dbService', function($q, $cordovaSQLite, config, $filter) {
            var db = window.openDatabase(config.db.name, config.db.version, config.db.description, config.db.size);
            $cordovaSQLite.execute(db, "CREATE TABLE IF NOT EXISTS view_helper (id integer primary key, date date)");
            var obj = {
                countHelperViews: function() {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    var query = "SELECT id FROM view_helper WHERE 1";
                    $cordovaSQLite
                            .execute(db, query)
                            .then(function(res) {
//                                console.log(res);
                                if (res.rows.length < 4) {
                                    obj.addHelperViews();                            
                                    deferred.resolve(true);
                                }else{
                                    deferred.reject();
                                }
                            })
                            .catch(function(err) {
                                deferred.reject(err);
                            });
                    return promise;
                },
                selectHelperViews: function() {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    var query = "SELECT date FROM view_helper WHERE 1";
                    $cordovaSQLite
                            .execute(db, query)
                            .then(function(res) {
                                deferred.resolve(res.rows.length);
                            })
                            .catch(function(err) {
                                deferred.reject(err);
                            });
                    return promise;
                },
                addHelperViews: function() {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    var query = "INSERT INTO view_helper (date) VALUES(?)";
                    $cordovaSQLite
                            .execute(db, query, [$filter('date')(new Date(), 'yyyy/MM/dd')])
                            .catch(function(err) {
                                deferred.reject(err);
                            });
                    return promise;
                },
                resetHelperViews: function() {
                    var deferred = $q.defer();
                    var promise = deferred.promise;
                    var query = "DELETE FROM view_helper WHERE 1";
                    $cordovaSQLite
                            .execute(db, query)
                            .catch(function(err) {
                                deferred.reject(err);
                            });
                    return promise;
                }
            }
            return obj;
        })