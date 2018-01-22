<?
require_once('../settings/settings.inc.php');
$uid = $_SESSION['UID'];
?>
<h2>User Settings</h2>
<br>

	<div class = "ui-corner-all panel" style="border:1px solid silver;text-align: left;width:500px;background: white none repeat scroll 0 0;">
		<span class = "panel-caption finetext">Change Password</span>
		<table style="width: 500px;">
			<tr>
				<td style="width: 40%" class="finetext textcenter">New password:</td>
				<td style="width: 40%;"><input type="password" class = "finestr textcenter pass"  id = "passFirst"></td>
				<td style="width: 20%" class="finetext textcenter"><button class="finebutton small" id="showPass">Show</button></td>
			</tr>
			<tr><td style="line-height: 8px;">&nbsp;</td><td></td><td></td></tr>
			<tr>
				<td colspan="3" style="text-align: center;"><button class="finebutton" id="passSave">Save</button></td>
			</tr>
		</table>
	</div>

<SCRIPT>
	$('#showPass').on('mousedown', function (){$(".pass").attr('type', 'text');}).on('mouseup', function () {$(".pass").attr('type', 'password');});
	$("#passSave").unbind().click(function(){
		var passes = validateText([$("#passFirst")]);
		if(passes){
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "EDIT_USER_PASSWORD",
					uid: <?=$uid?>,
					password:$("#passFirst").val(),
				}
			})
				.done(function (msg) {
					if(msg == true){
						$("#logout").click();
					}else{
						alert('Somthing wrong. Try another password.');
					}
				});
		}
	});
</SCRIPT>
