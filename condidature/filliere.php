<?php 
     // start session
     session_start();
     // connexion db
     require '../classes/dbConnexion.php';
     $user_id = $_SESSION['user_id'];

     if(isset($_GET['part'])){
          $part=(int)$_GET['part'];
     }else{
          $part=1;
     }
     // cy ---> cycle
     if(isset($_GET['cy'])){
          $cycle = $_GET['cy'];
          $_SESSION['cy']=$cycle;

     }
     // fi ---> filliere
     if(isset($_GET['fi'])){
          $filliere = $_GET['fi'];
          $cycle=$_SESSION['cy'];
          echo $filliere." ".$cycle;
          $query = "INSERT INTO `user_infos` (user_id,user_cycle,user_filliere)
                    VALUES (:u_i,:u_c,:u_f)";
          $stmt = $connect->prepare($query);
          $result = $stmt->execute([
               'u_i'=>$user_id,
               'u_c'=>$cycle,
               'u_f'=>$filliere
          ]);
          if($stmt->rowCount() > 0){
               //update user db is_signup to true
               $is_signup = 'true';
               $query = "UPDATE `u_auth`
                         SET is_signup = :i_s
                         WHERE user_id=:u_i";
               $stmt = $connect->prepare($query);
               $result = $stmt->execute([
                    ':i_s'=>$is_signup,
                    ':u_i'=>$user_id
               ]);
               if($stmt->rowCount() > 0){
                    // Redirect to final-page
                    header("location:http://localhost/insea-inscription/condidature/final-page.php");
               }
               else {
                    // PRINT ERROR message
                    echo " Can t update data base";
               }
          }else {
               // PRINT ERROR message
               echo " Can t find something in data base";
          }
     }
     
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inscription en Ligne</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../styles/filliere.css">

</head>
<body>
	<?php require '../parts/header.php';?>
	<div class="container">
          <?php if ($part === 1) {?>
          <!-- First part of Cycle -->
		<div class="first-container">
			<h1>Choisir le cycle que vous voulez intégrer</h1>
			
			<form  method="post" action="" >
				<div class="form-container">
                         <a href="http://localhost/insea-inscription/condidature/filliere.php?cy=master&part=2">
                              <div class="cycle" tabindex="1">
                                   <img id="analytics" src="../images/analytics.png" alt="">
                                   <span class="title">MASTER</span>
                              </div>
                         </a>
                         <a href="http://localhost/insea-inscription/condidature/filliere.php?cy=doctorat&part=2">
                              <div class="cycle" tabindex="1">
                                   <img id="searcher" src="../images/searcher.png" alt="">
                                   <span class="title">DOCTORAT</span>
                              </div>
                         </a>
                         <a href="http://localhost/insea-inscription/condidature/filliere.php?cy=ing&part=2">
                              <div class="cycle" tabindex="1">
                                   <img id="servers" src="../images/servers.png" alt="">
                                   <span class="title">INGENIEUR</span>
                              </div>
                         </a>
				</div>
				
			</form>
          </div>
          <?php } else if ($part === 2) {?>

          <!-- Second part of Cycle -->
          <div class="first-container">
			<h1>Choisir la fillière que vous voulez intégrer</h1>
			
			<form  method="post" action="filliere.php" >
				<div class="form-container">
               
                         <a href="http://localhost/insea-inscription/condidature/filliere.php?fi=ds&part=2">
                              <div class="cycle" tabindex="1">
                                   <img id="data" src="../images/data-mining.png" alt="">
                                   <span class="title data">DATA SCIENCE</span>
                              </div>
                         </a>   
                         
                         <a href="http://localhost/insea-inscription/condidature/filliere.php?fi=ro&part=2">
                              <div class="cycle" tabindex="1">
                                   <img id="directional" src="../images/directional-sign.png" alt="">
                                   <span class="title">RECHERCHE<br>OPERATIONNEL</span>
                              </div>
                         </a>
                         <a href="http://localhost/insea-inscription/condidature/filliere.php?fi=se&part=2">
                              <div class="cycle" tabindex="1">
                              
                                   <img id="interest" src="../images/interest-rate.png" alt="">
                                   <span class="title">STATISTIQUE<br>ECONOMIE</span>
                              </div>
                         </a>
                         <a href="http://localhost/insea-inscription/condidature/filliere.php?fi=sd&part=2">
                              <div class="cycle" tabindex="1">
                              
                                   <img id="population" src="../images/population.png" alt="">
                                   <span class="title">STATISTIQUE<br>DEMOGRAOHIE</span>
                              </div>
                         </a>
                         <a href="http://localhost/insea-inscription/condidature/filliere.php?fi=af&part=2">
                              <div class="cycle" tabindex="1">
                              
                                   <img id="profits" src="../images/profits.png" alt="">
                                   <span class="title">ACTUARIAT<br>FINANCE</span>
                              </div>
                         </a>
                         <a href="http://localhost/insea-inscription/condidature/filliere.php?fi=dse&part=2">
                              <div class="cycle" tabindex="1">
     
                                   <img id="programming" src="../images/programming.png" alt="">
                                   <span class="title">DATA SOFTWARE<br>ENGINEERING</span>
                              </div>
                         </a>
				</div>
				
			</form>
          </div>
          <?php }?>
	</div>
</body>
</html>