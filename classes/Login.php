<?php
    class Login {
        private $error;
        private $id;
        private $username;
        private $password;
        
        public function getError() {
            return $this->error;
        }
        
        public function getId() {
            return $this->id;
        }
        
        public function getUsername() {
            return $this->username;
        }
        
        public function getPassword() {
            return $this->password;
        }
        
        public function setError($newError) {
            $this->error = $newError;
        }
        
        public function setId($newId) {
            $this->id = $newId;
        }
        
        public function setUsername($newUsername) {
            $this->username = $newUsername;
        }
        
        public function setPassword($newPassword) {
            $this->password = $newPassword;
        }
        
        
        
        public function attemptUserLogin() {
            // Look up the username and password in the database
            $query = "SELECT id, username FROM user WHERE username = '$this->username' AND password = SHA('$this->password')";
            $data = $this->runQuery($query);
                
            if (mysqli_num_rows($data) == 1) {
                // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
                $row = mysqli_fetch_array($data);
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                setcookie('id', $row['id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
                header('Location: ' . DOMAIN_PATH . '/index.php');
            }
            else {
              // The username/password are incorrect so set an error message
              $this->error = 'Sorry, you must enter a valid username and password to log in.';
            }
        }
        
        public function attemptAdminLogin() {
            // Look up the username and password in the database
            $query = "SELECT id FROM admin WHERE username = '$this->username' AND password = SHA('$this->password')";
            $data = $this->runQuery($query);
                
            if (mysqli_num_rows($data) == 1) {
                // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
                $row = mysqli_fetch_array($data);
                $_SESSION['admin_id'] = $row['id'];
                setcookie('admin_id', $row['id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
                header('Location: ' . DOMAIN_PATH . '/admin.php');
            }
            else {
              // The username/password are incorrect so set an error message
              $this->error = 'Sorry, you must enter a valid username and password to log in.';
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