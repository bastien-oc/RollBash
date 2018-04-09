<?
	// Query the database to fetch the comments.
	$comments_query = "SELECT comment_id, author, date, comment, address FROM `$comments_table` WHERE `bash_entry_id` = $id ORDER BY date DESC";
	$comments_result = mysql_query($comments_query);

	$j = 2; // Counter for Comments, used to distinguish Odd and Even entries.
			// We start with 2. The first entry is the Bash itself.
	while ($row = mysql_fetch_array($comments_result, MYSQL_NUM))
	{
		// Fetch comment's data
		$comment_id			= $row[0]; // Fetch comment's unique ID.
		$comment_author		= $row[1]; // Comment's Author.
		$comment_date		= strtotime($row[2]); // Comment's Time.
		$comment_comment	= $row[3]; // Comment itself.
		$comment_address	= $row[4]; // Poster's IP address
		
		$long = ip2long($comment_address);
		if ($long == -1 || $long === FALSE) {
			$comment_host		= "unknown_host";
		} else {
			$comment_host		= "<a>" . gethostbyaddr($comment_address) . "</a>";
		}
		
		// Translate MySQL DATETIME into our own date.
		$display_date	= date("D H:i, d M Y", $comment_date);
		
		// Decide, based on local loop, whether we're displaying Odd or Even comment.
		if ($j % 2) {
			$comment_odd = "odd";
			} else {
			$comment_odd = "even";
			}
			
		if ($comment_odd == "odd") { $margin = 4; } else { $margin = 3; }
		
		// Render the comment
		echo "<div class=\"$comment_odd\" style=\" margin-left: ".$margin."em;\">";
		
			echo "<div style=\"float: right; text-align: right;\"><small>\n";
			echo "<a href=\"#post\">Reply</a></small></div>\n";
		
			echo "<b>Author</b>: <a>$comment_author</a><small> @ $comment_host</small></br>";
			echo "<hr>";
			echo "<div class=\"quote\">$comment_comment</div>";
			echo "<hr>";
			echo "<small><b>Posted:</b> <a>$display_date</a></small>";
		
		echo "</div>";
		
		// Increase the "j" variable we're using to distinguish Odd and Even numbers.
		$j++;
	}
	
	if (mysql_num_rows($comments_result) != 0) {
		echo "<hr>\n";
	}
		
	echo "<a name=\"post\"></a>\n";
	echo "<div class=\"odd\" style=\" margin-left: 4.em;\">";
		echo "<form action=\"submit_comment.php\" method=\"POST\">\n";			
			echo "<input type=\"hidden\" name=\"bash_id\" value=\"$id\"></input>\n"; 
			echo "<b>Author</b>: ";
			// If user is logged in, auto-fill Author field.
				if ( isset($_SESSION['uname']) ) {
					$author = $_SESSION['uname'];
					echo "<input type=\"hidden\" name=\"author\" value=\"$author\"><b>$author</b></input>";
				} else {
					echo '<input type=\"text\" name=\"author\"></input>';
				}
			echo "</br>\n";
			echo "<hr>\n";
			echo "<div class=\"quote\"><textarea name=\"comment\"></textarea></div>\n";
			echo "<div align=center><button type=\"submit\">Post Comment</button>\n</div>\n";
			echo "<hr>\n";
			echo "<small><b>Attention!</b> No comments are anonymous. IP addresses are logged and displayed.</small>\n";
		echo "</form>";
	echo "</div>";
?>