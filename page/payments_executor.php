<?
require_once('../settings/settings.inc.php');
$projId = $_REQUEST["PROJ_ID"];
$payments = '';
$clientPayments = $project->get_clients_payments($projId);
$costsPayments = $project->get_costs_payments($projId);
 if($clientPayments){
	 $payments .= '
	 <tr>
		<td colspan = "7" class="tableBracker"><b>Client\'s payments</b></td>
	</tr>';
	foreach($clientPayments as $item){
		$payments .= '
						<tr>
							<td>'.date('d.m.Y', $item->PAYMENT_DAY).'</td>
							<td>'.date('F, Y', $item->MONTH).'</td>
							<td>Incoming payment</td>
							<td>'.$stuff->format($item->PAYMENT).'</td>
							<td>'.$item->U_FAMILY.' '.$item->U_NAME.'</td>
							<td>'.$item->COMMENT.'</td>
							<td><button class="finebutton small edit" data-function="projects_payments" id="' . $item->ID . '">Edit</button>&nbsp;<button class="finebutton small delete" data-function="projects_payments" id="' . $item->ID . '">Del</button></td>
						</tr>
		';
	}
 }
 if($costsPayments){
	 $payments .= '
	 <tr>
		<td colspan = "7" class="tableBracker"><b>pbr payments</b></td>
	</tr>';
	foreach($costsPayments as $item){
		$payments .= '
						<tr>
							<td>'.date('d.m.Y', $item->PAYMENT_DAY).'</td>
							<td>'.date('F, Y', $item->MONTH).'</td>
							<td>'.$stuff->translate_payment_type($item->TYPE).'</td>
							<td>'.$stuff->format($item->PAYMENT).'</td>
							<td>'.$item->U_FAMILY.' '.$item->U_NAME.'</td>
							<td>'.$item->COMMENT.'</td>
							<td><button class="finebutton small edit" data-function="costs_payments" id="' . $item->ID . '">Edit</button>&nbsp;<button class="finebutton small delete" data-function="costs_payments" id="' . $item->ID . '">Del</button></td>
						</tr>
		';
	}
 }


?>

<script>

	$(".month").unbind().MonthPicker({Button: false,MonthFormat: 'MM, yy'});
	$("#paymentCostTypes").chosen({disable_search_threshold: 5, allow_single_deselect: true});
	$("#payment").unbind().click(function(){
		var nums = validateNum([$("#paymentSum")]);
		var texts = validateText([$("#paymentMonth")]);
		if(nums && texts){
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "ADD_CLIENT_PAYMENT",
					id: <?=$projId?>,
					month:$("#paymentMonth").val(),
					sum:$("#paymentSum").val(),
					description:$("#paymentDescr").val(),
					activeTab:$( "#paymentsArea" ).tabs( "option", "active" ),
				}
			})
				.done(function () {
					$("#payments").click();
				});
		}
	});
	$("#paymentCost").unbind().click(function(){
		var nums = validateNum([$("#paymentCostSum")]);
		var texts = validateText([$("#paymentCostMonth")]);
		var selections = validateSelection([$("#paymentCostTypes")]);
		if(nums && texts && selections){
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "ADD_COST_PAYMENT",
					id: <?=$projId?>,
					month:$("#paymentCostMonth").val(),
					type:$("#paymentCostTypes").val(),
					sum:$("#paymentCostSum").val(),
					description:$("#paymentCostDescr").val(),
					activeTab:$( "#paymentsArea" ).tabs( "option", "active" ),
				}
			})
				.done(function () {
					$("#payments").click();
				});
		}
	});
	$(".finebutton.small.delete").unbind().click(function(){
		var sender = $(this);
		confirmDelete("Payment").then(function (answer) {
			if(answer == true){
				$.ajax({
					method: "POST",
					url: "page/executor.php",
					data: {
						operation: "DEL_PAYMENT",
						paymentType:sender.data("function"),
						id: sender.attr("id"),
					}
				})
					.done(function (msg) {
						$("#payments").click();
					});
			}
		});
	});
	$(".finebutton.small.edit").unbind().click(function(){
		$("#dialogId").val($(this).attr("id"));
		$("#dialogType").val($(this).data("function"));
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			datatype: JSON,
			data: {
				operation: "GET_PAYMENT_DETAIL_STR",
				paymentType:$(this).data("function"),
				id: $(this).attr("id")
			}
		})
			.done(function (msg) {
				var res = $.parseJSON(msg);
				$("#monthTD").html(res.payMonth);
				$("#typeTD").html(res.payType);
				$("#sumTD").html(res.paySum);
				$("#commentTD").html(res.payComment);
				$("#editDialog").dialog("open");

			});
	});
	$("#editDialog").dialog({
		closeText: "",
		modal:true,
		width:400,
		height:"auto",
		autoOpen: false,
		resizable: false,
		title : "Edit Payment",
		position: {   my: "top",
			at: "top",
			of: "#content" }
	});
