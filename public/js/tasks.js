verifySession();

$(document).ready(function(){
    $('#create_task').click(function(){
        console.log(myCalendar.getFormatedDate("%h.%i.%A"));
    });

    $('#logout').on("click",function(){
        logout();
    });
    
});

var myCalendar;
function doOnLoad(){
    startCalendar();
}

function startCalendar() {
    myCalendar = new dhtmlXCalendarObject({input: "new_task_date", button: "calendar_icon"});
  
}

$("#save_new_task_btn").on("click",function(){
    
});

function Task(name, due_date, category, notifications){
    this.name = name;
    this.due_date = due_date;
    this.category = category;
    this.notifications = notifications;
}

function verifySession(){
    $.ajax({
        type: "POST",
        url: "/projects/planner/resources/php/verifyUser.php",
        data: {
            functionname : "verifyUser"
        },
        dataType: "json",
        success: function(data){
            if(data == true){
                console.log("Logged in successfully");
            }else{
                alert("Please login.")
                window.location.href = ("signup.html");
            }
        }
    });
}

function logout(){
        console.log("logoutttt");

    $.post("/projects/planner/resources/php/verifyUser.php",
    {
        functionname: "logout"
    },
    function(data, status){
        console.log(status);
        if(data == "true"){
            alert("logout out successfully");
            window.location.href = ("signup.html");
        }
    });
}
   