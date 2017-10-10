<?php
    session_start();

    require_once('includes/appvars.php');
    require_once('includes/connectvars.php');
    
    require_once('classes/Chat.php');
    
    $chat = new Chat();

    if ($chat->shouldRefresh()) {
        echo 'true';   
    }
    else {
        echo 'false';
    }
?>