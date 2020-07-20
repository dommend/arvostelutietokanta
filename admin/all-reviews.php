<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }

//show message from add / edit page
if(isset($_GET['delcat'])){ 

    $stmt = $db->prepare('DELETE FROM cats WHERE catID = :catID') ;
    $stmt->execute(array(':catID' => $_GET['delcat']));

    header('Location: categories.php?action=deleted');
    exit;
} 

?>
<!doctype html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <title>Ylläpito — Kaikki arvostelut</title>
  <?php require('includes/head-meta.php'); ?>

  <script language="JavaScript" type="text/javascript">
  function delcat(id, title)
  {
      if (confirm("Oletko varma, että haluat poistaa '" + title + "'"))
      {
          window.location.href = 'categories.php?delcat=' + id;
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
        echo '<h3>Kategoria '.$_GET['action'].'.</h3>'; 
    } 
    ?>
 
        <h2>Kaikki arvostelut</h2>
				<table id="arvostelut">
					<thead>
						<tr>
							<th>Otsikko</th>
							<th>Päiväys</th>
							<th>Tila</th>
							<th>Kirjoittaja</th>
							<th>Toiminto</th>
						</tr>
					</thead>
					<?php
					try {

						$stmt = $db->query('SELECT postID, postTitle, postDate, postStatus,postAuthor FROM posts ORDER BY postID DESC');
						while ($row = $stmt->fetch()) {
							echo '<tr>';
							echo '<td>' . $row['postTitle'] . '</td>';
							echo '<td class="center">' . date('d/m/Y', strtotime($row['postDate'])) . '</td>';
					?>

							<td class="center status-<?php echo $row['postStatus']; ?>">
							<?php if ($row['postStatus'] == 1) {
								echo 'Julkaistu';
							} else {
								echo 'Luonnos';
							} ?>
							</td>
							<td class="center">
									<?php echo $row['postAuthor']; ?>
							</td>
							<td class="center">
								<a href="edit-review.php?id=<?php echo $row['postID']; ?>">Muokkaa</a> |
								<a href="javascript:delpost('<?php echo $row['postID']; ?>','<?php echo $row['postTitle']; ?>')">Poista</a>
							</td>

					<?php
							echo '</tr>';
						}
					} catch (PDOException $e) {
						echo $e->getMessage();
					}

					?>
				</table>
				<p><a href="add-review.php" class="button">Lisää arvostelu</a></p>
				<p><small><strong>Uptime:</strong> <?php system("uptime"); ?></small></p>

</div>
</main>

</div>
</div>
</body>

</html>