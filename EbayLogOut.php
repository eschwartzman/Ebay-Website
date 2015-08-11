<?php
  session_start();
  $_SESSION['loggedIn']=False;
  session_destroy();
  header('location: Final_login.php'); // redirct to home
?>