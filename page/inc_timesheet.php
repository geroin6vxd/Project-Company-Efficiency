
<?
require_once('../settings/settings.inc.php');
$date = isset($_SESSION['TIMESHEET'])? $_SESSION['TIMESHEET']: date('Y-m-d');
$day_of_week = date('N', strtotime($date));
$given_date = strtotime($date);
$first_of_week = strtotime(date('Y-m-d', strtotime("- {$day_of_week} day", $given_date)));
$end_of_week = $first_of_week + 604800;
for ($i = 1; $i <= 8; $i++) {
	$week_array[] = date('d.m.Y', strtotime("+ {$i} day", $first_of_week));
}
$lastweek =  date('Y-m-d',strtotime($date . " -1 week"));
$nextweek =  date('Y-m-d',strtotime($date . " +1 week"));
$ddate = new DateTime($date);
$week = $ddate->format("W");
$year = $ddate->format("Y");
$currUser= $user->get_users($_SESSION['UID']);
?>
<link rel="stylesheet" href="../styles/timesheet.css" type="text/css" media="all"/>
<h2>Time Sheet</h2>
<button class="finebutton" id="minus">-1 week</button>
<button class="finebutton" id="today">Today</button>
<button class="finebutton" id="plus">+1 week</button>
<br>
<br>
<div id = "editTimeDialog" class="panel-collapse collapse" style=" overflow: visible !important;"></div>
<div id="grid">
	<table  id = "tsheet" border=0 cellpadding=0 cellspacing=0 width=1102 class=xl659480
	        style='border-collapse:collapse;table-layout:fixed;width:827pt'>
		<col class=xl679480 width=47 style='mso-width-source:userset;mso-width-alt: 1504;width:35pt'>
		<col class=xl659480 width=179 style='mso-width-source:userset;mso-width-alt: 5728;width:134pt'>
		<col class=xl659480 width=91 style='mso-width-source:userset;mso-width-alt: 2912;width:68pt'>
		<col class=xl659480 width=47 style='mso-width-source:userset;mso-width-alt: 1504;width:35pt'>
		<col class=xl659480 width=43 style='mso-width-source:userset;mso-width-alt: 1376;width:32pt'>
		<col class=xl679480 width=47 style='mso-width-source:userset;mso-width-alt: 1504;width:35pt'>
		<col class=xl659480 width=53 style='mso-width-source:userset;mso-width-alt: 1696;width:40pt'>
		<col class=xl659480 width=85 span=7 style='mso-width-source:userset; mso-width-alt:2720;width:64pt'>
		<tr height=20 style='mso-height-source:userset;height:15.6pt'>
			<td height=20 class=xl769480 width=47 style='height:15.6pt;width:35pt'>&nbsp;</td>
			<td class=xl779480 width=179 style=';width:134pt'>&nbsp;</td>
			<td class=xl789480 width=91 style=';width:68pt'>&nbsp;</td>
			<td class=xl789480 width=47 style=';width:35pt'>&nbsp;</td>
			<td class=xl789480 width=43 style=';width:32pt'>&nbsp;</td>
			<td class=xl1159480 width=47 style=';width:35pt'>&nbsp;</td>
			<td class=xl789480 width=53 style=';width:40pt'>&nbsp;</td>
			<td colspan=7 rowspan=4 class=xl1369480 width=425 style=';width:320pt; font-size:50px;'>Time Sheet</td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl819480 style='height:15.75pt;'>Name</td>
			<td class=xl689480 style=';'><?=$currUser->FAMILY?></td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl729480 style=';'>Year</td>
			<td class=xl709480 style=';'><?=$year?></td>
			<td class=xl689480 style=';'>&nbsp;</td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl839480 style='height:15.75pt;'>&nbsp;</td>
			<td class=xl689480 style=';'><?=$currUser->U_NAME?></td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl729480 style=';'>Week</td>
			<td class=xl709480 style=';'><?=$week?></td>
			<td class=xl689480 style=';'>&nbsp;</td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl1349480 style='height:15.75pt;'>No</td>
			<td class=xl1359480 style=';'><?=$currUser->NUMBER?></td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl709480 style=';'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl839480 style='height:15.75pt;'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl709480 style=';'>&nbsp;</td>
			<td class=xl689480 style=';'>&nbsp;</td>
			<td class=xl709480 style=';'>from</td>
			<td class=xl1479480 style=';'><?=$week_array[0]?></td>
			<td class=xl709480 style=';'>to</td>
			<td class=xl1099480 style=';'><?=$week_array[6]?></td>
			<td class=xl699480 style=';'>&nbsp;</td>
			<td class=xl699480 style=';'>&nbsp;</td>
			<td class=xl1109480 style=';'>&nbsp;</td>
		</tr>
		<tr height=22 style='height:16.5pt'>
			<td height=22 class=xl859480 style='height:16.5pt;'>&nbsp;</td>
			<td class=xl869480 style=';'>&nbsp;</td>
			<td class=xl869480 style=';'>&nbsp;</td>
			<td class=xl869480 style=';'>&nbsp;</td>
			<td class=xl869480 style=';'>&nbsp;</td>
			<td class=xl1129480 style=';'>&nbsp;</td>
			<td class=xl869480 style=';'>&nbsp;</td>
			<td class=xl1179480 style=';'>&nbsp;</td>
			<td class=xl1129480 style=';'>&nbsp;</td>
			<td class=xl1119480 style=';'>&nbsp;</td>
			<td class=xl999480 style=';'>&nbsp;</td>
			<td class=xl999480 style=';'>&nbsp;</td>
			<td class=xl999480 style=';'>&nbsp;</td>
			<td class=xl1139480 style=';'>&nbsp;</td>
		</tr>
		<tr height=42 style='height:31.5pt'>
			<td height=42 class=xl1259480 style='height:31.5pt;'>No</td>
			<td class=xl1269480 width=179 style=';;  width:134pt'>Project Name</td>
			<td class=xl1279480 width=91 style=';;  width:68pt'>Project No</td>
			<td class=xl1269480 width=47 style=';;  width:35pt'>Office</td>
			<td class=xl1269480 width=43 style=';;  width:32pt'>Task</td>
			<td class=xl1279480 width=47 style=';;  width:35pt'>Phase</td>
			<td class=xl1279480 width=53 style=';;  width:40pt'>Hours</td>
			<td class=xl1289480 width=85 style=';;  width:64pt'>Monday</td>
			<td class=xl1289480 width=85 style=';;  width:64pt'>Tuesday</td>
			<td class=xl1289480 width=85 style=';;  width:64pt'>Wednesday</td>
			<td class=xl1289480 width=85 style=';;  width:64pt'>Thursday</td>
			<td class=xl1289480 width=85 style=';;  width:64pt'>Friday</td>
			<td class=xl959480 width=85 style=';;  width:64pt'>Saturday</td>
			<td class=xl969480 width=85 style=';;  width:64pt'>Sunday</td>
		</tr>
		<tr height=22 style='height:16.5pt'>
			<td height=22 class=xl1049480 style='height:16.5pt;'>&nbsp;</td>
			<td class=xl1299480 width=179 style=';;  width:134pt'>&nbsp;</td>
			<td class=xl1309480 width=91 style=';;  width:68pt'>&nbsp;</td>
			<td class=xl1299480 width=47 style=';;  width:35pt'>&nbsp;</td>
			<td class=xl1299480 width=43 style=';;  width:32pt'>&nbsp;</td>
			<td class=xl1309480 width=47 style=';;  width:35pt'>&nbsp;</td>
			<td class=xl1309480 width=53 style=';;  width:40pt'>&nbsp;</td>
			<td class=xl1319480 width=85 style=' width:64pt'><?=$week_array[0]?></td>
			<td class=xl1319480 width=85 style=' width:64pt'><?=$week_array[1]?></td>
			<td class=xl1319480 width=85 style=' width:64pt'><?=$week_array[2]?></td>
			<td class=xl1319480 width=85 style=' width:64pt'><?=$week_array[3]?></td>
			<td class=xl1319480 width=85 style=' width:64pt'><?=$week_array[4]?></td>
			<td class=xl1329480 width=85 style=' width:64pt'><?=$week_array[5]?></td>
			<td class=xl1339480 width=85 style=' width:64pt'><?=$week_array[6]?></td>
		</tr>


