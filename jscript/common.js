function page(p_name) {
    $.ajax
    ({
        url: 'page/' + p_name,
        cache: false,
        error: function () {
            alert('Ошибка обращения к серверу!');
            return false;
        },
        success: function (data, status) {
            $('.ui-dialog').remove();
            $('#content').html(data);
        }
    });
}
function validateText(objArray){
    var success = true;
    for (index = 0; index < objArray.length; ++index) {
        if ($(objArray[index]).val() == ""){
            $("#" + $(objArray[index]).attr("id")).css("background", "#FFCCCC");
            success = false;
        }else{
            $("#" + $(objArray[index]).attr("id")).css("background", "#ffffff");
        }
    }
    return success;
}
function validateNum(objArray){
    var success = true;
    for (index = 0; index < objArray.length; ++index) {
        if (!($.isNumeric($(objArray[index]).val()))){
            $("#" + $(objArray[index]).attr("id")).css("background", "#FFCCCC");
            success = false;
        }else{
            $("#" + $(objArray[index]).attr("id")).css("background", "#ffffff");
        }
    }
    return success;
}
function validateSelection(objArray){
    var success = true;
    for (index = 0; index < objArray.length; ++index) {
        if (($(objArray[index]).val() == "false") || ($(objArray[index]).val() == null)){
            $("#" + $(objArray[index]).attr("id")+"_chosen a").css("background", "#FFCCCC");
            success = false;
        }else{
            $("#" + $(objArray[index]).attr("id")+"_chosen a").css("background","rgba(0, 0, 0, 0) linear-gradient(#fff 20%, #f6f6f6 50%, #eee 52%, #f4f4f4 100%) repeat scroll 0 0 padding-box");
        }
    }
    return success;
}
function loadTimesheet(){
    $.ajax({
        method: "POST",
        url: "page/executor.php",
        cache: false,
        data: {
            operation: "LOAD_TIMESHEET"
        }
    })
    .done(function (msg) {
            $("#PAGE_CONTENT").html(msg);
    });
}
function loadAllTimesheets(){
    $.ajax({
        method: "POST",
        url: "page/executor.php",
        cache: false,
        data: {
            operation: "LOAD_ALL_TIMESHEETS"
        }
    })
    .done(function (msg) {
            $("#PAGE_CONTENT").html(msg);
    });
}
function confirmDelete(question){
    var request = Math.floor(Math.random() * 900) + 100;
    var defer = $.Deferred();
    $('<div id = "confirmator" class = "textcenter">' +
        'To confirm  deletion of this ' + question + ' retype this number'+
        '<h1>'+request+'</h1>'+
        '<input type="text" class="finestr textcenter" id="answer"><br>'+
        '<button class="finebutton" id="deleteYes">OK</button>'+
        '<button class="finebutton" id="deleteNo">Cancel</button>'+
        '</div>').dialog({
        closeText: "",
        modal:true,
        width:400,
        height:"auto",
        autoOpen: true,
        resizable: false,
        title : "Confirmation",
        close : function(){
            $('#confirmator').dialog("destroy").remove();
        }
    });
    $("#deleteYes").unbind().click(function(){
        if(request == $("#answer").val()){
            defer.resolve(true);
            $('#confirmator').dialog("close");
        }else{
            $("#answer").css("background", "#FFCCCC");
        }
    });
    $("#deleteNo").unbind().click(function(){
        defer.resolve(false);
        $('#confirmator').dialog("close");
    });
    return defer.promise();
}
function formatNumber(num){
    return new Intl.NumberFormat('ru-RU', {maximumFractionDigits: 2, minimumFractionDigits:2} ).format(num);
}