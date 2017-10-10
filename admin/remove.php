<?php
    session_start();
    
    require_once('../includes/appvars.php');
    require_once('../includes/connectvars.php');
    
    require_once('../classes/Admin.php');
    
    $admin = new Admin();
    
    // If the session vars aren't set, try to set them with a cookie
    if (!isset($_SESSION['admin_id'])) {
        if (isset($_COOKIE['admin_id'])) {
            $_SESSION['admin_id'] = $_COOKIE['admin_id'];
        }
        else {
            header('Location: index.php');
        }
    }
?>

<!DOCTYPE html>
<html>
    <?php
        // Per page vars
        $title = 'Remove User or Table';
        $pageCss = 'admin.css';
    ?>
    
    <head>
        <?php require_once('includes/head.php'); ?>
    </head>
    
    <body>
        
        <header>
            <div id="nav" class="container">
                <a href="index.php"><img src="../images/logo_20.png" alt="Chit Chat" /></a>
                
                <nav>
                    <a href='logout.php'>Log Out</a>
                    <a href='admin.php'>Admin Home</a>
                </nav>
            </div>
        </header>
        
        <article>
            <div class="container">
                <div class="topSpacer"></div>
            
<?php
    
    // Creates query with the id of the entry to remove
    if (isset($_GET['id'])) {
        $admin->removeUser();
    }
    else if (isset($_GET['table'])) {
        $admin->removeTable();
    }
?>
                <div class="bottomSpacer"></div>
            </div>
        </article>
        
        <article>
            <div class="container">
                
            </div>
        </article>

    </body>

</html>
        
    </body>
    
</html>