<?
	$res = $project->get_timesheet($_SESSION["UID"], $first_of_week, 604799, false);
	$sharr = array();
	$count=1;
	foreach ($res as $item){$sharr[$item->PROJECT][$item->OFFICE][$item->TASK][$item->PHASE][] = array($item->P_DATE, $item->HOURS, $item->ID);}
	foreach($sharr as $proj => $office_array){
		$currProj = $project->get_project($proj, false, true);
		foreach($office_array as $office=>$task_array){
			$currOffice = $project->get_offices($office);
			foreach($task_array as $task=>$phase_array){
				$currTask = $project->get_task($task);
				foreach ($phase_array as $phase=>$date_array) {
					$currPhase = $project->get_phases($phase);
					print('		<tr height=21 style="height:15.75pt">');
					print('		<td height=21 class=xl1189480 style="height:15.75pt">'.$count.'</td>
								<td class=xl1199480 width=179 style=";width:134pt">'.$currProj->PROJ_CAPTION.'</td>
								<td class=xl1209480 width=91 style=";width:68pt">'.$currProj->PROJ_YEAR.' '.$currProj->PROJ_TYPE.' '.$currProj->PROJ_NUM.'</td>
								<td class=xl1199480 width=47 style=";width:35pt" title="'.$currOffice->CAPTION.'">'.$currOffice->CODE.'</td>
								<td class=xl1199480 width=43 style=";width:32pt" title="'.$currTask->CAPTION.'">'.$currTask->CODE.'</td>
								<td class=xl1209480 width=47 style=";width:35pt" title="'.$currPhase->CAPTION.'">'.$currPhase->CODE.'</td>
								<td class="xl1219480 sumweek" width=53 style=";width:40pt">&nbsp;</td>');
								$curr_date_begin = $first_of_week;
								$curr_date_end = $first_of_week + 86400;
								for($i = 1; $i <= 7 ; $i++){
									$value='';
									$tdid='';
									$class = ($i > 5) ? 'hourcell xl939480 empty' : 'hourcell empty';
									foreach ($date_array as $item){
										if (($item[0] >= $curr_date_begin) && ($item[0] < $curr_date_end)) {
											$value = $item[1];
											$tdid = 'id='.$item[2];
											$class = ($i > 5) ? 'hourcell xl939480 filled' : 'hourcell filled';
										}
									}
									print('<td date = "'.$curr_date_begin.'" class="'.$class.' day'.$i.'" width=85 style=";width:64pt" '.$tdid.'>'.$value.'</td>');
									$curr_date_begin = $curr_date_end;
									$curr_date_end = $curr_date_begin + 86400;
								};
					print("</tr>");
					$count++;
				}
			}
		}
	}
?>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl1189480 style='height:15.75pt'>&nbsp;</td>
			<td class=xl1199480 width=179 style=';width:134pt'>&nbsp;</td>
			<td class=xl1209480 width=91 style=';width:68pt'>&nbsp;</td>
			<td class=xl1199480 width=47 style=';width:35pt'>&nbsp;</td>
			<td class=xl1199480 width=43 style=';width:32pt'>&nbsp;</td>
			<td class=xl1209480 width=47 style=';width:35pt'>&nbsp;</td>
			<td class=xl1219480 width=53 style=';width:40pt'>&nbsp;</td>
<?
				$curr_date_begin = $first_of_week;
				$curr_date_end = $curr_date_begin + 86400;
				for($i = 1; $i <= 7 ; $i++){
				$class = ($i > 5) ? 'hourcell xl939480 empty' : 'hourcell empty';
				print('<td date = "'.$curr_date_begin.'" class="'.$class.' day'.$i.'" width=85 style=";width:64pt"></td>');
				$curr_date_begin = $curr_date_end;
				$curr_date_end = $curr_date_begin + 86400;
			};
?>
		</tr>
		<tr height=22 style='height:16.5pt'>
			<td height=22 class=xl1049480 style='height:16.5pt;'>&nbsp;</td>
			<td class=xl1059480 >Total Hours</td>
			<td class=xl1069480 >&nbsp;</td>
			<td class=xl1079480 >&nbsp;</td>
			<td class=xl1079480 >&nbsp;</td>
			<td class=xl1169480 >&nbsp;</td>
			<td class="xl1089480" id="allsum"></td>
			<td class="allday1 xl1089480" ></td>
			<td class="allday2 xl1089480" ></td>
			<td class="allday3 xl1089480" ></td>
			<td class="allday4 xl1089480" ></td>
			<td class="allday5 xl1089480" ></td>
			<td class="allday6 xl979480" width=85 style=' width:64pt'></td>
			<td class="allday7 xl989480" width=85 style='width:64pt'></td>
		</tr>
	</table>
</div>

<SCRIPT>
	// Starting stuff
	$("#editTimeDialog").dialog({
		closeText: "",
		modal:true,
		width:500,
		height:"auto",
		autoOpen: false,
		resizable: false,
		title : "Add/Edit/Remove Time",
		position: {   my: "center",
						at: "center",
					of: "#content" }
	});

	// Week Counting per project
	$(".sumweek").each(function(){
		var sum = 0;
		$(this).siblings(".hourcell").each(function(){
			sum += Number($(this).text()) || 0;
		});
		$(this).text(sum);
	});
	//Week counting per Day
	for(var i=1;i<=7; i++){
		var sum = 0;
		$(".allday"+i).each(function(){
			$(".day"+i).each(function(){
				sum += Number($(this).text()) || 0;
			});
			$(this).text(sum);
		});
	}
	// Total hours
	var sum = 0;
	$(".sumweek").each(function(){
		sum += Number($(this).text()) || 0;
	});
	$("#allsum").text(sum);
	// Date navigator
	$("#minus").unbind().click(function () {
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			data: {
				operation: "SET_DATE",
				date: "<?=$lastweek?>"
			}
		})
			.done(function (msg) {
				loadTimesheet();
			});
	});
	$("#plus").unbind().click(function () {
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			data: {
				operation: "SET_DATE",
				date: "<?=$nextweek?>"
			}
		})
		.done(function (msg) {
			loadTimesheet();
		});
	});
	$("#today").unbind().click(function () {
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			data: {
				operation: "SET_DATE",
				date: "<?=date('Y-m-d')?>"
			}
		})
		.done(function (msg) {
			loadTimesheet();
		});
	});
	// Cell tooltiper
	$(".hourcell.filled").attr("title", "Click to edit or delete...")
	$(".hourcell.empty").attr("title", "Click to fill...")
	//handlers
	function loadEditDialog(eventId){
		$("#editTimeDialog").html("<br><h2 class='textcenter'>Loading...</h2>");
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			data: {
				operation: "LOAD_EDIT_TIME_DIALOG",
				id: eventId
			}
		})
		.done(function (msg) {
			$("#editTimeDialog").html(msg);
		});
	}
	function loadNewDialog(time, prevId){
		$("#editTimeDialog").html("<br><h2 class='textcenter'>Loading...</h2>");
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			data: {
				operation: "LOAD_NEW_TIME_DIALOG",
				time: time,
				prevId:prevId,
			}
		})
			.done(function (msg) {
				$("#editTimeDialog").html(msg);
			});
	}
	$(".filled").unbind().click(function(){
		loadEditDialog($(this).attr("id"));
		$("#editTimeDialog").dialog("open");
	});
	$(".empty").unbind().click(function(){
		var prevId;
		prevId = $(this).prevAll(".filled").attr('id');
		if (prevId ==  undefined) prevId = $(this).nextAll(".filled").attr('id');
		if (prevId ==  undefined) prevId = false;
		loadNewDialog($(this).attr("date"), prevId);
		$("#editTimeDialog").dialog("open");
	});
</SCRIPT>