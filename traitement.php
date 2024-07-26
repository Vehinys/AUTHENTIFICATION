<?php
session_start();

if(isset($_GET["action"])) {
    switch($_GET["action"]) {
        case "register":
            // si le formulaire est soumis
            if($_POST["submit"]) {
                // connexion à la base de données
                $pdo = new PDO("mysql:host=localhost;dbname=php_hash;charset=utf8", "root", "");

                // filtrer la saisie des champs du formulaire d'inscription
                $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, "email",FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
                $pass1 = filter_input(INPUT_POST, "pass1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $pass2 = filter_input(INPUT_POST, "pass2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    
                if($pseudo && $email && $pass1 && $pass2) {
                    // var_dump("ok");die;
                    $requete = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                    $requete->execute(["email" => $email]);
                    $user = $requete->fetch();
                    // si l'utilisateur existe
                    if($user) {
                        header("Location: register.php"); exit;
                    } else {
                        //insertion de l'utilisateur en BDD
                        if($pass1 == $pass2 && strlen($pass1) >= 5) { // vérification que les 2 mots de passes sont identiques, et qu'il a un minimum de caractères
                            $insertUser = $pdo->prepare("INSERT INTO user (pseudo, email, password) VALUES (:pseudo, :email, :password)");
                            $insertUser->execute([ // insertion de l'utilisateur dans la BDD avec une requête préparée (pour éviter injectrions sql)
                                "pseudo" => $pseudo,
                                "email" => $email,
                                "password" => password_hash($pass1, PASSWORD_DEFAULT) // on stocke le mot de passe hashé en base de donnée. 
                            ]);
                            header("Location: login.php"); exit; // prendre l'habitude de faire un exit après une redirection avec la méthode header();
                        } else {
                            // message "Les mots de passe ne sont pas identiques ou mot de passe trop court !"
                        }
                    }
                } else {
                    // problème de saisie dans les champs de formulaire
                }
            }
            // par défaut j'affiche le formulaire d'inscription
            header("Location: register.php"); exit;

        break;

        case "login":
            //connexion à l'application

            if($_POST["submit"]) { // si le formulaire est soumis
                // connexion à la base de données
                $pdo = new PDO("mysql:host=localhost;dbname=php_hash;charset=utf8", "root", "");

                // filtrer les champs (faille XSS)
                $email = filter_input(INPUT_POST, "email",FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL); // filtre pour lutter contre la faille XSS
                $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                //on vérife que les filtres sont valides
                if($email && $password) {
                    $requete = $pdo->prepare("SELECT * FROM user WHERE email = :email"); // requete préparée pour lutter contre la faille d'injection SQL
                    $requete->execute(["email" => $email]);
                    $user = $requete->fetch();
                    //var_dump($user); die;

                    // si l'utilisateur existe
                    if($user) {
                        $hash = $user["password"]; // on récupère le mot de passe haché de la BDD (accessible depuis la variable $user)

                        if(password_verify($password, $hash)) { // on vérifie vérifie que les empreintes numériques correspondent à l'aide de la fonction password_verify()
                            $_SESSION["user"] = $user; // si les mdp correspondent, on met $user en session à l'aide de la superglobale $_SESSION
                            header("Location: home.php"); exit; // on redirige l'utilisateur
                        } else {
                            header("Location: login.php"); exit;
                            // message utilisateur inconnu ou mot de passe incorrect
                        }
                    } else {
                        header("Location: login.php"); exit;
                        // message utilisateur inconnu ou mot de passe incorrect
                    }
                }
            } 

            header("Location: login.php"); exit;
        break;

        case "profile": 
            header("Location: profile.php"); exit;
        break;

        case "logout":
            unset($_SESSION["user"]);
            header("Location: home.php"); exit;
        break;
    }
}

