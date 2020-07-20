<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
	header('Location: login.php');
}
?>
<!doctype html>
<html lang="fi">

<head>
	<meta charset="utf-8">
	<title>Ylläpito — Muokkaa arvostelua</title>
	<?php require('includes/head-meta.php'); ?>


	<script>
		tinymce.init({
			selector: "textarea",
			plugins: [
				"advlist autolink lists link image charmap print preview anchor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste"
			],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
		});
	</script>
</head>

<body>

	<div id="wrapper" class="flex">

		<?php include('includes/menu.php'); ?>


		<main>
			<div class="innermargin">
				<p><a class="back-to" href="./">
						<i class="material-icons">
							arrow_back
						</i>
						Siirry hallintapaneeliin</a></p>

				<h2>Muokkaa arvostelua</h2>
				<p>Pakolliset kentät on merkattu tähdellä (<span class="required">*</span>).


					<?php

					//if form has been submitted process it
					if (isset($_POST['submit'])) {

						// $_POST = array_map( 'stripslashes', $_POST );

						//collect form data
						extract($_POST);

						//very basic validation
						if ($postID == '') {
							$error[] = 'Jotain on pielessä. Arvostelusta puuttuu ID.';
						}

						//very basic validation
						if ($postTitle == '') {
							$error[] = 'Ole hyvä ja kirjoita otsikko.';
						}

						if ($postDesc == '') {
							$error[] = 'Ole hyvä ja kirjoita kuvaus';
						}

						if ($postCont == '') {
							$error[] = 'Ole hyvä ja kirjoita arvostelu.';
						}

						if (!isset($error)) {
							try {

								$stmt = $db->query('SELECT postDate FROM posts');
								while ($row = $stmt->fetch()) {

									list($date, $time) = explode(" ", $row['postDate']);
									list($year, $month, $day) = explode("-", $date);

								$postSlug = slug($year . $month . $day . '-' . $postTitle);

								}

								//insert into database
								$stmt = $db->prepare('UPDATE posts SET postTitle = :postTitle, postSlug = :postSlug, postRate = :postRate, postDesc = :postDesc, postCont = :postCont, postTags = :postTags, postStatus = :postStatus, postAuthor = :postAuthor WHERE postID = :postID');

								

								$stmt->execute(array(
									':postTitle' => $postTitle,
									':postSlug' => $postSlug,
									':postRate' => $postRate,
									':postDesc' => $postDesc,
									':postCont' => $postCont,
									':postID' => $postID,
									':postTags' => $postTags,
									':postStatus' => $postStatus,
									':postAuthor' => $postAuthor
								
								));
								//delete all items with the current postID
								$stmt = $db->prepare('DELETE FROM cat_cats WHERE postID = :postID');
								$stmt->execute(array(':postID' => $postID));

								if (is_array($catID)) {
									foreach ($_POST['catID'] as $catID) {
										$stmt = $db->prepare('INSERT INTO cat_cats (postID,catID)VALUES(:postID,:catID)');
										$stmt->execute(array(
											':postID' => $postID,
											':catID' => $catID
										));
									}
								}
								//redirect to index page
								header('Location: index.php?action=päivitetty');
								exit;
							} catch (PDOException $e) {
								echo $e->getMessage();
							}
						}
					}

					?>


					<?php
					//check for any errors
					if (isset($error)) {
						foreach ($error as $error) {
							echo $error . '<br />';
						}
					}

					try {

						$stmt = $db->prepare('SELECT postID, postTitle, postRate, postDesc, postCont, postTags, postStatus, postSlug, postAuthor FROM posts WHERE postID = :postID');
						$stmt->execute(array(':postID' => $_GET['id']));
						$row = $stmt->fetch();
					} catch (PDOException $e) {
						echo $e->getMessage();
					}

					?>

					<form action='' method='post'>
						<div id="review-field" class="flex">
							<input type='hidden' name='postAuthor' value='admin'>
							<input type='hidden' name='postID' value='<?php echo $row['postID']; ?>'>
							<div class="column main">
								<div class="row">
									<h3><label>Otsikko <span class="required">*</span></label></h3>
									<input type='text' name='postTitle' class="post-title-field" value='<?php echo $row['postTitle']; ?>'>
								</div>
								<div class="row">
									<h3><label>Kuvaus <span class="required">*</span></label></h3>
									<p>Anna lyhyt kuvaus arvostelusta. Esitetään etusivunäkymässä.</p>
									<textarea name='postDesc' class="description-area" cols='60' rows='5'><?php echo $row['postDesc']; ?></textarea>
								</div>
								<div class="row">
									<h3><label>Arvostelu <span class="required">*</span></label></h3>
									<p>Kirjoita itse arvostelu.</p>
									<textarea name='postCont' class="review-area" cols='60' rows='20'><?php echo $row['postCont']; ?></textarea></p>
			</div>
	</div>

	<div class="column sidebar">
		<div class="row">
			<p><input type='submit' name='submit' class="button" value='Päivitä'></p>

			<h3>Tila</h3>

			<p>Arvostelu on <?php if ($row['postStatus'] == 1) { 
				echo 'julkaistu. ';
				echo '<code><a href="http://'.$_SERVER['SERVER_NAME']; ?>/arvostelu/<?php echo $row['postSlug'].'">' . $_SERVER['SERVER_NAME']; ?>/arvostelu/<?php echo $row['postSlug'] . '</a></code>';  
			} 
				else {
					echo 'luonnos.';
				} ?></p>


			<select name='postStatus' id="status">
			<option value="<?php echo $row['postStatus']; ?>" selected>
			<?php if ($row['postStatus'] == 1) { 
				echo 'Julkaistu'; } 
				else {
					echo 'Luonnos';
				} ?>
			</option>
			<?php if ($row['postStatus'] == 1) { 
				echo '<option value="0">Luonnos</option>';
			}  else {
				echo '<option value="1">Julkaistu</option>'; 
			} ?>
			</select>

			<h3><label>Arvosana (0-5)</label></h3>
			<p>Nykyinen arvosana on <?php echo $row['postRate']; ?>. Arvosanselitys löytyy <a href="http://dev.zurial.fi/arvostelu/info.php">info-sivulta.</a></p>
			
			
			<select name='postRate' id="rating">
				<option value="<?php echo $row['postRate']; ?>" selected><?php echo $row['postRate']; ?></option>
				<option value="0">0</option>
				<option value="0.5">0.5</option>
				<option value="1">1</option>
				<option value="1.5">1.5</option>
				<option value="2">2</option>
				<option value="2.5">2.5</option>
				<option value="3">3</option>
				<option value="3.5">3.5</option>
				<option value="4">4</option>
				<option value="4.5">4.5</option>
				<option value="5">5</option>
			</select>

			<?php if (isset($error)) {
				echo $_POST['postRate'];
			} ?>


			<div class="row">

				<h3>
					Kategoriat
				</h3>
				<p><a href="categories.php">Muokkaa kategorioita</a> /
					<a href="add-category.php">Lisää uusi kategoria</a></p>
				<fieldset>
					<?php
					$checked = null;

					$stmt2 = $db->query('SELECT catID, catTitle FROM cats ORDER BY catTitle');
					while ($row2 = $stmt2->fetch()) {

						$stmt3 = $db->prepare('SELECT catID FROM cat_cats WHERE catID = :catID AND postID = :postID');
						$stmt3->execute(array(':catID' => $row2['catID'], ':postID' => $row['postID']));
						$row3 = $stmt3->fetch();

						if ($row3['catID'] == $row2['catID']) {
							$checked = 'checked=checked';
						} else {
							$checked = null;
						}

						echo "<input type='checkbox' name='catID[]' value='" . $row2['catID'] . "' $checked> " . $row2['catTitle'] . "<br />";
					}

					?>

				</fieldset>
				<div class="row">
					<h3><label>Avainsanat</label></h3>
					<p>Erota avainsanat pilkulla ilman välilyöntejä esim. (arvostelu,musiikki,kappale).</p>
					<input type='text' name='postTags' class="post-tag-field" value='<?php echo $row['postTags']; ?>' style="width:400px;">


				</div>
				</form>
				</main>

			</div>
		</div>
</body>

</html>