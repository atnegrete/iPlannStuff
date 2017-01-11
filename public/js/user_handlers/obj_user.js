
var UserHandler = function(){
    
}

UserHandler.logout = function() {
    $.post("/projects/planner/resources/php/verifyUser.php",
    {
        functionname: "logout"
    },
    function(data, status){
        console.log(status);
        if(data == "true"){
            alert("logout out successfully");
            window.location.href = ("#/login");
        }
    });
}

UserHandler.getUserName = function(){
    var user;
    $.post("/projects/planner/resources/php/verifyUser.php",
    {
        functionname: "logout"
    },
    function(data, status){
        console.log(status);
        if(! ("error" in data)){
            user = data.user_name;
        }else{
            user = data.error;
        }
    });
    return user;
}

UserHandler.attachRequestHandlers = function(invite_id){
    UserHandler.acceptUserRequest(invite_id);
    UserHandler.rejectUserRequest(invite_id);
}

UserHandler.acceptUserRequest = function(invite_id){
    var accept = $("[data-accept-request-id='"+invite_id+"']");
    accept.on("click", function(){
        // Update DB friend request.
        $.ajax({
            type: "POST",
            url: "/projects/planner/resources/php/task_handlers/task_sharing_handler_two.php",
            data: {
                functionname : "acceptUserRequest",
                invite_id : invite_id
            },
            dataType: "json",
            success: function(data){
                if(!('error' in data) ){
                    $.bootstrapGrowl(data.result,{
                        type: 'success',
                        delay: 3000,
                        allow_dismiss: false,
                    });
                    // Update scope for requests.
                    UserHandler.updateRequestsScope();
                }else{
                    $.bootstrapGrowl(data.error,{
                        type: 'danger',
                        delay: 3000,
                        allow_dismiss: false,
                    });          
                }
            }
        });
    });
}

UserHandler.rejectUserRequest = function(invite_id){
    var reject = $("[data-reject-request-id='"+invite_id+"']");
    reject.on("click", function(){
        $.ajax({
            type: "POST",
            url: "/projects/planner/resources/php/task_handlers/task_sharing_handler_two.php",
            data: {
                functionname : "rejectUserRequest",
                invite_id : invite_id
            },
            dataType: "json",
            success: function(data){
                if(!('error' in data) ){
                    $.bootstrapGrowl(data.result,{
                        type: 'warning',
                        delay: 3000,
                        allow_dismiss: false,
                    });
                    // Update scope for requests.
                    UserHandler.updateRequestsScope();
                }else{
                    $.bootstrapGrowl(data.error,{
                        type: 'danger',
                        delay: 3000,
                        allow_dismiss: false,
                    });          
                }
            }
        });
    });
}

UserHandler.updateRequestsScope = function(){
    var scope = angular.element("#share_center_nav").scope();
    scope.updateRequestsScope();
}

UserHandler.attachRemoveFriendHandlers = function(friend_id){
    var remove = $("[data-remove-friend-id='"+friend_id+"']");
    $(remove).on("click", function(){
        dhtmlx.confirm({
            title:"Remove Friend",
            ok:"Yes", cancel:"Cancel",
            text:"Are you susre you want to permamently remove this friend?",
            callback:function(result){
                console.log("TODO : Update DB and remove friend.");
            }
        });
    });
}