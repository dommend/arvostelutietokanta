<aside>

	<?php
	// function to get the current page name
	function PageName()
	{
		return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
	}

	$current_page = PageName(); ?>

	<ul id="adminmenu">
		<li><a href="../" target="_blank" class="viewpage"><span> <i class="material-icons">arrow_back</i>Vieraile sivulla</span></a></li>
		<li><a class="<?php echo $current_page == 'index.php' ? 'active' : NULL ?>" href="index.php"><span> <i class="material-icons">library_books</i> Dashboard</a></span></li>
		<li><a class="<?php echo $current_page == 'all-reviews.php' ? 'active' : NULL ?>" href="all-reviews.php"><span> <i class="material-icons">library_books</i> Kaikki arvostelut</a></span></li>
		<li class="break"><a class="<?php echo $current_page == 'add-review.php' ? 'active' : NULL ?>" href="add-review.php"><span> <i class="material-icons">post_add</i> Lisää arvostelu</a></span></li>
		<li><a class="<?php echo $current_page == 'categories.php' ? 'active' : NULL ?>" href="categories.php"><span><i class="material-icons">format_list_bulleted</i> Kategoriat</a></span></li>
		<li class="break"><a class="<?php echo $current_page == 'add-category.php' ? 'active' : NULL ?>" href="add-category.php"><span><i class="material-icons">playlist_add</i>Lisää kategoria</span></a></li>
		<li><a class="<?php echo $current_page == 'users.php' ? 'active' : NULL ?>" href="users.php"><span><i class="material-icons">people_alt</i> Käyttäjät</a></span></li>
		<li><a class="<?php echo $current_page == 'add-user.php' ? 'active' : NULL ?>" href="add-user.php"><span> <i class="material-icons">person_add</i> Lisää käyttäjä</a></span></li>
		<li><a href="logout.php" class="logout"><span> <i class="material-icons">exit_to_app</i> Kirjaudu ulos</span></a></li>
	</ul>
</aside>