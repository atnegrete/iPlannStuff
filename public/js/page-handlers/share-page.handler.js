function verifySessionSharePage(){
    $.ajax({
        async: false,
        type: "POST",
        url: "/projects/planner/resources/php/verifyUser.php",
        data: {
            functionname : "verifyUser"
        },
        dataType: "json",
        success: function(data){
            if(data == true){
                console.log("Session verified.");
            }else{
                $.bootstrapGrowl("Please Login.",{
                    type: 'info',
                    delay: 3000,
                    allow_dismiss: false,
                });
                window.location.href = ("#/login");
                succeed = false;
            }
        }
    });
    return succeed;
}

verifySessionSharePage();