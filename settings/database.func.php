<?
function db_connect()
{
	$db_link = mysqli_connect(DB_HOST . ':3306', DB_USER, DB_PASS);
	if ($db_link) {
		return $db_link;
	} else return FALSE;
}

function db_query($query, $db_link = FALSE)
{
	$db_link = db_connect();
	mysqli_select_db($db_link, DB_NAME);
	$res = mysqli_query($db_link, $query);
	if ($res === TRUE) return TRUE;
	elseif ($res) {
		$result = array();
		while (($rec = mysqli_fetch_object($res)) != NULL) {
			$result[] = $rec;
		}
		mysqli_free_result($res);
		return $result;
	} else return FALSE;
}

function db_set_encoding($encoding)
{
	$db_link = db_connect();
	return (mysqli_query($db_link, 'SET NAMES ' . $encoding, $db_link) && mysqli_query('SET CHARACTER SET ' . $encoding));
}

?>