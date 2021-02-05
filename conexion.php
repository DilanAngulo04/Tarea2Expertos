<?php 

global $conection;

$link = '163.178.107.2';
$usuario = 'labsturrialba';
$pass = 'Saucr.2191';
$bd = "tarea2ExpertosB70448";

try{
    $conection = mysqli_connect($link, $usuario, $pass, $bd);

   // echo 'conectado';
}catch (mysqli_connect_errno $e){
    print "Error: " . $e->getMessage() . "</br>";
    die();
}

?>