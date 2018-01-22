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
	});

</SCRIPT>
<div style = "float:none;">
	<button class="finebutton" id="logout">Logout</button>
</div>
<h1>Refer to the general manager or system administrator</h1>