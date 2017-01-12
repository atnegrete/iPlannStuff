function show_overlay(){
    var loading_page =  '<div id="loader-wrapper"></div>';
    $("body").append(loading_page);
}

function remove_overlay() {
    $("#loader-wrapper").remove();
}

function verifySession(){
    var succeed;
    show_overlay()
    $.ajax({
        //async: false,
        type: "POST",
        url: "/projects/planner/resources/php/verifyUser.php",
        data: {
            functionname : "verifyUser"
        },
        dataType: "json",
        success: function(data){
            if(data == true){
                //console.log("Session verified.");
                succeed = true;
            }else{
               // window.location.href = ("#/login");
                //location.reload();
                succeed = false;
            }
        }
    }).done(function(data){
        if(data){
            remove_overlay();
            succeed = true;
        }else {
            remove_overlay();
            $.bootstrapGrowl("Please Login.",{
                type: 'info',
                delay: 3000,
                allow_dismiss: false,
            });
            window.location.href = ("#/login");
            location.reload();
            succeed = false;
        }
    });

    return succeed;
}
