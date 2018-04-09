<div id="subheader"><b>Submit New Bash</b></div>
<div id="content">
	<div class="odd">
	
	<form action="submit.php" method="post">		
		
		<div align="center"> <!-- Table Container -->
			<table>
				<tr>
					<td>
						<small><b>Author</b>:</small></br>
						<?  // If user is logged in, auto-fill Author field.
							if ( isset($_SESSION['uname']) ) {
								$author = $_SESSION['uname'];
								echo "<input type=\"hidden\" name=\"author\" value=\"$author\"><b>$author</b></input>";
							} else
							echo '<input type="text" name="author" style="width: 100%; font-size: smaller;"></input>';
						?>
					</td>
					<td>
						<small><b>Category</b></small></br>
						<select name="category" style="width: 100%; font-size: smaller;" selected="Other">
							<option>RPG</option>
							<option>Chat and IM</option>
							<option>Gaming</option>
							<option>Other</option>
						</select>
					</td>
					<td>
						<small><b>Source</b></small></br>
						<input type="text" name="source"   style="width: 100%; font-size: smaller;"></input>	
					</td>
				</tr>
			</table>
		</div>
		
		<small><b>Title</b>:</small></br>
		<input type="text" name="title"  style="width: 100%;"></input></br>
		
		<hr>
		<small><b>Entry Contents</b></small>
		<div class="quote">
			<textarea name="quote"></textarea>
		</div>
		<small><b>English Translation (Optional. Use only if main text is not English)</b></small>
		<div class="quote">
			<textarea name="quote_engb"></textarea>
		</div>
		<div align=center><button type="submit">Submit Entry</button></div>
		<hr>
		<small><span class="attention"><b>Attention!</b> Once posted, bash <u><b>will not</b></u> immediately appear on the main page. It must first pass trough our moderation queue! Do not try to submit same thing few times in a row.</span></small>
		
	</form>
	</div>
</div>
	
