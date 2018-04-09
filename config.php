<?
	define(ACC_GUEST		,-1);
	define(ACC_NORMAL		,0);
	define(ACC_VIP			,1);
	define(ACC_MODERATOR	,2);
	define(ACC_ADMINISTRATOR,3);

	$db_host 	= "localhost";
	$db_name	= "iggyslab_bash";
	$db_user	= "iggyslab_rollage";
	$db_pass	= "LubieBudyn1";
	$db_table 	= "rollbash";
	
	$admin_mail = "tomignatius1991@gmail.com";
	$title 		= "Bash";
	
	function get_user_level($user, $link)
	{
		$usr_table = $db_table . "rollbash_users";
		
		$query = "SELECT `acc_level` FROM $usr_table WHERE `username` LIKE '$user'";
		$result = mysql_query($query, $link);
		
		if (mysql_error()) { echo "</br><b>MySQL returned error:</b> " . mysql_error(); }
		
		$data = mysql_fetch_array($result);
		
		$user_level = $data[0];
		return $user_level;
		
		mysql_close($link);
	}
	
	function file_permission($perms)
	{
		// Owner
		$info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ?
					(($perms & 0x0800) ? 's' : 'x' ) :
					(($perms & 0x0800) ? 'S' : '-'));

		// Group
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ?
					(($perms & 0x0400) ? 's' : 'x' ) :
					(($perms & 0x0400) ? 'S' : '-'));

		// World
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ?
					(($perms & 0x0200) ? 't' : 'x' ) :
					(($perms & 0x0200) ? 'T' : '-'));

		return $info;
	}

	function isValidURL($url)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}
?>