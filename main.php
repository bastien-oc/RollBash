<?
	// --
	// The following function prints a links to different
	// set of bash entriest.
	// --
	function print_page_links($pages_num)
	{
		echo "<div id=\"pages\" align=\"right\">";
		echo "<a href=\"index.php\">1</a>&nbsp;";
			
		for ($i = 1; $i <= $pages_num; $i++)
		{
			$p = $i+1;
			echo "<a href=\"index.php?offset=" . $i * 10 . "\">" . $p . "</a>&nbsp;";
		}
		echo "</div>";
	}
?>

<!-- Categories Box -->
<div id="subheader"><b>Tags</b></div>
<div id="notes">
		<div id="textarea">The list items will in future be clickable, to allow easy access to the categories you want to read!</div>
		<table align="center">
		<tr style="font-size: x-small;">
			<td>
				<b><a href="?category=RPG">RPG</a></b> <? echo "($posts_num_rpg)"; ?>
				<ul>
					<!--
					<li><a href="?tags=classic_rollage">	Klasyczny Rollage</a></li>
					<li><a href="?tags=world_of_darkness">	Swiat Mroku</a></li>
					<li><a href="?tags=rollshima">			RollShima</a></li>
					<li><a href="?tags=spacebusters">		SpaceBusters</a></li>
					<li><a href="?tags=other&category=rpg">	Other</a></li>
					-->
					<li>Klasyczny Rollage</li>
					<li>Swiat Mroku</li>
					<li>RollShima</li>
					<li>SpaceBusters</li>
					<li>Other</li>
				</ul>
			</td>
						
			<td>
				<b><a href="?category=Chat+and+IM">Chat and IM</a></b> <? echo "($posts_num_chat)"; ?>
				<ul>
					<li>Rollage Offtop</li>
					<li>Instant Messenger</a>
					<li>IRC</li>
					<li>Other</li>
				</ul>
			</td>
			
			<td>
				<b><a href="?category=Gaming">Gaming</a></b> <? echo "($posts_num_game)"; ?>
				<ul>
					<li>MMOs</li>
					<li>RollBusters Party</li>
					<li>Other</li>
				</ul>
			</td>
			
			<td>
				<b><a href="?category=Other">Other</a></b> <? echo "($posts_num_misc)"; ?>
				<ul>
					<li>TV Shows and Movies</li>
					<li>NPC Dialogues</li>
					<li>RPG Qutoes</li>
					<li>Others</li>
				</ul>
			</td>
		</tr>
		</table>
</div>

<div id="subheader"><b>&nbsp;</b></div>

<div id="content">

<?php
	// --
	// Display available page numbers.
	// --	
	print_page_links($pages_num);
	echo "<hr>";
	
	// Get entries offset.
	$offset = 0;
	if (array_key_exists('offset',$_GET)) {
		$offset = $_GET['offset'];
	}
	
/**
 * Build the proper query and query the server.
 * Note, that this is also where the hidden rows are filtered.
 */
	
	$query = 			"SELECT id, post_date, author, category, source, title, text, explicit, text_translated, rating ";
	
	// If we have requested moderation mode, use Moderation Queue instead of normal table.
	if ($moderation_mode == true) {
		$use_table = $db_table . "_queue";
	} else {
		$use_table = $table;
	}
	
	$query = $query . 	"FROM $use_table ";

	$title = "";
	$id = "";
	$category = "";
	$source = "";
	if ( isset($_GET['title'])) 		{ $title = $_GET['title'];}
	if ( isset($_GET['id'])) 			{ $id = $_GET['id']; }
	if ( isset($_GET['category'])) 		{ $category = $_GET['category']; }
	if ( isset($_GET['source'])) 		{ $source = $_GET['source']; }
	$request_id = $id;
	$request_title = $title;
	$request_category = $category;
	$request_source = $source;

	#$query = "";
	
	if ( isset($_GET['id']) || isset($_GET['title']) || isset($_GET['category']) || isset($_GET['source']) )
	{
		$query = $query . "WHERE ";
		$request_id = $id;
		if ($request_id != "")
		{
			$query = $query . "`id` = '" . $request_id . "' AND ";
		}
		
		$request_title = $title;
		if ($request_title != "")
		{
			$request_title = str_replace("'","\'",$request_title);
			$query = $query . "`title` LIKE '" . $request_title . "' AND ";
		}	
		
		$request_category = $category;
		if ($request_category != "")
		{
			$query = $query . "`category` LIKE '" . $request_category . "' AND ";
		}
		
		$request_source = $source;
		if ($request_source != "")
		{
			$query = $query . "`source` LIKE '" . $request_source . "' AND ";
		}
		
		// We add one more requirement to the query, so all these ANDs won't screw up the code.
		$query = $query . "`text` IS NOT NULL ";
		
	}	
	
	$query = $query . 	"ORDER BY post_date DESC LIMIT $offset, 10;";
	
	$start_time = microtime();
	
	#print($query);
	$link->query("SET NAMES 'UTF8'");
	$result = $link->query($query);

	$end_time = microtime();
	

	$debug = false;
	if (array_key_exists('debug',$_GET)){
		$debug = $_GET['debug'];
	}
	
	if ($debug == true) {
		echo "<div style=\"font-family: Consolas, Lucida Console, Courier New, Monospace;\">";
		echo $query . "</br>";
		
		if (mysql_error($link)) {
			echo mysql_error($link);
		}
		echo "</br>";
		$exec_time = $end_time - $start_time;
		echo "Execution Time: $exec_time ms.";
		echo "<hr>";
		echo "</div>";
	}