</script>
<script>
	$("#edSaveButton").unbind().click(function(){
		var month = $("#edPayMonth").val();
		var typer = $("#edPayTypes").val();
		var summ = (Number($("#edPaySumm").val().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0);
		var comm = $("#edPayComm").val();
		var success = true;
		var sumCheck = $("#edPaySumm").val().replace(/[^\d,-]/g, '').replace(/,/g, '.')
		if (!($.isNumeric(sumCheck))){
			$("#edPaySumm").css("background", "#FFCCCC");
			success = false;
		}else{
			$("#edPaySumm").css("background", "#ffffff");
		}
		if(success){
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "EDIT_PAYMENT",
					id: $("#dialogId").val(),
					activeTab:$( "#paymentsArea" ).tabs( "option", "active" ),
					payFormType: $("#dialogType").val(),
					month:month,
					payType:typer,
					sum:summ,
					comm:comm
				}
			})
				.done(function () {
					$("#payments").click();
				});
		}

	});
	$("#edCancelButton").unbind().click(function(){
		$("#editDialog").dialog("close");
	});
</script>
<div id="payArea">
<table style="width:100%">
	<tr>
		<td style="width:400px; vertical-align: top;">
			<div class = "ui-corner-all panel" style="border:1px solid silver;text-align: left;">
				<span class = "panel-caption finetext">Client's payments</span>
				<table style="width: 100%;">
					<tr>
						<td style="width: 30%" class="finetext textcenter">Month:*</td>
						<td style="width: 70%"><input type="text" class = "finestr textcenter month" readonly id = "paymentMonth" value="<?=date('F, Y')?>"></td>
					</tr>
					<tr><td style="line-height: 8px;">&nbsp;</td><td></td></tr>
					<tr>
						<td class="finetext textcenter">Sum:*</td>
						<td><input type="text" class = "finestr textcenter" id = "paymentSum"></td>
					</tr>
					<tr><td style="line-height: 8px;">&nbsp;</td><td></td></tr>
					<tr>
						<td class="finetext textcenter">Comment:</td>
						<td><textarea rows="2" class = "finearea" id = "paymentDescr"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;"><button class="finebutton" id="payment">Save</button></td>
					</tr>
				</table>
			</div>
			<br>
			<br>
			<div class = "ui-corner-all panel" style="border:1px solid silver;text-align: left;">
				<span class = "panel-caption finetext">pbr payments</span>
				<table style="width: 100%;">
					<tr>
						<td style="width: 30%" class="finetext textcenter">Month:*</td>
						<td style="width: 70%"><input type="text" class = "finestr textcenter month" readonly id = "paymentCostMonth" value="<?=date('F, Y')?>"></td>
					</tr>
					<tr><td style="line-height: 8px;">&nbsp;</td><td></td></tr>
					<tr>
						<td style="width: 30%" class="finetext textcenter">Type:*</td>
						<td style="width: 70%">
							<select id="paymentCostTypes" data-placeholder="Choose a payment type...">
								<option value="false"></option>
								<option value="SUB_CONTRACTORS">Sub-contractors</option>
								<option value="FREE_LANCERS">Freelancers</option>
								<option value="TRAVELLING">Travelling</option>
								<option value="CASH">Cash</option>
								<option value="OTHER_COSTS">Other Costs</option>
							</select>
						</td>
					</tr>
					<tr><td style="line-height: 8px;">&nbsp;</td><td></td></tr>
					<tr>
						<td class="finetext textcenter">Sum:*</td>
						<td><input type="text" class = "finestr textcenter" id = "paymentCostSum"></td>
					</tr>
					<tr><td style="line-height: 8px;">&nbsp;</td><td></td></tr>
					<tr>
						<td class="finetext textcenter">Comment:</td>
						<td><textarea rows="2" class = "finearea" id = "paymentCostDescr"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;"><button class="finebutton" id="paymentCost">Save</button></td>
					</tr>
				</table>
			</div>
		</td>
		<td rowspan="2" style="width:auto;height:100%; vertical-align: top;">
			<div class = "ui-corner-all panel" style="border:1px solid silver;text-align: left;">
				<span class = "panel-caption finetext">Current payments</span>
				<table style="width: 100%;" class="finetable">
					<tr>
						<td style="width: 100px;" class="textcenter"><b>Date</b></td>
						<td style="width: 100px;" class="textcenter"><b>Payment month</b></td>
						<td style="width: 200px;" class="textcenter"><b>Type</b></td>
						<td style="width: 100px;" class="textcenter"><b>Sum</b></td>
						<td style="width: 110px;" class="textcenter"><b>Executor</b></td>
						<td style="width: auto;" class="textcenter"><b>Comment</b></td>
						<td style="width: 150px;" class="textcenter"><b>Operations</b></td>
					</tr>
					<?=$payments?>
				</table>
			</div>
		</td>
	</tr>
</table>
</div>



