<?php 
	// start session
	session_start();
	if (isset($_SESSION['admin_id'])){
		$admin_id=$_SESSION['admin_id'];
		// connect to db
		require '../classes/dbConnexion.php';



		// fetching admin infos
		$query = "SELECT * FROM `admin_auth` WHERE admin_id=:admin_id";
		$stmt= $connect->prepare($query);
		$result=$stmt->execute([
			':admin_id'=>$admin_id
		]);
		if($stmt->rowCount()>0){
			// Fetching admin data
			$admin = $stmt->fetch(PDO::FETCH_ASSOC);
			$full_name = explode(" ",$admin['admin_name']);
			$admin_first_name = $full_name[0] ;
			(count($full_name)<3) ? $admin_last_name = $full_name[1]:$admin_last_name = $full_name[1].$full_name[2];
			$admin_work = $admin['admin_work'];
		}else{
			// Sortir de la session + demander d'identifier une autre fois
		}
	}else{
		// out to login page
		session_destroy();
		header('location:http://localhost/insea-inscription/login/login-page-admi.php');
	}


	// Fetching demandes number from u_auth table
	$query = "SELECT COUNT(user_id) AS not_validé FROM `u_auth` WHERE is_validate=:validate";
	$stmt= $connect->prepare($query);
	$result=$stmt->execute([
		':validate'=>0
	]);
	if($stmt->rowCount()>0){
		$no_validé = $stmt->fetch(PDO::FETCH_ASSOC);
		$nbr_no_validé = $no_validé['not_validé'];
	}
	else {
		// Ask to re authenticate in order to fix the problem
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Inscription en Ligne</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../styles/profil.css">
	
</head>
<body>
	<?php require("../parts/header.php") ;?>
	<div class="container">
			<div class="second-container">
				<div class="main">
					<div class="first-part">
						<!-- <div class="item">
							<span class="label">ETAT DE CONDIDATURE :</span>
							<button class="state">Admissible à passer l'exam</button>
						</div>
						<div class="item">
							<span class="label">NOMBRE TOTAL DE CONDIDATURE :</span>
							<span class="number"> 555 </span>
                              </div> -->
                              <h1>LISTE DES CONDIDATURES A CONFIRMER<br>POUR L'ANNEE UNIVERSITAIRE<br>2020/2021</h1>
                              <div class="hr"><hr></div>
                              <div class="infos-container">
                                   <div class="p-container">
								<p>** Les demandes à confirmer **</p>
								<?php echo '<span class="number">'.$nbr_no_validé.'</span>';?>
                                   </div>
                                   <a href="http://localhost/insea-inscription/profil/validation-page-admin.php" class="cta consulter">CONSULTER LES DEMANDES</a>
                              </div>
					</div>
				</div>
				<div class="profil">
					<div class="img"></div>
					<div class="info-bar info-admin"><?php echo $admin_first_name?> </div>
					<div class="info-bar info-admin"><?php echo $admin_last_name?></div>
					<div class="info-bar info-admin"><?php echo $admin_work?></div>
					<div class="btns">
						<a href="./logout.php?from_a=AZD09844" class="cta logout">Quitter</a>
					</div>	
					
				</div>
		</div>
	</div>
	<?php require '../parts/footer.php' ;?>
</body>
</html>