/* LIVE */
$i = 1;

if ($result == false) {
	echo "<div class=\"odd\">";
	if ($moderation_mode == true) {
		$block = "Yuppe! Queue is empty!";
		$block_msg = "There are currently no new messages awaiting moderation.";
	} else {
		$block = "Requested page does not exist!";
		$block_msg = "The requested article might have been manually removed by the site admin. We apologize for any inconvinience.";
	}
	
	echo "<b><a>Four, oh four!</a></b><br>$block</br><hr>\n";
	echo "<div class=\"quote\">";
	echo "$block_msg";
	echo "</div>";
	echo "<hr>";
	echo "<b>Bash ID</b>: $request_id, <b>Bash Title</b>: $request_title\n";
	echo "</div>";
	echo "<hr>";
}

if ($result != false)
{
	while ( $row = $result->fetch_array(MYSQLI_ASSOC) )
	{
		# DEBUG
		# print_r($row);
	/**
	* Translate the $row array into something more
	*	easy to use.
	*/
		$id 		= $row['id']; // Entry's ID
		$date		= $row['post_date']; // Entry's Post Date
		$author		= $row['author']; // Author of the entry
		$category 	= $row['category']; // Category, i.e: RPG Session, IM
		$source 	= $row['source']; // Source, i.e: Classic Rollage, GG
		$title 		= $row['title']; // Custom Label
		$text		= $row['text']; // Quote
		$explicit	= $row['explicit']; // Whether it contains explicit vocabulary
		$text_enGB	= $row['text']; // Personally translated quote
		$rating		= $row['rating']; // Fixed Rating
		
		if ($rating == "" || $rating == 0) {	$rating = 0; }
		
		// Translate MySQL date to our own, custom date.	
		$date_tmp	= strtotime($date);
		$date = date("D H:i, d M Y", $date_tmp);
		
		// Check whether the translation was requested.
		$translate = false;
		if (array_key_exists('translate',$_GET)) { $translate = true; }
		if ($translate == true)
		{
			if ($text_enGB != "") {
				$text	= $text_enGB;
			} else {
				$text = translate($text, "en", "pl");
			}
		}
		
		/**
		// This part of the code translates "<" and ">" into
		// 	corresponding HTML entities. It is crucial that they all get
		// 	translated, or else they will be hidden.
		// Remember, the whole act of str_replace() disallows HTML formatting.
		//  We don't want the Bash entries to contain malicious code, after all.
		**/
			$text = str_replace("<","&lt;", $text);
			$text = str_replace(">","&gt;", $text);
			
			// Format each new line to a seperate paragraph entity.
			$text = "<p id=\"quote\">" . $text;
			$text = str_replace("\n","</p>\n<p id=\"quote\">",$text);
			// Remove all carriage returns, to remove double-break between lines.
			$text = str_replace("\r",NULL,$text);
			
			// Transcribe most basic BBCode into equivalent HTML code.
			$bb_code	= array("[b]","[/b]","[u]","[/u]","[i]","[/i]","[center]","[/center]");
			$bb_html	= array("<b>","</b>","<u>","</u>","<i>","</i>","<center>","</center>");
			$text = str_replace($bb_code,$bb_html,$text);
			
			// Automatic "Paragraph Seperator", as I call it.
			$text = str_replace("***","<center><b>***</b></center>",$text);
			
			
		// Fill default values if no data is provided.
		if ($category == "") { $category = "Miscellaneous"; }
		if ($source == "") { $source = "Unknown"; }
		
		// Check wheter the entry is Odd or Even.
		if ($i % 2) {
			$odd = "odd";
			} else {
			$odd = "even";
			}
		
			// Create a link to the post based on its title,
			//	or make title up if it has none.
			$title_url = "title/" . urlencode($title) . "/";
			if ($title == "") {
				$display_title = "Bash #$id";
			} else {
				$display_title = "<a class=\"$odd\" href=\"$title_url\">$title</a>\n";
			}	


		echo "<div class=\"$odd\">\n";

		/* Header */
			// Translation || Moderation Box
			echo "<div style=\"float: right; text-align: right;\"><small>\n";
			
			// Translation Par
			if ($translate == true) {
					echo "<a href=\"?id=$id\">Read in Original Language</a>\n";
				} else {
					if ($text_enGB != "") {
						$translation_availability = "Translation Available - Read Now";
					} else {
						$translation_availability = "Translate using Google Translate";
					}
					echo "<a href=\"?id=$id&translate=true\">$translation_availability</a>\n";
				}
			echo "</small></div>\n";
			
			// Display the information: Bash ID, Post Date and Author.
			echo "<a name=\"$id\"></a><b class=\$odd\">Bash</b> <a class=\"$odd\" href=\"$id/\">#$id</a>,&nbsp;\n";
			echo "<b>Posted</b>: <a class=\"$odd\">$date</a>,&nbsp;\n";
			echo "<b>Author</b>:  <a class=\"$odd\">$author</a>\n";
			echo "</br>\n";

		/* Sub-Header */		
			// Display the information: Bash Title, Category and Source (or tags)
			$tmp = urlencode ( $category ); $tmp2 = urlencode( $source );
			echo "<small><b>Title</b>: $display_title, <b>Category</b>: <a class=\"$odd\" href=\"?category=$tmp\">$category</a>, <b>Source</b>: <a class=\"$odd\" href=\"?source=$tmp2\">$source</a></small>\n";
			echo "<hr>\n";

		/* Bash Entry */
		// Determine, what to display.
			echo "<div class=\"quote\" id=\"$id\">";
			
			// Stage 1 - Determine whether it is a single link (images only, for now), or not
			$preg_jpg  = "/^(.*).jpg$/";
			$preg_png = "/^(.*).png$/";
			$preg_gif = "/^(.*).gif$/";
			$preg_jpeg = "/^(.*).jpeg$/";
			
			$preg = false;
			$preg = (preg_match($preg_jpg,$text) || preg_match($preg_png,$text) || preg_match($preg_gif,$text) || preg_match($preg_jpeg,$text));
			
			if (preg_match($preg_jpg,$text) || preg_match($preg_png,$text) || preg_match($preg_gif,$text) || preg_match($preg_jpeg,$text) )
			{
				// If the check matches the link format, insert <img> tag.
				$text = "<img src=\" " . $text . "\"></img>";
			}   // Otherwise, do nothing.
				
			$is_url = false;
			// Stage 2 - Translate text if requested. Use Google Translate services
				// DO NOT translate if the entry is marked as URL.
				// Info - the code below does not alter the text itself, but merely inserts a note about the translation.
				if ($is_url != true) {
					if ($translate == true) {
						if ($text_enGB != "") {
							echo "<span style=\"color: lime;\">This is personally translated quote. Enjoy accurate translation!</span></br>\n";
						} else {
							echo "<span style=\"color: red;\">This is translation provided by Google Translate. It <b>might not</b> be accurate.</br></span>\n";
						}
					}
				}
				
			// Stage 3 - Check Explicity Mark. If marked as explicit, hide it and post link to it. Else, display the text.
			/* DISABLED
				if ( ($explicit == true) and ( ($request_id == "") and ($request_title == "") ) ) {
					echo "<span style=\"color: red;\">This quote is marked to have explicit or vulgar content. Click <a href=\"$id/\">here</a> to view it.</span>\n";
				} else {
				// Hooray, it's not explicit, thus display it
						echo $text;
				}
			*/
			
			// Stage 4 - Display the entry.
			echo $text;
			
			echo "</div>\n";

		/* Footer */
			echo "<hr>\n";
			// Display Moderation box
			echo "<div style=\"float: right; text-align: right;\">";
				// Moderation Box
				if ($acc_level >= 1)
				{
					// In Moderation Mode. Display Moderation Options.
					if ($use_table == ($db_table . "_queue") )
					{
						// We're browsing the Queued entries. Allow "Approve" and "Remove" options.
						echo "<small><a href=\"moderation.php?id=$id&pool=$use_table&action=approve\">Approve Post</a> :: <a href=\"moderation.php?id=$id&pool=$use_table&action=remove\">Delete Post</a></small>";
					} else {
						// We're browsing live entries. Allow "Remove" option only.
						echo "<small><a href=\"moderation.php?id=$id&pool=$use_table&action=remove\">Remove Post</a></small>";
					}
				}
			echo "</div>";
			
			// Retrieve Comments Count for the Post
			$comments_table = $db_table . "_comments";
			$comments_query = "SELECT * FROM `$comments_table` WHERE `bash_entry_id` = $id";
			$comments_result = $link->query($comments_query); #mysql_query($comments_query);
			$comments_num = $comments_result->num_rows; #mysql_num_rows($comments_result);
			
			// Fetch Post Ratings!
			$table_votes = $db_table . "_votes";
			$tmp_query = "SELECT `value` FROM `$table_votes` WHERE `id` = $id";
			$votes_result = $link->query($tmp_query); #mysql_query($tmp_query);
			$vote = 0;	// Default current post's vote rating to 0;
			
			if ($votes_result != false) {
				while ( $votes = $votes_result->fetch_array() ) { #mysql_fetch_array($votes_result, MYSQL_NUM) ) {
					$value = $votes[0];
					$vote = $vote + $value;
				}
			}

			
			// Calculate Average
			if ($votes_result->num_rows > 0 ) {
				$rating = $vote / $votes_result->num_rows;
			}
			$rating = number_format($rating,2);
			
			// Display the information: Rating and Comments Count
			if ($rating > 0 ) {
				$rv = "#00ff00";
				$rs = "+";
				} else
			if ($rating < 0 ) {
				$rv = "red";
				$rs = "-";
				} else
			if ($rating == 0) {
				$rv = "#e0e2e4";
				$rs = "";
				}
				
			$rating_text = "<span style=\"color: $rv;\">$rs$rating</span>";
			
			// VOTING MENU
			// We do not want guests to vote.
			if ($acc_level >= ACC_NORMAL) {
				// We are authorized to vote. Proceed.
				
				// Check whether user voted already or not.
				$table_votes = $db_table . "_votes";
				$tmp_query = "SELECT * FROM `$table_votes` WHERE `uname` = '$uname' AND `id` = $id";
				$votes_result = $link->query($tmp_query);
				
				if ($votes_result->num_rows > 0) {
					// We have already voted.
					$vm = "(Thank you for voting!)";
				} else {
					// We have not yet voted.
					$vm = "(<b><a href=\"?vote=1&id=$id\">Vote +</a></b>|<b><a href=\"?vote=-1&id=$id\">Vote &ndash;</a></b>)";
				}
				
			} else {
				// We are not authorized to vote due to accoutn level.
				$vm = "";
			}
			
			// Information Line
			echo "<small><b class=\$odd\">Rating</b>: $rating_text $vm. <b>Comments</b>: $comments_num (<a href=\"$id/\">&raquo; see &laquo;</a>).</small>\n";
			

		/* Close Entry */
		echo "</div>\n";
		
		echo "<hr>\n";
		
		/** COMMENTS SYSTEM **/
		
		// If we have specified Entry's ID or Title, we want to see its comments too. Render them!
		if  ( $request_id != "" || $request_title ) {
			include "comments.php";
		}
			
	$i++;
	}
}


// And print available pages again.
print_page_links($pages_num);


?>