app.directive('taskDirective',function($timeout){
    return{
        restrict:'A',
        link:function(scope,elem,attrs){
            $timeout(function(){
                Task.setCalendar(scope.task.task_id, scope.task.due_date);
                Task.addDeleteTaskDialog(scope.task.task_id);
            },0)
        }
    } 
});