<?php
    session_start();

    require_once('../includes/appvars.php');
    require_once('../includes/connectvars.php');
    
    require_once('../classes/Login.php');
    
    $login = new Login();
    
    // Clear the error message
    $login->setError("");
    
    // If the user isn't logged in, try to log them in
    if (!isset($_SESSION['admin_id'])) {
        if (isset($_POST['submit'])) {

        // Grab the user-entered log-in data
        $login->setUsername($login->sanitizeInput($_POST['username']));
        $login->setPassword($login->sanitizeInput($_POST['password']));

            if (!empty($login->getUsername()) && !empty($login->getPassword())) {
                $login->attemptAdminLogin();
            }
            else {
                // The username/password weren't entered so set an error message
                $login->setError('Sorry, you must enter your username and password to log in.');
            }
        }
    }
?>
<!DOCTYPE html>
<html>

<?php
    // Per page vars
    $title = 'Admin - Log In';
    $pageCss = 'login.css';
?>

    <head>
        <?php require_once('includes/head.php'); ?>
    </head>

    <body>
        
        <header>
            <div id="nav" class="container">
                <a href="index.php"><img src="../images/logo_20.png" alt="Chit Chat" /></a>
            </div>
        </header>
        
        <article id="middle">
            <div class="container">
                <div class="topSpacer"></div>
                
                <h1>Admin - Log In</h1>
                
<?php
    // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
    if (empty($_SESSION['admin_id'])) {
        echo '<p class="error">' . $login->getError() . '</p>';
?>    
            
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <fieldset>
                        <label for="username">Username:</label>
                        <input type="text" name="username" value="<?php echo $login->getUsername(); ?>" /><br />
                        <label for="password">Password:</label>
                        <input type="password" name="password" />
                    </fieldset>
                    <br />
                    <input type="submit" value="Log In" name="submit" />
                </form>
                
<?php
    }
?>

            </div>
        </article>

    </body>

</html>