<?php
require_once('../settings/settings.inc.php');
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "LOGOUT") {
	$user->logout();
	die(TRUE);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_PROJECTS_TYPES") {
	if (isset($_REQUEST['id']) && ($_REQUEST['id']) != "") $id = $_REQUEST['id']; else $id = "";
	$res = $project->get_project_classes($id);
	$result = '<h2>Projects Types</h2><br><table class="finetable" id="prjectsTypesTable">';
	$result .= '<tr><td style="width:100px;">Code</td><td style="width:500px;">Type</td><td style="width:150px;">Operations</td></tr>';
	if ($res) {
		foreach ($res as $item) {
			$result .= '<tr><td class="textcenter code editable" id="' . $item->ID . '">' . $item->ABBR . '</td><td class="caption editable" id="' . $item->ID . '">' . $item->CAPTION . '</td><td><button class="finebutton small delete" id="' . $item->ID . '">Del</button></td></tr>';
		}
	}
	$result .= '<tr><td><input type="text" class="finestr textcenter" id="codeNew"></td><td><input type="text" class="finestr" id="captionNew"></td><td><button class="finebutton small add" id="New">Add</button></td></tr>';
	$result .= '</table>';
	$result .= '<SCRIPT>';
	$result .= '$("#prjectsTypesTable").editableTableWidget();
				$(".code").on("change", function(evt, newValue) {
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						datatype: JSON,
						data: {
							operation: "CHANGE_PROJECTS_TYPES_CODE",
							id: $(this).attr("id"),
							code: newValue
						}
					})
					.done(function () {
						$("#projectsTypes").click();
					})
				});
				$(".caption").on("change", function(evt, newValue) {
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						datatype: JSON,
						data: {
							operation: "CHANGE_PROJECTS_TYPES_CAPTION",
							id: $(this).attr("id"),
							caption: newValue
						}
					})
					.done(function () {
						$("#projectsTypes").click();
				})
			});

			$(".finebutton.small.add").unbind().click(function(){
				if (validateText([$("#codeNew"), $("#captionNew")])){
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						data: {
							operation: "ADD_PROJECTS_TYPES",
							code: $("#codeNew").val(),
							caption: $("#captionNew").val(),
							}
					})
					.done(function (msg) {
						$("#projectsTypes").click();
					});
				}
			});';
	$result .= '$(".finebutton.small.delete").unbind().click(function(){
					var sender = $(this);
					confirmDelete("Project Type").then(function (answer) {
						if(answer == true){
							$.ajax({
								method: "POST",
								url: "page/executor.php",
								data: {
									operation: "DEL_PROJECTS_TYPES",
									id: sender.attr("id"),
									}
							})
							.done(function (msg) {
								$("#projectsTypes").click();
							});
						}
					});
				});';
	$result .= '</SCRIPT>';
	die ($result);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_PROJECT_CODE") {
	if (isset($_REQUEST['id']) && ($_REQUEST['id']) != "") $id = $_REQUEST['id']; else $id = "xxx";
	$res = $project->get_project_code($id);
	$result = '<input type="text" placeholder="Project code will filled automatically" class = "finestr textcenter" readonly value="'.trim($res).'">';
	die ($result);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_PROJECT_ADDENDUM") {
	$projId = $_REQUEST['projId'];
	$id = $_REQUEST['id'];
	$start = $stuff->set_month_to_timestamp($_REQUEST['start']);
	$caption = $_REQUEST['caption'];
	$budget = $_REQUEST['budget'];
	$project->edit_addendum($id, $projId, $start, $caption, $budget);
	$result= $project->get_addendums_table($projId);
	die ($result);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "DEL_PROJECT_ADDENDUM") {
	$id = $_REQUEST['id'];
	$projId = $_REQUEST['projId'];
	$project->delete_addendum($id);
	$result= $project->get_addendums_table($projId);
	die ($result);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_PROJECT_TYPE_DETAIL_STR") {
	$id = $_REQUEST['id'];;
	$res = $project->get_project($id, false, false);
	$types = $project->get_project_classes();
	$result = array();
	$result['year']= ($id == "New")? '<input type="text" class = "finestr textcenter" id = "year" value="' .date("Y").'">' : '<input type="text" class = "finestr textcenter" id = "year" value="' .$res->PROJ_YEAR.'">';
	$result['start']= ($id == "New")?'<input type="text" class = "finestr textcenter" readonly id = "start" value="'.date('F, Y').'">' : '<input type="text" readonly class = "finestr textcenter" id = "start" value="' .$stuff->get_month_from_timestamp($res->PROJ_START).'">';
	$result['type']= ($id == "New")? $project->get_project_classes_chosen(false, "type") : $project->get_project_classes_chosen($res->PROJ_TYPE, "type");
	$result['finish']= ($id == "New")?'<input type="text" class = "finestr textcenter" readonly id = "finish" value="'.date('F, Y').'">' : '<input type="text" readonly class = "finestr textcenter" id = "finish" value="' .$stuff->get_month_from_timestamp($res->PROJ_FINISH).'">';
	$result['num']= ($id == "New")?  '<input type="text" class = "finestr textcenter" id = "num" value="' .$project->get_project_max_number().'">' : '<input type="text" class = "finestr textcenter" id = "num" value="' .$res->PROJ_NUM.'">';
	$result['budget']= ($id == "New")? '<input type="text" class = "finestr textcenter" id = "budget" value="0.00">' : '<input type="text" class = "finestr textcenter" id = "budget" value="' .$stuff->format($res->PROJ_BUDGET, 2, '.', '').'">';
	$result['budget'].='	<SCRIPT>
		$("#budget").focus(function(){
		$(this).val(Number($(this).val().replace(/[^\d,-]/g, "").replace(/,/g, ".")) || 0);
		})
		$("#budget").blur(function(){
			$(this).val(formatNumber($(this).val()));
		})
		</SCRIPT>';
	$result['caption']= ($id == "New")? '<textarea rows="2" class = "finearea" id = "caption"></textarea>' : '<textarea rows="2" class = "finearea" id = "caption">'.$res->PROJ_CAPTION.'</textarea>';
	$result['description']= ($id == "New")? '<textarea rows="5" class = "finearea" id = "description"></textarea>' : '<textarea rows="5" class = "finearea" id = "description">'.$res->PROJ_DESCRIPTION.'</textarea>';
	$result['manager']= ($id == "New")?  $user->get_users_chosen(false, 'manager'): $user->get_users_chosen($res->PROJ_MANAGER, 'manager');
	$result['addendums']=($id == "New")? "<div class= 'textcenter' style = 'height:100%;font-size:16px; vertical-align:middle;'><br><br><br>You can add addendums after you save the main project data!</div>" : $project->get_addendums_table($id);
	die (json_encode($result));
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "CHANGE_PROJECTS_TYPES_CODE") {
	$id = $_REQUEST['id'];
	$code = $_REQUEST['code'];
	$res = $project->edit_project_classes_code($id, $code);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "CHANGE_PROJECTS_TYPES_CAPTION") {
	$id = $_REQUEST['id'];
	$caption = $_REQUEST['caption'];
	$res = $project->edit_project_classes_caption($id, $caption);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_PROJECTS_TYPES") {
	$code = $_REQUEST['code'];
	$caption = $_REQUEST['caption'];
	 $res = $project->add_project_classes($code, $caption);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "DEL_PROJECTS_TYPES"){
	$id = $_REQUEST['id'];
	$res = $project->delete_project_classes($id);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_USERS") {
	if (isset($_REQUEST['id']) && ($_REQUEST['id']) != "") $id = $_REQUEST['id']; else $id = "";
	$res = $user->get_users($id, false);
	$result = '<h2>Employees</h2><br><table id="usersTable" class="finetable">';
	$result .= '<tr><td style="width:30px; font-weight: bold" class="textcenter">No</td><td style="width:50px;font-weight: bold">Login</td><td style="width:200px;font-weight: bold">Family</td><td style="width:200px;font-weight: bold">Name</td><td style="width:200px;font-weight: bold">Role</td><td style="width:30px;font-weight: bold">Rated</td><td style="width:150px;font-weight: bold">Last login</td><td style="width:100px;font-weight: bold">Operations</td></tr>';
	if ($res) {
		foreach ($res as $item) {
			$rated = ($item->RATED == 1) ? 'checked' : '';
			$result .= '<tr><td class="textcenter editable number" id="' . $item->ID . '">' . $item->NUMBER . '</td><td class="textcenter editable abbr" id="' . $item->ID . '">' . $item->ABBR . '</td><td class="editable family" id="' . $item->ID . '">' . $item->FAMILY . '</td><td class="editable name" id="' . $item->ID . '">' . $item->U_NAME . '</td><td>' . $user->get_roles($item->HOMEPAGE , $item->ID, 'exists') . '</td><td><input type="checkbox" class = "rated" '.$rated.'  id="' . $item->ID . '" ></td><td>'.date("H:i m.d.Y", $item->LAST_ACTIVITY).'</td><td><button class="finebutton small delete" id="' . $item->ID . '">Del</button></td></tr>';
		}
	}
	$result .= '<tr><td><input type="text" class="finestr textcenter" id="numberNew"></td><td><input type="text" class="finestr textcenter" id="abbrNew"></td><td><input type="text" class="finestr" id="familyNew"></td><td><input type="text" class="finestr" id="nameNew"></td><td>' . $user->get_roles(false , 'roleNew', 'roleNew') . '</td><td><input type="checkbox"  id="ratedNew" ></td><td></td><td><button class="finebutton small add" id="New">Add</button></td></tr>';
	$result .= '</table>';
	$result .= '<SCRIPT>';
	$result .= '
		$("#usersTable").editableTableWidget();
		$(".number").on("change", function(evt, newValue) {
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			datatype: JSON,
			data: {
				operation: "EDIT_USER_NUMBER",
				id: $(this).attr("id"),
				number: newValue
			}
		})
			.done(function () {
				$("#users").click();
			});
		});
		$(".abbr").on("change", function(evt, newValue) {
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			datatype: JSON,
			data: {
				operation: "EDIT_USER_LOGIN",
				id: $(this).attr("id"),
				login: newValue
			}
		})
			.done(function () {
				$("#users").click();
			});
		});
		$(".family").on("change", function(evt, newValue) {
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			datatype: JSON,
			data: {
				operation: "EDIT_USER_FAMILY",
				id: $(this).attr("id"),
				family: newValue
			}
		})
			.done(function () {
				$("#users").click();
			});
		});
		$(".name").on("change", function(evt, newValue) {
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			datatype: JSON,
			data: {
				operation: "EDIT_USER_NAME",
				id: $(this).attr("id"),
				name: newValue
			}
		})
			.done(function () {
				$("#users").click();
			});
		});
		$(".rated").on("change", function(evt, newValue) {
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			datatype: JSON,
			data: {
				operation: "EDIT_USER_RATED",
				id: $(this).attr("id"),
				rated: $(this).prop("checked")
			}
		})
			.done(function () {
				$("#users").click();
			});
		});

	$(".roles_chosen.exists").chosen({disable_search_threshold: 5, allow_single_deselect: true}).change(function(){
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			data: {
				operation: "EDIT_USER_HOMEPAGE",
				id: $(this).attr("id"),
				homepage: $(this).val()
			}
		})
		.done(function (msg) {
			$("#users").click();
		});
	});

	$(".roles_chosen.roleNew").chosen({disable_search_threshold: 5, allow_single_deselect: true});';

	$result .= '$(".finebutton.small.delete").unbind().click(function(){
					var sender = $(this);
					confirmDelete("Employee").then(function (answer) {
						if(answer == true){
							$.ajax({
								method: "POST",
								url: "page/executor.php",
								data: {
									operation: "DEL_USER",
									id: sender.attr("id"),
									}
							})
							.done(function (msg) {
								$("#users").click();
							});
						}
					});
				});';

	$result .= '$(".finebutton.small.add#New").unbind().click(function(){
					var texts = validateText([$("#numberNew"),$("#abbrNew"),$("#familyNew"),$("#nameNew")]);
					if(texts){
						$.ajax({
							method: "POST",
							url: "page/executor.php",
							data: {
								operation: "ADD_USER",
								number: $("#numberNew").val(),
								abbr: $("#abbrNew").val(),
								family: $("#familyNew").val(),
								name: $("#nameNew").val(),
								role: $("#roleNew").val(),
								rated: $("#ratedNew").prop("checked"),
							}
						})
						.done(function (msg) {
							$("#users").click();
						});
					}
				});';
	$result .= '</SCRIPT>';
	die ($result);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_USER") {
	$number = $_REQUEST['number'];
	$login = $_REQUEST['abbr'];
	$family = $_REQUEST['family'];
	$name = $_REQUEST['name'];
	$role = $_REQUEST['role'];
	$rate = $_REQUEST['rated'];
	$res = $user->add_user($number,$login, $family, $name, $role, $rate);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_USER_HOMEPAGE") {
	$id = $_REQUEST['id'];
	$homepage = $_REQUEST['homepage'];
	$res = $user->edit_user_homepage($id, $homepage);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_USER_NUMBER") {
	$id = $_REQUEST['id'];
	$number = $_REQUEST['number'];
	$res = $user->edit_user_number($id, $number);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_USER_LOGIN") {
	$id = $_REQUEST['id'];
	$login = $_REQUEST['login'];
	$res = $user->edit_user_login($id, $login);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_USER_FAMILY") {
	$id = $_REQUEST['id'];
	$family = $_REQUEST['family'];
	$res = $user->edit_user_family($id, $family);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_USER_NAME") {
	$id = $_REQUEST['id'];
	$name = $_REQUEST['name'];
	$res = $user->edit_user_name($id, $name);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_USER_RATED") {
	$id = $_REQUEST['id'];
	$rated = $_REQUEST['rated'];
	$res = $user->edit_user_rated($id, $rated);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "DEL_USER"){
	$id = $_REQUEST['id'];
	$res = $user->delete_user($id);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_USERS_COST") {
	if (isset($_REQUEST['id']) && ($_REQUEST['id']) != "") $id = $_REQUEST['id']; else $id = "";
	$res = $user->get_rated_users($id);
	$result = '<h2>Employees Costs</h2><br><table class="finetable" id="ratesTable">';
	$result .= '<tr><td style="width:100px;font-weight: bold">No</td><td style="width:500px;font-weight: bold">Employee</td><td style="width:150px;font-weight: bold">Hourly rates</td></tr>';
	if ($res) {
		foreach ($res as $item) {
			$result .= '<tr><td class=" textcenter">' . $item->NUMBER . '</td><td style="text-align: left;">' . $item->FAMILY . ' ' . $item->U_NAME . '</td><td style="text-align: right;" class=" editable" id="' . $item->ID . '">' . $stuff->format($item->COST) . '</td></tr>';
		}
	}
	$result .= '</table>';
	$result .= '<SCRIPT>';
	$result .= '$("#ratesTable").editableTableWidget({formatting:true});
				$(".editable").on("validate", function(evt, newValue) {
					var fnum = newValue.replace(/[^\d,-]/g, "").replace(/,/g, ".");
					return ($.isNumeric(fnum) || newValue =="");
				}).on("change", function(evt, newValue) {
					if(newValue == ""){$(this).text("0")}
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						data: {
						operation: "EDIT_COST",
						id: $(this).attr("id"),
						cost: newValue.replace(/[^\d,-]/g, "").replace(/,/g, ".")
						}
					}).done(function () {
						$("#usersCosts").click();
					});
				});
