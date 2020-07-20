<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }

//show message from add / edit page
if(isset($_GET['deluser'])){ 

	//if user id is 1 ignore
	if($_GET['deluser'] !='1'){

		$stmt = $db->prepare('DELETE FROM members WHERE memberID = :memberID') ;
		$stmt->execute(array(':memberID' => $_GET['deluser']));

		header('Location: users.php?action=deleted');
		exit;

	}
} 

?>
<!doctype html>
<html lang="fi">
<head>
	<meta charset="utf-8">
	<title>Ylläpito — Käyttäjät</title>
	<?php require('includes/head-meta.php'); ?>


  <script language="JavaScript" type="text/javascript">
  function deluser(id, title)
  {
	  if (confirm("Oletko varma, että haluatko poistaa '" + title + "'"))
	  {
	  	window.location.href = 'users.php?deluser=' + id;
	  }
  }
  </script>
</head>
<body>

<div id="wrapper" class="flex">

<?php include('includes/menu.php'); ?>
<main>
	<div class="innermargin">
	<?php 
	//show message from add / edit page
	if(isset($_GET['action'])){ 
		echo '<h3>User '.$_GET['action'].'.</h3>'; 
	} 
	?>

	<table>
		<thead>
	<tr>
		<th>Käyttänäjänimi</th>
		<th>Sähköposti</th>
		<th>Toiminto</th>
	</tr>
		</thead>
	<?php
		try {

			$stmt = $db->query('SELECT memberID, username, email FROM members ORDER BY username');
			while($row = $stmt->fetch()){
				
				echo '<tr>';
				echo '<td class="center">'.$row['username'].'</td>';
				echo '<td class="center">'.$row['email'].'</td>';
				?>

				<td class="center">
					<a href="edit-user.php?id=<?php echo $row['memberID'];?>">Muokkaa</a> 
					<?php if($row['memberID'] != 1){?>
						| <a href="javascript:deluser('<?php echo $row['memberID'];?>','<?php echo $row['username'];?>')">Poista</a>
					<?php } ?>
				</td>
				
				<?php 
				echo '</tr>';

			}

		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
	?>
	</table>

	<p><a href='add-user.php' class='button'>Lisää käyttäjä</a></p>

	</div>
	</div>
</body>

</html>
