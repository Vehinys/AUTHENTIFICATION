<?php
session_start();

$password  = "monMotdePasse1234";
$password2 = "monMotdePasse1234";

echo "-------------------------------------------------------<br>";
echo "Algorithme de hachage FAIBLE";
echo"<br>";

echo"<br>";
echo"Hachage - md5 : <br> ";
echo"<br>";

$md5   = hash('md5', $password);
$md5_2 = hash('md5', $password2);

echo $md5."<br>";
echo $md5_2."<br><br>";

echo "-------------------------------------------------------<br>";

echo"<br>";
echo"Hachage - sha256 : <br>";
echo"<br>";

$sha256   = hash('sha256', $password);
$sha256_2 = hash('sha256', $password2);

echo $sha256."<br>";
echo $sha256_2."<br><br>";

echo "-------------------------------------------------------<br>";
echo"<br>";
echo "Algorithme de hachage FORT ( bcrypt)";
echo"<br>";

echo"<br>";
echo"Hachage - password_hash - PASSWORD_DEFAULT : <br> ";
echo"<br>";

$hash = password_hash($password,PASSWORD_DEFAULT);
$hash2 = password_hash($password2,PASSWORD_DEFAULT);

echo $hash."<br>";
echo $hash2."<br><br>";

echo "-------------------------------------------------------<br>";
echo"<br>";
echo "Vérification & comparer la saisie dans le formulaire et stocker en base de donnée  ";
echo"<br>";

echo"<br>";
echo"Saisie dans le formulaire de login : <br> ";
echo"<br>";

$saisie = "monMotdePasse1234";

$check = password_verify($saisie,$hash);
$user = "Albert";


if(password_verify($saisie,$hash)) {
   echo" les mots de passe correspondent !";
   $_SESSION["user"] = $user;
   echo"<br>";
   echo $user." est connecté !";
} else {
    echo "Les mots de passe sont différents !";
}

echo"<br>";
echo"<br>";

echo "-------------------------------------------------------<br>";
