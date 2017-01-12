app.directive('loginPageDirective',function($timeout){
    return{
        restrict:'A',
        link:function(scope,elem,attrs){
            $timeout(function(){
                $.getScript("../js/page-handlers/login-page.handler.js", function( data, textStatus, jqxhr ) {
                    //console.log( data ); // Data returned
                    //console.log( textStatus ); // Success
                    //console.log( jqxhr.status ); // 200
                    //console.log( "Load was performed." );
                });
            },0)
        }
    } 
});