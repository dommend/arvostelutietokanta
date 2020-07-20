


<header>
<div class="innercontainer flex">
    <div id="title">
        <h1><a href="index.php">Arvostelut</a></h1>
    </div>
    <?php 
        // function to get the current page name
function PageName() {
  return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

$current_page = PageName(); ?>
      <nav id="page-nav">
      <label for="hamburger"><span>&#9776;</span></label>
      <input type="checkbox" id="hamburger"/>
            <ul class="menu">
            <li><a class="<?php echo $current_page == 'index.php' ? 'active':NULL ?>" href="index.php">Etusivu</a></li>
            <li><a class="<?php echo $current_page == 'info.php' ? 'active':NULL ?>" href="info.php">Info</a></li>
            <li><a class="<?php echo $current_page == 'top.php' ? 'active':NULL ?>" href="top.php">Parhaat</a></li>
            <li><a class="<?php echo $current_page == 'flop.php' ? 'active':NULL ?>" href="flop.php">Huonoimmat</a></li>
            <li><a class="<?php echo $current_page == 'send.php' ? 'active':NULL ?>" href="send.php">Lähetä arvostelu</a></li>
            </ul>
        </nav>
</div>
</header>



