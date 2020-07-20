<?php
//include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
	header('Location: login.php');
}

//show message from add / edit page
if (isset($_GET['delpost'])) {

	$stmt = $db->prepare('DELETE FROM posts WHERE postID = :postID');
	$stmt->execute(array(':postID' => $_GET['delpost']));

	header('Location: index.php?action=deleted');
	exit;
}
?>
<!doctype html>
<html lang="fi">

<head>
	<meta charset="utf-8">
	<title>Ylläpito</title>
	<?php require('includes/head-meta.php'); ?>

	<script language="JavaScript" type="text/javascript">
		function delpost(id, title) {
			if (confirm("Oletko varma, että haluat poistaa arvostelun '" + title + "'")) {
				window.location.href = 'index.php?delpost=' + id;
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
				if (isset($_GET['action'])) {
					echo '<h3 class="message">Arvostelu ' . $_GET['action'] . '</h3>';
				}
				?>

				<div id="page-info">
					<h1>Ylläpito</h1>
					<p>Tervetuloa Arvostelutietokantainternetsivuston ylläpitoon. Tänään on <?php echo date("d.m.Y"); ?>.</p>
					<p>Matkasi miellyttävyydestä vastaavat korkeasti koulutetut avaruuspapukaijat rakensivat tämän sivun ajassa

						<?php
						$time = microtime();
						$time = explode(' ', $time);
						$time = $time[1] + $time[0];
						$finish = $time;
						$total_time = round(($finish - $start), 4);
						echo $total_time;
						?>.</p>

					<?php
					//The COUNT SQL statement that we will use.
					$sql = "SELECT COUNT(*) AS num FROM posts WHERE postStatus=0";
					//Prepare the COUNT SQL statement.
					$stmt = $db->prepare($sql);
					//Execute the COUNT statement.
					$stmt->execute();
					//Fetch the row that MySQL returned.
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					if ($stmt->rowCount() == 0) {
						echo "Ei julkaisua odottavia arvosteluja.";
					} else {
						//The $row array will contain "num". Print it out.
						echo 'Julkaisua odottavien arvosteluja on ' . $row['num'] . 'kpl.';
					}
					?></p>


				</div>
				<!-- -->



				<h2>Julkaisua odottavat arvostelut</h2>
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

						$stmt = $db->query('SELECT postID, postTitle, postDate, postStatus, postAuthor FROM posts WHERE postStatus=0 ORDER BY postID DESC');

						while ($row = $stmt->fetch()) {
							echo '<tr>';
							echo '<td>' . $row['postTitle'] . '</td>';
							echo '<td class="center">' . date('d/m/Y', strtotime($row['postDate'])) . '</td>'

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


				<hr>


				<h2>Julkaistut arvostelut</h2>
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

						$stmt = $db->query('SELECT postID, postTitle, postDate, postStatus, postAuthor FROM posts WHERE postStatus=1 ORDER BY postID DESC');
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
		</main>

	</div>
	</div>





</body>

</html>