<?
require_once('../settings/settings.inc.php');
$enalytic = ($project->get_projects_by_user($_SESSION['UID'])) ? '<button class="finebutton" id="enalytic">Analytics</button><script>
		$("#enalytic").unbind().click(function () {
		$.ajax({
		method: "POST",
		url: "page/executor.php",
		data: {
		operation: "LOAD_ENALYTIC",
		}
		})
		.done(function (msg) {
		$(".ui-dialog").remove();
		$("#PAGE_CONTENT").html(msg);
		});
		});
		</script>' : '';
?>
<SCRIPT>
	$(document).ready(function () {
		$("#logout").unbind().click(function () {
			if (confirm("Are you really want to exit?")) {
				$.ajax({
					method: "POST",
					url: "page/executor.php",
					data: {operation: "LOGOUT"}
				})
					.done(function (msg) {
						location.href = "login.php";
					});
			}
		});
		$("#projectsTypes").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {operation: "GET_PROJECTS_TYPES"}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					$("#PAGE_CONTENT").html(msg);
				});
		});

		$("#projects").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {operation: "GET_PROJECTS"}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					$("#PAGE_CONTENT").html(msg);
				});
		});
		$("#timeSheet").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {
					operation: "SET_DATE",
					date: "<?=date('Y-m-d')?>"
				}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					loadTimesheet();
				});
		});
		$("#offices").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {operation: "GET_OFFICES"}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					$("#PAGE_CONTENT").html(msg);
				});
		});
		$("#tasks").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {operation: "GET_TASKS"}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					$("#PAGE_CONTENT").html(msg);
				});
		});
		$("#projectPhases").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {operation: "GET_PHASES"}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					$("#PAGE_CONTENT").html(msg);
				});
		});
		$("#settings").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {
					operation: "LOAD_SETTINGS",
				}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					$("#PAGE_CONTENT").html(msg);
				});
		});
		$("#allTimeSheets").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {
					operation: "SET_DATE",
					date: "<?=date('Y-m-d')?>"
				}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					loadAllTimesheets();
				});
		});
		$("#timeSheet").click();
	});

</SCRIPT>
<div>
	<div style = "float:none;">
		<button class="finebutton" id="allTimeSheets">All Timesheets</button>
		<button class="finebutton" id="projectsTypes">Project Types</button>
		<button class="finebutton" id="offices">Offices</button>
		<button class="finebutton" id="tasks">Tasks</button>
		<button class="finebutton" id="projectPhases">Project Phases</button>
		<button class="finebutton" id="projects">Projects</button>
		<button class="finebutton" id="timeSheet">Timesheet</button>
		<?=$enalytic?>
		<button class="finebutton" id="settings">Settings</button>
		<button class="finebutton" id="logout">Logout</button>
	</div>
	<div id="PAGE_CONTENT" style="padding-top: 15px;"></div>
	<div style="clear: both;"></div>
</div>