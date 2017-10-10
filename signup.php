<?php
    require_once('includes/appvars.php');
    require_once('includes/connectvars.php');
    
    require_once('classes/Signup.php');
    
    $signup = new Signup();
?>
<!DOCTYPE html>
<html>

<?php
    // Per page vars
    $title = 'Chit Chat - Sign Up';
    $pageCss = 'signup.css';
?>

    <head>
        <?php require_once('includes/head.php'); ?>
    </head>

    <body>
        
        <header>
            <div id="nav" class="container">
                <a href="index.php"><img src="images/logo_20.png" alt="Chit Chat" /></a>
            </div>
        </header>
        
        <article id="middle">
            <div class="container">
                <div class="topSpacer"></div>
                
<?php
    if (isset($_POST['submit'])) {
        // Grab the profile data from the POST
        $signup->setUsername($signup->sanitizeInput($_POST['username']));
        $signup->setPassword1($signup->sanitizeInput($_POST['password1']));
        $signup->setPassword2($signup->sanitizeInput($_POST['password2']));
        
        $success = $signup->createUser();
    }
?>
                <h1>Sign Up</h1>
                <p>Please enter your username and desired password to create an account for Chit Chat.</p>
                
<?php
    echo '<p class="error">' . $signup->getError() . '</p>';
?>
                
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <fieldset>
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo $signup->getUsername(); ?>" /><br />
                        <label for="password1">Password:</label>
                        <input type="password" id="password1" name="password1" /><br />
                        <label for="password2">Password (retype):</label>
                        <input type="password" id="password2" name="password2" /><br />
                    </fieldset>
                    <br />
                    <input type="submit" value="Sign Up" name="submit" />
                </form>
                
                <?= $success; ?>
                    
            </div>
        </article>

    </body>

</html>