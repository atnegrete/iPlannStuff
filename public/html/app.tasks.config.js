
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
    .when("/home", {
        templateUrl: "home-tasks/home-tasks.template.html", 
        controller: "homeTasksController",
        resolve: {
            "check" : function($location){
                verifySession().done(function(result){
                    if(result){
                        $location.path('/home');
                        console.log("session verified");
                        remove_overlay();
                    }else{
                        $location.path('/login');
                        console.log("session not verified");
                        remove_overlay();
                        $.bootstrapGrowl("Please Login.",{
                            type: 'info',
                            delay: 3000,
                            allow_dismiss: false,
                        });
                    }
                });
            }
        }
    })
    .when("/share", {
        templateUrl: "home-share/home-share.template.html",
        controller: "sharePageController",
        resolve: {
            "check" : function($location){
                verifySession().done(function(result){
                    if(result){
                        $location.path('/share');
                        console.log("session verified");
                        remove_overlay();
                    }else{
                        $location.path('/login');
                        console.log("session not verified");
                        remove_overlay();
                        $.bootstrapGrowl("Please Login.",{
                            type: 'info',
                            delay: 3000,
                            allow_dismiss: false,
                        });
                    }
                });
            }
        }
    })
    .when("/welcome", {
        templateUrl: "welcome-page/welcome-page.template.html"
    })
    .when("/signup", {
        templateUrl: "signup-page/signup-page.template.html",
    })
    .when("/login", {
        templateUrl: "login-page/login-page.template.html",
    })
    .when("/logout", {
        controller:"logoutController"
    })
    .when("/planner", {
        templateUrl: "home-planner/home-planner.template.html",
        resolve: {
            "check" : function($location){
                verifySession().done(function(result){
                    if(result){
                        $location.path('/planner');
                        console.log("session verified");
                        remove_overlay();
                    }else{
                        $location.path('/login');
                        console.log("session not verified");
                        remove_overlay();
                        $.bootstrapGrowl("Please Login.",{
                            type: 'info',
                            delay: 3000,
                            allow_dismiss: false,
                        });
                    }
                });
            }
        }
    })
    .when("/contact", {
        templateUrl: "home-planner/home-planner.template.html",
        resolve: {
            "check" : function($location){
                verifySession().done(function(result){
                    if(result){
                        $location.path('/planner');
                        console.log("session verified");
                        remove_overlay();
                    }else{
                        $location.path('/login');
                        console.log("session not verified");
                        remove_overlay();
                        $.bootstrapGrowl("Please Login.",{
                            type: 'info',
                            delay: 3000,
                            allow_dismiss: false,
                        });
                    }
                });
            }
        }
    })
    .when("/settings", {
        templateUrl: "home-planner/home-planner.template.html",
        resolve: {
            "check" : function($location){
                verifySession().done(function(result){
                    if(result){
                        $location.path('/planner');
                        console.log("session verified");
                        remove_overlay();
                    }else{
                        $location.path('/login');
                        console.log("session not verified");
                        remove_overlay();
                        $.bootstrapGrowl("Please Login.",{
                            type: 'info',
                            delay: 3000,
                            allow_dismiss: false,
                        });
                    }
                });
            }
        }
    })
    .otherwise({redirectTo: 'home'});
}]);


app.factory('Scopes', function ($rootScope, $q, $http, $interval){
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
                friendsRequestsPromise.resolve(data.result);                    
                if(data.result.length > 0) {
                    if($("#requests_badge").length == 0){
                        var badge = '<span class="w3-badge w3-margin-left w3-green" id="requests_badge">'+data.result.length+'</span>';
                        $("#share_tasks_navbar").append(badge);
                    }
                }else{
                    $("#requests_badge").remove();
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

    var loadCategories = function(){
        var categoriesPromise = $q.defer();
        $http({
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
                categoriesPromise.resolve(data.category_ids);
            })
            .error(function(data, status, headers, config) {
                alert("Categories Request failed");
        });
        return categoriesPromise.promise;
    }
 
    return {
        getFriendsRequests : loadFriendRequests,
        getFriends : loadFriends,
        getCategories : loadCategories,
        store: function (key, value) {
            mem[key] = value;
        },
        get: function (key) {
            return mem[key];
        }
    };
});
