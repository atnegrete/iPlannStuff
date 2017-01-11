var reminder = function(i1, s1) {
    this.i1 = i1; 
    this.s1 = s1;
}


var newTaskCalendar;
function doOnLoad(){
    newTaskCalendar();
}

function newTaskCalendar() {
    newTaskCalendar = new dhtmlXCalendarObject({input: "new_task_date", button: "calendar_icon"});
    //myCalendar.showToday();
    newTaskCalendar.showTime();
    newTaskCalendar.setDateFormat("%M %j @ %h:%i %A");
}