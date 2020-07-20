<?php
//include config
require_once('../includes/config.php');


//check if already logged in
if( $user->is_logged_in() ){ header('Location: index.php'); } 
?>
<!doctype html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <title>Ylläpitoon kirjautuminen</title>
  <?php require('includes/head-meta.php'); ?>
</head>
<body id="login_page">

<div id="login">

	<?php

	//process login form if submitted
	if(isset($_POST['submit'])){

		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		
		if($user->login($username,$password)){ 

			//logged in return to index page
			header('Location: index.php');
			exit;
		

		} else {
			$message = '<p class="error">Käyttäjätunnus tai salasana oli väärin</p>';
		}

	}//end if submit

	if(isset($message)){ echo $message; }
	?>

	<form action="" method="post">
	<p><label>Käyttäjätunnus</label><input type="text" name="username" value=""  /></p>
	<p><label>Salasana</label><input type="password" name="password" value=""  /></p>
	<p><label></label><input type="submit" name="submit" value="Kirjaudu sisälle"  /></p>
	</form>

</div>
</body>
</html>
