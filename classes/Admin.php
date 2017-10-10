<?php
    class Admin {
        private $users = array();
        
        public function retrieveUsers() {
            $query = "SELECT id, username, first_name, last_name, join_date FROM user ORDER BY id";
            
            $data = $this->runQuery($query);
            
            if (mysqli_num_rows($data) >= 1) {
                while ($row = mysqli_fetch_array($data)) {
                    array_push($this->users, array('id' => $row['id'], 'username' => $row['username'], 'first_name' => $row['first_name'], 'last_name' => $row['last_name'], 'join_date' => $row['join_date']));
                }
            }
        }
        
        public function displayUsers() {
            $this->retrieveUsers();
            
            echo "<tr>";
            echo "<th>Username</th>";
            echo "<th>First Name</th>";
            echo "<th>Last Name</th>";
            echo "<th>Join Date</th>";
            echo "</tr>"; 
            
            foreach ($this->users as $user) {
                echo "<tr>";
                echo "<td>" . $user['username'] . "</td>";
                echo "<td>" . $user['first_name'] . "</td>";
                echo "<td>" . $user['last_name'] . "</td>";
                echo "<td>" . $user['join_date'] . "</td>";
                echo "<td><a href='remove.php?id=" . $user['id'] . "&username=" . $user['username'] . "'>Remove User</a></td>";
                echo "</tr>";
            }
        }
        
        public function removeUser() {
            $query = "DELETE FROM conversation WHERE table_name LIKE '%" . $_GET['username'] . "%'";
            
            $this->runQuery($query);
            
            $query = "DELETE FROM user WHERE id = " . $_GET['id'];
            
            $this->runQuery($query);
            
            echo '<p>The user has been removed successfully. Would you like to <a href="admin.php">go back to admin home</a>?</p>';
        }
        
        public function displayTables() {
            $query = " SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DB_NAME . "' AND table_name != 'user' AND table_name != 'conversation' AND table_name != 'admin'";
            
            $data = $this->runQuery($query);
            
            echo "<tr>";
            echo "<th>Username A</th>";
            echo "<th>Username B</th>";
            echo "<th>Table Name</th>";
            echo "</tr>"; 
            
            if (mysqli_num_rows($data) >= 1) {
                while ($row = mysqli_fetch_array($data)) {
                    $names = explode('_', $row['table_name']);
                    echo "<tr>";
                    echo "<td>" . $names[0] . "</td>";
                    echo "<td>" . $names[1] . "</td>";
                    echo "<td>" . $row['table_name'] . "</td>";
                    echo "<td><a href='remove.php?table=" . $row['table_name'] . "'>Remove Table</a></td>";
                    echo "</tr>";
                }
            }
        }
        
        public function removeTable() {
            $query = "DELETE FROM conversation WHERE table_name = '" . $_GET['table'] . "'";
            
            $this->runQuery($query);
            
            $query = "DROP TABLE " . $_GET['table'];
            
            $this->runQuery($query);
            
            echo '<p>The table has been removed successfully. Would you like to <a href="admin.php">go back to admin home</a>?</p>';
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