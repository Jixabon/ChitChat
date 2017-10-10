<?php
    session_start();

    require_once('includes/appvars.php');
    require_once('includes/connectvars.php');
    
    require_once('classes/Chat.php');
    
    $chat = new Chat();
    
    $chat->setTableName($_POST['table_name']);
    
    $chat->retrieveMessages();
    $chat->displayMessages($_POST['senderName'], $_POST['recieverName']);
    $chat->updateConversation();
?>