';
	$result .= '</SCRIPT>';
	die ($result);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "LOAD_SETTINGS") {
	ob_start();
	include_once('inc_user_settings.php');
	die (ob_get_clean());
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_COST") {
	$id = $_REQUEST['id'];
	$cost = $_REQUEST['cost'];
	$res = $user->edit_user_cost($id,$cost);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_USER_PASSWORD") {
	$uid = $_REQUEST['uid'];
	$passwd = $_REQUEST['password'];
	$res = $user->edit_user_password($uid,$passwd);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_PROJECTS") {
	ob_start();
	include_once('inc_projects.php');
	die (ob_get_clean());
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_PROJECT") {
	$id = $_REQUEST['id'];
	$year = $_REQUEST['year'];
	$type = $_REQUEST['type'];
	$num = $_REQUEST['num'];
	$caption = $_REQUEST['caption'];
	$description = $_REQUEST['description'];
	$manager = $_REQUEST['manager'];
	$start = $_REQUEST['start'];
	$finish = $_REQUEST['finish'];
	$budget = $_REQUEST['budget'];
	if ($id == "New") $res = $project->add_project($year, $type, $num, $caption, $description,$stuff->set_month_to_timestamp($start),$stuff->set_month_to_timestamp($finish),$budget, $manager); else $res = $project->edit_project($id, $year, $type, $num, $caption, $description,$stuff->set_month_to_timestamp($start),$stuff->set_month_to_timestamp($finish),$budget, $manager);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "DEL_PROJECT") {
	$id = $_REQUEST['id'];
	$res = $project->delete_project($id);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "SET_DATE") {
	 $_SESSION['TIMESHEET'] = $_REQUEST['date'];
	die (true);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "SET_DATE_ALL") {
	 $_SESSION['TIMESHEET_ALL'] = $_REQUEST['date'];
	die (true);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "SET_CUR_YEAR_FOR_COSTS") {
	 $_SESSION['CUR_YEAR'] = $_REQUEST['year'];
	die (true);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "SET_CUR_YEAR_FOR_PAYMENTS") {
	 $_SESSION['CUR_PAYMENTS_YEAR'] = $_REQUEST['year'];
	die (true);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "LOAD_TIMESHEET") {
	ob_start();
	include_once('inc_timesheet.php');
	die (ob_get_clean());
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "LOAD_ALL_TIMESHEETS") {
	ob_start();
	include_once('inc_all_timesheets.php');
	die (ob_get_clean());
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_OFFICES") {
	if (isset($_REQUEST['id']) && ($_REQUEST['id']) != "") $id = $_REQUEST['id']; else $id = "";
	$res = $project->get_offices($id);
	$result = '<h2>Offices</h2><br><table class="finetable" id="officesTable">';
	$result .= '<tr><td style="width:100px;font-weight: bold;">Code</td><td style="width:500px;font-weight: bold;">Caption</td><td style="width:150px;font-weight: bold;">Operations</td></tr>';
	if ($res) {
		foreach ($res as $item) {
			$result .= '<tr><td class="textcenter editable code" id="' . $item->ID . '">' . $item->CODE . '</td><td class="editable caption" id="' . $item->ID . '">' . $item->CAPTION . '</td><td><button class="finebutton small delete" id="' . $item->ID . '">Del</button></td></tr>';
		}
	}
	$result .= '<tr><td><input type="text" class="finestr textcenter" id="codeNew"></td><td><input type="text" class="finestr" id="captionNew"></td><td><button class="finebutton small add" id="New">Add</button></td></tr>';
	$result .= '</table>';
	$result .= '<SCRIPT>';
	$result .= '$("#officesTable").editableTableWidget();
				$(".code").on("change", function(evt, newValue) {
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						datatype: JSON,
						data: {
							operation: "CHANGE_OFFICES_CODE",
							id: $(this).attr("id"),
							code: newValue
						}
					})
					.done(function () {
						$("#offices").click();
					})
				});
				$(".caption").on("change", function(evt, newValue) {
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						datatype: JSON,
						data: {
							operation: "CHANGE_OFFICES_CAPTION",
							id: $(this).attr("id"),
							caption: newValue
						}
					})
					.done(function () {
						$("#offices").click();
				})
			});

			$(".finebutton.small.add").unbind().click(function(){
				if (validateText([$("#codeNew"), $("#captionNew")])){
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						data: {
							operation: "ADD_OFFICES",
							code: $("#codeNew").val(),
							caption: $("#captionNew").val(),
							}
					})
					.done(function (msg) {
						$("#offices").click();
					});
				}
			});';
	$result .= '$(".finebutton.small.delete").unbind().click(function(){
					var sender = $(this);
					confirmDelete("Office").then(function (answer) {
						if(answer == true){
							$.ajax({
								method: "POST",
								url: "page/executor.php",
								data: {
									operation: "DEL_OFFICES",
									id: sender.attr("id"),
									}
							})
							.done(function (msg) {
								$("#offices").click();
							});
						}
					});
				});';
	$result .= '</SCRIPT>';
	die ($result);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "CHANGE_OFFICES_CODE") {
	$id = $_REQUEST['id'];
	$code = $_REQUEST['code'];
	$res = $project->edit_offices_code($id, $code);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "CHANGE_OFFICES_CAPTION") {
	$id = $_REQUEST['id'];
	$caption = $_REQUEST['caption'];
	$res = $project->edit_offices_caption($id, $caption);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_OFFICES") {
	$id = $_REQUEST['id'];
	$code = $_REQUEST['code'];
	$caption = $_REQUEST['caption'];
	$res = $project->add_offices($code, $caption);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "DEL_OFFICES"){
	$id = $_REQUEST['id'];
	$res = $project->delete_offices($id);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_TASKS") {
	if (isset($_REQUEST['id']) && ($_REQUEST['id']) != "") $id = $_REQUEST['id']; else $id = "";
	$res = $project->get_task($id);
	$result = '<h2>Tasks</h2><br><table class="finetable" id="tasksTable">';
	$result .= '<tr><td style="width:100px;font-weight: bold;">Code</td><td style="width:500px;font-weight: bold;">Caption</td><td style="width:150px;font-weight: bold;">Operations</td></tr>';
	if ($res) {
		foreach ($res as $item) {
			$result .= '<tr><td class="textcenter editable code" id="' . $item->ID . '">' . $item->CODE . '</td><td class="editable caption" id="' . $item->ID . '">' . $item->CAPTION . '</td><td><button class="finebutton small delete" id="' . $item->ID . '">Del</button></td></tr>';
		}
	}
	$result .= '<tr><td><input type="text" class="finestr textcenter" id="codeNew"></td><td><input type="text" class="finestr" id="captionNew"></td><td><button class="finebutton small add" id="New">Add</button></td></tr>';
	$result .= '</table>';
	$result .= '<SCRIPT>';
	$result .= '$("#tasksTable").editableTableWidget();
				$(".code").on("change", function(evt, newValue) {
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						datatype: JSON,
						data: {
							operation: "CHANGE_TASKS_CODE",
							id: $(this).attr("id"),
							code: newValue
						}
					})
					.done(function () {
						$("#tasks").click();
					})
				});
				$(".caption").on("change", function(evt, newValue) {
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						datatype: JSON,
						data: {
							operation: "CHANGE_TASKS_CAPTION",
							id: $(this).attr("id"),
							caption: newValue
						}
					})
					.done(function () {
						$("#tasks").click();
				})
			});

			$(".finebutton.small.add").unbind().click(function(){
				if (validateText([$("#codeNew"), $("#captionNew")])){
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						data: {
							operation: "ADD_TASKS",
							code: $("#codeNew").val(),
							caption: $("#captionNew").val(),
							}
					})
					.done(function (msg) {
						$("#tasks").click();
					});
				}
			});';
	$result .= '$(".finebutton.small.delete").unbind().click(function(){
					var sender = $(this);
					confirmDelete("Task").then(function (answer) {
						if(answer == true){
							$.ajax({
								method: "POST",
								url: "page/executor.php",
								data: {
									operation: "DEL_TASKS",
									id: sender.attr("id"),
									}
							})
							.done(function (msg) {
								$("#tasks").click();
							});
						}
					});
				});';
	$result .= '</SCRIPT>';
	die ($result);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "CHANGE_TASKS_CODE") {
	$id = $_REQUEST['id'];
	$code = $_REQUEST['code'];
	$res = $project->edit_task_code($id, $code);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "CHANGE_TASKS_CAPTION") {
	$id = $_REQUEST['id'];
	$caption = $_REQUEST['caption'];
	$res = $project->edit_task_caption($id, $caption);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_TASKS") {
	$id = $_REQUEST['id'];
	$code = $_REQUEST['code'];
	$caption = $_REQUEST['caption'];
	$res = $project->add_task($code, $caption);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "DEL_TASKS"){
	$id = $_REQUEST['id'];
	$res = $project->delete_task($id);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_PHASES") {
	if (isset($_REQUEST['id']) && ($_REQUEST['id']) != "") $id = $_REQUEST['id']; else $id = "";
	$res = $project->get_phases($id);
	$result = '<h2>Phases</h2><br><table class="finetable" id="phasesTable" >';
	$result .= '<tr><td style="width:100px;font-weight: bold;">Code</td><td style="width:500px;font-weight: bold;">Caption</td><td style="width:150px;font-weight: bold;">Operations</td></tr>';
	if ($res) {
		foreach ($res as $item) {
			$result .= '<tr><td class="textcenter editable code" id="' . $item->ID . '">' . $item->CODE . '</td><td class="caption editable" id="' . $item->ID . '">' . $item->CAPTION . '</td><td><button class="finebutton small delete" id="' . $item->ID . '">Del</button></td></tr>';
		}
	}
	$result .= '<tr><td><input type="text" class="finestr textcenter" id="codeNew"></td><td><input type="text" class="finestr" id="captionNew"></td><td><button class="finebutton small add" id="New">Add</button></td></tr>';
	$result .= '</table>';
	$result .= '<SCRIPT>';
	$result .= '$("#phasesTable").editableTableWidget();
				$(".code").on("change", function(evt, newValue) {
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						datatype: JSON,
						data: {
							operation: "CHANGE_PHASES_CODE",
							id: $(this).attr("id"),
							code: newValue
						}
					})
					.done(function () {
						$("#projectPhases").click();
					})
				});
				$(".caption").on("change", function(evt, newValue) {
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						datatype: JSON,
						data: {
							operation: "CHANGE_PHASES_CAPTION",
							id: $(this).attr("id"),
							caption: newValue
						}
					})
					.done(function () {
						$("#projectPhases").click();
				})
			});

			$(".finebutton.small.add").unbind().click(function(){
				if (validateText([$("#codeNew"), $("#captionNew")])){
					$.ajax({
						method: "POST",
						url: "page/executor.php",
						data: {
							operation: "ADD_PHASES",
							code: $("#codeNew").val(),
							caption: $("#captionNew").val(),
							}
					})
					.done(function (msg) {
						$("#projectPhases").click();
					});
				}
			});';
	$result .= '$(".finebutton.small.delete").unbind().click(function(){
					var sender = $(this);
					confirmDelete("Phase").then(function (answer) {
						if(answer == true){
							$.ajax({
								method: "POST",
								url: "page/executor.php",
								data: {
									operation: "DEL_PHASES",
									id: sender.attr("id"),
									}
							})
							.done(function (msg) {
								$("#projectPhases").click();
							});
						}
					});
				});';
	$result .= '</SCRIPT>';
	die ($result);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "CHANGE_PHASES_CODE") {
	$id = $_REQUEST['id'];
	$code = $_REQUEST['code'];
	$res = $project->edit_phases_code($id, $code);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "CHANGE_PHASES_CAPTION") {
	$id = $_REQUEST['id'];
	$caption = $_REQUEST['caption'];
	$res = $project->edit_phases_caption($id, $caption);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_PHASES") {
	$id = $_REQUEST['id'];
	$code = $_REQUEST['code'];
	$caption = $_REQUEST['caption'];
	$res = $project->add_phases($code, $caption);;
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "DEL_PHASES"){
	$id = $_REQUEST['id'];
	$res = $project->delete_phases($id);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "LOAD_EDIT_TIME_DIALOG"){
	$id = $_REQUEST['id'];
	ob_start();
	include_once('editTimeDialog.php');
	die (ob_get_clean());
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "LOAD_NEW_TIME_DIALOG"){
	$time = $_REQUEST['time'];
	$prevId = $_REQUEST['prevId'];
	ob_start();
	include_once('newTimeDialog.php');
	die (ob_get_clean());
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "NEW_TIMESHEET_EVENT") {
	$emp = $_REQUEST['emp'];
	$time = $_REQUEST['time'];
	$proj = $_REQUEST['project'];
	$office = $_REQUEST['office'];
	$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : 0;
	$phase = isset($_REQUEST['phase'])? $_REQUEST['phase']: 0;
	$hours = $_REQUEST['hours'];
	$res = $project->add_timesheet_event($emp, $time, $proj, $office, $task, $phase, $hours);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_TIMESHEET_EVENT") {
	$id = $_REQUEST['id'];
	$proj = $_REQUEST['project'];
	$office = $_REQUEST['office'];
	$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : 0;
	$phase = isset($_REQUEST['phase'])? $_REQUEST['phase']: 0;
	$hours = $_REQUEST['hours'];
	$time = $_REQUEST['time'];
	$res = $project->edit_timesheet_event($id, $proj, $office, $task, $phase, $hours);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "DELETE_TIMESHEET_EVENT") {
	$id = $_REQUEST['id'];
	$res = $project->delete_timesheet_event($id);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "LOAD_ANALYTIC") {
	ob_start();
	include_once('inc_analytic.php');
	die (ob_get_clean());
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "LOAD_ENALYTIC") {
	ob_start();
	include_once('inc_enalytic.php');
	die (ob_get_clean());
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_COST_PLAN_SUM") {
	$projId = $_REQUEST['projId'];
	$type = $_REQUEST['type'];
	$value = $_REQUEST['value'];
	$res = $project->add_cost_plan_sum($projId, $type, $value);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_CONTRACT_BUDGET_EVENT") {
	$budId = $_REQUEST['budId'];
	$date = $_REQUEST['date'];
	$value = $_REQUEST['value'];
	$res = $project->add_contract_budget_event($budId, $date, $value);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_COST_PLAN_EVENT") {
	$projId = $_REQUEST['projId'];
	$type = $_REQUEST['type'];
	$date = $_REQUEST['date'];
	$value = $_REQUEST['value'];
	$res = $project->add_costs_plan_event($projId, $type, $date, $value);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_ADDEND_BUDGET_EVENT") {
	$addId = $_REQUEST['addId'];
	$date = $_REQUEST['date'];
	$value = $_REQUEST['value'];
	$res = $project->add_addendum_budget_event($addId, $date, $value);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "LOAD_PAYMENTS") {
	ob_start();
	include_once('inc_payments.php');
	die (ob_get_clean());
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_CLIENT_PAYMENT") {
	$projId = $_REQUEST['id'];
	$month = $stuff->set_month_to_timestamp($_REQUEST['month']);
	$sum = $_REQUEST['sum'];
	$descr = $_REQUEST['description'];
	$user = $_SESSION['UID'];
	$time = time();
	$_SESSION['CUR_TAB'] = $_REQUEST['activeTab'];
	$res = $project->add_client_payment($projId, $month, $sum, $descr, $user, $time);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "ADD_COST_PAYMENT") {
	$projId = $_REQUEST['id'];
	$month = $stuff->set_month_to_timestamp($_REQUEST['month']);
	$type = $_REQUEST['type'];
	$sum = $_REQUEST['sum'];
	$descr = $_REQUEST['description'];
	$user = $_SESSION['UID'];
	$time = time();
	$_SESSION['CUR_TAB'] = $_REQUEST['activeTab'];
	$res = $project->add_cost_payment($projId, $month, $type, $sum, $descr, $user, $time);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "DEL_PAYMENT"){
	$id = $_REQUEST['id'];
	$type = $_REQUEST['paymentType'];
	$res = $project->delete_payment($id, $type);
	die($res);
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "GET_PAYMENT_DETAIL_STR"){
	$id = $_REQUEST['id'];
	$type = $_REQUEST['paymentType'];
	$res = $project->get_payment($id, $type);
	$result = array();
	$result['payMonth']='<input type="text" class = "finestr textcenter" readonly id = "edPayMonth" value="' .date('F, Y', $res[0]->MONTH).'">';
	$result['payMonth'].= '
	<SCRIPT>
		$("#edPayMonth.month-picker").remove();
		$("#edPayMonth").unbind().MonthPicker({Button: false,MonthFormat: "MM, yy"});
	</SCRIPT>
	';
	if($type == 'costs_payments'){
		$result['payType'] ='<select id="edPayTypes" data-placeholder="Choose a payment type..."><option value="false"></option>';
		if($res[0]->TYPE == 'SUB_CONTRACTORS'){
			$result['payType'] .= '<option value="SUB_CONTRACTORS" selected>Sub-contractors</option>';
		}else{
			$result['payType'] .= '<option value="SUB_CONTRACTORS">Sub-contractors</option>';
		};
		if($res[0]->TYPE == 'FREE_LANCERS'){
			$result['payType'] .= '<option value="FREE_LANCERS" selected>Freelancers</option>';
		}else{
			$result['payType'] .= '<option value="FREE_LANCERS">Freelancers</option>';
		};
		if($res[0]->TYPE == 'TRAVELLING'){
			$result['payType'] .= '<option value="TRAVELLING" selected>Travelling</option>';
		}else{
			$result['payType'] .= '<option value="TRAVELLING">Travelling</option>';
		};
		if($res[0]->TYPE == 'CASH'){
			$result['payType'] .= '<option value="CASH" selected>Cash</option>';
		}else{
			$result['payType'] .= '<option value="CASH">Cash</option>';
		};
		if($res[0]->TYPE == 'OTHER_COSTS'){
			$result['payType'] .= '<option value="OTHER_COSTS" selected>Other Costs</option></select>';
		}else{
			$result['payType'] .= '<option value="OTHER_COSTS">Other Costs</option></select>';
		};
		$result['payType'] .= '
		<SCRIPT>
			$("#edPayTypes").chosen({disable_search_threshold: 10});
		</SCRIPT>
		';
	}else{
		$result['payType']='Incoming Payment';
	}
	$result['paySum']='<input type="text" class = "finestr textcenter" id = "edPaySumm" value="' .$stuff->format($res[0]->PAYMENT).'">';
	$result['paySum'].='
	<SCRIPT>
		$("#edPaySumm").focus(function(){
			$(this).val(Number($(this).val().replace(/[^\d,-]/g, "").replace(/,/g, ".")) || 0);
		})
		$("#edPaySumm").blur(function(){
			$(this).val(formatNumber($(this).val()));
		})
	</SCRIPT>
	';

	$result['payComment']='<input type="text" class = "finestr textcenter" id = "edPayComm" value="' .$res[0]->COMMENT.'">';
	die (json_encode($result));
}
if (isset($_REQUEST['operation']) && ($_REQUEST['operation']) == "EDIT_PAYMENT") {
	$_SESSION['CUR_TAB'] = $_REQUEST['activeTab'];
	$payID = $_REQUEST['id'];
	$payForm = $_REQUEST['payFormType'];
	$payMonth = $stuff->set_month_to_timestamp($_REQUEST['month']);
	$payType = $_REQUEST['payType'];
	$paySum = $_REQUEST['sum'];
	$payComm = $_REQUEST['comm'];
	$res = $project->update_payment($payID, $payForm, $payMonth, $payType, $paySum, $payComm);
	die($res);
}


die('Operation not found!');

