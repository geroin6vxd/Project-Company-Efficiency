<?
require_once('../settings/settings.inc.php');
$projId = $_REQUEST["PROJ_ID"];
$proj = $project->get_project($projId);
$addendums=$project->get_addendums($projId);
$start = new DateTime(date("Y-m-d", $proj->PROJ_START));
$finish = new DateTime(date("Y-m-d", $proj->PROJ_FINISH));
$diff = $finish->diff($start);
$monthCount = 1 + $diff->y * 12 + $diff->m + $diff->d / 30 + $diff->h / 24;
unset($start);
unset($finish);

//Work with Budget Plan
$tempArr = $project->get_contract_budget_event($projId, false);
$budgetArray = array();
foreach ($tempArr as $item){
	$budgetArray[$item->MONTH] = $item->PAYMENT;
}
$tempArr = array();
$tempArr = $project->get_clients_payments_sum_month($projId);
$clientPayArray = array();
foreach ($tempArr as $item){
	$clientPayArray[$item->MONTH] = $item->SUMPAY;
}
$tempArr = array();
$tempArr = $project->get_costs_plan_event($projId, false, false);
$costsArray = array();
foreach ($tempArr as $item){
	$costsArray[$item->TYPE][$item->MONTH] = $item->PAYMENT;
}
$tempArr = array();
$tempArr = $project->get_costs_payments($projId);
$costsPayArray = array();
foreach ($tempArr as $item){
	$costsPayArray[$item->TYPE][$item->MONTH] = $costsPayArray[$item->TYPE][$item->MONTH] + $item->PAYMENT;
}
$tempArr = array();
$tempArr = $project->get_oe_costs($projId);
$oeCosts = array();
foreach ($tempArr as $item){
	$costMonth = $stuff->set_month_to_timestamp($stuff->get_month_from_timestamp($item->P_DATE));
	$oeCosts[$costMonth] = $oeCosts[$costMonth] + $item->SUMM;
}
//Generating random rows
$cols = '';$rowClear='';$rowClear2='';$rowDates='';$mainContractRow='';$totalContractRow='';$clientPayments = '';$difference = '';$coe ='';$csubcontr='';
$ctravel = '';$ccash = '';$cother ='';$ctotal='';$aoe ='';$asubcontr='';$actravel = '';$acash = '';$aother ='';$atotal='';$cdiff='';$cfb='';$cfa='';$cfdiff='';
for ($i = 0; $i < $monthCount; $i++) {
	// Generating cols descr
	$cols .= "<col style='width:65pt'>";
	// Generate empty string
	$rowClear.="<td class=xl1530318 width=68 style='width:51pt'></td>";
	$rowClear2.="<td class=xl8530318 width=68 style='border-top:none;width:51pt'>&nbsp;</td>";
	//generate string with dates
	$dateVar = strtotime('+' . $i . ' month', $proj->PROJ_START) ;
	$rowDates.="<td class=xl7830318>" . date('M,Y', $dateVar) . "</td>";


	$budgetPayment = (isset($budgetArray[$dateVar])) ? $budgetArray[$dateVar] :"0" ;
	$clientPayment = (isset($clientPayArray[$dateVar])) ? $clientPayArray[$dateVar] :"0" ;
	$ownEngValPlan = (isset($costsArray['OWN_ENGINEERING'][$dateVar])) ? $costsArray['OWN_ENGINEERING'][$dateVar] :"0" ;
	$subcontrValPlan = (isset($costsArray['SUB_CONTRACTORS'][$dateVar])) ? $costsArray['SUB_CONTRACTORS'][$dateVar] :"0" ;
	$freelancersValPlan = (isset($costsArray['FREE_LANCERS'][$dateVar])) ? $costsArray['FREE_LANCERS'][$dateVar] :"0" ;
	$travelValPlan = (isset($costsArray['TRAVELLING'][$dateVar])) ? $costsArray['TRAVELLING'][$dateVar] :"0" ;
	$cashValPlan = (isset($costsArray['CASH'][$dateVar])) ? $costsArray['CASH'][$dateVar] :"0" ;
	$otherValPlan = (isset($costsArray['OTHER_COSTS'][$dateVar])) ? $costsArray['OTHER_COSTS'][$dateVar] :"0" ;
	$ownEngVal = (isset($oeCosts[$dateVar])) ? $oeCosts[$dateVar] :"0" ;
	$subcontrVal = (isset($costsPayArray['SUB_CONTRACTORS'][$dateVar])) ? $costsPayArray['SUB_CONTRACTORS'][$dateVar] :"0" ;
	$freelancersVal = (isset($costsPayArray['FREE_LANCERS'][$dateVar])) ? $costsPayArray['FREE_LANCERS'][$dateVar] :"0" ;
	$travelVal = (isset($costsPayArray['TRAVELLING'][$dateVar])) ? $costsPayArray['TRAVELLING'][$dateVar] :"0" ;
	$cashVal = (isset($costsPayArray['CASH'][$dateVar])) ? $costsPayArray['CASH'][$dateVar] :"0" ;
	$otherVal = (isset($costsPayArray['OTHER_COSTS'][$dateVar])) ? $costsPayArray['OTHER_COSTS'][$dateVar] :"0" ;
	// Working with budget plan cells
	$mainContractRow.="<td  class='xl8430318 mainBudget editable' budId='".$proj->ID."' date='".$dateVar."' style='width:auto;white-space:nowrap'>". $stuff->format($budgetPayment)."</td>";
	$totalContractRow.="<td class='xl10830318 totalContractRow' width=68 style='width:51pt' date='".$dateVar."'>0,00</td>";
	$clientPayments .= "<td class='xl8830318 clientPayments' width=68 style='width:51pt' date='".$dateVar."'>". $stuff->format($clientPayment)."</td>";
	$difference .= "<td class='xl10930318  paymentDiff' width=68 style='width:51pt' date='".$dateVar."'>0,00</td>";
	$coe .= "<td class='xl8430318 editable costs' costType = 'OWN_ENGINEERING' date='".$dateVar."' width=68 style='width:51pt'>". $stuff->format($ownEngValPlan)."</td>";
	$csubcontr .="<td class='xl8530318 editable costs' costType = 'SUB_CONTRACTORS' date='".$dateVar."' width=68 style='border-top:none;width:51pt'>". $stuff->format($subcontrValPlan)."</td>";
	$cfreelancers .="<td class='xl8530318 editable costs' costType = 'FREE_LANCERS' date='".$dateVar."' width=68 style='border-top:none;width:51pt'>". $stuff->format($freelancersValPlan)."</td>";
	$ctravel .="<td class='xl8530318 editable costs' costType = 'TRAVELLING' date='".$dateVar."'  width=68 style='border-top:none;width:51pt'>". $stuff->format($travelValPlan)."</td>";
	$ccash .= "<td class='xl8530318 editable costs' costType = 'CASH' date='".$dateVar."'  width=68 style='border-top:none;width:51pt'>". $stuff->format($cashValPlan)."</td>";
	$cother .="<td class='xl8630318 editable costs' costType = 'OTHER_COSTS' date='".$dateVar."'  width=68 style='border-top:none;width:51pt'>". $stuff->format($otherValPlan)."</td>";
	$ctotal.="<td class='xl10830318 costsTotal'  date='".$dateVar."' width=68 style='width:51pt'>0,00</td>";
	$aoe .="<td class='xl8430318 costsVal'costType = 'OWN_ENGINEERING' date='".$dateVar."'  width=68 style='width:51pt'>". $stuff->format($ownEngVal)."</td>";
	$asubcontr.="<td class='xl8530318 costsVal' costType = 'SUB_CONTRACTORS' date='".$dateVar."'   width=68 style='border-top:none;width:51pt'>". $stuff->format($subcontrVal)."</td>";
	$afreelancers.="<td class='xl8530318 costsVal' costType = 'FREE_LANCERS' date='".$dateVar."'   width=68 style='border-top:none;width:51pt'>". $stuff->format($freelancersVal)."</td>";
	$atravel .="<td class='xl8530318 costsVal' costType = 'TRAVELLING' date='".$dateVar."'  width=68 style='border-top:none;width:51pt'>". $stuff->format($travelVal)."</td>" ;
	$acash .="<td class='xl8530318 costsVal' costType = 'CASH' date='".$dateVar."'  width=68 style='border-top:none;width:51pt'>". $stuff->format($cashVal)."</td>";
	$aother .="<td class='xl8630318 costsVal'  costType = 'OTHER_COSTS' date='".$dateVar."'  width=68 style='border-top:none;width:51pt'>". $stuff->format($otherVal)."</td>";
	$atotal.="<td class='xl10830318 costsTotalVal'  date='".$dateVar."' width=68 style='width:51pt'>0,00</td>";
	$cdiff.="<td class='xl12230318 costsDiff'  date='".$dateVar."' width=68 style='width:51pt'>0,00</td>";
	$cfb.="<td class='xl11530318 flowPlan' date='".$dateVar."'  width=68 style='width:51pt'>0,00</td>";
	$cfa.="<td class='xl11630318 flowFact' date='".$dateVar."'  width=68 style='border-top:none;width:51pt'>0,00</td>";
	$cfdiff.="<td class='xl10830318 flowDiff' date='".$dateVar."'  width=68 style='width:51pt'>0,00</td>";
}
// Generating Addendum rows
$addRows='';
$addsumBudget = 0;
foreach($addendums as $add) {
	$tempArr = $project->get_addendum_budget_event($add->ID, false);
	$addEventArray = array();
	foreach ($tempArr as $item){
		$addEventArray[$item->MONTH] = $item->PAYMENT;
	}
	$tempArr = array();
	$addsumBudget += $add->BUDGET;
	$addRows.="<tr class=xl1530318 height=21 style='height:15.75pt'>
					<td height=21 class=xl8230318 style='height:15.75pt;border-top:none;'>".$add->CAPTION."</td>
					<td class=xl9630318 width=142 style='border-left:none;border-top:none;width:107pt'>Started: ".date('M,Y', $add->START)."</td>
					<td class='xl10130318 addBudget' width=75 style='border-top:none;width:56pt'>". $stuff->format($add->BUDGET)."</td>
					<td class=xl9330318 width=19 style='width:14pt'></td>";
	for ($i = 0; $i < $monthCount; $i++) {
		$dateVar = strtotime('+' . $i . ' month', $proj->PROJ_START) ;
		$addPayment = (isset($addEventArray[$dateVar])) ? $addEventArray[$dateVar] :"0" ;
		$addRows.=" <td class='xl8630318 addendBudget editable' addId= '".$add->ID."' date='".$dateVar."' width=68 style='border-top:none;width:51pt'>". $stuff->format($addPayment)."</td>";
	}
	$addRows.="		<td class=xl7530318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
					<td class='xl10330318 addCheck' id='addCheck".$add->ID."' width=66 style='border-top:none;width:50pt'>0,00</td>
				</tr>";
}
?>
<STYLE>
	.editable{background: lightyellow;}
