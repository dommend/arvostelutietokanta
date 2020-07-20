<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="fi">

<head>
	<meta charset="utf-8">
	<title>Ylläpito —— Muokkaa kategoriaa</title>
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

    <?php include('menu.php');?>
    <p><a class="back-to" href="categories.php">
						<i class="material-icons">
							arrow_back
						</i>
						Siirry kategoriahallintaan</a></p>

    <h2>Muokkaa kategoriaa</h2>


    <?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

        $_POST = array_map( 'stripslashes', $_POST );

        //collect form data
        extract($_POST);

        //very basic validation
        if($catID ==''){
            $error[] = 'This post is missing a valid id!.';
        }

        if($catTitle ==''){
            $error[] = 'Please enter the title.';
        }

        if(!isset($error)){

            try {

                $catSlug = slug($catTitle);

                //insert into database
                $stmt = $db->prepare('UPDATE cats SET catTitle = :catTitle, catSlug = :catSlug WHERE catID = :catID') ;
                $stmt->execute(array(
                    ':catTitle' => $catTitle,
                    ':catSlug' => $catSlug,
                    ':catID' => $catID
                ));

                //redirect to index page
                header('Location: categories.php?action=updated');
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

            $stmt = $db->prepare('SELECT catID, catTitle FROM cats WHERE catID = :catID') ;
            $stmt->execute(array(':catID' => $_GET['id']));
            $row = $stmt->fetch(); 

        } catch(PDOException $e) {
            echo $e->getMessage();
        }

    ?>

    <form action='' method='post'>
        <input type='hidden' name='catID' value='<?php echo $row['catID'];?>'>

        <p><label>Nimi</label><br />
        <input type='text' name='catTitle' value='<?php echo $row['catTitle'];?>'></p>

        <p><input type='submit' name='submit' class='button' value='Update'></p>

    </form>

    </div>    
</main>

</div>
</div>
</body>

</html>