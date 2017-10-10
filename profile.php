<?php
    session_start();
    
    require_once('includes/appvars.php');
    require_once('includes/connectvars.php');
    
    require_once('classes/Profile.php');
    require_once('classes/Chat.php');
    
    $profile = new Profile();
    
    if ($_GET != null) {
        $profile->setDataFromDatabaseUsingGet();
    }
    else {
        $profile->setDataFromDatabaseUsingSession();
    }
    $fullname = $profile->getFirstName() . " " . $profile->getLastName();

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
    $title = "My Profile - " . $fullname;
    $pageCss = 'profile.css';
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
                    <a href='users.php'>Find People</a>
<?php
                if ($_GET != null) {
                    echo "<a href='profile.php'>My Profile</a>";
                }
?>
                    <a href='index.php'>Home</a>
                </nav>
            </div>
        </header>
        
        <article>
            <div class="container">
                <div class="topSpacer"></div>
                
                <div id="profile">
                    <h1><?= $fullname ?></h1>
                    <aside>
                        <img src="<?= MM_UPLOADPATH . $profile->getOldPicture() ?>" alt="Profile Picture" />
                    </aside>
                    <br />
                    <fieldset>
                        <legend>Personal Info</legend>
                        Gender: <?= $profile->getGender() ?>
                        <br />
                        Birthdate: <?= $profile->getBirthdate() ?>
                    </fieldset>
                
<?php
                if ($_GET == null) {
                    // Edit the logged in users profile
?>
                    <form id="editProfileForm" method="post" action="editprofile.php">
                        <input type="submit" name="edit" value="Edit Profile" />
                    </form>
<?php
                }
                else {
                    // Chat with the person
                    echo "<form id='chatWithPersonForm' method='post' action='chat.php?id=" . $_GET['id'] . "#bottom' >";
                    echo "<input type='submit' name='chat' value='Chat With This Person' />";
                    echo "</form>";
                }
?>
                </div>
<?php
    if ($_GET == null) {
        $chat = new Chat();
?>
                <h1>My Conversations</h1>
                <table id="conversations">
<?php
        $chat->displayConversations();
?>
                </table>
<?php
    }
?>
                <div class="bottomSpacer"></div>
            </div>
        </article>

    </body>

</html>