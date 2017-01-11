$(document).ready(function() {

    clearSignUpForm();

    $("form[name='signup']").validate({
        submitHandler : function(form) {
            if(checkForm()){
                submitForm();
            }
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
            carrier: document.forms["signup"]["carrier"].value,
            created_date: new DateHelper().convertDateToDateTimeFormat(new Date()),
            time_zone_id: document.forms["signup"]["time_zone"].value
        },
        success: function(retVal){
            if( !('error' in retVal) ) {
                $.bootstrapGrowl("Sucess signing up. Please log in.",{
                    type: 'success',
                    delay: 3000,
                    allow_dismiss: false,
                });
                clearSignUpForm();
                window.location.href = "#login";
            }
            else {
                document.forms["signup"]["password"].value = "";
                document.forms["signup"]["password2"].value = "";
                alert(retVal.error);
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
    form["time_zone"].value = "";
}

