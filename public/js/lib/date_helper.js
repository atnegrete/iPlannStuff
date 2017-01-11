
var DateHelper = function(){

    var minute_multiplier = 60000;
    var hour_multiplier = 60 * minute_multiplier;
    var day_multiplier = 24 * hour_multiplier;
    var week_multiplier = 7 * day_multiplier;

    this.modifyDateMinutes = function (date, mod, minutes){
        if(mod === "+"){
            return new Date(date.getTime() + (minutes * minute_multiplier));
        }else if(mod === "-"){
            return new Date(date.getTime() - (minutes * minute_multiplier));
        }
    }

    this.modifyDateHours = function (date, mod, hours){
        if(mod === "+"){
            return new Date(date.getTime() + (hours * hour_multiplier));
        }else if(mod === "-"){
            return new Date(date.getTime() - (hours * hour_multiplier));
        }
    }

    this.modifyDateDays = function (date, mod, days){
        if(mod === "+"){
            return new Date(date.getTime() + (days * day_multiplier));
        }else if(mod === "-"){
            return new Date(date.getTime() - (days * day_multiplier));
        }
    }

    this.modifyDateWeeks = function (date, mod, weeks){
        if(mod === "+"){
            return new Date(date.getTime() + (weeks * week_multiplier));
        }else if(mod === "-"){
            return new Date(date.getTime() - (weeks * week_multiplier));
        }
    }

    this.convertReminderToDate = function(date, reminder){
        switch(reminder.s1){
            case "min":
                return this.modifyDateMinutes(date, "-", parseInt(reminder.i1));
                break;
            case "hr":
                return this.modifyDateHours(date, "-", parseInt(reminder.i1));
                break;
            case "day":
                return this.modifyDateDays(date, "-", parseInt(reminder.i1));
                break;
            case "week":
                return this.modifyDateWeeks(date,"-", parseInt(reminder.i1));
                break;
            default:
                break;
        }
        return "ERROR: Date not converted property.";
    }

    this.convertDateToDateTimeFormat = function(date){
        return date.getUTCFullYear() + "-" + this.twoDigits(1 + date.getMonth()) + "-" + 
                this.twoDigits(date.getDate()) + " " + this.twoDigits(date.getHours()) +
                ":" + this.twoDigits(date.getMinutes()) + ":" + this.twoDigits(date.getSeconds());
    }

    this.twoDigits = function(d) {
        if(0 <= d && d < 10) return "0" + d.toString();
        if(-10 < d && d < 0) return "-0" + (-1*d).toString();
        return d.toString();
    }

    this.getWeekTime = function(){
        return week_multiplier;
    }

    this.getDayTime = function(){
        return day_multiplier;
    }
    
    this.createJavaScriptDateFromDateTime = function(mysql_datetime){
        return new (Function.prototype.bind.apply(Date, [null].concat(mysql_datetime.split(/[\s:-]/)).map(function(v,i){return i==2?--v:v}) ));

        // var t, result = null;

        // if( typeof mysql_datetime === 'string' )
        // {
        //     t = mysql_datetime.split(/[- :]/);

        //     //when t[3], t[4] and t[5] are missing they defaults to zero
        //     result = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0));          
        // }
        // console.log(result);
        // return result;   
    }
}

DateHelper.getYesterdayAsDateTimeFormat = function(){
    var dh = new DateHelper();
    val = dh.convertDateToDateTimeFormat(new Date(Date.now() - dh.getDayTime()));
    return val;
}