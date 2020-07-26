<?php 

	// connect to the database 
	require('../classes/dbConnexion.php');
	require('../classes/class.phpmailer.php');
	require('../classes/class.smtp.php');


	$alert_msg="";
	if(isset($_POST["submit"])){
		//Declaring variables
		$fullName = $_POST["f_n"];
		$email = $_POST["email"];
		$pwd = $_POST["pwd"];
		$re_pwd = $_POST["re_pwd"];

		

		// Check the pwd : it will be handled by JS
		if( $pwd !== $re_pwd){
			$alert_msg='<p class="alert"> Verifier votre mot-de-pass </p>';
		}
		else {

			// Generate activation code & id for the new user
			$activation_code = md5(rand(1,1000000));
			$user_id = rand(100,1000000);
			$is_verified = "false";
			$is_signup = "false";

			// Check if the email already exists
			$query= " SELECT * FROM `u_auth` WHERE  user_email=:email ";
			$stmt=$connect->prepare($query);
			$result=$stmt->execute([
				':email'=>strtolower($email)
			]);
			// Checking 
			if ( $stmt->rowCount() > 0) {
				$alert_msg='<p class="alert"> Vous avez deja ouvrir un compte avec cet email adresse </p>';
			}
			else {
				
				// make the query
				$query="INSERT INTO `u_auth` (user_id,full_name,user_email,user_pwd,user_verificationCode,is_verified,is_signup)
						VALUES (:id,:f_n,:email,:pwd,:a_c,:verified,:signup)";
				$stmt=$connect->prepare($query);
				
				$result=$stmt->execute([
					':id'=>$user_id,
					':f_n'=>strtolower($fullName),
					':email'=>strtolower($email),
					':pwd'=>password_hash($pwd,PASSWORD_BCRYPT),
					':a_c'=>$activation_code,
					':verified'=>$is_verified,
					':signup'=>$is_signup
				]);

				//check if the execution was successful
				
				if($stmt->rowCount() > 0){
					// An Verification email has been sent to your mail box.

					// Create an SMTP object

					//Create email body
					$message_html= '    
					<head> 
					<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
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
				
					</style>
				</head>
				<body>
					<div class="container">
						<div class="green_bar">
							<a href="">Institut National d\'Economie et de Statistique Applique</a>
						</div>
						<div class="body">
							<h2> Verification de votre adresse email</h2>
							<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur dolor sunt voluptatum laboriosam hic quasi in perferendis sequi aliquam vero veniam veritatis facere laborum nesciunt.</p>
						</div>
				
						<a class="cta" href="http://localhost/insea-inscription/condidature/verified.php?activation_code='.$activation_code.'" target="_blank"> Verifier votre email</a>
						<p class="small">Le bouton ne fonctionne pas? Collez le lien suivant dans votre navigateur: <a href="http://localhost/insea-inscription/condidature/verified.php?activation_code='.$activation_code.'" target="_blank">http://localhost/insea-inscription/condidature/verified.php?activation_code='.$activation_code.'</a></p>
					</div>
				</body>
				</html>';
					// Create PHPMailer instance
					$mail =new PHPMailer();
					$mail->setFrom('no-reply@insea.ac.ma','Verification');
					$mail->addAddress($email,$fullName);
					$mail->Subject = 'Email Verfication';
					$mail->isHTML(true);
					$mail->Body = $message_html; 
					
					// SMTP verification
					$mail->IsSMTP();
					
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPAuth = true ;
					$mail->SMTPSecure = 'tls';

					/* changes*/
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

					
					// Send the email
					if(!$mail->send()) {
						$alert_msg ='<p class="alert"> Une erreur s\'est produite! Ressayer votre demande </p>';
					}
					else {
						$alert_msg ='<p class="success"> Un code de verification a été evoyée à votre adresse email </p>';
					}
				}
			}
		}
	
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Inscription en Ligne</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../styles/inscription.css">
	
</head>
<body>
	<?php require("../parts/header.php") ;?>
	<div class="container">
		<div class="first-container">
			<h1>Formulaire d'inscription</h1>
			<?php echo $alert_msg ?>
			<form  method="post" action="">
				<div class="form-container">
				
						<div class="first-col">
							<div class="input-container">
								<label class="label">Nom et Prenom</label>
								<input type="text" name="f_n" class="form-input" placeholder="Nom et Prenom" required>
							</div>
							<div class="input-container">
								<label class="label">Email</label>
								<input type="Email" name="email" class="form-input" placeholder="Email" required>
							</div>

						</div>
						<div class="second-col">
							<div class="input-container">
								<label class="label">Mot de Pass</label>
								<input type="password" name="pwd" class="form-input" placeholder="Mot de Pass" required>
							</div>
							<div class="input-container">
								<label class="label">Confirmer Mot de Pass</label>
								<input type="password" name="re_pwd" class="form-input" placeholder="Confirmer Mot de Pass" required>
							</div>
						</div>
				</div>
				<div class="cta-btn">
					<input type="submit" name="submit" value="Continuer" id="submit-btn">
				</div>
			</form>
		</div>
	</div>
</body>
</html>