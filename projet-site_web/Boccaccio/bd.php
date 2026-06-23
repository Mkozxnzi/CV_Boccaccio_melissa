<?php  
function getBD(){ 
$bd = new PDO('mysql:host=localhost;dbname=valar_morghulis;charset=utf8', 'root', 'root'); // PDO pour avoir accès a la bd
$bd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
             // gestion d'erreur de PDO     //indique toute erreur SQL que doit lancer une exception
return $bd; 
}
?> 