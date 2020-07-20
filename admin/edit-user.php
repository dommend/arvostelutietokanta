<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Ylläpito — Muokkaa käyttäjää</title>
  <?php require('includes/head-meta.php'); ?>
</head>
<body>

<div id="wrapper" class="flex">

<?php include('includes/menu.php'); ?>

<main>
	<div class="innermargin">

	<p><a class="back-to" href="users.php">
						<i class="material-icons">
							arrow_back
						</i>
						Siirry käyttäjähallintaan</a></p>

	<h2>Muokkaa käyttäjää</h2>


	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//very basic validation
		if($username ==''){
			$error[] = 'Ole hyvä, täytä käyttäjänimi.';
		}

		if( strlen($password) > 0){

			if($password ==''){
				$error[] = 'Ole hyvä, syötä salasana.';
			}

			if($passwordConfirm ==''){
				$error[] = 'Ole hyvä, vahvista salasana.';
			}

			if($password != $passwordConfirm){
				$error[] = 'Salasanat eivät täsmää.';
			}

		}
		

		if($email ==''){
			$error[] = 'Ole hyvä, kirjoita kunnollinen sähköposti.';
		}

		if(!isset($error)){

			try {

				if(isset($password)){

					$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);

					//update into database
					$stmt = $db->prepare('UPDATE members SET username = :username, password = :password, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':password' => $hashedpassword,
						':email' => $email,
						':memberID' => $memberID
					));


				} else {

					//update database
					$stmt = $db->prepare('UPDATE members SET username = :username, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':email' => $email,
						':memberID' => $memberID
					));

				}
				

				//redirect to index page
				header('Location: users.php?action=updated');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}

	?>


	<?php
	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo $error.'<br />';
		}
	}

		try {

			$stmt = $db->prepare('SELECT memberID, username, email FROM members WHERE memberID = :memberID') ;
			$stmt->execute(array(':memberID' => $_GET['id']));
			$row = $stmt->fetch(); 

		} catch(PDOException $e) {
		    echo $e->getMessage();
		}

	?>

	<form action='' method='post'>
		<input type='hidden' name='memberID' value='<?php echo $row['memberID'];?>'>

		<p><label>Käyttäjätunnus</label><br />
		<input type='text' name='username' value='<?php echo $row['username'];?>'></p>

		<p><label>Salasana (jos haluat vaihtaa salasanan täytä tämä)</label><br />
		<input type='password' name='password' value=''></p>

		<p><label>Vahvista salasana</label><br />
		<input type='password' name='passwordConfirm' value=''></p>

		<p><label>Sähköposti</label><br />
		<input type='text' name='email' value='<?php echo $row['email'];?>'></p>

		<p><input type='submit' class='button' name='submit' value='Päivitä käyttäjän tiedot'></p>

		</div>
	</div>
</body>

</html>