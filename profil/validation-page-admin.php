<?php
     // start session
	session_start();
     
	$admin_id=$_SESSION['admin_id'];
	// connect to db
     require '../classes/dbConnexion.php';
     require '../parts/state_array.php';

     $message = '';

     // Loading page : verifiaction page
     if( ( isset($_GET['page']) AND isset($_GET['v_c']) ) OR ( isset($_POST['rejected']) OR isset($_POST['rejected_send']) OR isset($_POST['valider']) )){
          $page=2;
          
          // Remember user id when changing page and clicking on btns
          
          if( isset($_GET['v_c']) ) {
               $user_id=$_GET['v_c'];
               $_SESSION['v_c']=$_GET['v_c'];
          }else{
               $user_id=$_SESSION['v_c'];
          }

          // Fetching ONE user docs
          $query = "SELECT * FROM `user_docs` WHERE user_id=:v_r";
          $stmt= $connect->prepare($query);
          $result=$stmt->execute([
               ':v_r'=>$user_id
          ]);
          if($stmt->rowCount()>0){
               $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
               
          }else{
               // Sortir de la session + demander d'identifier une autre fois
          }

          // Fetching ONE user auth
          $query = "SELECT * FROM `u_auth` WHERE user_id=:id";
          $stmt= $connect->prepare($query);
          $result=$stmt->execute([
               ':id'=>$user_id
          ]);
          if($stmt->rowCount()>0){
               $us = $stmt->fetch(PDO::FETCH_ASSOC);
              
               
          }else{
               // Sortir de la session + demander d'identifier une autre fois
          }

          function get_user_docs_spec($spec_doc){
               //Global var
               global $connect;
               global $user_id;
               // Fetch ONE user docs
               $query = "SELECT * FROM `user_docs` WHERE user_id=:v_r AND docs_type=:d_t";
               $stmt= $connect->prepare($query);
               $result=$stmt->execute([
                    ':v_r'=>$user_id,
                    ':d_t'=>$spec_doc
               ]);
               if($stmt->rowCount()>0){
                    return $user_doc = $stmt->fetch(PDO::FETCH_ASSOC);
                    
               }else{
                    // Sortir de la session + demander d'identifier une autre fois
                    return [];
               }
          }

          // For the rejected message container
          $rejected= false;
          if(isset($_POST['rejected'])){
               $rejected= true;
          }
          if(isset($_POST['rejected_send'])){
               // Chnage the is_validated to 0 and is_eligibale to 2

               $i_v = 0;
               $i_e = 2;
               $reject_msg = $_POST['message-de-reject'];

               $query = "UPDATE `u_auth`
                         SET is_eligible=:i_e , is_validate=:i_v , msg=:reject_msg
                         WHERE user_id=:id
               ";
	          $stmt= $connect->prepare($query);
               $result=$stmt->execute([
                    ':id'=>$user_id,
                    'i_v'=>$i_v,
                    'i_e'=>$i_e,
                    ':reject_msg'=>$reject_msg
               ]);
               if($stmt->rowCount()>0){
                    // header to the la liste des condidats
                    header('location:http://localhost/insea-inscription/profil/profil-page-admi.php');
               }else{
                    // Print an error msg to try again
               }
               
               
               
          }
          if(isset($_POST['valider'])){
               // Chnage the is_validated to 1 and is_eligibale to 1
               $i_v = 1;
               $i_e = 1;

               $query = "UPDATE `u_auth`
                         SET is_eligible=:i_e , is_validate=:i_v 
                         WHERE user_id=:id
               ";
	          $stmt= $connect->prepare($query);
               $result=$stmt->execute([
                    ':id'=>$user_id,
                    'i_v'=>$i_v,
                    'i_e'=>$i_e,
                    
               ]);
               if($stmt->rowCount()>0){
                    // header to the la liste des condidats
                    header('location:http://localhost/insea-inscription/profil/profil-page-admi.php');
               }
          }
          

     }
     else{
          $page=1;
     }

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
     
     // Fetching non validated condidate from u_auth table
     $query = "SELECT user_id,full_name FROM `u_auth` WHERE is_validate=:validate AND is_signup=:signup";
	$stmt= $connect->prepare($query);
	$result=$stmt->execute([
          ':validate'=>0,
          ':signup'=>'true'
	]);
	if($stmt->rowCount()>0){
          $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
     }else{
		// Sortir de la session + demander d'identifier une autre fois
     }

     
     
     // a function to get the user infos: filliere cycle
     function get_user_info($id){
          global $connect;
          
          // Fetching filliere et cycle from user_infos table
          $query = "SELECT user_cycle,user_filliere FROM `user_infos` WHERE user_id=:user_id" ;
          $stmt= $connect->prepare($query);
          $result=$stmt->execute([
               ':user_id'=>$id
          ]);
          if($stmt->rowCount()>0){
               // Fetch ONLY one line
               $user_infos = $stmt->fetch(PDO::FETCH_ASSOC);
               return $user_infos;
          }else{
               return [];
          }
     }

     

?>
<!DOCTYPE html>
<html>
<head>
	<title>Inscription en Ligne</title>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../styles/validate.css">
	
