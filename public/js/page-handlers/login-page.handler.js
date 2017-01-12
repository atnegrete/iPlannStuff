$(document).ready(function(){
    $("#login").validate({
        submitHandler : function(form) {
            console.log(form);
            loginForm();
        }
    });
});

function loginForm(){
    var user = document.forms["login"]["user"].value;
    var pass = document.forms["login"]["password"].value;
    $.ajax({
        type: "POST",
        url: "/projects/planner/resources/php/login.php",
        data: {
            user: user,
            password: pass
        },
        dataType: "json",
        success: function(data){
            if(data.result){
                window.location.href = ("#/home");
                $.bootstrapGrowl("Login successfull.",{
                    type: 'success',
                    delay: 3000,
                    allow_dismiss: false,
                }); 
            }else{
                $.bootstrapGrowl("Incorrect username or password.",{
                    type: 'danger',
                    delay: 3000,
                    allow_dismiss: false,
                }); 
                document.forms["login"]["password"].value = "";
            }
        }
    });
}
