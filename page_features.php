<?
	function post_change($date,$t,$change)
	{
		$d_tmp	= strtotime($date);
		$d_date = date("D, d M Y",$d_tmp);
		
		$d_tmp  = strtotime($t);
		$d_time = date("H:i",$d_tmp);
		
		echo "<li><a>[$d_date] ($d_time)</a> $change </li>";
	}
	
	function line()
	{
		echo "<hr>";
	}
?>
<div id="subheader"><b>Features and Changelog</b></div>
<div id="notes">
	<div id="textarea">
		<b>Things that yet remain to be implemented</b>:</br>
		<small>... but will most likely be put aside anyway...</small></br>
		<ul>
			<li>Entry Ratings,</li>
			<li>RSS Feed.</li>
		</ul>
		<hr>
		<ul>
			<?
				//--2011-10-04
				post_change("2011-10-04","13:48","<a style='color: red'>Known Bug</a>: Google Translate module seems to have broken.");
				post_change("2011-10-04","13:43","<a>Improved</a> signup.php script. Now, upon registration, an IP address is collected.");
				
				//--2011-09-13
				post_change("2011-09-13","11:48","<a>Fixed</a> signup.php script. It now properly enters user's registration time.");
				
				line();
				//--2011-06-04
				post_change("2011-06-04","03:26","<a>Improved</a> display of posts. Lines now use hanging text style, to better seperate individual lines.");
				post_change("2011-06-04","02:00","<a>New!</a> Finally implemented simple Post Rating system.");
				post_change("2011-06-04","02:00","<a>Improved</a> account permissions.");
				
				line();
				//-- 2011-05-31
				post_change("2011-05-31","15:05","<a>Fix!</a> \"Author\" and \"Content\" field cannot be empty when posting a new comment.");
				
				line();
				//-- 2011-05-28
				post_change("2011-05-28","15:26","<a>New!</a> Added post counter for each of major categories.");
				post_change("2011-05-28","13:20","<a>Improved</a> authentication system and implemented Moderation functions for moderators.");
				post_change("2011-05-28","13:20","<a>New!</a> Implemented hard-coded categories and ability to filter displayed entries by category and source.");
			
				line();
				//-- 2011-05-27
				post_change("2011-05-27","23:26","<a>Hot!</a> Added simple authentication system. Registered user can now submit new bash entries directly into database, skipping the moderation queue. Also, IP address of registered users is not displayed in comments.");
				
				line();
				//-- 2011-05-25
				post_change("2011-05-25","16:20","<a>Hot!</a> Added support for most basic BBCode tags! You can now format the bash entry with B, U and I tags.");
				post_change("2011-05-25","13:37","<a>Hot!</a> Added support for translation by hand, in addition to using Google Translate!");
				
				line();
				//-- 2011-05-24
				post_change("2011-05-24","21:55","Added support for displaying limited number of entries, and thus added <a>bash pages</a>.");
				
				
				line();
				//-- 2011-05-23
				post_change("2011-05-23","23:09","Added a New Submission form.");
				post_change("2011-05-23","21:17","Finally, added a form for posting new comments!");
				post_change("2011-05-23","17:05","Added support for displaying other (predefined) pages.");
				
				line();
				//-- 2011-05-22
				post_change("2011-05-22","23:49","Comment support is now implemented.");
				post_change("2011-05-22","22:25","Filtering by ID and Post Title. You can now click the post ID or Title to open that post only, or copy the link to send it to a friend!");
				post_change("2011-05-22","22:00","Explicit Mark implemented. Inapropriate text will be shielded from \"browsing-only\" users, if the post is marked inappropriate for younger audience.");
				post_change("2011-05-22","??:??","Added option to translate posts into English!");
				post_change("2011-05-22","15:00","URL Shot-cuts: http://rollage.lagownia.pl/bash/<a>$ID</a>/ and http://rollage.lagownia.pl/bash/<a>$TITLE</a>/ .");
				
				line();
				//-- LOG
				post_change("2011-05-22","03:00","First working version of RollBash gets uploaded to web!");
				post_change("2011-05-22","00:00","Work on RollBash project begins.");
			?>
			<!--
			<li><a>New!</a> <a>[2011-05-24] (21:55)</a> Added support for displaying limited entries, and thus added <a>bash pages</a>.
			<hr>
			<li><a>New!</a> <a>[2011-05-23] (23:09)</a> Added a New Submission form.</li>
			<li><a>New!</a> <a>[2011-05-23] (21:17)</a> Finally, added a form for posting new comments!</li>
			<li><a>New!</a> <a>[2011-05-23] (17:05)</a> Added support for displaying specific pages.</li>
			<hr>
			<li><a>New!</a> <a>[2011-05-22] (23:49)</a> Comment support is now implemented. Alas, there is no way to submit them as of yet.</li>
			<li><a>New!</a> <a>[2011-05-22] (22:25)</a> Filtering by ID and Post Title. You can now click the post ID or Title to open that post only, or copy the link to send it to a friend!</li>
			<li><a>New!</a> <a>[2011-05-22] (22:00)</a> Explicit Mark implemented. Inapropriate text will be shielded from "browsing-only" users, if the post is marked inappropriate for younger audience. </li>
			<li><a>Hot!</a> <a>[2011-05-22] (??:??)</a> Translate posts to English! </li>
			<li><a>New!</a> <a>[2011-05-22] (15:00)</a> URL Shot-cuts: http://rollage.lagownia.pl/bash/<a>$ID</a>/ and http://rollage.lagownia.pl/bash/<a>$TITLE</a>/ .</li>
			<hr>
			<li><a>Log!</a> <a>[2011-05-22] (03:00)</a> First working version of RollBash!</li>
			<li><a>Log!</a> <a>[2011-05-22] (00:00)</a> Began work on the project.</li>
			-->
		</ul>
	</div>
</div>