
/**
 * Controls the Task Page (Home)
 */
app.controller('homeTasksController', function ( $scope, $http, Scopes) {

        var d = Scopes.getFriends();
        d.then(function(friends) {
            $scope.friends = friends;
            //console.log(friends);
        }, function(reason) {
            alert('Failed: ' + reason);
        }, function(update) {
            $scope.friends = update;
            //console.log(update);
        });

        var friendsRequestsPromise = Scopes.getFriendsRequests();
        friendsRequestsPromise.then(function(requests) {
            $scope.friendRequests = requests;
        }, function(reason) {
            alert('Failed: ' + reason);
        }, function(update) {
            $scope.friendRequests = update;
        });

        var loadCategories = $http({
                url: "/projects/planner/resources/php/init_handler/task_page_init_handler.php",
                method: "POST",
                data: JSON.stringify({
                    functionname: "getUserCategories",
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .success(function(data, status, headers, config) {
                $scope.user_category_ids = data.category_ids;
            })
            .error(function(data, status, headers, config) {
                alert("Categories Request failed");
        });

        var loadAllActiveTasks = $http({
                url: "/projects/planner/resources/php/init_handler/task_page_init_handler.php",
                method: "POST",
                data: JSON.stringify({
                    functionname: "getAllActiveTasks",
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .success(function(data, status, headers, config) {
                $scope.all_active_tasks = data.all_active_tasks;
                $scope.weekly_tasks = getWeeklyTasks($scope.all_active_tasks);
                $scope.overdue_tasks = getOverDueTasks($scope.all_active_tasks);
            })
            .error(function(data, status, headers, config) {
                alert("Categories Request failed");
        });

        var loadRecentlyCompletedTasks = $http({
                url: "/projects/planner/resources/php/init_handler/task_page_init_handler.php",
                method: "POST",
                data: JSON.stringify({
                    functionname: "getRecentlyCompletedTasks",
                    day_before: DateHelper.getYesterdayAsDateTimeFormat(),
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .success(function(data, status, headers, config) {
                $scope.recently_completed_tasks = data.recent_tasks;
            })
            .error(function(data, status, headers, config) {
                alert("Categories Request failed");
        });

        function getWeeklyTasks(all_tasks){
            var dh = new DateHelper();
            var current_date = new Date();
            var in_a_week = new Date(new Date().getTime() + dh.getWeekTime());
            var weekly_tasks = [];
            if(all_tasks != undefined){
                for(var i = 0; i < all_tasks.length; i++){
                    var task_due_date = dh.createJavaScriptDateFromDateTime(all_tasks[i].due_date);
                    if(task_due_date.getTime() > current_date.getTime() && task_due_date.getTime() < in_a_week.getTime()){
                        weekly_tasks.push(all_tasks[i]);
                    }
                }
            }
            return weekly_tasks;
        }

        function getOverDueTasks(all_tasks){
            var dh = new DateHelper();
            var current_date = new Date(Date.now());
            var overdue_tasks = [];
            if(all_tasks != undefined){
                for(var i = 0; i < all_tasks.length; i++){
                    var task_due_date = dh.createJavaScriptDateFromDateTime(all_tasks[i].due_date);
                    if(task_due_date.getTime() < current_date.getTime()){
                        overdue_tasks.push(all_tasks[i]);
                    }
                }
                if(overdue_tasks.length > 0){
                    $.bootstrapGrowl("You have " + overdue_tasks.length + " task/s overdue!",{
                        type: 'warning',
                        delay: 3000,
                        allow_dismiss: false,
                    });
                    var badge = '<span class="w3-badge w3-margin-left w3-red">'+overdue_tasks.length+'</span>';
                    $("#home_page_navbar").append(badge);
                }
            }
            return overdue_tasks;
        }

        $scope.updateScope = function(){
            var request = $http({
                url: "/projects/planner/resources/php/init_handler/task_page_init_handler.php",
                method: "POST",
                data: JSON.stringify({
                    functionname: "getAllActiveTasks",
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .success(function(data, status, headers, config) {
                $scope.all_active_tasks = data.all_active_tasks;
                $scope.weekly_tasks = getWeeklyTasks($scope.all_active_tasks);
                $scope.overdue_tasks = getOverDueTasks($scope.all_active_tasks);
            })
            .error(function(data, status, headers, config) {
                alert("Categories Request failed");
            });
        }

});