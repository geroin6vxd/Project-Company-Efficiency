<?
require_once('../settings/settings.inc.php');
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
		<? if ($_SESSION['UID'] == 3) { ?>
		$("#usersCosts").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {operation: "GET_USERS_COST"}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					$("#PAGE_CONTENT").html(msg);
				});
		});
		<? } ?>
		$("#users").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {operation: "GET_USERS"}
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
		$("#analytic").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {
					operation: "LOAD_ANALYTIC",
				}
			})
				.done(function (msg) {
					$('.ui-dialog').remove();
					$("#PAGE_CONTENT").html(msg);
				});
		});
		$("#payments").unbind().click(function () {
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				data: {
					operation: "LOAD_PAYMENTS",
				}
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
		$("#analytic").click();
	});

</SCRIPT>
<div>
	<div style = "float:none;">
		<button class="finebutton" id="analytic">Analytics</button>
		<button class="finebutton" id="timeSheet">Timesheet</button>
		<button class="finebutton" id="projects">Projects</button>
		<button class="finebutton" id="payments">Payments</button>
		<button class="finebutton" id="users">Employees</button>
		<? if ($_SESSION['UID'] == 3) { ?>
		<button class="finebutton" id="usersCosts">Employee Costs</button>
		<? } ?>
		<button class="finebutton" id="allTimeSheets">All Timesheets</button>
		<button class="finebutton" id="settings">Settings</button>
		<button class="finebutton" id="logout">Logout</button>
	</div>
	<div id="PAGE_CONTENT" style="padding-top: 15px;"></div>
	<div style="clear: both;"></div>
</div>