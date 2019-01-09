<?
	define("ACC_GUEST"		,-1);
	define("ACC_NORMAL"		,0);
	define("ACC_VIP"			,1);
	define("ACC_MODERATOR"	,2);
	define("ACC_ADMINISTRATOR",3);

	$db_host 	= "host";
	$db_name	= "name";
	$db_user	= "user";
	$db_pass	= "pass";
	$db_table 	= "table";
	
	$admin_mail = "admin@admin";
	$title 		= "Bash";
	
	function get_user_level($user, $link)
	{
		global $db_table;
		$usr_table = $db_table . "_users";
		
		$query = "SELECT `acc_level` FROM $usr_table WHERE `username` LIKE '$user'";
		$result = $link->query($query);
		
		if ($link->error) { echo "</br><b>MySQL returned error:</b> " . $link->error; }
		
		$data = $result->fetch_array();
		
		$user_level = $data[0];
		return $user_level;
		
		$link->close();
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