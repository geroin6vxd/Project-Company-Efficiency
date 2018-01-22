<?
require_once('../settings/settings.inc.php');
	if($prevId){
		$event = $project->get_timesheet_event($prevId);
		$currProject = $project->get_project($event->PROJECT, false, true);
		$projId =$event->PROJECT;
		$projCode =$currProject->PROJ_YEAR.' '.$currProject->PROJ_TYPE.' '.$currProject->PROJ_NUM;
		$projTask = $event->TASK;
		$projPhase = $event->PHASE;
	}else{
		$projId = false;
		$projCode = '';
		$projTask = false;
		$projPhase = false;
	}

?>
<table style="width: 100%">
	<tr>
		<td class="finetext">Project:*</td>
		<td id="projectTD"><?=$project->get_project_chosen($projId, "project", false, true)?></td>
	</tr>
	<tr><td>&nbsp;</td><td></td></tr>
	<tr>
		<td class="finetext">Project Code:*</td>
		<td id="projectCodeTD"><input type="text" placeholder="Project code will filled automatically" class = "finestr textcenter" readonly value = "<?=$projCode?>"> </td>
	</tr>
	<tr><td>&nbsp;</td><td></td></tr>
	<tr>
		<td class="finetext">Office:*</td>
		<td id="officeTD"><?=$project->get_offices_chosen("1", "off")?></td>
	</tr>
	<tr class = "realRow"><td>&nbsp;</td><td></td></tr>
	<tr class = "realRow">
		<td class="finetext">Task:*</td>
		<td id="taskTD"><?=$project->get_task_chosen($projTask, "task")?></td>
	</tr>
    <tr class = "realRow phaseRow"><td>&nbsp;</td><td></td></tr>
	<tr class = "realRow phaseRow">
		<td class="finetext">Phase:*</td>
		<td id="phaseTD"><?=$project->get_phases_chosen($projPhase, "phas")?></td>
	</tr><tr><td>&nbsp;</td><td></td></tr>
	<tr>
		<td class="finetext">Hours:*</td>
		<td id="hoursTD"><input type="text" id = "hours" placeholder="Enter the number of hours" class = "finestr textcenter" </td>
	</tr>
</table>
<table style="width: 100%">
	<tr class = "textcenter">
		<td style="width: 50%"><button class="finebutton" id="save">Save</button></td>
		<td style="width: 50%"><button class="finebutton" id="cancel">Cancel</button></td>
	</tr>
</table>
<SCRIPT>
    var task = $("#task").val();
    var proj = $("#project").val();
    if ((proj == 4)||(proj == 5)||(proj == 6)||(proj == 7)||(proj == 8)||(proj == 9)){
        $(".realRow").hide();
        $('#task').val('').trigger('chosen:updated');
        $('#phas').val('').trigger('chosen:updated');
    }else{
        $(".realRow").show();
        if ((task == 21)||(task == 26)){
            $(".phaseRow").hide();
            $('#phas').val('').trigger('chosen:updated');
        }else{
            $(".phaseRow").show();
        }
    }

	function getProjectCode(id){
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			data: {
				operation: "GET_PROJECT_CODE",
				id: id
			}
		})
			.done(function (msg) {
				$("#projectCodeTD").html(msg);
			});
	}
    $("#project").chosen().change(function(){
        $('#task').val('').trigger('chosen:updated');
        $('#phas').val('').trigger('chosen:updated');
        proj = $(this).val();
        getProjectCode(proj);
        if(proj) {
            if ((proj == 4) ||(proj == 5) || (proj == 6) || (proj == 7) || (proj == 8) || (proj == 9)) {
                $(".realRow").hide();
            } else {
                $(".realRow").show();
            }
        }
	});

    $("#task").chosen().change(function(){
        task = $(this).val();
        if(task) {
            if ((task == 21) ||(task == 26)) {
                $(".phaseRow").hide();
                $('#phas').val('').trigger('chosen:updated');
            } else {
                $(".phaseRow").show();
            }
        }
    });

	$(".finebutton#save").unbind().click(function(){
		var nums = validateNum([$("#hours")]);
		var selections = validateSelection([$("#project"),$("#off")]);
		var addSelections = true;
		var addSelectionsPhas = true;
		if((proj != 4)&&(proj != 5)&&(proj != 6)&&(proj != 7)&&(proj != 8)&&(proj != 9)) {
            addSelections = validateSelection([$("#task")]);
        }
		if((task != 21)&&(task != 26) && (proj != 4)&&(proj != 5)&&(proj != 6)&&(proj != 7)&&(proj != 8)&&(proj != 9)) {
            addSelectionsPhas = validateSelection([$("#phas")]);
        }
		if(nums && selections && addSelections && addSelectionsPhas){
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "NEW_TIMESHEET_EVENT",
					time: <?=$time?>,
					emp: <?=$_SESSION['UID']?>,
					project:$("#project").val(),
					office:$("#off").val(),
					phase:$("#phas").val(),
					task:$("#task").val(),
					hours:$("#hours").val()
				}
			})
				.done(function () {
					$("#editTimeDialog").dialog("close");
					loadTimesheet();
				});
		}
	});
	$(".finebutton#cancel").unbind().click(function(){
		$("#editTimeDialog").dialog("close");
	});
</SCRIPT>