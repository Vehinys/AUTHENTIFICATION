<?php

if(isset($_GET["action"])) {
    switch($_GET["action"]) {
        case "register":
            // Connexion à la base de données
            $pdo = new PDO("mysql:host=localhost;dbname=hash;charset=utf8", "root", "");

            // Filtrer la saisie des champs du formulaire d'inscription
            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email  = filter_input(INPUT_POST, "email",  FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
            $pass1  = filter_input(INPUT_POST, "pass1",  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pass2  = filter_input(INPUT_POST, "pass2",  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if($pseudo && $email && $pass1 && $pass2) {
                // var_dump("ok");die;
                $requete = $pdo->prepare("SELECT * FROM user WHERE email = :email ");
                $requete->execute(["email"=>$email]);
                $user = $requete->fetch();

                if($user) {
                    header("Location: register.php");exit;
                } else {
                    // var_dump("Utilisateur inexistant");die;
                    // insertion de l'utilisateur en BDD
                    if($pass1 == $pass2 && strlen($pass1) >= 5) {
                        $insertUser = $pdo->prepare("INSERT INTO user(pseudo, email, password) VALUES (:pseudo, :email, :password)");
                        $insertUser -> execute([
                            "pseudo"   => $pseudo,
                            "email"    => $email,
                            "password" => password_hash($pass1,PASSWORD_DEFAULT)
                        ]);
                        header("Location:login.php");exit;

                    } else {
                            // Message : " Les mos de passe ne sont pas identiques ou mot de passe trop court !
                    }
                }

            } else {
                // Message : Probleme de saisie dans les champs de formulaire
            }

        break;

        case "login":
            // Connexion à l'application
        break;
    }
}