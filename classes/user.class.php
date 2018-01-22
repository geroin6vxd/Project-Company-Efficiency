<?

class class_user
{
	public function is_login()
	{
		if (isset($_SESSION['UID']) && ($_SESSION['UID'] > 0)) return TRUE;
		return FALSE;
	}
	public function try_login($login, $password)
	{
		$_SESSION['UID'] = 0;
		$_SESSION['COST'] = 0;
		if (isset($login) && isset($password)) {
			$query = "SELECT ID, COST FROM users WHERE (DELETED < 1 OR DELETED IS NULL) AND ABBR = '" . $login . "' AND SECRET = '" . md5($password) . "' LIMIT 1";
			$res = db_query($query);
			if (is_array($res) && count($res)) {
				$row = array_pop($res);
				$_SESSION['UID'] = $row->ID;
				$_SESSION['COST'] = $row->COST;
				$this->set_user_last_activity($_SESSION['UID']);
				return TRUE;
			} else return FALSE;
		}
		else return FALSE;
	}
	public function get_homepage($id, $showAll = false)
	{
		if (isset($id)) {
			$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL)';
			$query = "SELECT HOMEPAGE FROM users WHERE  ID = '" . $id . "'".$deleted." LIMIT 1";
			$res = db_query($query);
			// Если выполнение запроса успешно
			if (is_array($res) && count($res)) {
				$row = array_pop($res);
				return $row->HOMEPAGE;
			} else return 'null.php';
		} // Иначе, возвращаем false
		else return 'null.php';
	}
	public function logout()
	{
		session_unset();
		return TRUE;
	}
	public function get_users_chosen($selected = FALSE, $id)
	{
		$proj = $this->get_users();
		$result = '<SELECT class="users_chosen" id="' . $id . '" data-placeholder="Choose a project manager..." style="width:100%;"><OPTION value="-1"></OPTION>';
		foreach ($proj as $item) {
			$sel = ($selected === $item->ID) ? "selected" : "";
			$result .= '<OPTION value="' . $item->ID . '" ' . $sel . ' >' . $item->U_NAME . ' ' . $item->FAMILY . '</OPTION>';
		}
		$result .= '</SELECT>
					<SCRIPT>
						$("#' . $id . '").chosen({disable_search_threshold: 5, allow_single_deselect: true});

					</SCRIPT>';
		return $result;
	}
	public function get_users($id=false, $showAll = false)
	{
		if (isset($id) && ($id != '')) {
			$expr = " AND ID = '" . $id . "' ";
		} else $expr = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';
		$query = 'SELECT * FROM users WHERE 1 '.$deleted . $expr.' ORDER BY NUMBER';
		$res = db_query($query);
		if (isset($id) && ($id != false)) $res = array_pop($res);
		if ($res) return $res; else return FALSE;
	}
	public function get_rated_users($id=false, $showAll = false)
	{
		if (isset($id) && ($id != '')) {
			$expr = "  AND ID = '" . $id . "' ";
		} else $expr = '';
		$deleted = ($showAll)?'':' AND (DELETED < 1 OR DELETED IS NULL) ';

		$query = 'SELECT * FROM users WHERE RATED = 1 ' .$deleted. $expr.' ORDER BY NUMBER';
		$res = db_query($query);
		if (isset($id) && ($id != false)) $res = array_pop($res);
		if ($res) return $res; else return FALSE;
	}
	public function add_user($number, $login, $family, $name, $role, $rated){
		$rate = ($rated == "true")?1:0;
		$query = 'INSERT INTO
							users
						  	(NUMBER, ABBR, FAMILY, U_NAME, SECRET, HOMEPAGE, RATED)
				  VALUES
				  			("' . $number.'", "' . $login.'", "'.$family.'", "'.$name.'", "'.md5(1111).'", "'.$role.'", "'.$rate.'")';
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function edit_user_homepage($id, $homepage){
		$query = 'UPDATE users SET HOMEPAGE = "' . $homepage.'" WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function edit_user_number($id, $number){
		$query = 'UPDATE users SET NUMBER = "' . $number.'" WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function edit_user_login($id, $login){
		$query = 'UPDATE users SET ABBR = "' . $login.'" WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function edit_user_family($id, $family){
		$query = 'UPDATE users SET FAMILY = "' . $family.'" WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function edit_user_name($id, $name){
		$query = 'UPDATE users SET U_NAME = "' . $name.'" WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function edit_user_rated($id, $rated){
		$rate = ($rated == 'true')? 1:0;
		$query = 'UPDATE users SET RATED = "' . $rate.'" WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function delete_user($id){
		$query = 'UPDATE users SET DELETED = 1 WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function set_user_last_activity($id){
		$query = 'UPDATE users SET LAST_ACTIVITY = UNIX_TIMESTAMP() WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function edit_user_cost($id, $cost){
		$query = 'UPDATE users SET COST = "' . $cost.'" WHERE ID = '.$id;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function edit_user_password($uid, $passwd){
		$query = 'UPDATE users SET SECRET = "' . md5($passwd).'" WHERE ID = '.$uid;
		$res = db_query($query);
		if ($res) return $res; else return FALSE;
	}
	public function get_roles($selected=false, $id, $class = ''){
		$result = '<SELECT class="roles_chosen '.$class.'" id="' . $id . '" data-placeholder="Choose employee role..." style="width:100%;"><OPTION value="null.php"></OPTION>';
		$result .= '<OPTION value="buhgalter.php" ';if($selected == "buhgalter.php"){$result.='selected';} $result.='>Buchhalter</OPTION>';
		$result .= '<OPTION value="director.php" ';if($selected == "director.php"){$result.='selected';} $result.='>Director</OPTION>';
		$result .= '<OPTION value="worker.php" ';if($selected == "worker.php"){$result.='selected';} $result.='>Engineer</OPTION>';
		$result .= '<OPTION value="manager.php" ';if($selected == "manager.php"){$result.='selected';} $result.='>Main manager</OPTION>';
		$result .= '<OPTION value="worker.php" ';if($selected == "worker.php"){$result.='selected';} $result.='>Office worker</OPTION>';
		$result .= '<OPTION value="admin.php" ';if($selected == "admin.php"){$result.='selected';} $result.='>System administrator</OPTION>';
		$result .= '</SELECT>';
		return $result;
	}
}

$user = new class_user();
?>

