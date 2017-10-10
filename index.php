<?php
    session_start();
    
    require_once('includes/appvars.php');
    require_once('includes/connectvars.php');

    // If the session vars aren't set, try to set them with a cookie
    if (!isset($_SESSION['id'])) {
        if (isset($_COOKIE['id']) && isset($_COOKIE['username'])) {
            $_SESSION['id'] = $_COOKIE['id'];
            $_SESSION['username'] = $_COOKIE['username'];
        }
    }
?>
<!DOCTYPE html>
<html>

<?php
    // Per page vars
    $title = 'Chit Chat';
    $pageCss = 'index.css';
?>

    <head>
        <?php require_once('includes/head.php'); ?>
    </head>

    <body>
        
        <header>
            <div id="nav" class="container">
                <a href="index.php"><img src="images/logo_20.png" alt="Chit Chat" /></a>
                
                <nav>
<?php
    if (isset($_SESSION['id']) || isset($_COOKIE['id'])) {
        echo "<a href='logout.php'>Log Out</a>";
        echo "<a href='users.php'>Find People</a>";
        echo "<a href='profile.php'>My Profile</a>";
    }
    else {
        echo "<a href='signup.php'>Sign Up</a>";
        echo "<a href='login.php'>Log In</a>";
    }
?>
                </nav>
            </div>
        </header>
        
        <article id="content">
            <div class="container">
                <div class="topSpacer"></div>
                    <p id="welcome">Welcome</p>
                    <p id="under">to Chit Chat!</p>
                    <!-- <img src="images/logo.png" alt="Chit Chat" /> -->
            </div>
        </article>
        
    </body>

</html>