<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="fi">
<head>
	<meta charset="utf-8">
	<title>Ylläpito — Lisää käyttäjä</title>
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

	<h2>Lisää käyttäjä</h2>

	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//very basic validation
		if($username ==''){
			$error[] = 'Ole hyvä, kirjoita käyttäjänimi.';
		}

		if($password ==''){
			$error[] = 'Ole hyvä, täytä salasana.';
		}

		if($passwordConfirm ==''){
			$error[] = 'Ole hyvä, vahvista salasana.';
		}

		if($password != $passwordConfirm){
			$error[] = 'Salasanat eivät täsmää.';
		}

		if($email ==''){
			$error[] = 'Ole hyvä, kirjoita kunnollinen sähköposti.';
		}

		if(!isset($error)){

			$hashedpassword = $user->password_hash($password, PASSWORD_BCRYPT);

			try {

				//insert into database
				$stmt = $db->prepare('INSERT INTO members (username,password,email) VALUES (:username, :password, :email)') ;
				$stmt->execute(array(
					':username' => $username,
					':password' => $hashedpassword,
					':email' => $email
				));

				//redirect to index page
				header('Location: users.php?action=added');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>

	<form action='' method='post'>

		<p><label>Käyttäjänimi</label><br />
		<input type='text' name='username' value='<?php if(isset($error)){ echo $_POST['username'];}?>'></p>

		<p><label>Salasana</label><br />
		<input type='password' name='password' value='<?php if(isset($error)){ echo $_POST['password'];}?>'></p>

		<p><label>Vahvista salasana</label><br />
		<input type='password' name='passwordConfirm' value='<?php if(isset($error)){ echo $_POST['passwordConfirm'];}?>'></p>

		<p><label>Sähköposti</label><br />
		<input type='text' name='email' value='<?php if(isset($error)){ echo $_POST['email'];}?>'></p>
		
		<p><input type='submit' name='submit' class='button' value='Lisää käyttäjä'></p>

	</form>

	</main>

	</div>
	</div>
</body>

</html>