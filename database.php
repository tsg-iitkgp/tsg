<?php
   class MyDB extends SQLite3 {
      function __construct() {
         $this->open('notices.db');
      }
   }
   $conn = new MyDB();
   if(!$conn) {
      echo $conn->lastErrorMsg();
   } 
?>