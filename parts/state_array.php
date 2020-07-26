<?php 
     // Array of states
     $states = [
          // password changed
          'p_c'=>'<p class="green">Votre mot-de-pass est changé. Connectez vous maintenant</p>',
          // already verified
          'a_v'=>'<p class="blue" >Votre email est déja vérifié</p>',
          // email verified
          'e_v'=>'<p class="green" > Votre email est vérifié. Connectez vous maintenant </p>',
          // you need to log in
          'l_i'=>'<p class="blue" >Vous devez se connecter pour completer l\'inscription</p>'
     ];

     // Array of messages at student profil
     $messages=[
          // 0 ---> encore de traitement
          0 =>'<button id="notYet">En cours de taritement</button>',
          // 1 ---> admis a passer l exam
          1 =>'<button id="state">Admissible à passer l\'exam</button>',
          // 2 ---> Refused
          2 =>'<button id="refus">Demande refusée</button>',
          
     ];

     // Array of fillieres
     $fillieres=[
          'ds'=>"DATA SCIENCE",
          'dse'=>"DATA SOFTWARE ENGINEERING",
          'af'=>"ACTUARIAT FINANCE",
          'sd'=>"STATISTIQUE DEMOGRAOHIE",
          'se'=>"STATISTIQUE ECONOMIE",
          'ro'=>"RECHERCHE OPERATIONNEL"

     ];

     // Array of cycle 
     $cycle=[
          'ing'=>"CYCLE INGENIEUR",
          'doctorat'=>"CYCLE DOCTORAT",
          'master'=>"CYCLE MASTER",
     ];

     $docs_type = [
          'a_r'=>'Attestation de reussite',
          'bac'=>'Baccalaureat',
          'cin'=>'CIN',
          'photo'=>"Photo d'identite",
     ]


?>