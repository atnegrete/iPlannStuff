// var app = angular.module('tasksApp', []);
// app.controller('tasksController', function($scope, $http) {


//     var loadCategories = $http({
//             url: "/projects/planner/resources/php/init_handler/task_page_init_handler.php",
//             method: "POST",
//             data: JSON.stringify({
//                 functionname: "getUserCategories",
//             }),
//             headers: {
//                 'Content-Type': 'application/json'
//             }
//         })
//         .success(function(data, status, headers, config) {
//             $scope.user_category_ids = data.category_ids;
//         })
//         .error(function(data, status, headers, config) {
//             alert("Categories Request failed");
//     });

//     var loadAllActiveTasks = $http({
//             url: "/projects/planner/resources/php/init_handler/task_page_init_handler.php",
//             method: "POST",
//             data: JSON.stringify({
//                 functionname: "getAllActiveTasks",
//             }),
//             headers: {
//                 'Content-Type': 'application/json'
//             }
//         })
//         .success(function(data, status, headers, config) {
//             $scope.all_active_tasks = data.all_active_tasks;
//             $scope.weekly_tasks = getWeeklyTasks($scope.all_active_tasks);
//             $scope.overdue_tasks = getOverDueTasks($scope.all_active_tasks);
//         })
//         .error(function(data, status, headers, config) {
//             alert("Categories Request failed");
//     });

//     var loadRecentlyCompletedTasks = $http({
//             url: "/projects/planner/resources/php/init_handler/task_page_init_handler.php",
//             method: "POST",
//             data: JSON.stringify({
//                 functionname: "getRecentlyCompletedTasks",
//                 day_before: DateHelper.getYesterdayAsDateTimeFormat(),
//             }),
//             headers: {
//                 'Content-Type': 'application/json'
//             }
//         })
//         .success(function(data, status, headers, config) {
//             $scope.recently_completed_tasks = data.recent_tasks;
//         })
//         .error(function(data, status, headers, config) {
//             alert("Categories Request failed");
//     });

//     function getWeeklyTasks(all_tasks){
//         var dh = new DateHelper();
//         var current_date = new Date();
//         var in_a_week = new Date(new Date().getTime() + dh.getWeekTime());
//         var weekly_tasks = [];
//         for(var i = 0; i < all_tasks.length; i++){
//             var task_due_date = dh.createJavaScriptDateFromDateTime(all_tasks[i].due_date);
//             if(task_due_date.getTime() > current_date.getTime() && task_due_date.getTime() < in_a_week.getTime()){
//                 weekly_tasks.push(all_tasks[i]);
//             }
//         }
//         return weekly_tasks;
//     }

//     function getOverDueTasks(all_tasks){
//         var dh = new DateHelper();
//         var current_date = new Date(Date.now());
//         var overdue_tasks = [];
//         for(var i = 0; i < all_tasks.length; i++){
//             var task_due_date = dh.createJavaScriptDateFromDateTime(all_tasks[i].due_date);
//             if(task_due_date.getTime() < current_date.getTime()){
//                 overdue_tasks.push(all_tasks[i]);
//             }
//         }
//         return overdue_tasks;
//     }

//     $scope.updateScope = function(){
//         var request = $http({
//             url: "/projects/planner/resources/php/init_handler/task_page_init_handler.php",
//             method: "POST",
//             data: JSON.stringify({
//                 functionname: "getAllActiveTasks",
//             }),
//             headers: {
//                 'Content-Type': 'application/json'
//             }
//         })
//         .success(function(data, status, headers, config) {
//             $scope.all_active_tasks = data.all_active_tasks;
//             $scope.weekly_tasks = getWeeklyTasks($scope.all_active_tasks);
//             $scope.overdue_tasks = getOverDueTasks($scope.all_active_tasks);
//         })
//         .error(function(data, status, headers, config) {
//             alert("Categories Request failed");
//         });
//     }

// });

// app.directive('taskDirective',function($timeout){
//     return{
//         restrict:'A',
//         link:function(scope,elem,attrs){
//             $timeout(function(){
//                 Task.setCalendar(scope.task.task_id, scope.task.due_date);
//                 Task.addDeleteTaskDialog(scope.task.task_id);
//             },0)
//         }
//     } 
// });