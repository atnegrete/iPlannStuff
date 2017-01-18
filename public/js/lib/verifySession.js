function show_overlay(){
    var loading_page =  '<div id="loader-wrapper"></div>';
    $("body").append(loading_page);
}

function remove_overlay() {
    $("#loader-wrapper").remove();
}

function verifySession(){
    //var succeed;
    show_overlay()
    return $.ajax({
        async: false,
        type: "POST",
        url: "/projects/planner/resources/php/verifyUser.php",
        data: {
            functionname : "verifyUser"
        },
        dataType: "json",

    });
}
