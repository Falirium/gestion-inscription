<?php 
     // To fix image problem
     $is_index = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
     <link rel="stylesheet" type="text/css" href="./styles/main.css">
     

     <title>Welcome to INSEA website</title>
</head>
<body>
     <?php 
          require "./parts/header.php";
      ?>        
     <div id="container">
         <div id="first-col">
               <h1>LES PORTES D'INSCRIPTION A L'INSEA SONT OUVERTES</h1>
               <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem fugit dolorem animi ut? Quisquam optio aliquam corporis ipsam fugiat perferendis dolor, accusamus consequatur quos, fugit, commodi dolorum aliquid modi illo.</p>
              <div class="cta-btn">
                <a href="./condidature/inscription.php" target="_blank" class="sgn-up">INSCRIRE</a>
                <a href="./login/login-page-etud.php" target="_blank" class="sgn-in">SE CONNECTER</a>
              </div>
         </div>
         <div id="second-col">
               <img src="images/insea.png" alt="Insea Logo">
         </div>
     </div>
</body>
</html>