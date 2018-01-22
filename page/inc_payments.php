<?
require_once('../settings/settings.inc.php');
$currYear = (isset($_SESSION['CUR_PAYMENTS_YEAR'])) ? $_SESSION['CUR_PAYMENTS_YEAR']: date('Y');
$currTab = (isset($_SESSION['CUR_TAB'])) ? $_SESSION['CUR_TAB']: 0;
$proj = $project->get_projects_by_year($currYear);
$projYears = $project->get_projects_years();
?>

<div style="width:500px;">
<select id = "yearSelector" data-placeholder="Nothihg selected">
	<?
	print ('<option value = "" ></option>');
	foreach($projYears as $item){
		$selected = ($item->PROJ_YEAR == $currYear) ? 'selected' : '';
		print ('<option value = "'.$item->PROJ_YEAR.'" '.$selected.'>Payments data on the projects in '.$item->PROJ_YEAR.' year</option>');
	}
	?>
</select>
</div>
<br>
<div id="paymentsArea">
	<ul>
<?
foreach($proj as $item){
	print('<li><a href="/page/payments_executor.php?PROJ_ID='.$item->ID.'">'.$item->PROJ_CAPTION.'</a></li>');
}
?>
	</ul>
</div>
<script>
	$("#paymentsArea").tabs({ active: "<?=$currTab?>"});
	$("#paymentsArea").on( "tabsbeforeload", function( event, ui ) {
		$('.ui-dialog').remove();
		$("#paymentCostTypes").remove();
		$(".month-picker").remove();
		$('#payArea').html("<h2>Loading...</h2>");
	} );
	$("#yearSelector").chosen({disable_search:true}).unbind().change(function(){
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			datatype: JSON,
			data: {
				operation: "SET_CUR_YEAR_FOR_PAYMENTS",
				year: $(this).val()
			}
		})
			.done(function (msg) {
				$("#payments").click();
			});
	});
</script>



<div id = "editDialog" class="panel-collapse collapse" style="display:none; overflow: visible !important;">
	<br>
	<div class = "ui-corner-all panel" style="border:1px solid silver;">
		<table id="paymentBlank" style="width: 100%;">
			<tr>
				<td style="width: 50%" class="finetext textcenter">Payment Month:</td>
				<td style="width: 50%" id="monthTD"></td>
			</tr>
			<tr><td style="line-height: 8px;">&nbsp;</td><td></td></tr>
			<tr>
				<td class="finetext textcenter">Type:</td>
				<td id="typeTD"></td>
			</tr>
			<tr><td style="line-height: 8px;">&nbsp;</td><td></td></tr>
			<tr>
				<td class="finetext textcenter">Sum:</td>
				<td id="sumTD"></td>
			</tr>
			<tr><td style="line-height: 8px;">&nbsp;</td><td></td></tr>
			<tr>
				<td class="finetext textcenter">Comment:</td>
				<td id="commentTD"></td>
			</tr>
		</table>
	</div>
	<br>
	<table style="width: 100%">
		<tr class = "textcenter">
			<td style="width: 50%"><button class="finebutton" id="edSaveButton">Save</button></td>
			<td style="width: 50%"><button class="finebutton" autofocus id="edCancelButton">Cancel</button></td>
		</tr>
	</table>
	<input type="hidden" id="dialogId">
	<input type="hidden" id="dialogType">

</div>