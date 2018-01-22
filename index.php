<?php
require_once('settings/header.php');
?>
	<script language="javascript">page('<?= $user->get_homepage($_SESSION['UID']);?>');</script>
<br>
	<div id="content">
	</div>
<?

require_once('settings/footer.php');
?>