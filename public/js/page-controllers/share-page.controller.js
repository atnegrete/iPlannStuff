app.controller('sharePageController', function ( $scope, $http, Scopes) {
   
    $scope.init = function(){
        //console.log("init");
        $tasks_scope = Scopes.get("homeTasksController");
        //console.log($tasks_scope.friends);
        if($tasks_scope != undefined){
            $scope.friends = $tasks_scope.friends;
        }
    }

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

    var getFriendRequests = $http({
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
                    if(data.result != null && data.result.length > 0){
                        $scope.friendRequests = data.result;
                    }
                    if(data.result.length > 0) {

                        $.bootstrapGrowl("You have " + data.result.length + " friend requests.",{
                            type: 'success',
                            delay: 2000,
                            allow_dismiss: false,
                        });
                        var badge = '<span class="w3-badge w3-margin-left w3-green">'+data.result.length+'</span>';
                        $("#share_tasks_navbar").append(badge);
                    }
                }else{
                    $.bootstrapGrowl(data.error,{
                        type: 'warning',
                        delay: 2000,
                        allow_dismiss: false,
                    });
                }
            })
            .error(function(data, status, headers, config) {
                alert(success);
            });

});