$(document).ready(function() {

    $("#signup").validate({
        submitHandler : function(form) {
            if(checkForm()){
                submitForm();
            }
        }
    });

    $("#login").validate({
        submitHandler : function(form) {
            loginForm();
        }
    });
});

function checkForm(){
    var pass = document.forms["signup"]["password"].value;
    var cpass = document.forms["signup"]["password2"].value;
    var phone = document.forms["signup"]["phone"].value;

    // Check passwords match.
    if(!(pass === cpass)){
        alert("Passwords don't match.")
        return false;
    }

    if(pass.length < 6){
        alert("Password length must be 6 or greater.");
        return false;
    }

    // Check phone
    if(phone.length != 10 || !phone.match(/^[0-9]+$/)){
        alert("Invalid phone.");
        return false;
    }

    
    return true;
}


function submitForm(){
    $.ajax({
        type: "POST",
        url: "/projects/planner/resources/php/signup.php",
        dataType: "json",
        data: {
            user: document.forms["signup"]["user"].value,
            password: document.forms["signup"]["password"].value,
            email: document.forms["signup"]["email"].value,
            phone: document.forms["signup"]["phone"].value,
            carrier: document.forms["signup"]["carrier"].value
        },
        success: function(retVal){
            if( !('error' in retVal) ) {
                alert("Sucess signing up. Please log in.");
                clearSignUpForm();
            }
            else {
                document.forms["signup"]["password"].value = "";
                document.forms["signup"]["password2"].value = "";
                alert(retVal.error);
            }
        }
    });
}

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
            if(data.result == true){
                console.log("Logged in successfully");
                location.replace("../html/tasks.html");                
            }else{
                alert("Invalid username or password.");
            }
        }
    });
}

function clearSignUpForm(){
    var form = document.forms["signup"];
    form["user"].value = "";
    form["password"].value = "";
    form["password2"].value = "";
    form["email"].value = "";
    form["phone"].value = "";
    form["carrier"].value = "";
}