</head>
<body>
	<?php require("../parts/header.php") ;?>
	     <div class="container">
			
                    <?php if($page == 1) { ?>
                         <div class="second-container">
                              <div class="main">
                                   <div class="first-part">
                                        <h1>LISTE DES CONDIDATURES</h1>
                                        <table style="width:100%">
                                             <tr>
                                                  <th>Matricule</th>
                                                  <th>Nom & Prenom</th>
                                                  <th>Cycle</th>
                                                  <th>Filliere</th>
                                                  <th>Action</th>
                                             </tr>
                                             <?php 
                                                  foreach($users as $key=>$value) {
                                                  // get this user row in user_infos
                                                  
                                                  $user_infos = get_user_info($value['user_id']);     
                                             ?>
                                                  
                                                  <tr>
                                                       <td><?php echo $value['user_id']?></td>
                                                       <td><?php echo strtoupper($value['full_name'])?></td>
                                                       <td><?php echo strtoupper($user_infos['user_cycle'])?> </td>
                                                       <td><?php echo strtoupper($user_infos['user_filliere'])?></td>
                                                       <td><?php echo '<a class ="btn-consulter" href="http://localhost/insea-inscription/profil/validation-page-admin.php?page=2&v_c='.$value['user_id'].'">CONSULTER</a>';?></td>
                                                  </tr>

                                             <?php }?>
                                             
                                        </table>
                                        
                                        
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
                    <?php } else if($page == 2) { ?>
                         <div class="second-container" id="second-container-2">
                              <div class="main" id="main-2">
                                   <h1>LES INFORMATIONS DU CONDIDAT</h1>
                                   <div class="first-part-2">
                                       
                                        <div class="first-col-2">
                                             <div class="cover-container">
                                                  <?php 
                                                       // $doc stands for one row of specefic document from user_docs table
                                                       $doc = get_user_docs_spec($docs_type['photo']);
                                                       echo '<img style="object-fit:cover"src="'.$doc["docs_name"].'" alt="Profil image">';
                                                  ?>
                                             </div>
                                             <?php 
                                                  // $us ---> stands for one row of  u_auth table
                                                  
                                                  $us_info = get_user_info($user_id);
                                             ?>
                                             <div class="text-container">
                                                  <span class="title">Nom et Prenom</span>
                                                  <span class="input-from-db"><?php echo strtoupper($us['full_name']) ?></span>
                                             </div>
                                             <div class="text-container">
                                                  <span class="title">Cycle</span>
                                                  <span class="input-from-db"><?php echo strtoupper($us_info['user_cycle']) ?></span>
                                             </div>
                                             <div class="text-container">
                                                  <span class="title">Fillière</span>
                                                  <span class="input-from-db"><?php echo strtoupper($fillieres[$us_info['user_filliere']])?></span>
                                             </div>

                                        </div>
                                        <div class="second-col-2">
                                             <div class="case-container">
                                                  <span class="title-type-2">Attestation de<br>réussite</span>
                                                  <?php 
                                                       $doc = get_user_docs_spec($docs_type['a_r']);
                                                       echo '<a class="href-consulter" href="'.$doc['docs_name'].'" target="_blank">CONSULTER</a>'; 
                                                  ?>
                                             </div>
                                             <div class="case-container">
                                                  <span class="title-type-2">CIN </span>
                                                  <?php 
                                                       $doc = get_user_docs_spec($docs_type['cin']);
                                                       echo '<a class="href-consulter" href="'.$doc['docs_name'].'" target="_blank">CONSULTER</a>' ;
                                                  ?>
                                             </div>
                                             <div class="case-container">
                                                  <span class="title-type-2">Copie de <br>Baccalaureat</span>
                                                  <?php 
                                                      $doc = get_user_docs_spec($docs_type['bac']);
                                                      echo '<a class="href-consulter" href="'.$doc['docs_name'].'" target="_blank">CONSULTER</a>' ;
                                                  ?>
                                             </div>
                                             <div class="case-container">
                                                  <span class="title-type-2">Photo d'identité</span>
                                                  <?php 
                                                       $doc = get_user_docs_spec($docs_type['photo']);
                                                       echo '<a class="href-consulter" href="'.$doc['docs_name'].'" target="_blank">CONSULTER</a>' ;
                                                  ?>
                                             </div>
                                        </div>

                                   </div>
                                   <div class="second-part">
                                        <form action="validation-page-admin" method="post">
                                             <input type="submit" class="btn green" value="VALIDER" name="valider">
                                             <input type="submit" class="btn red" value="REFUSER" name="rejected">
                                        </form>
                                        
                                   </div>
                                   <!-- the rejected message container -->
                                   <?php if($rejected){?>
                                        <div class="message-rejected-container">
                                             <form action="validation-page-admin.php" method="post" id="reject-msg">
                                                  <textarea rows="4" cols="50" name="message-de-reject" form="reject-msg">Enter le motif de rejet ici...</textarea>
                                                  <input class="btn-2 green" type="submit" name="rejected_send" value="ENVOYER">
                                             </form>
                                        </div>
                                   <?php } ?>
                              </div>
                              
                              
                         </div>
                    <?php }?>
				<!--<div class="profil">
					<div class="img"></div>
					<div class="info-bar info-admin"><?php echo $admin_first_name?> </div>
					<div class="info-bar info-admin"><?php echo $admin_last_name?></div>
					<div class="info-bar info-admin"><?php echo $admin_work?></div>
					<div class="btns">
						<button class="cta logout">Quitter</button>
					</div>	
					
				</div>-->
		     </div>
	     </div>
	<?php require '../parts/footer.php' ;?>
</body>
</html>