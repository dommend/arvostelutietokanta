<aside>
    <div class="innermargin">
        <h3>Sivupalkki</h3>

        <p><a class="button" href="admin/">Kirjaudu ylläpitoon tästä</a></p>

        <div class="row">
        <h3>Kategoriat</h3>
        <ul>
            <?php
            $stmt = $db->query('SELECT catTitle, catSlug FROM cats ORDER BY catID DESC');
            while ($row = $stmt->fetch()) {
                echo '<li><a href="c-' . $row['catSlug'] . '">' . $row['catTitle'] . '</a></li>';
            }
            ?>
        </ul>
        </div>

        <div class="row">
        <h3>Arkisto</h3>
        <ul>
            <?php
            $stmt = $db->query("SELECT Month(postDate) as Month, Year(postDate) as Year FROM posts GROUP BY Month(postDate), Year(postDate) ORDER BY postDate DESC");
    

            while ($row = $stmt->fetch()) {

                $monthName = date("m", mktime(0, 0, 0, $row['Month'], 10));
                $year = $row['Year'];
                $slug = 'a-' . $row['Month'] . '-' . $row['Year'];
                echo "<li><a href='$slug'>$monthName/$year</a></li>";
            }
            ?>
        </ul>
        </div>

        <div class="row tags">
        <h3>Avainsanat</h3>
        <ul>
            <?php
            $tagsArray = [];
            $stmt = $db->query('select distinct LOWER(postTags) as postTags from posts where postTags != "" group by postTags');
            while ($row = $stmt->fetch()) {
                $parts = explode(',', $row['postTags']);
                foreach ($parts as $tag) {
                    $tagsArray[] = $tag;
                }
            }

            $finalTags = array_unique($tagsArray);
            foreach ($finalTags as $tag) {
                echo "<li><a href='t-" . $tag . "'>" . ucwords($tag) . "</a></li>";
            }
            ?>
        </ul>
        </div>
    </div>
</aside>