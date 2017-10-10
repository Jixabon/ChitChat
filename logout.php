<?php
    require_once('includes/appvars.php');
    require_once('includes/connectvars.php');

    // If the user is logged in, delete the session vars to log them out
    session_start();
    if (isset($_SESSION['id'])) {
        // Delete the session vars by clearing the $_SESSION array
        $_SESSION = array();
        
        // Delete the session cookie by setting their expirations to an hour ago (3600)
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600);
        }
        
        // Destroy the session
        session_destroy();
    }
    
    // Delete the user ID and username cookies by setting their expirations to an hour ago (3600)
    setcookie('id', '', time() - 3600);
    setcookie('username', '', time() - 3600);
    
    // Redirect to the home page
    header('Location: ' . DOMAIN_PATH . '/index.php');
?>