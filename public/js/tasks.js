
// function verifySession(){
//     $.ajax({
//         async: false,
//         type: "POST",
//         url: "/projects/planner/resources/php/verifyUser.php",
//         data: {
//             functionname : "verifyUser"
//         },
//         dataType: "json",
//         success: function(data){
//             if(data == true){
//                 console.log("Logged in successfully");
//                 succeed = true;
//             }else{
//                 alert("Please login.")
//                 window.location.href = (window.location.href + "#/login");
//                 succeed = false;
//             }
//         }
//     });
//     return succeed;
// }

// if(verifySession()) {
    
//     // // Initialize reminders array.
//     // reminders = [];

//     // $(document).ready(function(){

//     //     // Verify task inputs & create a new Task.
//     //     $("#add_new_task_btn").click(function(){
//     //         var date_helper = new DateHelper();
//     //         var task_name = $("#new_task_name").val();
//     //         var task_due_date = myCalendar.getFormatedDate("%Y-%m-%d %H:%i:%s");
//     //         var task_category = parseInt($("#new_task_category").val());
//     //         if(Task.verifyTaskInputs(task_name, $("#new_task_date").val(), task_category)){
//     //             reminders_dates = [];
//     //             // Generate reminder dates.
//     //             for(i = 0; i < reminders.length; i++){
//     //                 var convertedReminder = date_helper.convertReminderToDate(new Date(myCalendar.getFormatedDate("%F %j, %Y %G:%i:%s")),reminders[i]);
//     //                 reminders_dates.push(date_helper.convertDateToDateTimeFormat(convertedReminder));
//     //             }
//     //             // Create task and add it to DB.
//     //             var task_created_date = date_helper.convertDateToDateTimeFormat(new Date());
//     //             var new_task = new Task(task_name, task_created_date, task_due_date, task_category, reminders_dates, reminders);
//     //             console.log(new_task);
//     //             new_task.addTaskToDB();
//     //         }
//     //     });

//     //     // Verify reminders inputs, create new reminder, add to reminders[]
//     //     $("#new_task_add_reminder").click(function(){
//     //         var i1 = $("#input_n1js").val();
//     //         //var i2 = $("#input_n2js").val();
//     //         var s1 = $("#select_n1js").val();
//     //         // var s2 = $("#select_n2js").val();
//     //         var error = "Error: Positive Integers for reminders.";
//     //         if(isPositiveInteger(i1) && (reminders.length < 5)){
//     //             var notification = "- " + i1 + " " + s1 + " before";
//     //             $("#new_task_reminders_ul").append("<li class='new_task_reminders_li'>" + notification + "</li>");
//     //             reminders.push(new reminder(i1, s1));
//     //         }else {
//     //             if(reminders.length >= 5){
//     //                 error = "Five reminders limit reached."
//     //             }
//     //             $.bootstrapGrowl(error,{
//     //                 type: 'danger',
//     //                 delay: 3000,
//     //                 allow_dismiss: false,
//     //             });
//     //         }
//     //     });

//     // });

//     // var myCalendar;
//     // function doOnLoad(){
//     //     newTaskCalendar();
//     // }

//     // function newTaskCalendar() {
//     //     myCalendar = new dhtmlXCalendarObject({input: "new_task_date", button: "calendar_icon"});
//     //     myCalendar.showToday();
//     //     myCalendar.showTime();
//     //     myCalendar.setDateFormat("%M %j @ %h:%i %A");
//     // }

//     // var reminder = function(i1, s1) {
//     //     this.i1 = i1; 
//     //     this.s1 = s1;
//     // }

// }
