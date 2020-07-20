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
	<title>Ylläpito — Lisää arvostelu</title>
	<?php require('includes/head-meta.php'); ?>
</head>

<body id="addreview">

	<div id="wrapper" class="flex">

		<?php include('includes/menu.php'); ?>

		<main>
			<div class="innermargin">
				<p><a class="back-to" href="./">
						<i class="material-icons">
							arrow_back
						</i>
						Siirry hallintapaneeliin</a></p>

				<h2>Lisää arvostelu</h2>
				<p>Pakolliset kentät on merkattu tähdellä (<span class="required">*</span>).




					<?php

					//if form has been submitted process it
					if (isset($_POST['submit'])) {

						// $_POST = array_map( 'stripslashes', $_POST );

						//collect form data
						extract($_POST);

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
								//insert into database
								$stmt = $db->prepare('INSERT INTO posts (postTitle,postSlug,postDesc,postCont,postDate,postRate,postTags,postStatus,postAuthor) VALUES (:postTitle, :postSlug, :postDesc, :postCont, :postDate, :postRate, :postTags, :postStatus, :postAuthor)');


								$postSlug = slug(date('Ymd'). '-' . $postTitle);

								$stmt->execute(array(
									':postTitle' => $postTitle,
									':postSlug' => $postSlug,
									':postDesc' => $postDesc,
									':postCont' => $postCont,
									':postDate' => date('Y-m-d H:i:s'),
									':postRate' => $postRate,
									':postTags' => $postTags,
									':postStatus' => $postStatus,
									':postAuthor' => $postAuthor
								
								));

						
								 // $postID = $db->lastInsertId();
							



								foreach ($_POST['catID'] as $catID) {
									//add categories
									$stmt = $db->prepare('INSERT INTO cat_cats (postID,catID) VALUES (:postID,:catID)');
									$stmt->execute(array(
										':postID' => $postID,
										':catID' => $catID
									));
								}
								//redirect to index page
								header('Location: index.php?action=lisatty');
								exit;
							} catch (PDOException $e) {
								echo $e->getMessage();
							}
						}
					}

					//check for any errors
					if (isset($error)) {
						foreach ($error as $error) {
							echo '<p class="error">' . $error . '</p>';
						}
					}
					?>
					<form action='' method='post'>
						<input type='hidden' name='postAuthor' value='admin'>



						<div id="review-field" class="flex">
							<div class="column main">
								<div class="row">
									<h3><label>Otsikko <span class="required">*</span></label></h3>
									<input type='text' name='postTitle' class="post-title-field" value='<?php if (isset($error)) {
																											echo $_POST['postTitle'];
																										} ?>'>
								</div>
								<div class="row">
									<h3><label>Kuvaus <span class="required">*</span></label></h3>
									<p>Anna lyhyt kuvaus arvostelusta. Esitetään etusivunäkymässä.</p>
									<textarea name='postDesc' class="description-area" cols='60' rows='5'>
										<?php if (isset($error)) {
											echo $_POST['postDesc'];
										} ?></textarea>
								</div>
								<div class="row">
									<h3><label>Arvostelu <span class="required">*</span></label></h3>
									<p>Kirjoita itse arvostelu.</p>
									<textarea name='postCont' class="review-area" cols='60' rows='20'>
										<?php if (isset($error)) {
											echo $_POST['postCont'];
										} ?></textarea>
								</div>
							</div>
							<div class="column sidebar">

								<div class="row">
									<p><input type='submit' name='submit' class="button" value='Lisää arvostelu'></p>

									<h3>Tila</h3>
									<select name='postStatus' id="status">
										<option value="<?php echo $row['postStatus']; ?>" selected>
											<?php if ($row['postStatus'] == 1) {
												echo 'Julkaistu';
											} else {
												echo 'Luonnos';
											} ?>
										</option>
										<?php if ($row['postStatus'] == 1) {
											echo '<option value="0">Luonnos</option>';
										} else {
											echo '<option value="1">Julkaistu</option>';
										} ?>
									</select>

									<h3><label>Arvosana (0-5)</label></h3>
									<p>Valitse arvosana. Jos arvosanaa ei valita, annetaan arvostelulle arvosana 0. Arvosanselitys löytyy <a href="http://dev.zurial.fi/arvostelu/info.php">info-sivulta.</a></p>

									<select name='postRate' id="rating">
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
								</div>

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

											if (isset($_POST['catID'])) {

												if (in_array($row2['catID'], $_POST['catID'])) {
													$checked = "checked='checked'";
												} else {
													$checked = null;
												}
											}

											echo "<input type='checkbox' name='catID[]' value='" . $row2['catID'] . "' $checked> " . $row2['catTitle'] . "<br />";
										}

										?>
									</fieldset>
								</div>
								<div class="row">

									<h3><label>Avainsanat</label></h3>
									<p>Erota avainsanat pilkulla ilman välilyöntejä esim. (arvostelu,musiikki,kappale).</p>
									<input type='text' name='postTags' class="post-tag-field" value='<?php if (isset($error)) {
																											echo $_POST['postTags'];
																										} ?>'>
								</div>
							</div>
						</diV>
					</form>
			</div>
	</div>
</body>

</html>