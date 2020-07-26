<?php
	// start session
	session_start();
	// Import db user
	require '../classes/dbConnexion.php';
	require '../parts/state_array.php' ;
	// Import db docs
	$message='';
	// Remember the user
	if(isset($_SESSION['user_id']) ) {
		if (isset($_POST['submit'])) {
			$user_id = $_SESSION['user_id'];

			$query = "SELECT * FROM `u_auth` WHERE user_id=:user_id";
			$stmt= $connect->prepare($query);
			$result=$stmt->execute([
				':user_id'=>$user_id
			]);
			if($stmt->rowCount()>0){
				// Create variables for $_FILES
				// Define a max size
				define('MAX_FILE_SIZE',6000000);
				$allowed_extensions = ['jpg','pdf','jpeg','png'];
				$uploaddir = '../docs/';
				$failed = true;

				// use a loop
				// $fileNameAttribute ----->the input name attribute | $fileProps---> the file properties
				
				// This variable helps to insert docs infos to database
				$passed = false;
				// compteur
				$i = 0;
				foreach($_FILES as $fileNameAttribute=>$fileProps){
					
					$fileName = $fileProps['name'];
					$fileSize = $fileProps['size'];
					$fileTempName = $fileProps['tmp_name'];
					$fileError = $fileProps['error'];
					$ext = explode(".",$fileName);
					$fileExtension = strtolower(end($ext));
					
					//check if the extension is respected
					if(in_array($fileExtension,$allowed_extensions)){
						echo 'true';
						// Check if the file has some error
						if($fileError === 0){
							if($fileSize < MAX_FILE_SIZE){

								$passed = true;
								$docs_array[$i] = [
									'fileNameAttribute' => $fileNameAttribute,
									'fileSize' => $fileSize,
									'fileExtension' => $fileExtension,
									'fileTempName' =>$fileTempName
								];
								// Increment the compteur
								$i=$i+1;

							}else{
								// Input $message error
								$message='<span class="alert"> Les fichiers sont trop volumineux ! Ne dépassez pas 6 MB ,Merci de réesayer </span>';
								$passed = false;
								break;
							}
						}else{
							// Input $message error
							$message='<span class="alert"> Les fichiers sont erronées ! Merci de réesayer </span>';
							$passed = false;
							break;
						}
					}else{
						// Input $message error
						$message='<span class="alert"> Respectez les formats des fichiers autorisés</span>';
						$passed = false;
						break;
					}

				// EOL
				}
				
				// Insert docs_array to database
				if($passed){
					foreach($docs_array as $index=>$fileProps){
						// Generate the new file name
						$fileNewName = $uploaddir.$user_id."_".$fileProps['fileNameAttribute'].".".$fileProps['fileExtension'];
						if(move_uploaded_file($fileProps['fileTempName'],$fileNewName)){
							//Insert this files into the database
							$query = "INSERT INTO `user_docs` (docs_name,user_id,docs_type,docs_size)
									VALUES (:d_n,:u_i,:d_t,:d_s)";
							$stmt= $connect->prepare($query);
							$result=$stmt->execute([
								':d_n'=>$fileNewName,
								':u_i'=>$user_id,
								':d_s'=>$fileProps['fileSize'],
								':d_t'=>$docs_type[$fileProps['fileNameAttribute']]
							]);
							if($stmt->rowCount()>0){
								// change $failed
								$failed = false;
							}
						}else{
							// Input $message error
							$message='<span class="alert"> Probleme dans le systeme ! Merci de réesayer une autre fois </span>';
						}
					}
				}
				// Passer à la page de choisir la filliere
				if(!$failed) {
					// HTTP de la page filliere.php
					header("location:http://localhost/insea-inscription/condidature/filliere.php");
				}
				else {
					// Input $message error
					$message='<span class="alert">Vous devez respecter les consignes demandés </span>';
				}

			}
			else {
				// Afficher un erreur
				// Si il y a un erreur dans lexecution de la requete
			}
		}
	}
	else {
		//Ask the user to login in
		header("location:http://localhost/insea-inscription/login/login-page-etud.php?st=l_i");
	}
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Inscription en Ligne</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../styles/upload.css">

</head>
<body>
	<?php require '../parts/header.php';?>
	<div class="container">
		<div class="first-container">
			<h1>Etape de documents</h1>
			<div class="alert-container">
				<img src="../images/alert.png" alt="alert">
				<h2 class="alert-note">Les documents à importer doivent etre en format de PDF,PNG,JPEG,JPG</h2>

			</div>
			<div class="error-container">
               	<?php echo $message ;?>
          	</div>
			<form  method="post" action="upload-page.php" enctype="multipart/form-data">
				<div class="form-container">
						<div class="first-col">
							<div class="input-container">
								<label class="label">Copie de CIN </label>
								<label for="" class="form-input">
									<input type="file" name="cin" required>
								</label>

							</div>
							<div class="input-container">
								<label class="label">Copie de Baccalaureat</label>
								<label for="" class="form-input">
									<input type="file" name="bac" required>
								</label>
							</div>

						</div>
						<div class="second-col">
							<div class="input-container">
								<label class="label">Attestation de réussite </label>
								<label for="" class="form-input">
									<!-- a_r  attestation de reussite -->
									<input type="file" name="a_r">
								</label>
							</div>
							<div class="input-container">
								<label class="label">Photo d'identité</label>
								<label for="" class="form-input">
									<input type="file" name="photo" required>
								</label>
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