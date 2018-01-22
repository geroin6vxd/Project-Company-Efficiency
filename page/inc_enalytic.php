<?
require_once('../settings/settings.inc.php');
$projYears = $project->get_projects_years($_SESSION['UID'], false, false);
$currYear = (isset($_SESSION['CUR_YEAR'])) ? $_SESSION['CUR_YEAR']:$projYears[0]->PROJ_YEAR;
$proj = $project->get_projects_by_year($currYear, $_SESSION['UID']);

?>
<link rel="stylesheet" href="../styles/cost.css" type="text/css" media="all"/>
<div style="width:500px;">
<select id = "yearSelector" data-placeholder="Nothihg selected">
	<?
	print ('<option value = "" ></option>');
	foreach($projYears as $item){
		$selected = ($item->PROJ_YEAR == $currYear) ? 'selected' : '';
		print ('<option value = "'.$item->PROJ_YEAR.'" '.$selected.'>'.'Analytical data on the projects in '.$item->PROJ_YEAR.' year</option>');
	}
	?>
</select>
</div>
<br>
<div id="projectsArea">
	<ul>
<?
foreach($proj as $item){
	print('<li><a href="/page/report_executor.php?PROJ_ID='.$item->ID.'">'.$item->PROJ_CAPTION.'</a></li>');
}
?>
	</ul>
</div>
<script>
	$("#projectsArea").tabs();
	$(".ui-tabs-tab").click(function(){
		$('.ui-dialog').remove();
		$('.costArea').html("<div style='text-align: center;'><h2>Loading...</h2></div>")
	});


	$("#yearSelector").chosen({disable_search:true}).unbind().change(function(){
		$.ajax({
			method: "POST",
			url: "page/executor.php",
			datatype: JSON,
			data: {
				operation: "SET_CUR_YEAR_FOR_COSTS",
				year: $(this).val()
			}
		})
			.done(function (msg) {
				$("#enalytic").click();
			});
	});
</script>