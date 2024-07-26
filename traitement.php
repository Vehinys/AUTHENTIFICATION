<?php

if(isset($_GET["action"])) {
    switch($_GET["action"]) {
        case "register" :
            $pdo = new PDO("mysql:host=localhost;dbname=hash;charset=utf8", "root", "");

            // Filtrer la saisie des champs du formulaire d'inscription
            $pseudo = filter_input(INPUT_POST,"pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email  = filter_input(INPUT_POST,"email",  FILTER_SANITIZE_FULL_SPECIAL_CHARS, 
            FILTER_VALIDATE_EMAIL);
            $pass1  = filter_input(INPUT_POST,"pass1",  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pass2  = filter_input(INPUT_POST,"pass2 ", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($pseudo && $email && $pass1 && $pass2 ){
                var_dump("ok");die;
            }

        break;
    }
}