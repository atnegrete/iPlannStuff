app.directive('shareTaskDirective',function($timeout){
    return{
        restrict:'A',
        link:function(scope,elem,attrs){
            $timeout(function(){
                //console.log(scope.friends, scope.task.shared_friends_id, scope.task.task_id);
                Task.handleTaskSharingFriends(scope,scope.friends, scope.task.shared_friends_id, scope.task, scope.friends.friend);
            },0)
        }
    } 
});