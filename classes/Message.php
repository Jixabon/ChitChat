<?php
    class Message {
        private $id;
        private $sent_by;
        private $sent_date;
        private $message;
        
        public function Message($id, $sentby, $sentdate, $message) {
            $this->id = $id;
            $this->sent_by = $sentby;
            $this->sent_date = $sentdate;
            $this->message = $message;
        }
        
        public function getId() {
            return $this->id;
        }
        
        public function getSentBy() {
            return $this->sent_by;
        }
        
        public function getSentDate() {
            return $this->sent_date;
        }
        
        public function getMessage() {
            return $this->message;
        }
        
        public function setId($newValue) {
            $this->id = $newValue;
        }
        
        public function setSentBy($newValue) {
            $this->sent_by = $newValue;
        }
        
        public function setSentDate($newValue) {
            $this->sent_date = $newValue;
        }
        
        public function setMessage($newValue) {
            $this->message = $newValue;
        }
    }
?>