<?php
    session_start();
    
    require_once('includes/appvars.php');
    require_once('includes/connectvars.php');
    
    require_once('classes/Chat.php');
    require_once('classes/Profile.php');
    
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
    
    $sender = new Profile();
    $reciever = new Profile();
    
    $sender->setDataFromDatabaseUsingSession();
    $reciever->setDataFromDatabaseUsingGet();
    
    $chat = new Chat();
    
    $chat->setUsernames($sender->getUsername(), $reciever->getUsername());
    $chat->createTableName();
    
    if (isset($_POST['sending'])) {
        if (!empty($_POST['message'])) {
            $chat->sendMessage($chat->sanitizeInput($_POST['message']));   
        }
    }

    if ($chat->checkExists() != true) {
        $chat->createChatTable();
        $chat->createConversations();
    }
?>
<!DOCTYPE html>
<html>

<?php
    $title = $reciever->getFirstName() . " "  . $reciever->getLastName();
    $pageCss = 'chat.css';
?>

    <head>
        <?php require_once('includes/head.php'); ?>
        <script>
            var timer = window.setTimeout(refresh, 5000);
            
            function setCursor() {
                document.getElementById('entry').focus();
            }
        
            function loadMessages() {
                var xhttp = new XMLHttpRequest();
                
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementById("messageListContent").innerHTML = xhttp.responseText;
                        window.scroll(0,document.body.scrollHeight);
                    }
                };
                
                xhttp.open("POST", "messages.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("table_name=<?php echo $chat->getTableName(); ?>&senderName=<?php echo $sender->getFirstName() ?>&recieverName=<?php echo $reciever->getFirstName() ?>");
            }
            
            function refresh() {
                var xmlhttp = new XMLHttpRequest();
                
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        if (xmlhttp.responseText == 'true') {
                            loadMessages();
                            timer = window.setTimeout(refresh, 5000);
                        }
                        else {
                            timer = window.setTimeout(refresh, 5000);
                        }
                    }
                };
                
                xmlhttp.open("GET", "refresh.php?id=<?php echo $_GET['id']; ?>&table_name=<?php echo $chat->getTableName(); ?>" , true);
                xmlhttp.send();
            }
        </script>
    </head>

    <body onLoad="loadMessages(); setCursor();">
        
        <header>
            <div id="nav" class="container">
                <div id="subnav">
                    <a href="index.php"><img src="images/logo_20.png" alt="Chit Chat" /></a>
                    <h1><?php echo $reciever->getFirstName() . " "  . $reciever->getLastName() ?></h1>
                </div>
                
                
                <nav>
                    <a href='logout.php'>Log Out</a>
                    <a href='users.php'>Find People</a>
                    <a href='profile.php'>My Profile</a>
                    <a href='index.php'>Home</a>
                </nav>
            </div>
        </header>

        <article id="messages">
            <div id="messageList" class="container">
                <div class="topSpacer"></div>
                <div id="messageListContent">
                    <!-- messages will appear here -->
                </div>
                <div class="bottomSpacer"></div>
            </div>
        </article>

        <article id="messageArea">
            <div id="entryArea" class="container">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . "?id=" . $_GET['id'] ?>">
                    <input type="text" id="entry" name="message" placeholder="Enter Message Here" autocomplete="off" maxlength="255" />
                    <input type="submit" name="sending" value="Send" />
                </form>
            </div>
        </article>

    </body>

</html>