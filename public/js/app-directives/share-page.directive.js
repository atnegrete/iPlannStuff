app.directive('friendRequestDirective',function($timeout){
    return{
        restrict:'A',
        link:function(scope,elem,attrs){
            $timeout(function(){
                UserHandler.attachRequestHandlers(scope.request.invite_id);
            },0)
        }
    } 
});

app.directive('friendRemoveDirective',function($timeout){
    return{
        restrict:'A',
        link:function(scope,elem,attrs){
            $timeout(function(){
                UserHandler.attachRemoveFriendHandlers(scope.friend.friendship_id);
            },0)
        }
    } 
});

