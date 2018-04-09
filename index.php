<?
	if (!$graphic)
	{
		if ( !function_exists("cp2iso") ) { function cp2iso($tekst){ return strtr($tekst, "\xA5\x8C\x8F\xB9\x9C\x9F","\xA1\xA6\xAC\xB1\xB6\xBC"); } } if ( ob_start("cp2iso") ) register_shutdown_function("ob_flush");
	}
?>

<?
	/* LOAD DEPENDENCIES */
	include_once "config.php";
	include_once "google_translate.php";
	session_start();
	
	/**
	 * Link with the MySQL database.
	 */
	
	$link = mysql_connect($db_host, $db_user, $db_pass);
	if (!$link) { die('Could not connect: ' . mysql_error()); }
	mysql_select_db("$db_name");
	
	/**
	 * MySQL Queries
	 */
	$table = $db_table . "_entries";
	$table_queue = $db_table . "_queue";
	$table_users = $db_table . "_users";
	
	// --
	// Get the number of entries from MySQL. Used later.
	// --
	$query = "SELECT * FROM $table";
	$bash_num_result = mysql_query($query);
	$bash_num		= mysql_num_rows($bash_num_result);
	$pages_num = $bash_num / 10;
	
	$query = "SELECT * FROM $table_queue";
	$moderation_num_result 	= mysql_query($query);
	$moderation_num			= mysql_num_rows($moderation_num_result);
	
	// -- 
	// Get the number of users!
	// --
	$query = "SELECT * FROM $table_users WHERE `active` = 1";
	$result 		= mysql_query($query);
	$active_users	= mysql_num_rows($result);
	
	$query = "SELECT * FROM $table_users WHERE `active` = 0";
	$result 		= mysql_query($query);
	$inactive_users	= mysql_num_rows($result);
	
	// -- 
	// Get user session related data.
	// --
	$uname = $_SESSION['uname'];
	
	if ($_SESSION['auth'] == false) 
	{
		$acc_level = ACC_GUEST;
	} else {
		$acc_level = get_user_level($_SESSION['uname'],$link);
	}
	
	// --
	// Get number of posts in each of major category.
	// --
	$query = "SELECT * FROM $table WHERE `category` = 'RPG'";
	$result			= mysql_query($query);
	$posts_num_rpg	= mysql_num_rows($result);
	
	$query = "SELECT * FROM $table WHERE `category` = 'Chat and IM'";
	$result			= mysql_query($query);
	$posts_num_chat	= mysql_num_rows($result);
	
	$query = "SELECT * FROM $table WHERE `category` = 'Gaming'";
	$result			= mysql_query($query);
	$posts_num_game	= mysql_num_rows($result);
	
	$query = "SELECT * FROM $table WHERE `category` = 'Other'";
	$result			= mysql_query($query);
	$posts_num_misc	= mysql_num_rows($result);
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="favicon" type="image/x-icon" href="/favicon.ico" />
	<title>RollBash</title>
	<LINK rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
	<?
		$page = $_GET['page'];
		if ($page == "") { $page = "main"; }
	?>
	
	<!-- Devel Box -->
	<div id="debug" style="visibility: hidden; position: fixed; right: 0px; top: 0px; margin: 1em;">
		<div id="debug-title"><b>Last Modifications</b></div>
		<p style="padding: 5px; margin: 0px; white-space: normal;">
			<b>index.php</b>: <a><? echo gmdate ("D H:i, d M Y", filemtime("index.php")); ?></a></br>
			<b>style.css</b>: <a><? echo gmdate ("D H:i, d M Y", filemtime("style.css")); ?></a></br></br>
			 <b>main.php</b>: <a><? echo gmdate ("D H:i, d M Y", filemtime("main.php" )); ?></a></br>
		 <b>comments.php</b>: <a><? echo gmdate ("D H:i, d M Y", filemtime("main.php" )); ?></a></br></br>
		 
		<?php
		
		if ($handle = opendir('.')) {
			while (false !== ($file = readdir($handle))) {
				$preg = "/^(.*).php$/";
				if (preg_match($preg,$file)) {
					$perm = fileperms($file);
					echo "<small>$file <a>" . gmdate ("H:i, d/m/y", filemtime($file)) . "</a> (" . file_permission($perm) . ")</small></br>";
				}
			}
				closedir($handle);
		}
		
		?>
		 
		</p>			
		<div id="debug-title"><b><a href="http://www.rollage.lagownia.pl/var/phpMyAdmin/">&laquo; Manage Entries &raquo;</a></b></div>
	</div>
	
	<!-- The Logo -->
	<div id="header">
			<a href="index.php"><img src="rollbash.png" style="border: 0px;"></a>
		</div>
		
	<!-- The Info Box -->
	<div id="subheader"><b>Control Panel</b></div>
	<div id="notes">
		<!-- Login Area -->
		<div id="pages" style="margin-right: 1em">
		<?
			if (isset($_SESSION['auth'])) {
				echo "You are logged in as <a>" . $_SESSION['uname'] ."</a> <small>(acc level <a>$acc_level</a>)</small> :: ";
				echo "<a href=\"?page=logout\">Log Out</a>";
			} else {
				echo  "<a href=\"?page=login\">Log In</a> <a href=\"?page=signup\">Register</a>";
			}
		?>
		</div>
		<hr>
		<!-- Quick Menu -->
		<div align="center" style="margin-top: 0.2em; text-align: center;">
			<a href="?page=submit"><img src="btn-submit.png"></a>
			<? 
			if ($acc_level >= ACC_MODERATOR) { 
				echo "<a href=\"?page=moderation\"><img src=\"btn-moderation.png\"></a>";
				}
			if ($acc_level >= ACC_ADMINISTRATOR) {
				echo "<a href=\"?page=administration\"><img src=\"btn-administration.png\"></a>";
				}
			?>
		</div>
	</div>
	<div id="footer"></div></br>
	
	<div id="subheader"><b>Info</b></div>
	<div id="notes">
		<hr>
		<!-- Text Area -->
		<div id="textarea">			
			<!-- Block Menu -->
			RollBash is under construction! Read the <a href="?page=features">implemented features and changelog list</a>!</br>
			<hr style="margin-right: 1em">
			</br>
			We currently have <a><? echo $bash_num; ?></a> bash entries, and another <a><? echo $moderation_num; ?></a> awaiting moderation!</br>
			We also have <a><? echo $active_users; ?></a> active users, and another <a><? echo $inactive_users; ?></a> awaiting approval.</br>
		</div>
	</div>
	<div id="footer"></div></br>
	
	<?
	
	
	{ /* Voting Box */
		$id = $_GET['id'];	
		$vote = $_GET['vote'];
		
		if ($vote != 0 && $acc_level >= ACC_NORMAL) {
			$table_votes = $db_table . "_votes";
			
			// Check whether user voted already or not.
			$query = "SELECT * FROM `$table_votes` WHERE `uname` = '$uname' AND `id` = $id";
			$result = mysql_query($query);
			
			if (mysql_num_rows($result) > 0) {
				// We have voted on that entry already. Abort!
			} else  {
				// We still have a vote remaining.
				$query = "INSERT INTO `$table_votes` (`id`,`uname`,`value`) VALUES ('$id','$uname','$vote');";
				mysql_query($query);
			}
		}
	}
	
	if ($_GET['error']) {
		// Define error type.
		$error = $_GET['error'];
		switch ($error) {
			case "nouser":
				$msg = "No such user exists in our database. Please try again using different login details."; 
				break;
			case "passwd":
				$msg = "Password mismach. Please try again or log in using different login details.";
				break;
			case "authed":
				$msg = "You are now logged in! Welcome, " . $_SESSION['uname'] . "!";
				break;
			case "denied":
				$msg = "Access has been denied due to account's insufficient level.";
				break;
			case "nopool":
				$msg = "No pool for moderation has been selected.";
				break;
			case "nodata":
				$msg = "Author and Text fields cannot be empty!";
				break;
			default:
				$msg = $error;
			}
			
		// Display error message
		echo "<div id=\"subheader\"><b>Oops, you broke it!</b></div>";
		echo "<div id=\"content\">";
		echo "<div class=\"even\" style=\"text-align: center\">";
		echo "There was an error processing your request! It says that you broke it!</br><br>";
		echo "<span style=\"color: red\">\"" . $msg . "\"</span>";
		echo "</div>";
		echo "</div>";
	} else
	
	if ($page == "features")
	{
		include_once "page_features.php";
	} else
	
	if ($page == "main")
	{
		// Display the Entries Script
		include_once "main.php";
	} else
	
	if ($page == "moderation")
	{
		// This script basically calls for main.php which in itself supports moderation.
		// However, we shall not allow this page if the user is not at least Moderator!
		if ($acc_level >= ACC_MODERATOR)
		{
			$moderation_mode = true;
		}
		
		// In either way we display main.php. However, only mods will be able to see 
		// the proper moderation options.
		include_once "main.php";
	} else
	
	if ($page == "submit")
	{
		// Display submission page
		include_once "submit_form.php";
	} else
	
	if ($page == "confirm")
	{
		echo "<div id=\"subheader\">Confirmation</div>";
		echo "<div id=\"content\">";
		echo "<div class=\"even\">";
		echo "Your post has been added to the queue! Thank you for your submission.</br>";
		echo "You can click <a href=\"index.php\">here</a> to return to the main page.";
		echo "</div>";
		echo "</div>";
	} else
	
	if ($page == "login" || $page == "signup")
	{
		if ($page ==  "login") {
			$action = "login.php";
			$block  = "Log In Form";
			$btn	= "Log In";
		} else {
			$action = "signup.php";
			$block  = "Registration Form";
			$btn	= "Register";
		}
		echo "<div id=\"subheader\"><b>$block</b></div>";
		echo "<div id=\"content\" style=\"text-align: center;\">";
		echo "<div class=\"even\" style=\"text-align: center\">";
		echo "<div align=\"center\">";
			echo "<form action=\"$action\" method=\"post\">";
			echo "Username:</br> <input type=\"text\" name=\"username\"></input></br>";
			echo "Password:</br> <input type=\"password\" name=\"password\"></input></br>";
			if ($page == "signup") {
				echo "E-mail address:</br> <input type=\"text\" name=\"email\"></input></br></br>";
			}; // Require additional e-mail field when signing up.
			echo "<div align=\"center\"><button type=\"submit\">$btn</button></div>";
			echo "</form>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
		echo "</div>";
	} else
	
	if ($page == "logout")
	{
		session_destroy();
		echo "<div id=\"subheader\">Logged Out</div>";
		echo "<div id=\"content\">";
		echo "<div class=\"even\">";
		echo "You are now logged out! Click <a href=\"index.php\">here</a> to return to main page.</br>";
		echo "</div>";
		echo "</div>";
	} else
	
	if ($page == "authed")	
	{
		echo "<div id=\"subheader\">Logged In</div>";
		echo "<div id=\"content\">";
		echo "<div class=\"even\">";
		echo "You are now logged in! Click <a href=\"index.php\">here</a> to return to main page.</br>";
		echo "</div>";
		echo "</div>";
	} else
	
	if ($page == "registered")
	{
		echo "<div id=\"subheader\">Registration Successful!</div>";
		echo "<div id=\"content\">";
		echo "<div class=\"even\">";
		echo "Registration was successful! However, before you can log in, the account must be approved by administrator. In meantile, click <a href=\"index.php\">here</a> to return to main page.</br>";
		echo "</div>";
		echo "</div>";
	} else
	
	if ($page == "administration")
	{
		if ($acc_level >= ACC_ADMINISTRATOR)
		{
			echo "<div id=\"subheader\">Administration Panel - Users</div>";
			echo "<div id=\"content\">";
			echo "<div class=\"even\">";
			
			$query = "SELECT * FROM $table_users";
			$result = mysql_query($query);
				
			echo "<table align=\"center\" style=\'padding: 0px\">";
			echo "<tr>";
				echo "<th>Username</th><th>E-mail</th><th>Account Level</th><th>Created on</th><th>Active</th>\n";
			echo "</tr>";
			
			$use_table = $db_table . "_users";
			
				while ($row = mysql_fetch_array($result,MYSQL_NUM))
				{
					echo "<tr style=\"margin: 0px; padding: 0pt;\">";
						echo "<td id=\"admin\">" . $row[0] . "</td>\n"; // Username
						echo "<td id=\"admin\">" . $row[3] . "</td>\n"; // E-mail
						echo "<td id=\"admin\">" . $row[4] . "</td>\n"; // Acc Level
						echo "<td id=\"admin\">" . $row[5] . "</td>\n"; // Creation Date
						echo "<td id=\"admin\">" . $row[1] . "</td>\n"; // Active
						// Activation and Deactivation
						if ($row[1] == 0) {
							echo "<td id=\"admin\"> <a href=\"moderation.php?action=approve&id=" . $row[0] . "&pool=$use_table\">Approve</a>";
						} else if ($row[1] ==1) {
							echo "<td id=\"admin\"> <a href=\"moderation.php?action=deactivate&id=" . $row[0] . "&pool=$use_table\">Deactivate</a>";
						}
						// User Removal
						echo "<td id=\"admin\"> <a href=\"moderation.php?action=remove&id=" . $row[0] . "&pool=$use_table\">Remove</a>";
						
					echo "</tr>";
				}
			
			echo "</table>";
			
			echo "</div>";
			echo "</div>"; // Content
			echo "<div id=\"footer\"></div></br>";
		} else {
			include "main.php";
		}
	}	
	
	?>
		
	</div>
	<div id="footer">Graphics, Web Design and Coding by 2011 &copy; <a href="http://ygnas.lagownia.pl/">Ygnas' Designs</a>. All rights reserved.</div>

</body>
</html>