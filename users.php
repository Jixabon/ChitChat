<?php
    session_start();
    
    require_once('includes/appvars.php');
    require_once('includes/connectvars.php');
    
    require_once('classes/Users.php');
    
    $users = new Users();

    // If the session vars aren't set, try to set them with a cookie
    if (!isset($_SESSION['id'])) {
        if (isset($_COOKIE['id']) && isset($_COOKIE['username'])) {
            $_SESSION['id'] = $_COOKIE['id'];
            $_SESSION['username'] = $_COOKIE['username'];
        }
        else {
            header('Location: ' . DOMAIN_PATH . '/login.php');
        }
    }
?>
<!DOCTYPE html>
<html>

<?php
    // Per page vars
    $title = "Find People";
    $pageCss = 'users.css';
?>

    <head>
        <?php require_once('includes/head.php'); ?>
    </head>

    <body>
        
        <header>
            <div id="nav" class="container">
                <a href="index.php"><img src="images/logo_20.png" alt="Chit Chat" /></a>
                
                <nav>
                    <a href='logout.php'>Log Out</a>
                    <a href='profile.php'>My Profile</a>
                    <a href='index.php'>Home</a>
                </nav>
            </div>
        </header>
        
        <article>
            <div class="container">
                <div class="topSpacer"></div>
                <table id="profile">
<?php
    $users->retrieveProfiles();
    $users->displayProfiles();
?>
                </table>
                <div class="bottomSpacer"></div>
            </div>
        </article>
        
        <article>
            <div class="container">
                
            </div>
        </article>

    </body>

</html>