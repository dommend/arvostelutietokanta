<?php require('includes/config.php');


$stmt = $db->prepare('SELECT catID,catTitle FROM cats WHERE catSlug = :catSlug');
$stmt->execute(array(':catSlug' => $_GET['id']));
$row = $stmt->fetch();

//if post does not exists redirect user.
if ($row['catID'] == '') {
    header('Location: ./');
    exit;
}

?>
<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="utf-8">
    <title>Arvostelut — <?php echo $row['catTitle']; ?></title>
    <?php require('includes/head-meta.php'); ?>
</head>

<body>
    <?php require('includes/header-nav.php'); ?>
    <div id="wrapper" class="innercontainer flex">
        <main>

            <div id="page-info" class="innermargin">
                <h2>Arvostelut kategoriassa '<?php echo $row['catTitle']; ?>'</h2>
                <p class="back-to"><a href="./">
					<i class="material-icons">arrow_back_ios</i> Takaisin etusivulle</a></p>
            </div>

            <?php
            try {
                $pages = new Paginator('5', 'p');

                $stmt = $db->prepare('SELECT posts.postID FROM posts, cat_cats WHERE postStatus=1 and posts.postID = cat_cats.postID AND cat_cats.catID = :catID');
                $stmt->execute(array(':catID' => $row['catID']));
                //pass number of records to
                $pages->set_total($stmt->rowCount());

                if ($stmt->rowCount() == 0) {
                    echo "<span class='error innermargin'>Ei arvosteluja. Kuinka mälsää.</span>";
                } else {

                    $stmt = $db->prepare('
                SELECT 
                    posts.postID, posts.postTitle, posts.postSlug, posts.postDesc, posts.postTags, posts.postDate, posts.postRate, posts.postStatus
                FROM 
                    posts,
                    cat_cats
                WHERE
                     posts.postID = cat_cats.postID
                     AND cat_cats.catID = :catID
                     AND postStatus=1
                ORDER BY 
                    postID DESC
                    
                ' . $pages->get_limit());

                    $stmt->execute(array(':catID' => $row['catID']));
                   
                    while ($row = $stmt->fetch()) {
                       
                        echo '<article class="review">';
                        echo '<div class="head flex">';
                        echo '<div class="head-title"><h3><a href="' . $row['postSlug'] . '">' . $row['postTitle'] . '</a></h3></div>';
                        echo '<div class="rating rate-' . str_replace(".", "", $row['postRate']) . '"><h4>' . $row['postRate'] . ' / 5</h4></div>';
                        echo '</div>';

                        echo '<div class="meta flex">';
                        echo '<div class="meta-column">';

                        echo '<span class="time">Arvostelu kirjoitettu ' . date('d.m.Y @ h:i', strtotime($row['postDate'])) . '';
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
                    echo $pages->page_links('c-' . $_GET['id'] . '&');
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