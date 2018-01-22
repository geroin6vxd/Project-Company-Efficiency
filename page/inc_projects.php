<?
require_once('../settings/settings.inc.php');
if (isset($_REQUEST['id']) && ($_REQUEST['id']) != "") $id = $_REQUEST['id']; else $id = "";
$res = $project->get_project($id, false, false);
?>
<h2>Projects</h2>
<br>
<table class="finetable">
	<tr>
		<td style="width:100px;">Code</td>
		<td style="width:600px;">Caption</td>
		<td style="width:150px;">Operations</td>
	</tr>
<?
	if ($res) {
	foreach ($res as $item) {
		print('<tr>
					<td id="code' . $item->ID . '">' . $item->PROJ_YEAR . ' ' . $item->PROJ_TYPE . ' ' . $item->PROJ_NUM . '</td>
					<td id="caption' . $item->ID . '">' . $item->PROJ_CAPTION . '</td>
					<td>
						<button class="finebutton small edit" id="' . $item->ID . '">Edit</button>&nbsp;
						<button class="finebutton small delete" id="' . $item->ID . '">Del</button>
					</td>
				</tr>');
		}
	}
?>
	<tr>
		<td></td>
		<td></td>
		<td><button class="finebutton small edit" id="New">Add</button></td>
	</tr>
</table>
<div id = "editDialog" class="panel-collapse collapse" style=" overflow: visible !important;">
	<br>
	<div class = "ui-corner-all panel" style="border:1px solid silver;">
		<span class = "panel-caption finetext">Main data</span>
		<table id="projBlank" style="width: 100%;">
		<tr>
			<td style="width: 20%" class="finetext textcenter">Project Year:*</td>
			<td style="width: 30%" id="yearTD"></td>
			<td style="width: 20%" class="finetext textcenter">Start of contract:*</td>
			<td style="width: 30%" id="startTD"></td>
		</tr>
		<tr><td style="line-height: 8px;">&nbsp;</td><td></td><td></td><td></td></tr>
		<tr>
			<td class="finetext textcenter">Project Type:*</td>
			<td id="typeTD"></td>
			<td class="finetext textcenter">Finish of contract:*</td>
			<td id="finishTD"></td>
		</tr>
		<tr><td style="line-height: 8px;">&nbsp;</td><td></td><td></td><td></td></tr>
		<tr>
			<td class="finetext textcenter">Project Num:*</td>
			<td id="numTD"></td>
			<td class="finetext textcenter">Budget, ruble:*</td>
			<td id="budgetTD"></td>
		</tr>
		<tr><td style="line-height: 8px;">&nbsp;</td><td></td><td></td><td></td></tr>
		<tr>
			<td class="finetext textcenter">Project Caption:*</td>
			<td id="captionTD"></td>
			<td class="finetext textcenter">Project Description:</td>
			<td id="descriptionTD"></td>
		</tr>
		<tr><td style="line-height: 8px;">&nbsp;</td><td></td><td></td><td></td></tr>
		<tr>
			<td class="finetext textcenter">Project Manager</td>
			<td id="managerTD"></td>
			<td></td>
			<td></td>
		</tr>
		</table>
	</div>
	<br>
	<br>
	<div class="ui-corner-all panel" id="addendumArea">
		<span class = "panel-caption finetext">Addendums</span>
		<div style="overflow-y: scroll; height:160px;" id = "addendumsContent">
		</div>
	</div>
	<table style="width: 100%">
		<tr class = "textcenter">
			<td style="width: 50%"><button class="finebutton" id="save">Save</button></td>
			<td style="width: 50%"><button class="finebutton" id="cancel">Cancel</button></td>
		</tr>
	</table>
	<input type="hidden" id="dialogId">
</div>
<SCRIPT>
	$("#editDialog").dialog({
		closeText: "",
		modal:true,
		width:900,
		height:"auto",
		autoOpen: false,
		resizable: false,
		title : "Add/Edit Project",
		position: {   my: "top",
			at: "top",
			of: "#content" }
	});
	$(".finebutton#save").unbind().click(function(){
		var nums = validateNum([$("#year"), $("#num")]);
		var sumCheck = $("#budget").val().replace(/[^\d,-]/g, '').replace(/,/g, '.')
		var succBud = true;
		if (!($.isNumeric(sumCheck))){
			$("#budget").css("background", "#FFCCCC");
			succBud = false;
		}else{
			$("#budget").css("background", "#ffffff");
		}
		var texts = validateText([$("#caption"),$("#start"),$("#finish")]);
		var selections = validateSelection([$("#type")]);
		if(nums && texts && selections && succBud){
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "EDIT_PROJECT",
					id: $("#dialogId").val(),
					year:$("#year").val(),
					type:$("#type").val(),
					num:$("#num").val(),
					start:$("#start").val(),
					finish:$("#finish").val(),
					budget:sumCheck,
					caption:$("#caption").val(),
					description:$("#description").val(),
					manager:$("#manager").val()
				}
			})
				.done(function () {
					$("#editDialog").dialog("close");
					$("#projects").click();
				});
		}
	});
	$(".finebutton#cancel").unbind().click(function(){
		$("#editDialog").dialog("close");
	});
	$(".finebutton.small.edit").unbind().click(function(){
		$("#dialogId").val($(this).attr("id"));
		if($(this).attr("id") === "New"){$("#addendumArea").hide()}else{$("#addendumArea").show()}
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			datatype: JSON,
			data: {
				operation: "GET_PROJECT_TYPE_DETAIL_STR",
				id: $(this).attr("id")
			}
		})
			.done(function (msg) {
				var res = $.parseJSON(msg);
				$("#yearTD").html(res.year);
				$("#startTD").html(res.start);
				$("#finishTD").html(res.finish);
				$("#typeTD").html(res.type);
				$("#numTD").html(res.num);
				$("#budgetTD").html(res.budget);
				$("#captionTD").html(res.caption);
				$("#descriptionTD").html(res.description);
				$("#managerTD").html(res.manager);
				$("#addendumsContent").html(res.addendums);
				$("#start, #finish").unbind().MonthPicker({Button: false,MonthFormat: 'MM, yy'});
				$("#editDialog").dialog("open");
			});
	});
	$(".finebutton.small.delete").unbind().click(function(){
		var id = $(this).attr("id");
		confirmDelete('Project').then(function (answer) {
			if(answer == true){
				$.ajax({
					method: "POST",
					url: "page/executor.php",
					data: {
						operation: "DEL_PROJECT",
						id: id,
					}
				})
					.done(function (msg) {
						$("#projects").click();
					});
			}
		});

	});

</SCRIPT>
