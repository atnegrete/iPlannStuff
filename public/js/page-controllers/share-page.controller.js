app.controller('sharePageController', function ( $scope, $http, Scopes) {
   
    
    var friendsPromise = Scopes.getFriends();
    friendsPromise.then(function(friends) {
        $scope.friends = friends;
    }, function(reason) {
        alert('Failed: ' + reason);
    }, function(update) {
        $scope.friends = update;
    });

    $scope.inviteFriendForm = function(isValid){
        if(isValid){
            email = document.forms["addFriendForm"]["email"].value;
            console.log(email);
            var request = $http({
            url: "/projects/planner/resources/php/task_handlers/task_sharing_handler_one.php",
            method: "POST",
            data: JSON.stringify({
                functionname: "sendInvite",
                invite_email: email
            }),
            headers: {
                'Content-Type': 'application/json'
            }
            })
            .success(function(data, status, headers, config) {
                if(! ("error" in data)){
                    $.bootstrapGrowl(data.result,{
                        type: 'success',
                        delay: 3000,
                        allow_dismiss: false,
                    });
                }else{
                    $.bootstrapGrowl(data.error,{
                        type: 'danger',
                        delay: 3000,
                        allow_dismiss: false,
                    });
                }
            })
            .error(function(data, status, headers, config) {
                alert(success);
            });
        }
    }

    $scope.updateRequestsScope = function(){
        var updateRequests = $http({
            url: "/projects/planner/resources/php/task_handlers/task_sharing_handler_one.php",
            method: "POST",
            data: JSON.stringify({
                functionname: "getFriendRequests"
            }),
            headers: {
                'Content-Type': 'application/json'
            }
            })
            .success(function(data, status, headers, config) {
                if(! ("error" in data)){
                    if(data.result != null){
                        $scope.friendRequests = data.result;
                        console.log("updated");
                    }
                }else{
                    $.bootstrapGrowl(data.error,{
                        type: 'danger',
                        delay: 3000,
                        allow_dismiss: false,
                    });
                }
            })
            .error(function(data, status, headers, config) {
                alert(success);
            });
    }

    var friendsRequestsPromise = Scopes.getFriendsRequests();
    friendsRequestsPromise.then(function(friends) {
        $scope.friendRequests = friends;
        console.log(friends);
    }, function(reason) {
        alert('Failed: ' + reason);
    }, function(update) {
        $scope.friendRequests = update;
        console.log(update);
    });
});