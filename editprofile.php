<?php
    session_start();
    
    require_once('includes/appvars.php');
    require_once('includes/connectvars.php');
    
    require_once('classes/Profile.php');
    
    $profile = new Profile();

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
    
    if (isset($_POST['submit'])) {
        // Grab the profile data from the POST
        $profile->setFirstName($profile->sanitizeInput($_POST['firstname']));
        $profile->setLastName($profile->sanitizeInput($_POST['lastname']));
        $profile->setGender($profile->sanitizeInput($_POST['gender']));
        $profile->setBirthdate($profile->sanitizeInput($_POST['birthdate']));
        $profile->setOldPicture($profile->sanitizeInput($_POST['old_picture']));
        $profile->setNewPicture($profile->sanitizeInput($_FILES['new_picture']['name']));
        $profile->setNewPictureType($_FILES['new_picture']['type']);
        $profile->setNewPictureSize($_FILES['new_picture']['size']);
        if (!empty($profile->getNewPicture())) {
            list($width, $height) = getimagesize($_FILES['new_picture']['tmp_name']);
            $profile->setNewPictureWidth($width);
            $profile->setNewPictureHeigth($height);
        }
        $profile->setError(false);

        // Validate and move the uploaded picture file, if necessary
        if (!empty($profile->getNewPicture())) {
            if ($profile->validateImage()) {
                if ($_FILES['new_picture']['error'] == 0) {
                    // Move the file to the target upload folder
                    $target = MM_UPLOADPATH . basename($profile->getNewPicture());
                    if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
                        // The new picture file move was successful, now make sure any old picture is deleted
                        if (!empty($profile->getOldPicture()) && ($profile->getOldPicture() != $profile->getNewPicture())) {
                            @unlink(MM_UPLOADPATH . $profile->getOldPicture());
                        }
                    }
                    else {
                        // The new picture file move failed, so delete the temporary file and set the error flag
                        @unlink($_FILES['new_picture']['tmp_name']);
                        $profile->setError(true);
                        $profile->setErrorMessage('<p class="error">Sorry, there was a problem uploading your picture.</p>');
                    }
                }
            }
            else {
                // The new picture file is not valid, so delete the temporary file and set the error flag
                @unlink($_FILES['new_picture']['tmp_name']);
                $profile->setError(true);
                $profile->setErrorMessage('<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
                        ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>');
            }
        }

        // Update the profile data in the database
        if (!$profile->getError()) {
            $profile->setDataFromForm();
        }
    } // End of check for form submission
    else {
        $profile->setDataFromDatabaseUsingSession();
    }
?>
<!DOCTYPE html>
<html>

<?php
    // Per page vars
    $title = 'Chit Chat - Edit Profile';
    $pageCss = 'editprofile.css';
?>

    <head>
        <?php require_once('includes/head.php'); ?>
    </head>

    <body>
        
        <header>
            <div id="nav" class="container">
                <a id="logo" href="index.php"><img src="images/logo_20.png" alt="Chit Chat" /></a>
                
                <nav>
                    <a href='logout.php'>Log Out</a>
                    <a href='users.php'>Find People</a>
                    <a href='profile.php'>My Profile</a>
                    <a href='index.php'>Home</a>
                </nav>
            </div>
        </header>
        
        <article id="middle">
            <div class="container">
                <div class="topSpacer"></div>
                
                <h1>Personal Information</h1>
<?php
    echo '<p class="error">' . $profile->getErrorMessage() . '</p>';
?>
                <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
                    <fieldset>
                        <label for="firstname">First name:</label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo $profile->getFirstName(); ?>" /><br />
                        <label for="lastname">Last name:</label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo $profile->getLastName(); ?>" /><br />
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender">
                            <option value="M" <?php if (!empty($profile->getGender()) && $profile->getGender() == 'M') echo 'selected = "selected"'; ?>>Male</option>
                            <option value="F" <?php if (!empty($profile->getGender()) && $profile->getGender() == 'F') echo 'selected = "selected"'; ?>>Female</option>
                        </select><br />
                        <label for="birthdate">Birthdate:</label>
                        <input type="text" id="birthdate" name="birthdate" value="<?php echo $profile->getBirthdate(); ?>" placeholder="YYYY-MM-DD"/><br />
                        <input type="hidden" name="old_picture" value="<?php echo $profile->getOldPicture(); ?>" />
                        <label for="new_picture">Picture(optional):</label>
                        <input type="file" id="new_picture" name="new_picture" />
                        <?php if (!empty($profile->getOldPicture())) echo '<img class="profile" src="' . MM_UPLOADPATH . $profile->getOldPicture() . '" alt="Profile Picture" />'; ?>
                    </fieldset>
                    <br />
                    <input type="submit" value="Save Profile" name="submit" />
                </form>
                
            </div>
        </article>
        
        <article>
            <div class="container">
                
            </div>
        </article>

    </body>

</html>