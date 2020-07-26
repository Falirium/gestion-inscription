<?php
     // start session
     session_start();


     $message = "";
     require '../parts/state_array.php';
     require '../classes/dbConnexion.php';
     if(isset($_GET['st'])){
          $state = $_GET['st'];
          $message = $states[$state];
     }

     if(isset($_POST['cnx'])){
          

          
          // Get data
          $input_email = $_POST['email'];
          $input_pwd = $_POST['password'];
          // Verifying .... 
          $query = "SELECT * FROM `u_auth` WHERE user_email=:email";
          $stmt = $connect->prepare($query);
          $result=$stmt->execute([
               ':email'=>$input_email
          ]);
          if($stmt->rowCount()>0){
               // Fetch user input
               $user = $stmt->fetch(PDO::FETCH_ASSOC); 
               if(password_verify($input_pwd,$user['user_pwd'])){
                    // Check if the user is completed sign up
                    $_SESSION['user_id']= $user['user_id'];
                    // Rember user id for profil page  | upload page
                    if( $user['is_signup'] ==='true'){
                         
                         header('location:http://localhost/insea-inscription/profil/profil-page-stu.php');
                    }else{
                         
                         header('location:http://localhost/insea-inscription/condidature/upload-page.php');
                    }
               }
               else{
                    $message = '<p class="alert">les informations d\'identification invalides</p>';
               }
          }else{
               $message = '<p class="alert">les informations d\'identification invalides</p>';
          }
     }

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
     <link rel="stylesheet" type="text/css" href="../styles/login.css">
     

     <title>Welcome to INSEA website</title>
</head>

<body>
     <?php 
          require('../parts/header.php'); 
      ?>        
     <div id="container">
          <div class="first-container">
               <h1>Espace étudiant</h1>
               <div class="img-container">
                    <img src="../images/user.png" alt="user">    
               </div>
          </div>
          <div class="error-container">
               <?php echo $message ;?>
          </div>
          <form action="login-page-etud.php" method="post">
          <div class="body">
               
                    <div class="input-container">
                         <label for="" class="label"> Email : </label>
                         <input type="email" class="form-input" placeholder="Votre email"  name="email" required>
                    </div>
                    <div class="input-container" >
                         <label for="" class="label"> Mot de Pass : </label>
                         <input type="password" class="form-input" placeholder="Votre mot de pass" name="password" required>
                    </div>
                    <a href="http://localhost/insea-inscription/login/restore_pwd.php">Vous avez oublié votre mot de pass ?</a>
                    <input type="submit" value="Connexion" class="cta-btn sign-up" name="cnx">
               
          </div>
          </form>
          <div class="form-footer">
               <hr>
               <label for="" id="head-info">où bien</label>
               <button class="cta-btn"> Déposer votre condidature </button>
               

          </div>
     </div>
</body>
</html>