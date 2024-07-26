<?php

if(isset($_GET["action"])) {
    switch($_GET["action"]) {
        case "register":

            //Si le formulaire est soumis
            if($_POST["submit"]) {
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
            }
            // par défaut j'affiche le formulaire d'inscription
            header("Location:register.php");exit;

        break;

        case "login":

            if($_POST["submit"]) {
                // Connexion à la base de données
                $pdo = new PDO("mysql:host=localhost;dbname=hash;charset=utf8", "root", "");

                // Filter les champs (faille xss)
                $email    = filter_input(INPUT_POST, "email",  FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
                $password = filter_input(INPUT_POST, "password",  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                // Si les filtres sont valides
                if($email && $password) {
                    $requete = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                    $requete->execute(["email"=> $email]);
                    $user = $requete->fetch();
                    //var_dump($user);die;
                    // est-ce que l'utilisateur existe
                    if($user) {
                        $hash = $user["password"];
                        if(password_verify($password, $hash)) {
                            $_SESSION["user"] = $user;
                            header("Location: home.php");;exit;
                        } else {
                            header("Location: login.php");exit;
                            //Message Utilisateur inconnu ou mot de passe incorrect
                        }
                    } else {
                        header("Location: login.php");exit;
                        //Message Utilisateur inconnu ou mot de passe incorrect
                    }
                }
            }

            header("Location : login.php");exit;
        break;
    }
}