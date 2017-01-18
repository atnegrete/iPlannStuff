var Task = function(task_name, task_created_date, task_due_date, task_category, reminder_dates, reminder_inputs) {
    this.task_name = task_name;
    this.task_created_date = task_created_date;
    this.task_due_date = task_due_date;
    this.task_category = task_category;
    this.reminder_dates = reminder_dates;
    this.reminder_inputs = reminder_inputs;
    this.task_id;

    // Add the given task object to the DB.
    this.addTaskToDB = function(){
        if(reminder_dates[0] == undefined){
            reminder_dates[0] = null;
            reminder_inputs[0] = new reminder(null,null);
        }
        if(reminder_dates[1] == undefined){
            reminder_dates[1] = null;
            reminder_inputs[1] = new reminder(null,null);
        }
        if(reminder_dates[2] == undefined){
            reminder_dates[2] = null;
            reminder_inputs[2] = new reminder(null,null);
        }
        if(reminder_dates[3] == undefined){
            reminder_dates[3] = null;
            reminder_inputs[3] = new reminder(null,null);
        }
        if(reminder_dates[4] == undefined){
            reminder_dates[4] = null;
            reminder_inputs[4] = new reminder(null,null);
        }
        $.ajax({
            type: "POST",
            url: "/projects/planner/resources/php/task_handlers/task_creation_handler.php",
            data: {
                task_name : task_name,
                task_created_date : task_created_date,
                task_due_date : task_due_date,
                task_category_id : task_category,
                reminder1 : reminder_dates[0],
                reminder2 : reminder_dates[1],
                reminder3 : reminder_dates[2],
                reminder4 : reminder_dates[3],
                reminder5 : reminder_dates[4],
                reminder_input1 : reminder_inputs[0].i1,
                reminder_input2 : reminder_inputs[1].i1,
                reminder_input3 : reminder_inputs[2].i1,
                reminder_input4 : reminder_inputs[3].i1,
                reminder_input5 : reminder_inputs[4].i1,
                reminder_select1 : reminder_inputs[0].s1,
                reminder_select2 : reminder_inputs[1].s1,
                reminder_select3 : reminder_inputs[2].s1,
                reminder_select4 : reminder_inputs[3].s1,
                reminder_select5 : reminder_inputs[4].s1,
            },
            dataType: "json",
            success: function(data){
                if(!('error' in data) ){
                    Task.updateScope();
                    $.bootstrapGrowl(data.result,{
                        type: 'success',
                        delay: 3000,
                        allow_dismiss: false,
                    });          
                }else{
                    $.bootstrapGrowl(data.error,{
                        type: 'danger',
                        delay: 3000,
                        allow_dismiss: false,
                    });          
                }
                //Task.resetTaskFields();
            }
        });
    }

    this.setTaskId = function(task_id) {
        this.task_id = task_id;
    }

}

// Verify the Task Inputs. 
// return true if inputs are correct.
Task.verifyTaskInputs = function(task_name, task_due_date, task_category) {
    error = null;
    if (task_name.length > 0) {
        if(task_due_date.length > 0){
            if(task_category != null){
                error = null;
            }else{
                error = "Don't forget to select a Category..";
            }
        }else {
            error = "Don't forget your Task Due Date..";
        }
    }else{
        error = "Don't forget your Task Name..";
    }

    if(error != null){
        $.bootstrapGrowl(error,{
                type: 'warning',
                delay: 3000,
                allow_dismiss: false,
        });
    }

    return error == null;
}

// Reset all task fields.
Task.resetTaskFields = function(){
    $("#new_task_name").val("");
    $("#new_task_date").val("");
    reminders = [];
    $(".new_task_reminders_li").remove();
    $("#input_n1js").val("");
    $("#select_n1js").val("min");
    $("#new_task_category").val("");
}

Task.getAllActiveTasksForUserId = function(user_id){
    $.ajax({
        type: "POST",
        url: "/projects/planner/resources/php/task_handlers/task_creation_handler.php",
        data: {
            task_name : task_name,
            task_created_date : task_created_date,
            task_due_date : task_due_date,
            task_category_id : task_category,
            reminder1 : reminders[0],
            reminder2 : reminders[1],
            reminder3 : reminders[2],
            reminder4 : reminders[3],
            reminder5 : reminders[4] 
        },
        dataType: "json",
        success: function(data){
            if(!('error' in data) ){
                $.bootstrapGrowl(data.result,{
                    type: 'success',
                    delay: 3000,
                    allow_dismiss: false,
                });          
            }else{
                $.bootstrapGrowl(data.error,{
                    type: 'danger',
                    delay: 3000,
                    allow_dismiss: false,
                });          
            }
            //Task.resetTaskFields();
        }
    });
}

