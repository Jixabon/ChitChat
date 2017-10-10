<?php
    class Profile {
        private $error;
        private $errorMessage;
        private $id;
        private $username;
        private $firstName;
        private $lastName;
        private $gender;
        private $birthdate;
        private $oldPicture;
        private $newPicture;
        private $newPictureType;
        private $newPictureSize;
        private $newPictureWidth;
        private $newPictureHeigth;
        
        public function getError() {
            return $this->error;
        }
        
        public function getErrorMessage() {
            return $this->errorMessage;
        }
        
        public function getId() {
            return $this->id;
        }
        
        public function getUsername() {
            return $this->username;
        }
        
        public function getFirstName() {
            return $this->firstName;
        }
        
        public function getLastName() {
            return $this->lastName;
        }
        
        public function getGender() {
            return $this->gender;
        }
        
        public function getBirthdate() {
            return $this->birthdate;
        }
        
        public function getOldPicture() {
            return $this->oldPicture;
        }
        
        public function getNewPicture() {
            return $this->newPicture;
        }
        
        public function getNewPictureType() {
            return $this->newPictureType;
        }
        
        public function getNewPictureSize() {
            return $this->newPicture;
        }
        
        public function getNewPictureWidth() {
            return $this->newPictureWidth;
        }
        
        public function getNewPictureHeigth() {
            return $this->newPictureHeigth;
        }
        
        public function setError($newError) {
            $this->error = $newError;
        }
        
        public function setErrorMessage($newMessage) {
            $this->errorMessage = $newMessage;
        }
        
        public function setId($newValue) {
            $this->id = $newValue;
        }
        
        public function setUsername($newValue) {
            $this->username = $newValue;
        }
        
        public function setFirstName($newName) {
            $this->firstName = $newName;
        }
        
        public function setLastName($newName) {
            $this->lastName = $newName;
        }
        
        public function setGender($newGender) {
            $this->gender = $newGender;
        }
        
        public function setBirthdate($newBirthdate) {
            $this->birthdate = $newBirthdate;
        }
        
        public function setOldPicture($newValue) {
            $this->oldPicture;
        }
        
        public function setNewPicture($newValue) {
            $this->newPicture = $newValue;
        }
        
        public function setNewPictureType($newValue) {
            $this->newPictureType = $newValue;
        }
        
        public function setNewPictureSize($newValue) {
            $this->newPictureSize = $newValue;
        }
        
        public function setNewPictureWidth($newValue) {
            $this->newPictureWidth = $newValue;
        }
        
        public function setNewPictureHeigth($newValue) {
            $this->newPictureHeigth = $newValue;
        }
        
        public function validateImage() {
            if ((($this->newPictureType == 'image/gif') 
                        || ($this->newPictureType == 'image/jpeg') 
                        || ($this->newPictureType == 'image/pjpeg') 
                        || ($this->newPictureType == 'image/png'))
                        && ($this->newPictureSize > 0) 
                        && ($this->newPictureSize <= MM_MAXFILESIZE) 
                        && ($this->newPictureWidth <= MM_MAXIMGWIDTH) 
                        && ($this->newPictureHeigth <= MM_MAXIMGHEIGHT)) {
                return true;
            }
            else {
                return false;
            }
        }
        
        public function setDataFromForm() {
            if (!empty($this->firstName) && !empty($this->lastName) && !empty($this->gender) && !empty($this->birthdate)) {
                // Only set the picture column if there is a new picture
                if (!empty($this->newPicture)) {
                  $query = "UPDATE user SET first_name = '$this->firstName', last_name = '$this->lastName', gender = '$this->gender', " .
                    " birthdate = '$this->birthdate', picture = '$this->newPicture' WHERE id = '" . $_SESSION['id'] . "'";
                }
                else {
                  $query = "UPDATE user SET first_name = '$this->firstName', last_name = '$this->lastName', gender = '$this->gender', " .
                    " birthdate = '$this->birthdate' WHERE id = '" . $_SESSION['id'] . "'";
                }
                $this->runQuery($query);
        
                // Confirm success with the user
                $this->errorMessage = '<p>Your profile has been successfully updated. Would you like to <a href="profile.php">view your profile</a>?</p>';
            }
            else {
                $this->errorMessage = '<p class="error">You must enter all of the profile data (the picture is optional).</p>';
            }
        }
        
        public function setDataFromDatabaseUsingGet() {
            // Grab the profile data from the database
            $query = "SELECT id, username, first_name, last_name, gender, birthdate, picture FROM user WHERE id = '" . $_GET['id'] . "'";
            $data = $this->runQuery($query);
            $row = mysqli_fetch_array($data);
    
            $this->setData($row);
        }
        
        public function setDataFromDatabaseUsingSession() {
            // Grab the profile data from the database
            $query = "SELECT id, username, first_name, last_name, gender, birthdate, picture FROM user WHERE id = '" . $_SESSION['id'] . "'";
            $data = $this->runQuery($query);
            $row = mysqli_fetch_array($data);
    
            $this->setData($row);
        }
        
        public function setData($row) {
            if ($row != NULL) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->firstName = $row['first_name'];
                $this->lastName = $row['last_name'];
                $this->gender = $row['gender'];
                $this->birthdate = $row['birthdate'];
                if ($row['picture'] == null) {
                    $this->oldPicture = 'default.png';
                }
                else {
                    $this->oldPicture = $row['picture'];
                }
            }
            else {
              $this->errorMessage = '<p class="error">There was a problem accessing your profile.</p>';
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