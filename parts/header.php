<!DOCTYPE html>
<html>
<head>
     <style type="text/css">
          body{
               box-sizing: border-box;
               font-size: 16px;
               font-family: 'Roboto Mono', monospace; 
          }
          header {
               padding: 10px 40px;
               display: flex;
               align-items: center;
               /*box-shadow : 0 3px 6px -1px rgba(100,100,100,0.3);*/
               border-bottom : 4px solid #0a8c55; 
          }
          .logo-container{
              flex:1;
          }
          .logo-container img{
              width: 280px;
              
          }
          .nav-container {
            flex: 2;
            
          }
          .nav-links{
              display: flex;
              justify-content: space-around;
              align-items:center;
              list-style: none;
              margin :0;
              text-align:center;
          }
          .nav-link {
            text-decoration: none;
            color: inherit;
            display : block;
            font-size:0.9rem;
            text-align : center;
            min-width : 100px;
            transition: all 0.2s ease-out;
          }
          .nav-link:hover{
               transform : scale(1.1,1.1);
          }
          
     </style>

</head>
<body>
<header>
          <div class="logo-container">
               <?php if(isset($is_index)) {?>
                    <img src="./images/insea-logo.png" alt="Insea main Logo">
               <?php }else {?>
                    <img src="../images/insea-logo.png" alt="Insea main Logo">
               <?php }?>
          </div>
          <nav class="nav-container">
               <ul class="nav-links">
                    <li><a href="#" class="nav-link">Etudes</a></li>
                    <li><a href="#" class="nav-link">Compus</a></li>
                    <li><a href="#" class="nav-link">Entreprises</a></li>
                    <li><a href="#" class="nav-link">Stages</a></li>
                    <li><a href="http://localhost/insea-inscription/login/login-page-etud.php" class="nav-link">Espace<br>Etudiant</a></li>
                    <li><a href="http://localhost/insea-inscription/login/login-page-admi.php" class="nav-link">Espace<br>Administration</a></li>
               </ul>
          </nav>
     </header>
</body>
</html>