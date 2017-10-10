<?php
    require_once('Message.php');

    class Chat {
        private $usernames;
        private $sortedUsernames;
        private $table_name;
        private $messages = array();
        
        public function getUsernames() {
            return $this->usernames;
        }
        
        public function getSortedUsernames() {
            return $this->sortedUsernames;
        }
        
        public function getTableName() {
            return $this->table_name;
        }
        
        public function getMessages() {
            return $this->messages;
        }
        
        public function setUsernames($username1, $username2) {
            $this->usernames = array("$username1", "$username2");
            $this->sortedUsernames = array("$username1", "$username2");
        }
        
        public function setTableName($newValue) {
            $this->table_name = $newValue;
        }
        
        public function setMessages($newValue) {
            $this->messages = $newValue;
        }
        
        public function createTableName() {
            sort($this->sortedUsernames);
            $delimiter = "_";
            $this->table_name = implode($delimiter, $this->sortedUsernames);
        }
        
        public function sendMessage($message) {
            $query = "INSERT INTO $this->table_name (sent_by, sent_date, message) VALUES (" . $_SESSION['id'] . ", CURRENT_TIMESTAMP, '$message')";
            
            $this->runQuery($query);
        }
        
        public function retrieveMessages() {
            $this->messages = array();
            
            $query = "SELECT * FROM $this->table_name ORDER BY id";
            
            $data = $this->runQuery($query);
            
            if (mysqli_num_rows($data)!=0) {
                if (mysqli_num_rows($data) >= 1) {
                    while ($row = mysqli_fetch_array($data)) {
                        array_push($this->messages, new Message($row['id'], $row['sent_by'], $row['sent_date'], $row['message']));
                    }
                }
            }
        }
        
        public function displayMessages($senderName, $recieverName) {
            if ($this->messages == null) {
                echo "<p>You have no messages. Send a message below!</p>";
            }
            else {            
                foreach ($this->messages as $message) {
                    if ($message->getSentBy() == $_SESSION['id']) {
                        echo "<div class='sent message'>";
                        echo "<h4>$senderName</h4>";
                        echo "<p>" . $message->getMessage() . "</p>";
                        echo "</div>";
                    }
                    else {
                        echo "<div class='recieved message'>";
                        echo "<h4>$recieverName</h4>";
                        echo "<p>" . $message->getMessage() . "</p>";
                        echo "</div>";
                    }
                }
            }
        }
        
        public function getConversations($oppositeInfo) {
            $oppositeData = array();
            
            foreach ($oppositeInfo as $username) {
                $query = "SELECT id, first_name, last_name FROM user WHERE username = '" . $username['name'] . "'";
                
                $data = $this->runQuery($query);
                
                $row = mysqli_fetch_array($data);
                
                array_push($oppositeData, array('id' => $row['id'], 'first_name' => $row['first_name'], 'last_name' => $row['last_name']));
            }
            
            return $oppositeData;
        }
        
        public function checkConversations() {
            $query = "SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE table_name LIKE '%" . $_SESSION['username'] . "%'";
            
            $oppositeInfo = array();
            
            $data = $this->runQuery($query);

            while ($row = mysqli_fetch_array($data)) {
                $table_names = explode("_", $row['table_name']);
                foreach ($table_names as $name) {
                    if ($name != $_SESSION['username']) {
                        array_push($oppositeInfo, array('name' => $name, 'table_name' => $row['table_name']));
                    }
                }
            }
            
            return $oppositeInfo;
        }
        
        public function displayConversations() {
            $oppositeInfo = $this->checkConversations();
            $oppositeData = $this->getConversations($oppositeInfo);
            
            $i = 0;
            foreach ($oppositeData as $convo) {
                if ($i % 2 == 0) {
                    echo "<tr>";
                }
                
                echo "<td>";
                echo "<a href='chat.php?id=" . $convo['id'] . "'>";
                
                if (!empty($convo['first_name']) || !empty($convo['last_name'])) {
                    echo $convo['first_name'] . " " . $convo['last_name'];
                }
                else {
                    echo "Unknown";
                }
                
                echo "</a>";
                
                $query = "SELECT num_last_seen FROM conversation WHERE user_id = " . $_SESSION['id'] . " AND table_name = '" . $oppositeInfo[$i]['table_name'] . "'";
                
                $data = $this->runQuery($query);
                
                $row = mysqli_fetch_array($data);
                
                $tableCount = $this->getCount($oppositeInfo[$i]['table_name']);
                $lastSeen = $row['num_last_seen'];
                $num_unseen = $tableCount - $lastSeen;
                
                if ($num_unseen > 0) {
                    echo "<pre class='notification'>$num_unseen</pre>";
                }
                
                echo "</td>";
                
                if ($i % 2 == 1) {
                    echo "</tr>";
                }
                
                $i++;
            }
        }
        
        public function checkExists() {
            $query = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '$this->table_name'";
                    
            $data = $this->runQuery($query);
            
            if (mysqli_num_rows($data)!=0) {
                return true;
            }
            else {
                return false;
            }
        }
        
        public function createChatTable() {
            $query = "CREATE TABLE $this->table_name (id INT PRIMARY KEY AUTO_INCREMENT, sent_by INT, sent_date timestamp, message varchar(255))";
                    
            if (!empty($this->table_name)) {
                $this->runQuery($query);
            }
        }
        
        public function createConversations() {
            $query = "INSERT INTO conversation (user_id, table_name, num_last_seen) VALUES (" . $_SESSION['id'] . ", '$this->table_name', " . $this->getCount($this->table_name) . ")";
            
            $this->runQuery($query);
            
            $query = "INSERT INTO conversation (user_id, table_name, num_last_seen) VALUES (" . $_GET['id'] . ", '$this->table_name', " . $this->getCount($this->table_name) . ")";
            
            $this->runQuery($query);
        }
        
        public function updateConversation() {
            $query = "UPDATE conversation SET num_last_seen = " . $this->getCount($this->table_name) . " WHERE user_id = " . $_SESSION['id'] . " AND table_name = '$this->table_name'";
            
            $this->runQuery($query);
        }
        
        public function shouldRefresh() {
            $query = "SELECT num_last_seen FROM conversation WHERE user_id = " . $_SESSION['id'] . " AND table_name = '" . $_GET['table_name'] . "'";
            
            $data = $this->runQuery($query);
            
            $row = mysqli_fetch_array($data);
            
            $tableCount = $this->getCount($_GET['table_name']);
            $lastSeen = $row['num_last_seen'];
            $num_unseen = $tableCount - $lastSeen;
            
            if ($num_unseen > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        
        public function getCount($table_name) {
            $query = 'SELECT COUNT(*) AS "count" FROM ' . $table_name;
            
            $data = $this->runQuery($query);
            
            if (mysqli_num_rows($data)!=0) {
                $row = mysqli_fetch_array($data);
                
                return $row['count'];
            }
        }
        
        public function runQuery($query) {
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $data = mysqli_query($dbc, $query);
            mysqli_close($dbc);
            
            return $data;
        }
        
        public function sanitizeInput($input) {
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $output = mysqli_real_escape_string($dbc, trim($input));
            mysqli_close($dbc);
            
            return $output;
        }
        
        public function validateInput($input) {
            // Checks if any properties are empty and returns an error.
            // if all fields are filled it will create the madlib string.
            if (empty($input)) {
                return false;
            } else {
                return true;
            }
        }
    }
?>