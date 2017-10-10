<?php
    class Users {
        private $profiles = array();
        
        public function getProfiles() {
            return $this->profiles;
        }
        
        public function retrieveProfiles() {
            $this->messages = array();
            
            $query = "SELECT id, first_name, last_name, picture FROM user WHERE id != " . $_SESSION['id'] . " ORDER BY id";
            
            $data = $this->runQuery($query);
            
            if (mysqli_num_rows($data) >= 1) {
                while ($row = mysqli_fetch_array($data)) {
                    array_push($this->profiles, array('id' => $row['id'], 'first_name' => $row['first_name'], 'last_name' => $row['last_name'], 'picture' => $row['picture']));
                }
            }
        }
        
        public function displayProfiles() {
            foreach ($this->profiles as $profile) {
                if ($profile['picture'] == null) {
                    $profile['picture'] = 'default.png';
                }
                
                echo "<tr>";
                echo "<td><img src=" . MM_UPLOADPATH . $profile['picture'] . " alt='Profile Picture' /></td>";
                echo "<td><h1>" . $profile['first_name'] . " "  . $profile['last_name'] . "</h1></td>";
                echo "<td>";
                echo "<form id='viewProfileForm' method='post' action='profile.php?id=" . $profile['id'] . "' >";
                echo "<input type='submit' name='view' value='View Their Profile' />";
                echo "</form>";
                echo "</td>";
                echo "<td>";
                echo "<form id='chatWithPersonForm' method='post' action='chat.php?id=" . $profile['id'] . "'>";
                echo "<input type='submit' name='chat' value='Chat With This Person' />";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
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