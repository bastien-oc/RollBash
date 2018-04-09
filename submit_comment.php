<?
	include_once "config.php";
	session_start();

	$data = array();
	$data = $_POST;
	
	// If there is no $_POST data, go back.
	if (!$_POST) {
		header('LOCATION: index.php');
		die();
	}
	
	$author 	= $data['author'];
	$comment 	= $data['comment'];
	$bash_id	= $data['bash_id'];
	$date		= gmdate("Y-m-d H:i:s");
	
	if ($author == "" || $comment == "") {
		header("Location: index.php?error=nodata");
		die();
	}
	
	// If user is logged in, we don't want to display his address.
	if ($_SESSION['auth'] == true) {
		$address = ""; } else {
		$address 	= $_SERVER['REMOTE_ADDR']; }
	
	$comment	= str_replace("'","\'",$comment);
	
	//$comment 	= utf8_encode($comment);
	
	/* Establish MySQL Connection */
		$link = mysql_connect($db_host, $db_user, $db_pass);
		if (!$link) { die('Could not connect: ' . mysql_error()); }
		// Select the database
		mysql_select_db("$db_name", $link);
		
	/* Insert */
		$table = $db_table . "_comments";
		$query = "INSERT INTO `$table` (`bash_entry_id`, `author`, `address`, `date`, `comment`) ";
		$query = $query .      "VALUES ('$bash_id',  '$author', '$address', '$date', '$comment'); ";
		
		mysql_set_charset('utf8');
		mysql_query($query);
		
		if (mysql_error($link)) {
			header("LOCATION: index.php?error=".urlencode(mysql_error($link)) );
			die();
		}
		
		mysql_close($link);
		
	header("LOCATION: index.php?id=$bash_id");
?>