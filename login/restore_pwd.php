<?php 
	// Start a session
	session_start();

	// Connect to db
	require '../classes/dbConnexion.php';
	require'../classes/class.phpmailer.php';
	require'../classes/class.smtp.php';
	$message = "";
	// determine the part
	if(isset($_GET['pa'])){
		$first = $_GET['pa'];
	}else{
		$first = 1;
	}
	


	if(isset($_POST['cta'])){
		$email = $_POST['email'];
		// From Login page
		// Test if the email
		$query = "SELECT * FROM `u_auth` WHERE user_email=:email";
		$stmt = $connect->prepare($query);
		$result = $stmt->execute([
			'email'=>$email
		]);
		if($stmt->rowCount() > 0){
			
			// fetch the query
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			$user_verificationCode =  $user['user_verificationCode'];
			// Store the verification code  in the session
			$_SESSION['user_verificationCode'] = $user['user_verificationCode'];
			
			// Create an SMTP object

			//Create email body
			$email = $user['user_email'];
			$message_html= '
				<head>
					<style>
					body{
						font-size: 16px;
						boxs-sizing: border-box;
						margin: 0;
						padding: 0;
						font-family: \'Roboto Mono\', monospace; 
						
			
					}
					.container {
					    width: 100%;
						height: 100vh;
						margin: 0 auto;
			
					}
					.green_bar {
						background-color: rgb(9, 180, 109);
						text-align:center;
						padding: 40px;
					}
					.green_bar a{
						color: white;
						font-size: 1.5rem;
					}
					.body{
						width: 60%;
						margin: 20px auto;
						padding: 10px;
						color: rgb(14, 156, 97);
						
					}
					.body h2 {
						color: rgb(14, 156, 97);
						text-align : center;
						font-size:1.2rem;
					}
					.body p {
						text-align : center;
					}
					.cta {
						display: block;
						width: 40%;
						margin: 30px auto;
						outline: none;
						border: none;
						padding: 15px 20px;
						font-size: 1.2rem;
						font-family: inherit;
						background-color: rgb(9, 180, 109);
						border-radius: 25px;
						cursor: pointer;
						text-decoration: none;
						color:#ffffff;
						text-align : center;
			
					}
						.small {
							color: gray;
							font-size: 0.8rem;
							text-align: left;
							width: 60%;
							margin: 20px auto;
						}

					</style>
				</head>
				<body>
					<div class="container">
						<div class="green_bar">
							<a href="">Institut National d\'Economie et de Statistique Applique</a>
						</div>
						<div class="body">
							<h2>Recuperation de votre mot-de-pass</h2>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur dolor sunt voluptatum laboriosam hic quasi in perferendis sequi aliquam vero veniam veritatis facere laborum nesciunt.</p>
						</div>

						<a class="cta" href="http://localhost/insea-inscription/login/restore_pwd.php?a_c='.$user_verificationCode.'&pa=2" target="_blank"> Changer</a>
						<p class="small">Le bouton ne fonctionne pas? Collez le lien suivant dans votre navigateur: <a href="http://localhost/insea-inscription/login/restore_pwd.php?a_c='.$user_verificationCode.'&pa=2">http://localhost/insea-inscription/login/login-page-etud.php?a_c='.$user_verificationCode.'&pa=2</a></p>
					</div>
				</body>
				</html>
				';
			// Create PHPMailer instance
			$mail =new PHPMailer();
			$mail->setFrom('no-reply@insea.ac.ma','Verification');
			$mail->addAddress($email);
			$mail->Subject = 'Email Verfication';
			$mail->isHTML(true);
			$mail->Body = $message_html; 
			
			// SMTP verification
			$mail->IsSMTP();
			
			$mail->Host = 'smtp.gmail.com';
			$mail->SMTPAuth = true ;
			$mail->SMTPSecure = 'tls';

			/*changes */
			$mail->SMTPAutoTLS = false;
			$mail->SMTPOptions = array(
				'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
				)
				);
						
			$mail->Username = 'insea.boite@gmail.com';
			$mail->Password = 'test_123456';
			$mail->Port = 587;
			/*$mail->SMTPDebug = SMTP::DEBUG_SERVER;*/
			if ($mail->Send()) {
				$_SESSION['part'] = 2;
				$message = '<p class="green"> Un lien de restauration a été envoyé à ton adresse email </p>';
			}
			else {
				$message = '<p class="alert"> Erreur,  </p>';
			}
			
			
		} else {
			$message = '<p class="alert"> Verifier votre adresse Email </p>';
		}
	}
	

	if(isset($_POST['cta_pwd'])) {
		$user_verificationCode = $_SESSION['user_verificationCode'];
		// Determine the part to show
		$first=$_SESSION['part'];
		$pwd = $_POST['pwd'];
		$re_pwd = $_POST['re_pwd'];
		if ($pwd !== $re_pwd) {
			$message = '<p class="alert"> Mots-de-pass sont incorrects</p>';
		}
		else {
			// Hash the password
			$hash_pwd = password_hash($pwd,PASSWORD_BCRYPT);
			// Update the database
				// Identify the user by its user_verificationCode from $_GET
			$query = "UPDATE `u_auth` 
               SET user_pwd = :pwd
			WHERE user_verificationCode=:a_c";
			$stmt= $connect->prepare($query);
			$result=$stmt->execute([
				':pwd'=>$hash_pwd,
				':a_c'=> $user_verificationCode
			]);
			if($stmt->rowCount() > 0){
				header("location:http://localhost/insea-inscription/login/login-page-etud?st=p_c");
			}
			else {
				$message = '<p class="alert">Erreur d\'authentification. Reessayer à plus tard </p>';
			}

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
		<?php if($first === 1) { ?>
          <div class="error-container">
               <?php echo $message ;?>
		</div>
		
          <div class="body">
			<form action="restore_pwd.php" method="post">
				<div class="input-container">
					<label for="" class="label"> Saisir votre Email : </label>
					<input type="email" class="form-input"  name="email" placeholder="Votre email" required>
				</div>
				<button class="cta-btn sign-up" name="cta"> Restaurer le mot-de-pass</button>
			</form>     
		</div>
		<?php } else {?>
			<div class="error-container">
              		<?php echo $message ;?>
			</div>
			<div class="body">
			<form action="restore_pwd.php?a_c=$user_verificationCode&pa=2" method="post">
				<div class="input-container">
					<label for="" class="label"> Saisir à nouveau mot-de-pass : </label>
					<input type="password" class="form-input"  name="pwd" placeholder="Votre nouveau mot-de-pass" required>
				</div>
				<div class="input-container">
					<label for="" class="label"> Confirmer votre nouveau mot-de-pass : </label>
					<input type="password" class="form-input"  name="re_pwd" placeholder="Confirmer le nouveau mot-de-pass" required>
				</div>
				<button class="cta-btn sign-up" name="cta_pwd"> Confimer </button>
			</form>     
		</div>
		<?php } ?>
 </body>
 </html>