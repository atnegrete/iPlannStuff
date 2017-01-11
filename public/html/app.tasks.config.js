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


app.factory('Scopes', function ($rootScope) {
    var mem = {};
 
    return {
        store: function (key, value) {
            mem[key] = value;
        },
        get: function (key) {
            return mem[key];
        }
    };
});