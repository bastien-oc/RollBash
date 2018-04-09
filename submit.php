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
	$category	= $data['category'];
	$source		= $data['source'];
	
	$title		= $data['title'];
	$quote		= $data['quote'];
	$quote_engb = $data['quote_engb'];
	if (array_key_exists('explicit', $data)) {
		$explicit	= $data['explicit'];
	} else { $explicit = false; }
	
	$title 		= str_replace("'","\'",$title);
	$quote 		= str_replace("'","\'",$quote);
	$quote_engb = str_replace("'","\'",$quote_engb);

	$date		= gmdate("Y-m-d H:i:s");
	
	//$comment 	= utf8_encode($comment);
	
	/* Establish MySQL Connection */
		$link = new mysqli($db_host, $db_user, $db_pass, $db_name);
		if (!$link) { die('Could not connect: ' . mysql_error()); }
		// Select the database
		#mysql_select_db("$db_name", $link);
		
	/* Insert */
		// Check for LEVEL
		// Default to moderation queue.
		$table = $db_table . "_queue";
		
		// If user is logged in, check their account level
		if ($_SESSION['auth'] == true) {
			$acc_level = get_user_level($_SESSION['uname'],$link);
			// Grant auto-approval for all above VIP
			if ($acc_level >= ACC_VIP) {
				$table = $db_table . "_entries";
				}
		}
			
		$query = "INSERT INTO `$table` (`post_date`,  `author`,  `category`,  `source`,  `title`,  `text`,  `text_translated`) ";
		$query = $query .      "VALUES (    '$date', '$author', '$category', '$source', '$title', '$quote', '$quote_engb');";
		
		#mysql_set_charset('utf8');
		$link->query($query);
				
		if ($link->error) {
			header("LOCATION: index.php?error=".urlencode($link->error) );
			die();
		}
		
		$link->close();
	header("LOCATION: index.php?page=confirm");
?>