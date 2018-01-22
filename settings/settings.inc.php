<?
//error_reporting(E_ALL);
define('SITE_NAME', 'Project Costs');
define('TITLE', SITE_NAME);
define('SITE_DOMAIN', 'project.itbrain.tech');
define('SITE_ADDR', 'http://' . SITE_DOMAIN);
define('WWW', '');
define('SITE_PATH', SITE_ADDR . WWW);
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . WWW);
define('IMG', SITE_ADDR . '/images');
define('COMPANY_NAME', 'pbr');
define('CODEPAGE', 'utf-8');
define('DB_HOST', 'localhost');
define('DB_NAME', 'project');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_CODEPAGE', 'utf8');
mb_internal_encoding(CODEPAGE);
header('Content-type: text/html; charset=' . CODEPAGE);
date_default_timezone_set('Europe/Moscow');
require_once(ROOT . '/classes/classes.php');
require_once('database.func.php');
if (!$user->is_login()) {
	$login = (isset($_REQUEST['name'])) ? $_REQUEST['name'] : '';
	$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : '';
	if (!$user->try_login($login, $password)) header('Location: ' . SITE_PATH . '/login.php');
}
?>