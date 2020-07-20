<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="fi">
<head>
  <meta charset="utf-8">
  <title>Ylläpito — Lisää kategoria</title>
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
    <p><a class="back-to" href="categories.php">
						<i class="material-icons">
							arrow_back
						</i>
						Siirry kategoriahallintaan</a></p>

    <h2>Lisää kategoria</h2>

    <?php

    //if form has been submitted process it
    if(isset($_POST['submit'])){

        $_POST = array_map( 'stripslashes', $_POST );

        //collect form data
        extract($_POST);

        //very basic validation
        if($catTitle ==''){
            $error[] = 'Please enter the Category.';
        }

        if(!isset($error)){

            try {

                $catSlug = slug($catTitle);

                //insert into database
                $stmt = $db->prepare('INSERT INTO cats (catTitle,catSlug) VALUES (:catTitle, :catSlug)') ;
                $stmt->execute(array(
                    ':catTitle' => $catTitle,
                    ':catSlug' => $catSlug
                ));

                //redirect to index page
                header('Location: categories.php?action=added');
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

        <p><label>Nimi</label><br />
        <input type='text' name='catTitle' value='<?php if(isset($error)){ echo $_POST['catTitle'];}?>'></p>

        <p><input type='submit' name='submit' class='button' value='Lisää kategoria'></p>

    </form>

    </div>    
    </main>

</div>
</div>
</body>

</html>