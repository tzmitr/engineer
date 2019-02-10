<?php
   session_start();
unset($_SESSION["loggedin"]);
unset($_SESSION["id"]);
unset($_SESSION["username"]);
unset($_SESSION["password"]);
   
   echo 'You have cleaned session';
    echo '</br>Click here to <a href = "login.php" title = "Login">login</a>';
?>