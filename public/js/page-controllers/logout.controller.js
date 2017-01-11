app.controller('logoutController', function ( $scope, $http) {

    var logout = function(){
        UserHandler.logout();
    }();
});