app.directive('signUpDirective',function($timeout){
    return{
        restrict:'A',
        link:function(scope,elem,attrs){
            $timeout(function(){
                $.getScript("../js/page-handlers/signup-page.handler.js", function( data, textStatus, jqxhr ) {
                    //console.log( data ); // Data returned
                    //console.log( textStatus ); // Success
                    //console.log( jqxhr.status ); // 200
                    //console.log( "Load was performed." );
                });
            },0)
        }
    } 
});