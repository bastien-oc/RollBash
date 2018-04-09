<?
	include_once "config.php";
	
	if (!$_POST) {
		header("Location: index.php?error=No input.");
		die();
	}
	
	$link = mysql_connect($db_host, $db_user, $db_pass);
	if (!$link) { die('Could not connect: ' . mysql_error()); }
	mysql_select_db("$db_name");
	
	$username = $_POST['username'];
	$password = sha1($_POST['password']);
	
	// Replace " ' " with escape character, so the MySQL won't screw up the query...
	$username = str_replace("'","\'",$username);
	$password = str_replace("'","\'",$password);
		
	// Define the table variable
	$table_users = $db_table . "_users";
	
	// Build the query
	$query = "SELECT * FROM $table_users WHERE `username` LIKE '$username' AND `password` = '$password' AND `active` = 1";
	
	$result = mysql_query($query);
	
	if (mysql_num_rows($result) == 0) {
		header("Location: index.php?error=passwd");
		die();
	}
	
	while ( $row = mysql_fetch_array($result, MYSQL_NUM) )
	{
		$username_db	= $row[0];
		$passwdsh_db	= $row[1];
		$privilege_db	= $row[2];
		
		session_start();
				
		$_SESSION['auth'] = true;
		$_SESSION['uname'] = $row[0];
		
		header("Location: index.php?page=authed");
	}
?>