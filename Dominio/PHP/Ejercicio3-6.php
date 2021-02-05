<?php

include_once '../../conexion.php';

$probabilidadTipoRed[0] = 0.4571;
$probabilidadTipoRed[1] = 0.5429;

$fiabilidad_red = $_POST['fiabilidad_red'];
$n_enlaces_red = $_POST['n_enlaces_red'];
$capacidad_red = $_POST['capacidad_red'];
$costo_red = $_POST['costo_red'];

$tipoRed[0] = 'A';
$tipoRed[1] = 'B';
$sql_ejercicio6 = "CALL procedimientoEjercicio6(?, ?, ?, ?, ?, @fiabilidadOutput, @enlacesOutput, @costoOutput, @capacidadOutput)";

for ($contador = 0; $contador < count($tipoRed) ; $contador++) {
    
     $call = mysqli_prepare($conection, $sql_ejercicio6);
     mysqli_stmt_bind_param($call, 'iiiis', $fiabilidad_red, $n_enlaces_red, $costo_red, $capacidad_red, $tipoRed[$contador]);
     mysqli_stmt_execute($call);
     
     $select_ejercicio6 = mysqli_query($conection, 'SELECT @fiabilidadOutput, @enlacesOutput, @costoOutput, @capacidadOutput');
     $get_tipo_Red = mysqli_fetch_assoc($select_ejercicio6);
     
     $fiabilidad_p[$contador] = $get_tipo_Red['@fiabilidadOutput'];
     $enlaces_p[$contador] = $get_tipo_Red['@enlacesOutput'];
     $costo_p[$contador] = $get_tipo_Red['@costoOutput'];
     $capacidad_p[$contador] = $get_tipo_Red['@capacidadOutput'];
     
     $probabilidad_total[$contador] =  $fiabilidad_p[$contador] * $enlaces_p[$contador] * $costo_p[$contador] * $capacidad_p[$contador] * $probabilidadTipoRed[$contador];
}

if($probabilidad_total[0]>$probabilidad_total[1]){
     echo json_encode("A");
}else{
     echo json_encode("B");
}

?>