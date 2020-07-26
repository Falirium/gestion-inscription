<?php 
     require "../classes/dbConnexion.php";
     if(isset($_GET["activation_code"])){
          $activation_code = $_GET["activation_code"];
          $query = "SELECT * FROM `u_auth` WHERE user_verificationCode=:a_c";
          $stmt = $connect->prepare($query);
          $result = $stmt->execute([
               ':a_c'=>$activation_code
          ]);
          if($stmt->rowCount() > 0) {
               // check if the user is already virefied his email
               $user = $stmt->fetch(PDO::FETCH_ASSOC);
               if($user['is_verified'] === 'true'){
                    //  st -----> state |   a_v ----> alreday verified
                    header("Location:http://localhost/insea-inscription/login/login-page-etud?st=a_v");
               }
               else {
                    $is_verified = "true";
                    $query = "UPDATE `u_auth` 
                    SET is_verified = :verified
                    WHERE user_verificationCode=:a_c";
                    $stmt = $connect->prepare($query);
                    $result = $stmt->execute([
                         ':verified'=>$is_verified,
                         ':a_c'=>$activation_code
                    ]);
                    if($stmt->rowCount() > 0) {
                         // st -----> state   |   e_v ----> email verified
                         header("Location:http://localhost/insea-inscription/login/login-page-etud?st=e_v");
                    }
                    else {
                         // Verification failed message
                         echo "Verification failed, please try later";
                    }
               }
          }
          else {
               echo 'You have already  verify you email';
          }

     }
     else {
          // Maybe change the URL
          echo "erroor";
     }
?>





charmecharme.