<?
 // signup.php
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
	$email	  = $_POST['email'];
	
	// Replace " ' " with escape character, so the MySQL won't screw up the query...
	$username = str_replace("'","\'",$username);
	$password = str_replace("'","\'",$password);
		
	// Define the table variable
	$table_users = $db_table . "_users";
	
	$sign_date = gmdate("Y-m-d H:i:s");
	if ( isset($_SERVER["REMOTE_ADDR"]) )    {
		$sign_ip = $_SERVER["REMOTE_ADDR"]; 
	} else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ) {
		$sign_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} else if ( isset($_SERVER["HTTP_CLIENT_IP"]) ) {
		$sign_ip = $_SERVER["HTTP_CLIENT_IP"];
	}	
	
	// Build the query
	$query = "INSERT INTO $table_users (`username`, `password`, `email`, `active`, `creation_date`, `ip`) VALUES ('$username','$password','$email', 0,'$sign_date', '$sign_ip');";
	
	$result = mysql_query($query);
	
	$sname = $_SERVER['SERVER_NAME'];
	
	if (!(mysql_error($link))) {
		mail($admin_mail,$title." - New User `$username` is awaiting account activation!", "Visit <a href=\"$sname/bash/index.php?page=administration\">$sname/bash/index.php?page=administration</a> to activate new user `$username`.\n<b>User's IP Address</b>: $sign_ip");
		mail($email,$title." - Signup Confirmation","Hello, $username! \nYour account was created. However, it still must be approved by the site administrator before you can log in. \nThank you for registering with us!");
	}
	
	header("Location: index.php?page=registered");
?>