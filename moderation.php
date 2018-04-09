<?
	include_once "config.php";
	session_start();
	
	//--
	// Link with the MySQL database.
	//--	
	$link = new mysqli($db_host, $db_user, $db_pass, $db_name);
	if (!$link) { die('Could not connect: ' . mysql_error()); }
	#mysql_select_db("$db_name");
	
	#mysql_set_charset('utf8');
	
	$acc_level = get_user_level($_SESSION['uname'],$link);
	
	if ($acc_level < 2) {
		header("Location: index.php?error=denied");
		die();
	}
	
	$pool = $_GET['pool'];
	if (!$pool) {
		header("Location: index.php?error=nopool");
		die();
	}
	
	$id 	= $_GET['id'];
	$action = $_GET['action'];
	
	// -- QUEUE MODERATION
	if ($pool == ($db_table . "_queue") )
	{
		// We are moderating queued entries. Thus we either APPROVE or REMOVE them.
		if ($action == "approve")
		{
			// Approve entry. Fetch it from database and move to proper table.
			$query = "SELECT * FROM $pool WHERE `id` = $id";
			$result = $link->query($query);
			$row   = $result->fetch_array();
			
			// echo "<pre style=\"white-space: pre\">" . htmlspecialchars(print_r($data)) . "</pre>";
			
			$date		= $row['post_date']; // Entry's Post Date
			$author		= $row['author']; // Author of the entry
			$category 	= $row['category']; // Category, i.e: RPG Session, IM
			$source 	= $row['source']; // Source, i.e: Classic Rollage, GG
			$title 		= $row['title']; // Custom Label
			$text		= $row['text']; // Quote
			$explicit	= $row['explicit']; // Whether it contains explicit vocabulary
			$text_enGB	= $row['text_translated']; // Personally translated quote
			
			$text 		= str_replace("'","\'",$text);
			$text_enGB 	= str_replace("'","\'",$text_enGB);
			
			$table = $db_table . "_entries";
			$query = "INSERT INTO `$table` (`post_date`,  `author`,  `category`,  `source`,  `title`,  `text`,  `text_translated`) ";
			$query = $query .      "VALUES (    '$date', '$author', '$category', '$source', '$title', '$text',  '$text_enGB');";
			
			echo $query . "</br>";
			$link->query($query);
			if ($link->error) {
				echo ($link->error);
				die();
			} else {
				$table = $db_table . "_queue";
				$query = "DELETE FROM `$table` WHERE `id` = $id";
				$link->query($query);
			}
			
			header("Location: index.php");
			die();
		}
		
		if ($action == "remove" )
		{
			// Removing from queue. Unlike with living entries, we don't bother with dumping a backup copy.
			$query = "DELETE FROM `$pool` WHERE `id` = $id";
			mysql_query($query);
			
			header("Location: index.php");
			die();
		}
	}
	
	/* LIVE ENTRIES */
	
	// Deny access to non-moder.
	if ($acc_level < 2) {
		header("Location: index.php?error=denied");
		die();
	}
	
	// -- LIVE ENTRIES MODERATION
	if ($pool == ($db_table . "_entries"))
	{
		// We're working on LIVE entries.
	    if ($action == "remove" )
		{
			// Fetch the entry from database. We will make an archive of Removed entries, in case some get removed by accident.
			$query = "SELECT * FROM $pool WHERE `id` = $id";
			$result = $link->query($query);
			$row   = $result->fetch_array();
			
			// Write entry down to archive.
			$archive = fopen("archive.txt", "a+");
			$data = print_r($row,true);
			
			fwrite($archive,$data);
			fclose($archive);
			
			$query = "DELETE FROM `$pool` WHERE `id` = $id";
			$link->query($query);
						
			header("Location: index.php");
			die();
		}
	}
	
	/* ADMIN SECTION */
	
	// Deny access to non-admin.
	if ($acc_level < 3) {
		header("Location: index.php?error=denied");
		die();
	}
	
	// -- USER ADMINISTRATION;
	if ($pool == ($db_table . "_users"))
	{
	
		// Remove User
		if ($action == "remove" )
		{
			$query = "DELETE FROM `$pool` WHERE `username` = '$id'";
			mysql_query($query);
			
			header("Location: index.php?page=administration");
			die();
		}
		
		// Approve and activate newly registered user.
		if ($action == "approve" )
		{
			$query = "UPDATE `$pool` SET `active` = '1' WHERE `username` = '$id'";
			mysql_query($query);
			
			header("Location: index.php?page=administration");
			die();
		}
		
		// Deactivate User
		if ($action == "deactivate" )
		{
			$query = "UPDATE `$pool` SET `active` = '0' WHERE `username` = '$id'";
			mysql_query($query);
			
			header("Location: index.php?page=administration");
			die();
		}
	}
?>