<?php 
	// start session
	session_start();

	$user_id=$_SESSION['user_id'];
	// connect to db
	require '../classes/dbConnexion.php';
	require '../parts/state_array.php';
	
	// Fetch user row in db
	$query = "SELECT * FROM `u_auth` WHERE user_id=:user_id";
	$stmt= $connect->prepare($query);
	$result=$stmt->execute([
		':user_id'=>$user_id
	]);
	if($stmt->rowCount()>0){
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		// Display the message of the user is eligibale to pass the exam
		$state = (int)$user['is_eligible'];
		$full_name = explode(' ',$user['full_name']) ;
		$user_first_name = strtoupper($full_name[0]);
		$user_last_name = strtoupper(end($full_name));
	}
	// Nombre de condidature cette annee
	$nombre_condidature = 0;
	$query = "SELECT COUNT(user_id) AS nbr FROM `u_auth` ";
	$stmt= $connect->prepare($query);
	$result=$stmt->execute();
	if($stmt->rowCount()>0){
		$count= $stmt->fetch(PDO::FETCH_ASSOC);
		$nombre_condidature = $count['nbr'];
	}
	// Determiner la filliere et le cycle 
	$query = "SELECT * FROM `user_infos` WHERE user_id=:u_i ";
	$stmt= $connect->prepare($query);
	$result=$stmt->execute([
		':u_i'=>$user_id
	]);
	if($stmt->rowCount()>0){
		$cy_and_fi = $stmt->fetch(PDO::FETCH_ASSOC);
		$user_cycle = $cycle[$cy_and_fi['user_cycle']];
		$user_filliere = $fillieres[$cy_and_fi['user_filliere']];
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Inscription en Ligne</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../styles/profil.css">
	<link rel="stylesheet" type="text/css" href="../styles/profil-student.css">
	
</head>
<body>
	<?php require("../parts/header.php") ;?>
	<div class="container">
		
			
			<div class="second-container">
				<div class="main">
					<div class="first-part">
						<div class="item">
							<span class="label">ETAT DE VOTRE<br>CONDIDATURE<br></span>
							<?php echo $messages[$state]?>
							
						</div>
						<div class="item">
							<span class="label">NOMBRE TOTAL DE CONDIDATURE POUR L'ANNEE 2020/2021</span>
							<span class="number-page1"> <?php echo $nombre_condidature ;?> </span>
						</div>
						<div class="hr"><hr></div>
						<div class="second-part" id="second-part">
							<?php if ($state == 0) {?>
							<!-- En cours de taritement -->
							<div class="message-container e_d_t">
								<p>Votre demande est en cours de traitement.Au dela de 7jours, on vous repondra à votre demande d'inscription.
									Merci de votre patience.
								</p>
							</div>
							<?php } else if  ($state == 2){ ?>
							<!-- Refus -->
							<div class="message-container refused">
								<p>Votre demande de condidature a été refusé. Vous trouverez ci-dessus le motif de refus : 
									<br><br><span> <?php echo '*'.$user['msg']?></span>
									<br><a href="#">Refaire la demande de condidature</a>
								</p>
							</div>
							<?php } else if  ($state == 1){ ?>

							<!-- Accepté -->
							<div class="exam-info">
								<div class="box">
									<div class="head-box">
										<img  id="place" src="../images/place.png" alt=""> 
										<span class="title">Centre d'examan</span>
									</div>
									<p>Rabat</p>
								</div>
								<div class="box">
									<div class="head-box">
										<img id="calendar" src="../images/calendar.png" alt=""> 
										<span class="title">Date d'examan</span>
									</div>
									<p>11-09-2020</p>
								</div>
								<div class="box">
									<div class="head-box">
										<img id="aide" src="../images/aide.png" alt=""> 
										<span class="title">Besion d'aide?</span>
									</div>
									<p>Rabat</p>
								</div>
							</div>
							<?php }  ?>
							

						</div>
					</div>
					
				</div>
				<div class="profil">
					<div class="img"></div>
					<div class="info-bar"><?php echo $user_first_name?></div>
					<div class="info-bar"><?php echo $user_last_name?></div>
					<div class="info-bar"><?php echo $user_cycle?></div>
					<div class="info-bar"><?php echo $user_filliere?></div>
					<div class="btns">
						
						<a href="./logout.php?from_u=AZD09844" class="cta logout">Quitter</a>
					</div>	
					
				</div>
				
				
		
			
		</div>
	</div>
	<?php require '../parts/footer.php' ;?>
</body>
</html>