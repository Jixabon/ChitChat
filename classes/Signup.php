<?php
    class Signup {
        private $error;
        private $username;
        private $password1;
        private $password2;
        
        public function getError() {
            return $this->error;
        }
        
        public function getUsername() {
            return $this->username;
        }
        
        public function getPassword1() {
            return $this->password1;
        }
        
        public function getPassword2() {
            return $this->password2;
        }
        
        public function setError($newError) {
            $this->error = $newError;
        }
        
        public function setUsername($newUsername) {
            $this->username = $newUsername;
        }
        
        public function setPassword1($newPassword1) {
            $this->password1 = $newPassword1;
        }
        
        public function setPassword2($newPassword2) {
            $this->password2 = $newPassword2;
        }
        
        public function createUser() {
            if (!empty($this->username) && !empty($this->password1) && !empty($this->password2) && ($this->password1 == $this->password2)) {
                // Make sure someone isn't already registered using this username
                $query = "SELECT * FROM user WHERE username = '$this->username'";
                $data = $this->runQuery($query);
                if (mysqli_num_rows($data) == 0) {
                    // The username is unique, so insert the data into the database
                    $query = "INSERT INTO user (username, password, join_date) VALUES ('$this->username', SHA('$this->password1'), CURDATE())";
                    $this->runQuery($query);
                    // Confirm success with the user
                    return '<p>Your new account has been successfully created. You\'re now ready to <a href="login.php">log in</a>.</p>';
                }
                else {
                    // An account already exists for this username, so display an error message
                    $this->error = '<p class="error">An account already exists for this username. Please use a different username.</p>';
                    $this->username = "";
                }
            }
            else {
                $this->error = '<p class="error">You must enter all of the sign-up data, including the desired password twice.</p>';
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