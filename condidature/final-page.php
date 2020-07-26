<?php
     // start session
     session_start();
     if(isset($_SESSION['user_id'])){

     }else{
          // rediret to the login page
          header("location:http://localhost/insea-inscription/login/login-page-etud.php?st=l_i");
     }

?>
<!DOCTYPE html>
<html>
<head>
	<title>Inscription en Ligne</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../styles/final.css">
	
</head>
<body>
	<?php require '../parts/header.php';
	
	?>
	<div class="container">
		<div class="first-container">
			<div class="checked-container">
				<img src="../images/checked.png" alt="checked">
               </div>
               <h1>Félicitation</h1>
               <div class="body-container">
                    <p>
                         Félicitation !! votre demande a été bien engegistée. On vous communique la date 
                         de passage d'examan aprés la vérification de votre considature.
                    </p>
                    <p>
                         Vous trouverez toutes les informations nécessaires dans votre espace étudiant.Vous devez 
                         le consulter à chaque fois.
                    </p>
               </div>
               <div class="cta-btn">
                    <a href="http://localhost/insea-inscription/login/login-page-etud.php">
                         <button id="sign-up">Connectez vous à votre espace étudiant</button>
                    </a>
               </div>
			
		</div>
	</div>
</body>
</html>