Task.setCalendar = function(id, date){
    var calendar_id = "calendar" + id +"";
    var calendar = new dhtmlXCalendarObject(calendar_id);
    calendar.setDateFormat("%Y-%m-%d %H:%i:%s");
    calendar.setDate(date);
    $("#"+calendar_id).val(calendar.getFormatedDate("%M %j %h:%i %A"));
    calendar.setDateFormat("%M %j %h:%i %A");
}

Task.completeTask = function(cb_element){
    console.log("completed");
    var dh = new DateHelper();
    var task_id = cb_element.data("task-id");
    var list_item = cb_element.closest("li");

    list_item.detach();
    cb_element.data("task-active", 0);
    cb_element.attr("src","../../resources/images/ic_check_box_black_24dp_1x.png");
    $("#recently_completed_list").append(list_item);

    // Udate task on DB
    $.ajax({
        type: "POST",
        url: "/projects/planner/resources/php/task_handlers/task_update_handler.php",
        data: {
            functionname : "updateTaskToCompleted",
            task_id : task_id,
            task_completed_date : dh.convertDateToDateTimeFormat(new Date(Date.now())),
        },
        dataType: "json",
        success: function(data){
            if(!('error' in data) ){
                Task.updateOverdueBadge();
                $.bootstrapGrowl(data.result,{
                    type: 'success',
                    delay: 3000,
                    allow_dismiss: false,
                });          
            }else{
                $.bootstrapGrowl(data.error,{
                    type: 'danger',
                    delay: 3000,
                    allow_dismiss: false,
                });          
            }
        }
    });
}

Task.uncompleteTask = function(cb_element){
    console.log("uncompleted");
    var dh = new DateHelper();
    var task_id = cb_element.data("task-id");
    var list_item = cb_element.closest("li");

    cb_element.data("task-active", 1);
    cb_element.attr("src","../../resources/images/ic_check_box_outline_blank_black_24dp_1x.png");

    // Check if task is overdue, if it is add it to overdue tasks. Otherwise see if it's due this week.
    js_due_date = dh.createJavaScriptDateFromDateTime($("#calendar"+task_id).data("task-date"));
    js_current_date = new Date(Date.now());
    js_next_week = new Date(Date.now() + dh.getWeekTime());
    list_item.detach();
    if(js_current_date.getTime() > js_due_date.getTime()){
        // It's overdue.
        $("#overdue_list").append(list_item);

    }else{
        // It's not overdue. Check if it's due this week to add to Weekly list.
        if(js_due_date.getTime() < js_next_week){
            $("#weekly_list").append(list_item);
        }
    }

    // Udate task on DB
    $.ajax({
        type: "POST",
        url: "/projects/planner/resources/php/task_handlers/task_update_handler.php",
        data: {
            functionname : "updateTaskToUncompleted",
            task_id : task_id,
        },
        dataType: "json",
        success: function(data){
            if(!('error' in data) ){
                Task.updateOverdueBadge();
                $.bootstrapGrowl(data.result,{
                    type: 'success',
                    delay: 3000,
                    allow_dismiss: false,

                });        
            }else{
                $.bootstrapGrowl(data.error,{
                    type: 'danger',
                    delay: 3000,
                    allow_dismiss: false,
                });          
            }
        }
    });
}

Task.updateScope = function(){
    var scope = angular.element("#tasks_center_nav").scope();
    scope.updateScope();
}

Task.updateOverdueBadge = function(){
    var scope = angular.element("#tasks_center_nav").scope();
    scope.updateOverdueBadge();
}

