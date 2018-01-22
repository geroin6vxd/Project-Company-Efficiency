<?
require_once('settings.inc.php');
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?= TITLE ?></title>

	<link rel="stylesheet" href="./styles/redmond/jquery-ui.min.css" type="text/css" media="all"/>
	<link rel="stylesheet" href="./styles/chosen.min.css" type="text/css" media="all"/>
	<link rel="stylesheet" href="./styles/main_style.css" type="text/css" media="all"/>
	<link rel="stylesheet" href="./styles/MonthPicker.css" type="text/css" media="all"/>
	<script type="text/javascript" src="./jscript/jquery-3.0.0.min.js"></script>
	<script type="text/javascript" src="./jscript/jquery-ui.min.js"></script>
	<script type="text/javascript" src="./jscript/common.js"></script>
    <script type="text/javascript" src="./jscript/moment.min.js"></script>
    <script type="text/javascript" src="./jscript/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="./jscript/MonthPicker.js"></script>
    <script type="text/javascript" src="./jscript/editabletd.js"></script>
</head>
<body>
<div align="center"
     style="border:1px solid; color:#000000; background: url(images/background.jpg) no-repeat; background-size: cover; border-color:#CCCCCC;padding: 5px 5px 5px 5px;">
