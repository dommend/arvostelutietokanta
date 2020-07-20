<?php require('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="fi">
<?php
			try {
//collect month and year data
				$month = $_GET['month'];
				$year = $_GET['year'];

				//set from and to dates
				$from = date('Y-m-01 00:00:00', strtotime("$year-$month"));
				$to = date('Y-m-31 23:59:59', strtotime("$year-$month"));

				$stmt = $db->prepare('SELECT postID, postTitle, postSlug, postDesc, postDate, postRate, postStatus FROM posts WHERE postStatus=1 and postDate >= :from AND postDate <= :to ORDER BY postID DESC');
				$stmt->execute(array(
					':from' => $from,
					':to' => $to
				)); }  catch (PDOException $e) {
					echo $e->getMessage();
				}

?>
<head>
	<meta charset="utf-8">
	<title>Arvostelut — Arvostelut ajalta <?php echo $month; ?>/<?php echo $year; ?></title>
	<?php require('includes/head-meta.php'); ?>
</head>

<body>
	<?php require('includes/header-nav.php'); ?>
	<div id="wrapper" class="innercontainer flex">
		<main>
			<div id="page-info" class="innermargin">
				<h2>Arvostelut ajalta <?php echo $month; ?>/<?php echo $year; ?></h2>
				<p class="back-to"><a href="./">
					<i class="material-icons">arrow_back_ios</i> Takaisin etusivulle</a></p>
			</div>

			
			<?php
			try {
				     if ($stmt->rowCount() == 0) {

                    echo "<span class='error innermargin'>Ei arvosteluja. Kuinka mälsää.</span>";
                }
				while ($row = $stmt->fetch()) {
				
					echo '<article class="review">';
					echo '<div class="head flex">';
					echo '<div class="head-title"><h3><a href="' . $row['postSlug'] . '">' . $row['postTitle'] . '</a></h3></div>';
					echo '<div class="rating rate-' . str_replace(".", "", $row['postRate']) . '"><h4>' . $row['postRate'] . ' / 5</h4></div>';
					echo '</div>';
					echo '<div class="meta flex">';
					echo '<div class="meta-column">';

					echo '<span class="time"><span>Arvostelu kirjoitettu</span> ' . date('d.m.Y @ h:i', strtotime($row['postDate'])) . '';
					echo '</span></div>';
					$stmt2 = $db->prepare('SELECT catTitle, catSlug FROM cats, cat_cats WHERE cats.catID = cat_cats.catID AND cat_cats.postID = :postID');
					$stmt2->execute(array(':postID' => $row['postID']));

					$catRow = $stmt2->fetchAll(PDO::FETCH_ASSOC);
					$links = array();
					echo '<div class="meta-column categories">';
					foreach ($catRow as $cat) {

						$links[] = "<a href='c-" . $cat['catSlug'] . "'>" . $cat['catTitle'] . "</a>";
					}
					echo implode(", ", $links);
					echo '</div>';


					echo '</div>';
					echo '<div class="contentmargin">';
					echo '<div class="description">' . $row['postDesc'] . '</div>';
					echo '<div class="open-article"><a href="' . $row['postSlug'] . '">Lue koko arvostelu</a></div>';
					echo '</div>';
					echo '</article>';
				}		
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
			?>
		</main>
		<?php require('includes/sidebar.php'); ?>
	</div>
	<?php require('includes/footer.php'); ?>

</body>

</html>