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
  <title>Ylläpito — Kategoriat</title>
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

    <table>
    <thead>
    <tr>
        <th>Kategoria</th>
        <th>Toiminto</th>
    </tr>
</thead>
    <?php
        try {

            $stmt = $db->query('SELECT catID, catTitle, catSlug FROM cats ORDER BY catTitle DESC');
            while($row = $stmt->fetch()){
                
                echo '<tr>';
                echo '<td>'.$row['catTitle'].'</td>';
                ?>

                <td class="center">
                    <a href="edit-category.php?id=<?php echo $row['catID'];?>">Muokkaa</a> | 
                    <a href="javascript:delcat('<?php echo $row['catID'];?>','<?php echo $row['catSlug'];?>')">Poista</a>
                </td>
                
                <?php 
                echo '</tr>';

            }

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    ?>
    </table>

    <p><a href='add-category.php' class='button'>Lisää kategoria</a></p>

</div>
</main>

</div>
</div>
</body>

</html>