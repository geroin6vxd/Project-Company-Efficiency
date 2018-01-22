<?

class class_project
{
	public function get_project_classes($id = FALSE, $showAll = false)
	{
		if (isset($id) && ($id != '')) {
			$expr = " AND ID = '" . $id . "' ";
		} else $expr = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';

		$query = 'SELECT * FROM projects_types WHERE 1 ' .$deleted. $expr . ' ORDER BY ABBR';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_project_classes_chosen($selected = FALSE, $id)
	{
		$proj = $this->get_project_classes();
		$result = '<SELECT class="proj_chosen" id="' . $id . '" data-placeholder="Choose a project type..." style="width:100%;"><OPTION value="false"></OPTION>';
		foreach ($proj as $item) {
			$sel = ($selected === $item->ABBR) ? "selected" : "";
			$result .= '<OPTION value="' . $item->ABBR . '" ' . $sel . ' >' . $item->ABBR . ' - ' . $item->CAPTION . '</OPTION>';
		}
		$result .= '</SELECT>
					<SCRIPT>
						$("#' . $id . '").chosen({disable_search_threshold: 5, allow_single_deselect: true});

					</SCRIPT>';
		return $result;
	}


	public function add_project_classes($code, $caption)
	{
		$query = 'INSERT INTO projects_types (ABBR, CAPTION) VALUES ("' . $code . '", "' . $caption . '")';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_project_classes_code($id, $code)
	{
		$query = 'UPDATE projects_types SET ABBR = "' . $code . '" WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_project_classes_caption($id, $caption)
	{
		$query = 'UPDATE projects_types SET CAPTION = "' . $caption . '" WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function delete_project_classes($id)
	{
		$query = 'UPDATE projects_types SET DELETED = 1 WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_project_code($id, $showAll = false)
	{
		$expr = "AND ID = '" . $id . "'";
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM projects  WHERE 1 ' .$deleted. $expr . ' ORDER BY ID';
		$res = db_query($query);
		$res = array_pop($res);
		$result = $res->PROJ_YEAR . ' ' . $res->PROJ_TYPE . ' ' . $res->PROJ_NUM;
		return $result;
	}

	public function get_project($id = FALSE, $showAll = false, $viewInternal = false)
	{
		if (isset($id) && ($id != FALSE)) {
			$expr = "AND ID = '" . $id . "'";
		} else $expr = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$internal = ($viewInternal)? '':' AND (INTERNAL < 1 OR INTERNAL IS NULL) ';
		$query = 'SELECT * FROM projects WHERE  1 ' .$deleted.$internal. $expr . ' ORDER BY PROJ_CAPTION';
		$res = db_query($query);
		if (isset($id) && ($id != FALSE)) $res = array_pop($res);
		if ($res) return $res; else return FALSE;
	}

	public function get_projects_years($uid=false, $showAll = false, $viewInternal = false)
	{
		if (isset($uid) && ($uid != FALSE)) {
			$expr = " AND PROJ_MANAGER = '" . $uid . "'";
		} else $expr = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$internal = ($viewInternal)? '':' AND (INTERNAL < 1 OR INTERNAL IS NULL) ';
		$query = 'SELECT DISTINCT PROJ_YEAR FROM projects WHERE 1 '.$deleted.$expr.$internal;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_projects_by_year($year, $uid = false, $showAll = false)
	{
		$expr1 = " AND PROJ_YEAR = '" . $year . "' ";
		if (isset($uid) && ($uid != FALSE)) {
			$expr2 = " AND PROJ_MANAGER = '" . $uid . "' ";
		} else $expr2 = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM projects WHERE 1 '. $deleted. $expr1 .$expr2. ' ORDER BY ID';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_projects_by_user($uid, $showAll = false)
	{
		$expr = " AND PROJ_MANAGER = '" . $uid . "' ";
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM projects WHERE 1 ' .$deleted. $expr. ' ORDER BY ID';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_project($id, $year, $type, $num, $caption, $description, $start, $finish, $budget, $manager)
	{
		$query = 'UPDATE projects SET PROJ_TYPE = "' . $type . '", PROJ_YEAR = "' . $year . '", PROJ_NUM = "' . $num . '", PROJ_CAPTION = "' . $caption . '", PROJ_DESCRIPTION = "' . $description . '", PROJ_START = "' . $start . '", PROJ_FINISH = "' . $finish . '", PROJ_BUDGET = "' . $budget . '", PROJ_MANAGER = "'.$manager.'" WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function delete_project($id)
	{
		$query = 'UPDATE projects SET DELETED = 1 WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function add_project($year, $type, $num, $caption, $description, $start, $finish, $budget, $manager)
	{
		$query = 'INSERT INTO projects (PROJ_TYPE, PROJ_YEAR, PROJ_NUM, PROJ_CAPTION, PROJ_DESCRIPTION, PROJ_START, PROJ_FINISH,PROJ_BUDGET, PROJ_MANAGER) VALUES ("' . $type . '", "' . $year . '","' . $num . '", "' . $caption . '","' . $description . '", "' . $start . '", "' . $finish . '", "' . $budget . '", "'.$manager.'")';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_project_chosen($selected = FALSE, $id, $showAll = false, $viewInternal = false)
	{
		$proj = $this->get_project(false, $showAll, $viewInternal);
		$result = '<SELECT class="proj_chosen", id="' . $id . '" data-placeholder="Choose a project..." style="width:100%;"><OPTION value="false"></OPTION>';
		// first optgroup
		$result .= '<OPTGROUP label="PROJECTS">';
		foreach ($proj as $item) {
			$sel = ($selected == $item->ID) ? "selected" : "";
			if ($item->INTERNAL < 1) {
				$result .= '<OPTION value="' . $item->ID . '" ' . $sel . ' >' . $item->PROJ_CAPTION . '</OPTION>';
			}
		}
		$result .= '</OPTGROUP>';

		// second optgroup
		$result .= '<OPTGROUP label="OFFICE SITUATIONS">';
		foreach ($proj as $item) {
			$sel = ($selected == $item->ID) ? "selected" : "";
			if ($item->INTERNAL == 1) {
				$result .= '<OPTION value="' . $item->ID . '" ' . $sel . ' >' . $item->PROJ_CAPTION . '</OPTION>';
			}
		}
		$result .= '</OPTGROUP>';
		$result .= '</SELECT>
					<SCRIPT>
						$("#' . $id . '").chosen({disable_search_threshold: 5, allow_single_deselect: true});

					</SCRIPT>';
		return $result;
	}

	public function get_project_max_number()
	{
		$year = date("Y");
		$query = 'SELECT MAX(PROJ_NUM) AS MAX FROM projects WHERE (DELETED < 1 OR DELETED IS NULL) AND PROJ_YEAR = ' . $year;
		$res = db_query($query);
		if ($res) return sprintf("%03d", $res[0]->MAX + 1); else return '001';
	}

	public function get_offices($id = FALSE, $showAll = false)
	{
		if (isset($id) && ($id != '')) {
			$expr = " AND ID = '" . $id . "' ";
		} else $expr = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM offices WHERE 1 ' .$deleted. $expr . ' ORDER BY CODE';
		$res = db_query($query);
		if (isset($id) && ($id != FALSE)) $res = array_pop($res);
		if ($res) return $res; else return FALSE;
	}

	public function get_offices_chosen($selected = FALSE, $id, $showAll = false)
	{
		$offices = $this->get_offices(FALSE, $showAll);
		$result = '<SELECT class="offices_chosen" id="' . $id . '" data-placeholder="Choose an office..." style="width:100%;"><OPTION value="false"></OPTION>';
		foreach ($offices as $item) {
			$sel = ($selected === $item->ID) ? "selected" : "";
			$result .= '<OPTION value="' . $item->ID . '" ' . $sel . ' >' . $item->CODE . ' - ' . $item->CAPTION . '</OPTION>';
		}
		$result .= '</SELECT>
					<SCRIPT>
						$("#' . $id . '").chosen({disable_search_threshold: 5, allow_single_deselect: true});

					</SCRIPT>';
		return $result;
	}

	public function add_offices($code, $caption)
	{
		$query = 'INSERT INTO offices (CODE, CAPTION) VALUES ("' . $code . '", "' . $caption . '")';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_offices_code($id, $code)
	{
		$query = 'UPDATE offices SET CODE = "' . $code . '" WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_offices_caption($id, $caption)
	{
		$query = 'UPDATE offices SET  CAPTION = "' . $caption . '" WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function delete_offices($id)
	{
		$query = 'UPDATE offices SET DELETED = 1  WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_task($id, $showAll = false)
	{
		if (isset($id) && ($id != '')) {
			$expr = " AND ID = '" . $id . "' ";
		} else $expr = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM tasks WHERE 1 ' .$deleted. $expr . ' ORDER BY CODE';
		$res = db_query($query);
		if (isset($id) && ($id != FALSE)) $res = array_pop($res);
		if ($res) return $res; else return FALSE;
	}

	public function add_task($code, $caption)
	{
		$query = 'INSERT INTO tasks (CODE, CAPTION) VALUES ("' . $code . '", "' . $caption . '")';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_task_code($id, $code)
	{
		$query = 'UPDATE tasks SET CODE = "' . $code . '" WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_task_caption($id, $caption)
	{
		$query = 'UPDATE tasks SET  CAPTION = "' . $caption . '" WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function delete_task($id)
	{
		$query = 'UPDATE  tasks  SET DELETED = 1 WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_task_chosen($selected = FALSE, $id, $showAll = false)
	{
		$task = $this->get_task(FALSE, $showAll);
		$result = '<SELECT class="proj_chosen", id="' . $id . '" data-placeholder="Choose a task..." style="width:100%;"><OPTION value="false"></OPTION>';

		// first optgroup
		$result .= '<OPTGROUP label="GLOBAL TASKS">';
		foreach ($task as $item) {
			$sel = ($selected == $item->ID) ? "selected" : "";
			if (($item->ID == 21) ||  ($item->ID == 26)){
				$result .= '<OPTION value="' . $item->ID . '" ' . $sel . ' >' . $item->CODE . ' - ' . $item->CAPTION . '</OPTION>';
			}
		}
		$result .= '</OPTGROUP>';

		// second optgroup
		$result .= '<OPTGROUP label="DETAILED TASKS">';
		foreach ($task as $item) {
			$sel = ($selected == $item->ID) ? "selected" : "";
			if (($item->ID != 21) ||  ($item->ID != 26)){
				$result .= '<OPTION value="' . $item->ID . '" ' . $sel . ' >' . $item->CODE . ' - ' . $item->CAPTION . '</OPTION>';
			}
		}
		$result .= '</OPTGROUP>';

		$result .= '</SELECT>
					<SCRIPT>
						$("#' . $id . '").chosen({disable_search_threshold: 5, allow_single_deselect: true});

					</SCRIPT>';
		return $result;
	}

	public function get_phases($id = FALSE, $showAll = false)
	{
		if (isset($id) && ($id != '')) {
			$expr = " AND ID = '" . $id . "' ";
		} else $expr = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM phases WHERE 1 ' .$deleted. $expr . ' ORDER BY CODE';
		$res = db_query($query);
		if (isset($id) && ($id != FALSE)) $res = array_pop($res);
		if ($res) return $res; else return FALSE;
	}

	public function get_phases_chosen($selected = FALSE, $id, $showAll = false)
	{
		$phas = $this->get_phases(FALSE, $showAll);
		$result = '<SELECT class="proj_chosen", id="' . $id . '" data-placeholder="Choose a phase..." style="width:100%;"><OPTION value="false"></OPTION>';
		foreach ($phas as $item) {
			$sel = ($selected == $item->ID) ? "selected" : "";
			$result .= '<OPTION value="' . $item->ID . '" ' . $sel . ' >' . $item->CODE . ' - ' . $item->CAPTION . '</OPTION>';
		}
		$result .= '</SELECT>
					<SCRIPT>
						$("#' . $id . '").chosen({disable_search_threshold: 5, allow_single_deselect: true});

					</SCRIPT>';
		return $result;
	}

	public function add_phases($code, $caption)
	{
		$query = 'INSERT INTO phases (CODE, CAPTION) VALUES ("' . $code . '", "' . $caption . '")';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_phases_code($id, $code)
	{
		$query = 'UPDATE phases SET CODE = "' . $code . '" WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_phases_caption($id, $caption)
	{
		$query = 'UPDATE phases SET CAPTION = "' . $caption . '" WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function delete_phases($id)
	{
		$query = 'UPDATE  phases  SET DELETED = 1 WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_timesheet($employee = FALSE, $date_start, $duration, $project = FALSE,  $showAll = false)
	{
		$expr = "";
		if (isset($employee) && ($employee != FALSE)) $expr .= "  AND EMPLOYEE = '" . $employee . "' ";
		if (isset($project) && ($project != FALSE)) $expr .= "  AND PROJECT = '" . $project . "' ";
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$date_end = $date_start + $duration;
		$query = 'SELECT * FROM timesheet WHERE 1 AND P_DATE BETWEEN ' . $date_start . ' AND ' . $date_end . $expr . $deleted.' ORDER BY P_DATE';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function add_timesheet_event($emp, $time, $proj, $office, $task, $phase, $hours)
	{
		$summ = $_SESSION['COST'] * $hours;
		$query = "SELECT * FROM timesheet WHERE (DELETED < 1 OR DELETED IS NULL) AND EMPLOYEE = '" . $emp . "' AND PROJECT = '" . $proj . "' AND OFFICE = '" . $office . "' AND TASK = '" . $task . "' AND PHASE = '" . $phase . "' AND P_DATE = '" . $time . "'";
		$res = db_query($query);
		$s = array_pop($res);
		if ($s) {
			$hourses = $hours + $s->HOURS;
			$summes = $summ + $s->SUMM;
			$query = 'UPDATE timesheet SET PROJECT = "' . $proj . '", OFFICE = "' . $office . '", TASK = "' . $task . '", PHASE = "' . $phase . '" , HOURS = "' . $hourses . '", SUMM = "'.$summes.'" WHERE ID = ' . $s->ID;
		} else {
			$query = 'INSERT INTO timesheet (EMPLOYEE, PROJECT, OFFICE, TASK, PHASE, P_DATE, HOURS, SUMM) VALUES ("' . $emp . '", "' . $proj . '","' . $office . '","' . $task . '","' . $phase . '","' . $time . '","' . $hours . '","'.$summ.'")';
		}
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function edit_timesheet_event($id, $proj, $office, $task, $phase, $hours)
	{
		$summ = $_SESSION['COST'] * $hours;
		$currEvent = $this->get_timesheet_event($id);
		$query = "SELECT * FROM timesheet WHERE (DELETED < 1 OR DELETED IS NULL) AND PROJECT = '" . $proj . "' AND EMPLOYEE = '" . $_SESSION["UID"] . "' AND OFFICE = '" . $office . "' AND TASK = '" . $task . "' AND PHASE = '" . $phase . "' AND ID <> '" . $id . "' AND P_DATE = '" . $currEvent->P_DATE . "'";
		$res = db_query($query);
		$s = array_pop($res);
		if ($s <> NULL) {
			$this->delete_timesheet_event($id);
			$hourses = $hours + $s->HOURS;
			$summes = $summ + $s->SUMM;
			$query = 'UPDATE timesheet SET  HOURS = "' . $hourses . '", SUMM = "'.$summes.'" WHERE ID = ' . $s->ID;
		} else {
			$query = 'UPDATE timesheet SET PROJECT = "' . $proj . '", OFFICE = "' . $office . '", TASK = "' . $task . '", PHASE = "' . $phase . '" , HOURS =  "' . $hours . '", SUMM = "'.$summ.'" WHERE ID = ' . $id;
		}
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_timesheet_event($id)
	{
		$expr = "  ID = '" . $id . "' ";
		$query = 'SELECT * FROM timesheet WHERE ' . $expr;
		$res = db_query($query);
		if (isset($id) && ($id != FALSE)) $res = array_pop($res);
		if ($res) return $res; else return FALSE;
	}

	public function delete_timesheet_event($id)
	{
		$query = 'DELETE FROM timesheet WHERE ID = ' . $id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_addendums($projId,  $showAll = false)
	{
		$expr = "  PROJ_ID = '" . $projId . "' ";
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM addendums WHERE 1 AND ' . $expr .$deleted. ' ORDER BY ID';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_addendums_table($projId, $showAll = false)
	{
		$stuff = new class_stuff();
		$addendums = $this->get_addendums($projId, $showAll);
		$addTable = '<table  class = "finetable" style="width: 100%;">
				<tr>
					<td style="width: 50%" class="addendum textcenter">Caption</td>
					<td style="width: 17%" class="addendum textcenter">Start</td>
					<td style="width: 17%" class="addendum textcenter">Budget</td>
					<td style="width: 16%" class="addendum textcenter">Operations</td>
				</tr>';
		foreach ($addendums as $item) {
			$addTable .= '<tr>
					<td class="addendum" ><input type = "text" class = "finestr textcenter" value = "' . $item->CAPTION . '" id = "addCaption' . $item->ID . '" ></td >
					<td class="addendum " ><input type = "text" readonly class = "finestr textcenter addStartMonth" value = "' . $stuff->get_month_from_timestamp($item->START) . '" id = "addStart' . $item->ID . '" ></td >
					<td class="addendum " ><input type = "text" class = "finestr textcenter addbudget" id = "addBudget' . $item->ID . '" value="' . $stuff->format($item->BUDGET) . '" ></td >
					<td class="addendum " >
						<button class="finebutton small editaddendum" id = "' . $item->ID . '" > Save</button >
						<button class="finebutton small deladdendum" id = "' . $item->ID . '" > Del</button >
					</td >
				</tr >';
		}
		$addTable .= '<tr>
					<td class="addendum"><input type="text" class = "finestr textcenter" id = "addCaptionNew"></td>
					<td class="addendum "><input type="text" readonly class = "finestr textcenter addStartMonth" id = "addStartNew"></td>
					<td class="addendum "><input type="text" class = "finestr textcenter addbudget" id = "addBudgetNew" value="' . $stuff->format(0) . '"></td>
					<td class="addendum ">
						<button class="finebutton small editaddendum" id="New">Add</button>
					</td>
				</tr>
			</table>
			<script>
				$(".addbudget").focus(function(){
					$(this).val(Number($(this).val().replace(/[^\d,-]/g, "").replace(/,/g, ".")) || 0);
				})
				$(".addbudget").blur(function(){
					$(this).val(formatNumber($(this).val()));
				})

				$(".addStartMonth").unbind().MonthPicker({Button: false,MonthFormat: \'MM, yy\'});
				$(".finebutton.small.editaddendum").unbind().click(function(){
					var success = true;
					var sumCheck = $("#addBudget"+$(this).attr("id")).val().replace(/[^\d,-]/g, "").replace(/,/g, ".");
					if (!($.isNumeric(sumCheck))){
						$("#addBudget"+$(this).attr("id")).css("background", "#FFCCCC");
						success = false;
					}else{
						$("#addBudget"+$(this).attr("id")).css("background", "#ffffff");
					}
					var texts = validateText([$("#addCaption"+$(this).attr("id")),$("#addStart"+$(this).attr("id"))]);
					if(success && texts){
						$.ajax({
							method: "POST",
							url: "page/executor.php",
							datatype: JSON,
							data: {
								operation: "EDIT_PROJECT_ADDENDUM",
								projId:$("#dialogId").val(),
								id: $(this).attr("id"),
								caption:$("#addCaption"+$(this).attr("id")).val(),
								start:$("#addStart"+$(this).attr("id")).val(),
								budget:(Number($("#addBudget"+$(this).attr("id")).val().replace(/[^\d,-]/g, "").replace(/,/g, ".")) || 0)
							}
						})
							.done(function (msg) {
							$("#addendumsContent").html(msg);
						});
					}
				});
				$(".finebutton.small.deladdendum").unbind().click(function(){
					if(confirm("Are you really want to delete this Addendum?")){
						$.ajax({
							method: "POST",
							url: "page/executor.php",
							data: {
								operation: "DEL_PROJECT_ADDENDUM",
								id: $(this).attr("id"),
								projId:$("#dialogId").val(),
							}
						})
						.done(function (msg) {
							$("#addendumsContent").html(msg);
						});
					}
				});
			</script>
	';
		return $addTable;
	}

	public function edit_addendum($id, $projId, $start, $caption, $budget){
		if ($id === "New"){
			$query = 'INSERT INTO addendums (PROJ_ID, CAPTION, START, BUDGET) VALUES ("'.$projId.'", "'.$caption.'", "'.$start.'", "'.$budget.'")';
		}else{
			$query = 'UPDATE addendums SET CAPTION = "'.$caption.'", START = "'.$start.'", BUDGET = "'.$budget.'" WHERE ID = '.$id;
		}
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function delete_addendum($id){
		$query = 'UPDATE  addendums  SET DELETED = 1 WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_contract_budget_event($budId = false, $data = false , $showAll = false){
		if (isset($budId) && ($budId != '')) {
			$expr1 = " AND PROJECT_ID = '" . $budId . "' ";
		} else $expr1 = '';
		if (isset($data) && ($data != '')) {
			$expr2 = " AND MONTH = '" . $data . "' ";
		} else $expr2 = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM budget_plan WHERE 1 ' . $expr1.$expr2.$deleted . ' ORDER BY ID';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function add_contract_budget_event($budId, $date, $payment){
		$isset = $this->get_contract_budget_event($budId,$date);
		if($isset){
			$query = 'UPDATE budget_plan SET PAYMENT = "'.$payment.'" WHERE ID = '.$isset[0]->ID;
		}else{
			$query='INSERT INTO budget_plan (PROJECT_ID, MONTH, PAYMENT) VALUES ("'.$budId.'", "'.$date.'", "'.$payment.'")';
		}
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_costs_plan_event($projId = false, $type = false, $data = false, $showAll = false){
		if (isset($projId) && ($projId != '')) {
			$expr1 = " AND PROJECT_ID = '" . $projId . "' ";
		} else $expr1 = '';
		if (isset($data) && ($data != '')) {
			$expr2 = " AND MONTH = '" . $data . "' ";
		} else $expr2 = '';
		if (isset($type) && ($type != '')) {
			$expr3 = " AND TYPE = '" . $type . "' ";
		} else $expr3 = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM costs_plan WHERE 1 ' . $expr1.$expr2.$expr3.$deleted . ' ORDER BY ID';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function add_costs_plan_event($projId, $type, $date, $payment, $showAll = false){
		$isset = $this->get_costs_plan_event($projId, $type,$date, $showAll);
		if($isset){
			$query = 'UPDATE costs_plan SET PAYMENT = "'.$payment.'" WHERE ID = '.$isset[0]->ID;
		}else{
			$query='INSERT INTO costs_plan (PROJECT_ID, MONTH, TYPE, PAYMENT) VALUES ("'.$projId.'", "'.$date.'", "'.$type.'", "'.$payment.'")';
		}
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_addendum_budget_event($addId, $data, $showAll = false){
		if (isset($addId) && ($addId != '')) {
			$expr1 = " AND ADDENDUM_ID = '" . $addId . "' ";
		} else $expr1 = '';
		if (isset($data) && ($data != '')) {
			$expr2 = " AND MONTH = '" . $data . "' ";
		} else $expr2 = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM addendums_plan WHERE 1 ' . $expr1.$expr2.$deleted . ' ORDER BY ID';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function add_addendum_budget_event($addId, $date, $payment, $showAll = false){
		$isset = $this->get_addendum_budget_event($addId,$date, $showAll);
		if($isset){
			$query = 'UPDATE addendums_plan SET PAYMENT = "'.$payment.'" WHERE ID = '.$isset[0]->ID;
		}else{
			$query='INSERT INTO addendums_plan (ADDENDUM_ID, MONTH, PAYMENT) VALUES ("'.$addId.'", "'.$date.'", "'.$payment.'")';
		}
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function add_cost_plan_sum($projId, $type, $value){
		$query = 'UPDATE projects SET '.$type.' = "'.$value.'" WHERE ID = '.$projId;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function add_client_payment($projId, $month, $sum, $descr, $user, $time){
		$query='INSERT INTO projects_payments (PROJECT_ID, MONTH, PAYMENT, COMMENT, PAYMENT_DEALER, PAYMENT_DAY) VALUES ("'.$projId.'", "'.$month.'", "'.$sum.'", "'.$descr.'", "'.$user.'", "'.$time.'")';
    	$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function add_cost_payment($projId, $month, $type, $sum, $descr, $user, $time){
		$query='INSERT INTO costs_payments (PROJECT_ID, MONTH, TYPE, PAYMENT, COMMENT, PAYMENT_DEALER, PAYMENT_DAY) VALUES ("'.$projId.'", "'.$month.'", "'.$type.'", "'.$sum.'", "'.$descr.'", "'.$user.'", "'.$time.'")';
    	$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_clients_payments($projId, $showAll = false)
	{
		$deleted = ($showAll)?'':' AND (projects_payments.DELETED < 1 OR projects_payments.DELETED IS NULL) ';
		$query = 'SELECT
						projects_payments.ID,
						projects_payments.PROJECT_ID,
						projects_payments.MONTH,
						projects_payments.PAYMENT,
						projects_payments.COMMENT,
						projects_payments.PAYMENT_DAY,
						users.FAMILY AS U_FAMILY,
						users.U_NAME AS U_NAME

				  FROM
				  		projects_payments
				  LEFT JOIN
				  		users
				  ON
				  		users.ID = projects_payments.PAYMENT_DEALER
				  WHERE
				  		projects_payments.PROJECT_ID = ' . $projId . '
				        '.$deleted.'
				  ORDER BY
				        projects_payments.ID';

		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_costs_payments($projId, $showAll = false)
	{
		$deleted = ($showAll)?'':' AND (costs_payments.DELETED < 1 OR costs_payments.DELETED IS NULL) ';
		$query = 'SELECT
						costs_payments.ID,
						costs_payments.PROJECT_ID,
						costs_payments.MONTH,
						costs_payments.PAYMENT,
						costs_payments.TYPE,
						costs_payments.COMMENT,
						costs_payments.PAYMENT_DAY,
						users.FAMILY AS U_FAMILY,
						users.U_NAME AS U_NAME

				  FROM
				  		costs_payments
				  LEFT JOIN
				  		users
				  ON
				  		users.ID = costs_payments.PAYMENT_DEALER
				  WHERE
				  		costs_payments.PROJECT_ID = ' . $projId . '
				        '.$deleted.'
				  ORDER BY
				        costs_payments.ID';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function delete_payment($id, $type){
		$query = 'UPDATE  '.$type.'  SET DELETED = 1 WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function update_payment($payId, $payForm, $payMonth, $payType, $paySum, $payComm){
		$query = '  UPDATE  '.$payForm.'
					SET
					MONTH = "'.$payMonth.'",
					TYPE =  "'.$payType.'",
					PAYMENT =  "'.$paySum.'",
					COMMENT =  "'.$payComm.'"
					WHERE ID = '.$payId;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_payment($id, $type){
		$query = 'SELECT
						'.$type.'.ID,
						'.$type.'.MONTH,
						'.$type.'.PAYMENT,
						'.$type.'.TYPE,
						'.$type.'.COMMENT,
						'.$type.'.PAYMENT_DAY
				  FROM
				  		'.$type.'

				  WHERE
				  		'.$type.'.ID = ' . $id .'
				  ORDER BY
				        '.$type.'.ID';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_clients_payments_sum_month($projId)
	{
		$query = 'SELECT MONTH, SUM(PAYMENT) as SUMPAY from projects_payments  WHERE (DELETED < 1 OR DELETED IS NULL) AND PROJECT_ID = ' . $projId . ' GROUP BY MONTH';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}

	public function get_oe_costs($projId)
	{
		$query = 'SELECT
						timesheet.SUMM,
						timesheet.P_DATE
				  FROM
				  		timesheet
				  WHERE
				  		timesheet.PROJECT = ' . $projId . '
				  AND
				        (timesheet.DELETED < 1 OR timesheet.DELETED IS NULL)
				  ORDER BY
				        timesheet.P_DATE';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}


}
$project = new class_project();
?>

