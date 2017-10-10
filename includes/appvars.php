<?php
  // Define application constants
  define('MM_UPLOADPATH', 'images/users/');
  define('MM_MAXFILESIZE', 1000000);      // 1 GB
  define('MM_MAXIMGWIDTH', 120);        // 120 pixels
  define('MM_MAXIMGHEIGHT', 120);       // 120 pixels
  
  // Site wide paths
  define('DOMAIN_PATH', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
?>
