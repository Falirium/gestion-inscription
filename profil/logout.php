<?php
     // destroy a session !!!! es
     session_start();

     // Come from the user 
     if (isset($_GET['from_u'])){
          unset($_SESSION['user_id']);
          // Go to login student
          header('location:http://localhost/insea-inscription/login/login-page-etud.php');
     }

     // Come from the admin
     if (isset($_GET['from_a'])){
          unset($_SESSION['admin_id']);
          // Go to login student
          header('location:http://localhost/insea-inscription/login/login-page-admi.php');
     }
     

?>