</STYLE>
<div class="costArea scroll" align=left >
	<script>
		function recalculate(){
			//Recalculate Main Contract Check
			var budgetPlan = Number(($("#budgetPlan").text()).replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".mainBudget").each(function() {budgetPlan += Number(($(this).text()).replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;});
			$("#budgetPlanCheck").text(formatNumber(budgetPlan));



			// Recalculate Addendums Check
			$( ".addCheck" ).each(function(){
				var sum = Number($(this).siblings( ".addBudget").eq(0).text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
				$(this).siblings( ".addendBudget" ).each(function(){
					sum  += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
				});
				$(this).text(formatNumber(sum));
			});


			//Recalculate Total Contract monthly sum
			$( ".totalContractRow" ).each(function(){
				var date = $(this).attr( "date");
				var sum=0;
				$(".mainBudget[date="+date+"], .addendBudget[date="+date+"]").each(function(){
					sum  += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
				});
				$(this).text(formatNumber(sum));
			});


			// Recalculate Total Contract Check
			var totalContractPlan = Number($("#totalContractRowPlan").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".totalContractRow").each(function() {
				totalContractPlan += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#totalContractRowCheck").text(formatNumber(totalContractPlan));


			// Recalculate Client Payments
			var clientPaymentsSum = 0;
			$(".clientPayments").each(function() {
				clientPaymentsSum += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#clientPaymentsSum").text(formatNumber(clientPaymentsSum));


			// Recalculate Client Payments Check
			$("#clientPaymentsCheck").text(formatNumber((Number($("#clientPaymentsSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0) - clientPaymentsSum));


			//Recalculate Payment and Budget Diff monthly sum
			$( ".paymentDiff" ).each(function(){
				var date = $(this).attr( "date");
				var budget=$(".totalContractRow[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				var pays = $(".clientPayments[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				$(this).text(formatNumber((Number(budget) || 0) - (Number(pays) || 0)));
			});


			//Recalculate Payment and Budget Diff  sum
			$("#contractDiff").text(formatNumber((Number($("#totalContractRowPlan").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0) - (Number($("#clientPaymentsSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0)));



			// Recalculate Payment and Budget  Diff Check
			var contractDiff = Number($("#contractDiff").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'));
			$(".paymentDiff").each(function() {
				contractDiff -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#contractCheck").text(formatNumber(contractDiff));


			// Recalculate Cost Plan Check
			//OE
			var costPlan = Number($("#OWN_ENGINEERING").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".costs[costType='OWN_ENGINEERING']").each(function() {
				costPlan += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#OWN_ENGINEERING_CHECK").text(formatNumber(costPlan));

			//SC
			costPlan = Number($("#SUB_CONTRACTORS").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".costs[costType='SUB_CONTRACTORS']").each(function() {
				costPlan += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#SUB_CONTRACTORS_CHECK").text(formatNumber(costPlan));

			//FL
			costPlan = Number($("#FREE_LANCERS").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".costs[costType='FREE_LANCERS']").each(function() {
				costPlan += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#FREE_LANCERS_CHECK").text(formatNumber(costPlan));

			//TR
			costPlan = Number($("#TRAVELLING").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".costs[costType='TRAVELLING']").each(function() {
				costPlan += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#TRAVELLING_CHECK").text(formatNumber(costPlan));

			//CA
			costPlan = Number($("#CASH").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".costs[costType='CASH']").each(function() {
				costPlan += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#CASH_CHECK").text(formatNumber(costPlan));

			//OC
			costPlan = Number($("#OTHER_COSTS").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".costs[costType='OTHER_COSTS']").each(function() {
				costPlan += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#OTHER_COSTS_CHECK").text(formatNumber(costPlan));


			// Recalculate Total Cost Sum
			var totalCostSum = 0;
			$(".costPlan").each(function() {
				totalCostSum += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#costPlanSum").text(formatNumber(totalCostSum));


			//Recalculate Total Costs monthly sum
			$( ".costsTotal" ).each(function(){
				var date = $(this).attr( "date");
				var sum=0;
				$(".costs[date="+date+"]").each(function(){
					sum  += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
				});
				$(this).text(formatNumber(sum));
			});


			// Recalculate Total Cost Check
			var costCheck = Number($("#costPlanSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".costsTotal").each(function() {
				costCheck += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#costPlanCheck").text(formatNumber(costCheck));


			// Recalculate Costs Fact
			//OE
			var costFact =0;
			$(".costsVal[costType='OWN_ENGINEERING']").each(function() {
				costFact += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#OWN_ENGINEERING_VAL").text(formatNumber(costFact));

			//SC
			costFact =0;
			$(".costsVal[costType='SUB_CONTRACTORS']").each(function() {
				costFact += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#SUB_CONTRACTORS_VAL").text(formatNumber(costFact));

			//FL
			costFact =0;
			$(".costsVal[costType='FREE_LANCERS']").each(function() {
				costFact += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#FREE_LANCERS_VAL").text(formatNumber(costFact));

			//TR
			costFact = 0;
			$(".costsVal[costType='TRAVELLING']").each(function() {
				costFact += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#TRAVELLING_VAL").text(formatNumber(costFact));

			//CA
			costFact = 0;
			$(".costsVal[costType='CASH']").each(function() {
				costFact += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#CASH_VAL").text(formatNumber(costFact));

			//OC
			costFact = 0;
			$(".costsVal[costType='OTHER_COSTS']").each(function() {
				costFact += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#OTHER_COSTS_VAL").text(formatNumber(costFact));


			// Recalculate Costs Fact Check
			//OE
			var costFact =Number($("#OWN_ENGINEERING_VAL").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			$(".costsVal[costType='OWN_ENGINEERING']").each(function() {
				costFact -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#OWN_ENGINEERING_VAL_CHECK").text(formatNumber(costFact));

			//SC
			var costFact =Number($("#SUB_CONTRACTORS_VAL").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			$(".costsVal[costType='SUB_CONTRACTORS']").each(function() {
				costFact -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#SUB_CONTRACTORS_VAL_CHECK").text(formatNumber(costFact));

			//FL
			var costFact =Number($("#FREE_LANCERS_VAL").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			$(".costsVal[costType='FREE_LANCERS']").each(function() {
				costFact -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#FREE_LANCERS_VAL_CHECK").text(formatNumber(costFact));

			//TR
			var costFact =Number($("#TRAVELLING_VAL").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			$(".costsVal[costType='TRAVELLING']").each(function() {
				costFact -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#TRAVELLING_VAL_CHECK").text(formatNumber(costFact));

			//CA
			var costFact =Number($("#CASH_VAL").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			$(".costsVal[costType='CASH']").each(function() {
				costFact -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#CASH_VAL_CHECK").text(formatNumber(costFact));

			//OC
			var costFact =Number($("#OTHER_COSTS_VAL").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			$(".costsVal[costType='OTHER_COSTS']").each(function() {
				costFact -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#OTHER_COSTS_VAL_CHECK").text(formatNumber(costFact));


			// Recalculate Total Cost Sum Val
			var totalCostSumVal = 0;
			$(".costValSum").each(function() {
				totalCostSumVal += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#TOTAL_COST_VAL").text(formatNumber(totalCostSumVal));


			//Recalculate Total Costs monthly sum
			$( ".costsTotalVal" ).each(function(){
				var date = $(this).attr( "date");
				var sum=0;
				$(".costsVal[date="+date+"]").each(function(){
					sum  += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
				});
				$(this).text(formatNumber(sum));
			});


			// Recalculate Total Cost Check
			var costValCheck = Number($("#TOTAL_COST_VAL").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'));
			$(".costsTotalVal").each(function() {
				costValCheck -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#TOTAL_COST_VAL_CHECK").text(formatNumber(costValCheck));


			//Recalculate Costs Plan and Fact Diff monthly sum
			$( ".costsDiff" ).each(function(){
				var date = $(this).attr( "date");
				var plan=$(".costsTotal[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				var fact = $(".costsTotalVal[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				$(this).text(formatNumber((Number(plan) || 0) - (Number(fact) || 0)));
			});


			//Recalculate Costs Plan and Fact Diff  sum
			$("#costDiffSum").text(formatNumber((Number($("#costPlanSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0) - (Number($("#TOTAL_COST_VAL").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0)));


			// Recalculate Costs Plan and Fact Diff Check
			var costsDiff = Number($("#costDiffSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'));
			$(".costsDiff").each(function() {
				costsDiff -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#costDiffSumCheck").text(formatNumber(costsDiff));


			//Recalculate Cash Flow Budgeted Monthly
			$( ".flowPlan" ).each(function(){
				var date = $(this).attr( "date");
				var plan=$(".totalContractRow[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				var fact = $(".costsTotal[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				$(this).text(formatNumber((Number(plan) || 0) - (Number(fact) || 0)));
			});


			//Recalculate Cash Flow Budgeted Sum
			$("#flowPlanSum").text(formatNumber((Number($("#totalContractRowPlan").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0) - (Number($("#costPlanSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0)));


			//Recalculate Cash Flow Budgeted Check
			var flowPlan = Number($("#flowPlanSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'))*(-1);
			$(".flowPlan").each(function() {
				flowPlan += Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#flowPlanCheck").text(formatNumber(flowPlan));


			//Recalculate Cash Flow Fact Monthly
			$( ".flowFact" ).each(function(){
				var date = $(this).attr( "date");
				var plan=$(".clientPayments[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				var fact = $(".costsTotalVal[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				$(this).text(formatNumber((Number(plan) || 0) - (Number(fact) || 0)));
			});


			//Recalculate Cash Flow Fact Sum
			$("#flowFactSum").text(formatNumber((Number($("#clientPaymentsSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0) - (Number($("#TOTAL_COST_VAL").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0)));


			//Recalculate Cash Flow Fact Check
			var flowFact = Number($("#flowFactSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'));
			$(".flowFact").each(function() {
				flowFact -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#flowFactCheck").text(formatNumber(flowFact));


			//Recalculate Cash Flow Diff Monthly
			$( ".flowDiff" ).each(function(){
				var date = $(this).attr( "date");
				var plan=$(".flowPlan[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				var fact = $(".flowFact[date="+date+"]").text().replace(/[^\d,-]/g, '').replace(/,/g, '.');
				$(this).text(formatNumber((Number(fact) || 0)-(Number(plan) || 0)));
			});


			//Recalculate Cash Flow Diff Summ
			$("#flowDiffSum").text(formatNumber((Number($("#flowFactSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0)- (Number($("#flowPlanSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0)));


			//Recalculate Cash Flow Diff Check
			var flowDiff= Number($("#flowDiffSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'));
			$(".flowDiff").each(function() {
				flowDiff -= Number($(this).text().replace(/[^\d,-]/g, '').replace(/,/g, '.')) || 0;
			});
			$("#flowDiffCheck").text(formatNumber(flowDiff));


			//Recalculate Profit
			var contBud = Number($("#totalContractRowPlan").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'));
			var costFlow = Number($("#flowPlanSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'));
			$("#profitBudgeted").text(((costFlow * 100 / contBud).toFixed(2)).replace("NaN", '-').replace("Infinity", '-')+"%");
			var contAct = Number($("#clientPaymentsSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'));
			var flowAct = Number($("#flowFactSum").text().replace(/[^\d,-]/g, '').replace(/,/g, '.'));
			$("#profitActual").text(((flowAct * 100 / contAct).toFixed(2).replace("NaN", '-').replace("Infinity", '-'))+"%");
		}


		$("#costsTable").editableTableWidget({formatting:true});
		$(".editable").on('validate', function(evt, newValue) {
			var fnum = newValue.replace(/[^\d,-]/g, '').replace(/,/g, '.');
			return ($.isNumeric(fnum) || newValue =='');
		});
		$(".mainBudget").on('change', function(evt, newValue) {
			if(newValue == ''){$(this).text("0")}
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "ADD_CONTRACT_BUDGET_EVENT",
					budId: $(this).attr("budId"),
					date: $(this).attr("date"),
					value:newValue.replace(/[^\d,-]/g, '').replace(/,/g, '.')
				}
			})
				.done(function () {
					recalculate();
				});
		});
		$(".addendBudget").on('change', function(evt, newValue) {
			if(newValue == ''){$(this).text("0")}
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "ADD_ADDEND_BUDGET_EVENT",
					addId: $(this).attr("addId"),
					date: $(this).attr("date"),
					value:newValue.replace(/[^\d,-]/g, '').replace(/,/g, '.')
				}
			})
				.done(function () {
					recalculate();
				});
		});
		$(".costPlan").on('change', function(evt, newValue) {
			if(newValue == ''){$(this).text("0")}
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "ADD_COST_PLAN_SUM",
					projId: <?=$projId?>,
					type: $(this).attr("id"),
					value:newValue.replace(/[^\d,-]/g, '').replace(/,/g, '.')
				}
			})
				.done(function () {
					recalculate();
				});
		});
		$(".costs").on('change', function(evt, newValue) {
			if(newValue == ''){$(this).text("0")}
			$.ajax({
				method: "POST",
				url: "page/executor.php",
				datatype: JSON,
				data: {
					operation: "ADD_COST_PLAN_EVENT",
					projId: <?=$projId?>,
					type: $(this).attr("costType"),
					date: $(this).attr("date"),
					value:newValue.replace(/[^\d,-]/g, '').replace(/,/g, '.')
				}
			})
				.done(function () {
					recalculate();
				});
		});
		$("#summaryDialog").draggable();

		recalculate();
	</script>
	<table id = "costsTable" border=0 cellpadding=0 cellspacing=0 width=591 style='table-layout:fixed;'>
		<col width=202 style='mso-width-source:userset;mso-width-alt:7387;width:152pt'>
		<col width=142 style='mso-width-source:userset;mso-width-alt:5193;width:107pt'>
		<col class=xl1530318 width=75 style='mso-width-source:userset;mso-width-alt:2742;width:70pt'>
		<col class=xl1530318 width=19 style='mso-width-source:userset;mso-width-alt: 694;width:14pt'>
		<?=$cols?>
		<col class=xl1530318 width=19 style='mso-width-source:userset;mso-width-alt:694;width:14pt'>
		<col width=75 style='mso-width-source:userset;mso-width-alt:2413;width:70pt'>

		<tr height=27 style='height:20.25pt'>
			<td height=27 class=xl6430318 width=202 style='height:20.25pt;width:152pt'>Project:</td>
			<td colspan=2 class=xl9130318 width=217 style='width:163pt'><?= $proj->PROJ_CAPTION ?></td>
			<td class=xl9130318 width=19 style='width:14pt'></td>
			<?=$rowClear?>
			<td class=xl1530318 width=19 style='width:14pt'></td>
			<td class=xl1530318 width=66 style='width:50pt'></td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl6730318 style='height:15.75pt'>Project-Number:</td>
			<td colspan=2 class=xl9230318><?= $project->get_project_code($proj->ID) ?></td>
			<td class=xl9230318></td>
			<?=$rowClear?>
			<td class=xl1530318></td>
			<td class=xl1530318></td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td colspan=<?= $monthCount + 7 ?> height=20 class=xl7730318 style='height:15.0pt'></td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl6530318 style='height:15.75pt'>CONTRACT (Budget)</td>
			<td class=xl7730318>Description</td>
			<td class=xl7730318>Ruble</td>
			<td class=xl7730318></td>
			<?=$rowDates?>
			<td class=xl7730318></td>
			<td class=xl10730318>Check</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl7930318 width=202 style='height:15.0pt;width:152pt'>Main contract:</td>
			<td class=xl9430318 width=142 style='border-left:none;width:107pt'><?= $proj->PROJ_DESCRIPTION ?></td>
			<td class=xl9930318 width=75 style='width:60pt; white-space:nowrap' id = "budgetPlan"><?= $stuff->format($proj->PROJ_BUDGET)?></td>
			<td class=xl9330318 width=19 style='width:14pt'></td>
			<?=$mainContractRow?>
			<td class=xl7530318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt' id = "budgetPlanCheck">0,00</td>
		</tr>
		<tr class=xl1530318 height=20 style='height:15.0pt'>
			<td height=20 class=xl8030318 width=202 style='height:15.0pt;border-top:none;
  width:152pt'>Addendums:
			</td>
			<td class=xl9530318 width=142 style='border-top:none;border-left:none;
  width:107pt'>&nbsp;</td>
			<td class=xl10030318 width=75 style='width:56pt;
    border-top: medium none;'>&nbsp;</td>
			<td class=xl9330318 width=19 style='width:14pt'></td>
			<?=$rowClear2?>
			<td class=xl7530318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt'>&nbsp;</td>
		</tr>
		<?=$addRows?>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl10830318 width=202 style='height:15.0pt;width:152pt;border-top:1px solid black;'>TOTAL
				CONTRACT (Budgeted)
			</td>
			<td class=xl10830318 width=142 style='border-left:none;width:107pt'>&nbsp;</td>
			<td class=xl10830318 width=75 style='width:56pt' id="totalContractRowPlan"><?= $stuff->format($proj->PROJ_BUDGET+$addsumBudget)?></td>
			<td class=xl9330318 width=19 style='width:14pt'></td>
			<?=$totalContractRow?>
			<td class=xl7530318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class='xl10830318' id="totalContractRowCheck" width=66 style='width:50pt'>0</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td colspan=<?= $monthCount + 7 ?> height=20 class=xl7730318 style='height:15.0pt'></td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl8130318 width=202 style='height:15.0pt;
  width:152pt'>CLIENT PAYMENTS (Actual)
			</td>
			<td class=xl9830318 width=142 style='border-left:none;
  width:107pt'>&nbsp;</td>
			<td class=xl11330318 width=75 style='width:56pt' id="clientPaymentsSum">0</td>
			<td class=xl7030318 width=19 style='width:14pt'></td>
			<?=$clientPayments?>
			<td class=xl7530318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl11330318 width=66 style='width:50pt'id="clientPaymentsCheck">0</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td colspan=<?= $monthCount + 7 ?> height=20 class=xl7730318 style='height:15.0pt'></td>
		</tr>
		<tr class=xl1530318 height=21 style='height:15.75pt'>
			<td height=21 class=xl9830318 width=202 style='height:15.75pt;width:152pt;border-right:1px solid black;'>DIFFERENCE
			</td>
			<td class=xl9830318 width=142 style='border-left:none;width:107pt'>&nbsp;</td>
			<td class='xl10630318 ' width=75 style='width:56pt' id="contractDiff">0</td>
			<td class=xl7130318 width=19 style='width:14pt'></td>
			<?=$difference?>
			<td class=xl7530318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl11430318 width=66 style='width:50pt' id="contractCheck">0</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td colspan=<?= $monthCount + 7 ?> height=20 class=xl7330318 width=591 style='height:15.0pt;
  width:444pt'></td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl6930318 width=202 style='height:15.75pt;width:152pt'>COSTS
				(Budget)
			</td>
			<td class=xl8930318 width=142 style='width:107pt'></td>
			<td class=xl7330318 width=75 style='width:56pt'></td>
			<td class=xl7330318 width=19 style='border-right:none;width:14pt'></td>
			<?=$rowDates?>
			<td class=xl7330318 width=19 style='width:14pt'></td>
			<td class=xl10730318>Check</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl9030318 width=202 style='height:15.0pt;width:152pt'>Own
				engineering
			</td>
			<td class=xl12430318 width=142 style='border-left:none;width:107pt'>&nbsp;</td>
			<td class='xl12530318 editable costPlan' id="OWN_ENGINEERING" width=75 style='width:56pt'><? if ($proj->OWN_ENGINEERING) print  $stuff->format($proj->OWN_ENGINEERING); else print("0,00");?></td>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<?=$coe?>
			<td class=xl7430318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl10330318 width=66 style=border-top:none;'width:50pt' id="OWN_ENGINEERING_CHECK">0,00</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl8130318 width=202 style='height:15.0pt;border-top:none;
  width:152pt'>Sub-contractors
			</td>
			<td class='xl9530318' width=142 style='border-top:none;border-left:none;
  width:107pt'>&nbsp;</td>
			<td class='xl12630318 editable costPlan' id="SUB_CONTRACTORS" width=75 style='width:56pt;border-top:none;'><? if ($proj->SUB_CONTRACTORS) print  $stuff->format($proj->SUB_CONTRACTORS); else print("0,00");?></td>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<?=$csubcontr?>
			<td class=xl7430318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt' id="SUB_CONTRACTORS_CHECK">0,00</td>
		</tr>

		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl8130318 width=202 style='height:15.0pt;border-top:none;
  width:152pt'>Freellancers
			</td>
			<td class='xl9530318' width=142 style='border-top:none;border-left:none;
  width:107pt'>&nbsp;</td>
			<td class='xl12630318 editable costPlan' id="FREE_LANCERS" width=75 style='width:56pt;border-top:none;'><? if ($proj->FREE_LANCERS) print  $stuff->format($proj->FREE_LANCERS); else print("0,00");?></td>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<?=$cfreelancers?>
			<td class=xl7430318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt' id="FREE_LANCERS_CHECK">0,00</td>
		</tr>

		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl8130318 width=202 style='height:15.0pt;border-top:none;
  width:152pt'>Travelling
			</td>
			<td class=xl9530318 width=142 style='border-left:none;border-top:none;width:107pt'>&nbsp;</td>
			<td class='xl12630318 editable costPlan' id="TRAVELLING" width=75 style='border-top:none;width:56pt'><? if ($proj->TRAVELLING) print  $stuff->format($proj->TRAVELLING); else print("0,00");?></td>
			<td class=xl7230318 width=19 style='border-top:none;width:14pt'></td>
			<?=$ctravel?>
			<td class=xl7430318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt' id="TRAVELLING_CHECK">0,00</td>
		</tr>
		<tr class=xl1530318 height=20 style='height:15.0pt'>
			<td height=20 class=xl8130318 width=202 style='height:15.0pt;border-top:none;
  width:152pt'>Cash
			</td>
			<td class=xl9530318 width=142 style='border-top:none;border-left:none;width:107pt'>&nbsp;</td>
			<td class='xl12630318 editable costPlan' id="CASH" width=75 style='border-top:none;width:56pt'><? if ($proj->CASH) print  $stuff->format($proj->CASH); else print("0,00");?></td>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<?=$ccash?>
			<td class=xl7430318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt' id="CASH_CHECK">0,00</td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl8330318 width=202 style='height:15.75pt;border-top:
  none;width:152pt'>Other costs
			</td>
			<td class=xl9630318 width=142 style='border-top:none;border-left:none;width:107pt'>&nbsp;</td>
			<td class='xl12730318 editable costPlan' id="OTHER_COSTS" width=75 style='border-top:none;width:56pt'><? if ($proj->OTHER_COSTS) print  $stuff->format($proj->OTHER_COSTS); else print("0,00");?></td>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<?=$cother?>
			<td class=xl7430318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl11130318 width=66 style='border-top:none;width:50pt'  id="OTHER_COSTS_CHECK">0,00</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl10830318 width=202 style='height:15.0pt;width:152pt'>TOTAL
				COSTS (Budgeted)
			</td>
			<td class=xl10830318 width=142 style='width:107pt'></td>
			<td class=xl10830318 width=75 style='width:56pt' id="costPlanSum">0,00</td>
			<td class=xl7130318 width=19 style='width:14pt'></td>
			<?=$ctotal?>
			<td class=xl7030318 width=19 style='width:14pt'></td>
			<td class=xl10830318 width=66 style='width:50pt'id="costPlanCheck">0,00</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td colspan=<?= $monthCount + 7 ?> height=20 class=xl12330318 width=591 style='height:15.0pt;
  width:444pt'></td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl6930318 width=202 style='height:15.75pt;width:152pt'>COSTS
				(Actual)
			</td>
			<td class=xl8930318 width=142 style='width:107pt'></td>
			<td class=xl7330318 width=75 style='width:56pt'></td>
			<td class=xl7330318 width=19 style='width:14pt'></td>
			<?=$rowDates?>
			<td class=xl7330318 width=19 style='width:14pt'></td>
			<td class=xl10730318>Check</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl9030318 width=202 style='height:15.0pt;width:152pt'>Own
				engineering
			</td>
			<td class=xl12430318 width=142 style='border-left:none;width:107pt'>&nbsp;</td>
			<td class='xl12530318 costValSum' width=75 style='width:56pt' id="OWN_ENGINEERING_VAL">0,00</td>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<?=$aoe?>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt' id="OWN_ENGINEERING_VAL_CHECK">0,00</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl8130318 width=202 style='height:15.0pt;border-top:none;
  width:152pt'>Sub-contractors
			</td>
			<td class=xl9530318 width=142 style='border-top:none;border-left:none;
  width:107pt'>&nbsp;</td>
			<td class='xl10330318 costValSum' width=75 style='border-top:none;width:56pt' id="SUB_CONTRACTORS_VAL">0,00</td>
			<td class=xl7030318 width=19 style='width:14pt'></td>
			<?=$asubcontr?>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt'id="SUB_CONTRACTORS_VAL_CHECK">0,00</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl8130318 width=202 style='height:15.0pt;border-top:none;
  width:152pt'>Freelancers
			</td>
			<td class=xl9530318 width=142 style='border-top:none;border-left:none;
  width:107pt'>&nbsp;</td>
			<td class='xl10330318 costValSum' width=75 style='border-top:none;width:56pt' id="FREE_LANCERS_VAL">0,00</td>
			<td class=xl7030318 width=19 style='width:14pt'></td>
			<?=$afreelancers?>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt'id="FREE_LANCERS_VAL_CHECK">0,00</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl8130318 width=202 style='height:15.0pt;border-top:none;
  width:152pt'>Travelling
			</td>
			<td class=xl9530318 width=142 style='border-top:none;border-left:none;width:107pt'>&nbsp;</td>
			<td class='xl10330318 costValSum' width=75 style='border-top:none;width:56pt' id = "TRAVELLING_VAL">0,00</td>
			<td class=xl7030318 width=19 style='width:14pt'></td>
			<?=$atravel?>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt'  id = "TRAVELLING_VAL_CHECK">0,00</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl8130318 width=202 style='height:15.0pt;border-top:none;
  width:152pt'>Cash
			</td>
			<td class=xl9530318 width=142 style='border-top:none;border-left:none;width:107pt'>&nbsp;</td>
			<td class='xl12630318 costValSum' width=75 style='border-top:none;width:56pt' id="CASH_VAL">0,00</td>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<?=$acash?>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<td class=xl10330318 width=66 style='border-top:none;width:50pt'  id="CASH_VAL_CHECK">0,00</td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl8330318 width=202 style='height:15.75pt;border-top:
  none;width:152pt'>Other costs
			</td>
			<td class=xl9630318 width=142 style='border-top:none;border-left:none;width:107pt'>&nbsp;</td>
			<td class='xl11130318 costValSum' width=75 style='border-top:none;width:56pt' id="OTHER_COSTS_VAL">0,00</td>
			<td class=xl7630318 width=19 style='border-left:none;width:14pt'>&nbsp;</td>
			<?=$aother?>
			<td class=xl7430318 width=19 style='border-right:none;width:14pt'>&nbsp;</td>
			<td class=xl11130318 width=66 style='border-top:none;width:50pt'id="OTHER_COSTS_VAL_CHECK">0,00</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl10830318 width=202 style='height:15.0pt;width:152pt'>TOTAL
				COSTS (Actual)
			</td>
			<td class=xl10830318 width=142 style='width:107pt'></td>
			<td class=xl10830318 width=75 style='width:56pt' id="TOTAL_COST_VAL">0,00</td>
			<td class=xl7130318 width=19 style='width:14pt'></td>
			<?=$atotal?>
			<td class=xl7030318 width=19 style='width:14pt'></td>
			<td class=xl10830318 width=66 style='width:50pt'id="TOTAL_COST_VAL_CHECK">0,00</td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td colspan=<?= $monthCount + 7 ?> height=21 class=xl12330318 width=591 style='height:15.75pt;
  width:444pt'></td>
		</tr>
		<tr class=xl1530318 height=21 style='height:15.75pt'>
			<td height=21 class=xl11930318 width=202 style='height:15.75pt;width:152pt'>DIFFERENCE</td>
			<td class=xl12030318 width=142 style='width:107pt'>&nbsp;</td>
			<td class=xl12830318 width=75 style='width:56pt' id="costDiffSum">0,00</td>
			<td class=xl7130318 width=19 style='width:14pt'></td>
			<?=$cdiff?>
			<td class=xl7030318 width=19 style='width:14pt'></td>
			<td class=xl12130318 width=66 style='width:50pt'id="costDiffSumCheck">0,00</td>
		</tr>
		<tr height=20 style='height:15.0pt'>
			<td colspan=<?= $monthCount + 7 ?> height=20 class=xl7330318 width=591 style='height:15.0pt;
  width:444pt'></td>
		</tr>
		<tr height=21 style='height:15.75pt'>
			<td height=21 class=xl6930318 width=202 style='height:15.75pt;width:152pt'>Cash
				Flow
			</td>
			<td class=xl8930318 width=142 style='width:107pt'></td>
			<td class=xl7330318 width=75 style='width:56pt'></td>
			<td class=xl7330318 width=19 style='width:14pt'></td>
			<?=$rowDates?>
			<td class=xl7330318 width=19 style='width:14pt'></td>
			<td class=xl10730318>Check</td>
		</tr>
		<tr class=xl6630318 height=21 style='height:15.75pt'>
			<td height=21 class=xl9030318 width=202 style='height:15.75pt;width:152pt'>Budgeted</td>
			<td class=xl12430318 width=142 style='border-left:none;width:107pt'>&nbsp;</td>
			<td class=xl12530318 width=75 style='width:56pt'id="flowPlanSum">0,00</td>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<?=$cfb?>
			<td class=xl7330318 width=19 style='width:14pt'></td>
			<td class=xl11730318 width=66 style='border-top:none;width:50pt' id="flowPlanCheck">0,00</td>
		</tr>
		<tr class=xl1530318 height=21 style='height:15.75pt'>
			<td height=21 class=xl8330318 width=202 style='height:15.75pt;border-top:
  none;width:152pt'>Actual</td>
			<td class=xl9630318 width=142 style='border-top:none;border-left:none;  width:107pt'>&nbsp;</td>
			<td class=xl12730318 width=75 style='border-top:none;width:56pt' id="flowFactSum">0,00</td>
			<td class=xl7230318 width=19 style='width:14pt'></td>
			<?=$cfa?>
			<td class=xl7330318 width=19 style='width:14pt'></td>
			<td class=xl11830318 width=66 style='border-top:none;width:50pt' id="flowFactCheck">0,00</td>
		</tr>
		<tr class=xl1530318 height=20 style='height:15.0pt'>
			<td height=20 class=xl10830318 width=202 style='height:15.0pt;width:152pt'>DIFFERENCE</td>
			<td class=xl10830318 width=142 style='width:107pt'></td>
			<td class=xl10830318 width=75 style='width:56pt' id="flowDiffSum">0,00</td>
			<td class=xl7130318 width=19 style='width:14pt'></td>
			<?=$cfdiff?>
			<td class=xl7030318 width=19 style='width:14pt'></td>
			<td class=xl10830318 width=66 style='width:50pt' id="flowDiffCheck">0,00</td>
		</tr>
	</table>
	<div id="summaryDialog" style="cursor: pointer;">
		<div class = "ui-corner-all panel" style="border:1px solid silver;text-align: left;background: white;">
			<span class = "panel-caption finetext">Profit</span>
			<table style="width: 100%;">
				<tr>
					<td style="width: 50%" class="finetext textright">Budgeted:</td>
					<td style="width: 50%" class="finetext textright" id="profitBudgeted"></td>
				</tr>
				<tr>
					<td style="width: 50%" class="finetext textright">Actual:</td>
					<td style="width: 50%" class="finetext textright" id="profitActual"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