Task.deleteTask = function(task_id){
    console.log("Deleting task id: " + task_id);
    var list_item = $("[data-task-id='"+task_id+"']").closest("li");
    //Udate task on DB
    $.ajax({
        type: "POST",
        url: "/projects/planner/resources/php/task_handlers/task_update_handler.php",
        data: {
            functionname : "deleteTask",
            task_id : task_id,
        },
        dataType: "json",
        success: function(data){
            if(!('error' in data) ){
                $.bootstrapGrowl(data.result,{
                    type: 'success',
                    delay: 3000,
                    allow_dismiss: false,
                });
                list_item.remove();
                Task.updateOverdueBadge();
            }else{
                $.bootstrapGrowl(data.error,{
                    type: 'danger',
                    delay: 3000,
                    allow_dismiss: false,
                });          
            }
        }
    });
}

Task.handleTaskSharingFriends = function(already_shared_friends){
    if($.isArray(already_shared_friends)){
        for(var i = 0; i < already_shared_friends.length; i++){
            var shared_friend = already_shared_friends[i];
            var friend_button = $('[data-friend-id="'+shared_friend.to_id+'"]')
            //console.log(friend_button);
            $(friend_button).css('background-image','url(../../resources/images/ic_check_circle_black_24dp_1x.png)');
            $(friend_button).data("friend-selected",1);
        }
    }
}

Task.removeFriendShareTask = function(task_id, friend_id){
    $.ajax({
        type: "POST",
        url: "/projects/planner/resources/php/task_handlers/task_sharing_handler_two.php",
        data: {
            functionname : "removeFriendShareTask",
            task_id : task_id,
            friend_id : friend_id
        },
        dataType: "json",
        success: function(data){
            if(!('error' in data) ){
                console.log("sucess removing");
                $.bootstrapGrowl(data.result,{
                    type: 'success',
                    delay: 3000,
                    allow_dismiss: false,
                });
            }else{
                console.log("failed removing");
                $.bootstrapGrowl(data.error,{
                    type: 'danger',
                    delay: 3000,
                    allow_dismiss: false,
                });          
            }
        }
    });
}

Task.addFriendShareTask = function(task_id, friend_id){
    $.ajax({
        type: "POST",
        url: "/projects/planner/resources/php/task_handlers/task_sharing_handler_two.php",
        data: {
            functionname : "addFriendShareTask",
            task_id : task_id,
            friend_id : friend_id
        },
        dataType: "json",
        success: function(data){
            if(!('error' in data) ){
                console.log("sucess adding");
                $.bootstrapGrowl(data.result,{
                    type: 'success',
                    delay: 3000,
                    allow_dismiss: false,
                });
            }else{
                console.log("failed adding");
                $.bootstrapGrowl(data.error,{
                    type: 'danger',
                    delay: 3000,
                    allow_dismiss: false,
                });          
            }
        }
    });
}

Task.addEventHandlers = function(task_id){
    // Check box
    $("input[data-task-id='"+task_id+"']").on("click", function(){
        // Change data-active
        if($(this).data("task-active") == 1){
            Task.completeTask($(this));
        }else{
            Task.uncompleteTask($(this));
        }
    });

    // Delete button
    $("input[data-delete-id='"+task_id+"']").on("click", function(){
        // Open up Delete Confirmation.
        var remove_id = $(this).data("delete-id");
        dhtmlx.confirm({
            title:"Remove Task",
            ok:"Yes", cancel:"Cancel",
            text:"Are you susre you want to permamently remove this Task?",
            callback:function(result){
                //console.log("TODO : Update DB and remove task." + remove_id);
                if(result){
                    Task.deleteTask(remove_id);
                }
            }
        });
    });
}

Task.addFriendClickedToShareEventHandlers = function(friend_id){
    // Handle Button for sharing tasks with friends.
    $("button[data-friend-id='"+friend_id+"']").on("click", function(element){
        var selected = $(this).data("friend-selected");
        // Check if the friend is already added.
        if(selected == 1){
            $(this).data("friend-selected",0);
            $(this).css('background-image','url(../../resources/images/ic_check_box_outline_blank_white_24dp_1x.png)');
            console.log("remove");
            Task.removeFriendShareTask($(this).data("share-task-id"), $(this).data("friend-id"));
        }else{
            $(this).data("friend-selected",1);
            $(this).css('background-image','url(../../resources/images/ic_check_circle_black_24dp_1x.png)');
            console.log("select");
            Task.addFriendShareTask($(this).data("share-task-id"), $(this).data("friend-id"));
        }

    });
}