
"use strict"; 

/**
 * Main AngularJS Web Application
 */
var app = angular.module('tasksApp', [
  'ngRoute'
]);

/**
 * Configure the Routes
 */
app.config(['$routeProvider', function ($routeProvider) {
  $routeProvider
    // Home
    .when("/home", {templateUrl: "home-tasks/home-tasks.template.html", controller: "homeTasksController"})
    .when("/share", {templateUrl: "home-share/home-share.template.html", controller: "sharePageController"})
    .when("/welcome", {templateUrl: "welcome-page/welcome-page.template.html"})
    .when("/signup", {templateUrl: "signup-page/signup-page.template.html",  controller: "signupPageController"})
    .when("/login", {templateUrl: "login-page/login-page.template.html", controller: "loginPageController"})
    .when("/logout", {controller:"logoutController"})
    .when("/planner", {templateUrl: "home-planner/home-planner.template.html"})
    .otherwise({redirectTo: 'home'});
}]);


app.factory('Scopes', function ($rootScope, $q, $http) {
    var mem = {};

    var loadFriends = function(){
        var friendsPromise = $q.defer();
        $http({
                url: "/projects/planner/resources/php/init_handler/task_page_init_handler.php",
                method: "POST",
                data: JSON.stringify({
                    functionname: "getUserFriends",
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .success(function(data, status, headers, config) {
                friendsPromise.resolve(data.friends);
            })
            .error(function(data, status, headers, config) {
                alert("Friends Request failed");
        });
        return friendsPromise.promise;
    }

    var loadFriendRequests = function(){
        var friendsRequestsPromise = $q.defer();
        $http({
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
            if(!("error" in data)){
                if(data.result.length > 0){
                    friendsRequestsPromise.resolve(data.result);                    
                }
                if(data.result.length > 0) {
                    $.bootstrapGrowl("You have " + data.result.length + " friend request/s.",{
                        type: 'success',
                        delay: 1000,
                        allow_dismiss: false,
                    });
                    var badge = '<span class="w3-badge w3-margin-left w3-green">'+data.result.length+'</span>';
                    $("#share_tasks_navbar").append(badge);
                }
            }else{
                console.log(data.error);
            }
        })
        .error(function(data, status, headers, config) {
            alert(status);
        });
        return friendsRequestsPromise.promise;
    }
 
    return {
        getFriendsRequests : loadFriendRequests,
        getFriends : loadFriends,
        store: function (key, value) {
            mem[key] = value;
        },
        get: function (key) {
            return mem[key];
        }
    };
});
